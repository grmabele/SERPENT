<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('Classes/serpent.class.php');
require_once('Classes/bdd.class.php');
require_once('Classes/race.class.php');


$bdd = new BDD();
$serpent = new Serpent();

    if (isset($_POST['filter'])) {
        if(isset($_POST['nameRace'])){
            $_SESSION['nameRace'] =$_POST['nameRace'];
        }
        if(isset($_POST['filter_gender'])){
            $_SESSION['filter_gender']=$_POST['filter_gender'];
        }
    }

    // On détermine sur quelle page on se trouve
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $currentPage = (int) strip_tags($_GET['page']);
    } else {
        $currentPage = 1;
    }

    // Vérification si le bouton de réinitialisation a été cliqué
    if (isset($_POST["resetFilter"])) {
        unset($_SESSION["nameRace"]);
        unset($_SESSION["filter_gender"]);
    
        header('Location: ' . $_SERVER['PHP_SELF'] . '?idpage=listSerpents');
        exit();
    }

    $filterGenre = isset($_SESSION['filter_gender']) ? $_SESSION['filter_gender'] : '';
    $filterRace = isset($_SESSION['nameRace']) ? (int)$_SESSION['nameRace'] + 1 : 0;


    // On détermine le nombre de serpents dans la BDD?
    $sql = "SELECT COUNT(*) AS nb_serpents FROM Serpents";

    //On prépare la req et on execute
    $query = $bdd->execReq($sql);
    
    $res = $query[0];
    $nbSerpents = (int) $res['nb_serpents'];  // On S'assure que $nbSerpents est un entier


    // On détermine le nombre de Serpents par page 
    if (!isset($_POST["valider"]) && !isset($_SESSION["parPage"])) {
        $parPage = 10; 
    } else if (isset($_POST["valider"])) {
        $parPage = intval($_POST["nbSerpents"]);
        $_SESSION["parPage"] = $parPage;
    } else {
        $parPage = (int)$_SESSION["parPage"]; 
    }

    // Construction de la clause WHERE en fonction des critères de filtrage
    $whereConditions = "WHERE (Serpents.inLoveRoom = 0 OR Serpents.inLoveRoom IS NULL)";
    if (!empty($filterGenre)) {
        $whereConditions .= " AND Serpents.genre = '" . $filterGenre . "'";
    }
    if (!empty($filterRace)) {
        $whereConditions .= " AND Races.idRace = '" . $filterRace . "'";
    }

    // requête pour calculer le nombre total de serpents avec filtrage
    $sql = "SELECT COUNT(*) AS nb_serpents FROM Serpents INNER JOIN Races ON Serpents.idRace = Races.idRace " . $whereConditions;
    $query = $bdd->execReq($sql);
    $res = $query[0];
    $nbSerpents = (int) $res['nb_serpents'];

    $pages = ceil($nbSerpents / $parPage);

    // Calcul du 1er serpent de la page
    $first = ($currentPage * $parPage) - $parPage;

    $sort = $_GET['sort'] ?? 'idSerpents'; // Valeur par défaut pour le tri
    $direction = $_GET['direction'] ?? 'asc'; // Valeur par défaut pour la direction

    //On s'assure que les paramètres de tri sont valides
    $allowedSorts = ['nom', 'genre', 'race', 'idSerpents', 'poids'];
    $allowedDirections = ['asc', 'desc'];
    if (!in_array($sort, $allowedSorts) || !in_array($direction, $allowedDirections)) {
        $sort = 'idSerpents';
        $direction = 'asc';
    }

    // Gestion de la colonne de tri en fonction du paramètre 'sort'
    $sortColumn = $sort;
    if ($sort === 'race') {
        $sortColumn = 'Races.nomRace'; 
    } else if ($sort !== 'idSerpents') {
        $sortColumn = 'Serpents.' . $sort; 
    }

    $sql = "SELECT Serpents.*, Races.nomRace 
        FROM Serpents 
        INNER JOIN Races ON Serpents.idRace = Races.idRace 
        " . $whereConditions . " 
        ORDER BY " . $sortColumn . " " . $direction . " 
        LIMIT " . $first . "," . $parPage;

    $query = $bdd->execReq($sql);

    // Récupérez les résultats
    $serpents = $query;

// Vérification si un message est stocké dans la session et l'affichez
if (isset($_SESSION['message'])) {
    echo "<div class='success-message'>" . htmlspecialchars($_SESSION['message']) . "</div>";
    unset($_SESSION['message']); 
}

// Vérification si le bouton "Générer" a été cliqué
if (isset($_POST['generateSerpents'])) {
    // Génère une liste de serpents aléatoires
    $serpentsAleatoires = $serpent->generateRandomSerpents();

    
    // Insèrtion des serpents générés dans la base de données
    foreach ($serpentsAleatoires as $serpentData) {
        $serpent->insertSerpent($serpentData);
    }

    $_SESSION['message'] = "Vous avez généré 15 serpents avec succès";
    
    header('Location: index.php?idpage=listSerpents');
    exit;
}

date_default_timezone_set('Europe/Paris'); 

$now = new DateTime();
//echo $now->format('Y-m-d H:i:s');
$sql = "SELECT Serpents.idSerpents, Serpents.nom, Serpents.dureeVie, Serpents.heure_et_date_naissance, 
               Serpents.poids, Serpents.genre, Serpents.idRace, Serpents.estMort 
        FROM Serpents 
        INNER JOIN Races ON Serpents.idRace = Races.idRace 
        WHERE Serpents.estMort = 0 OR Serpents.estMort IS NULL;";
$serpents_aut = $bdd->execReq($sql);

if ($serpents_aut && is_array($serpents_aut)) {
    foreach ($serpents_aut as $serpentDec) {
        $dateNaissance = new DateTime($serpentDec['heure_et_date_naissance']);
        $dureeVieMinutes = $serpentDec['dureeVie']; // Durée de vie en minutes

        // Calcul de la date de décès prévue
        $dateDeces = clone $dateNaissance;
        $dateDeces->add(new DateInterval("PT{$dureeVieMinutes}M")); // Ajout de la durée de vie à la date de naissance

        if ($now >= $dateDeces) {
            // Après décès du serpent, mise à jour dans la base de données
            $updateSql = "UPDATE Serpents SET estMort = 1 WHERE idSerpents = " . $serpentDec['idSerpents'];
            $bdd->execReq($updateSql);
        }
    }
}

// Comptage les serpents mâles
$sqlMale = "SELECT COUNT(*) AS male_count FROM Serpents WHERE genre = 'Mâle'";
$queryMale = $bdd->execReq($sqlMale);
$maleCount = $queryMale[0]['male_count'];

// Comptage les serpents femelles
$sqlFemale = "SELECT COUNT(*) AS female_count FROM Serpents WHERE genre = 'Femelle'";
$queryFemale = $bdd->execReq($sqlFemale);
$femaleCount = $queryFemale[0]['female_count'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">


    <title>Liste des Serpents</title>

    <style>

        .btn-love {
                    background-color: #ffb6c1; 
                    border: none; 
                    border-radius: 20px; 
                    font-weight: bold; 
                    font-size: 16px; 
                    padding: 10px 20px; 
                    display: flex; 
                    align-items: center; 
                    justify-content: center; 
                }

        .text-gray {
            color: gray !important;
        }

        .success-message {
        color: green;
        background-color: #ccffcc; 
        text-align: center; 
        padding: 10px; 
        border-radius: 5px; 
        margin: 10px 0; 
        }
        a {
            text-decoration: none !important;
        }

        .btn-white {
        background-color: white;
        color: #333;
        border: 1px solid #ccc;
        }

        /* CSS pour centrer le contenu */
        .text-center {
            text-align: center;
        }

        .serpent-counts span {
            margin: 0 10px; /* Ajoute de l'espace horizontal entre les icônes et le texte */
        }

        /* Si vous souhaitez que les instructions prennent toute la largeur et soient centrées verticalement */
        .instructions {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        /* CSS pour agrandir les icônes */
        .fas {
            font-size: 24px; /* Ajustez la taille selon vos besoins */
        }

        /* Ajout de styles pour les icônes */
        .fas {
            margin-right: 5px; /* Espacement entre l'icône et le texte */
        }

    </style>
</head>

<body class="w-100">

    <?php if(isset($_SESSION['message1'])): ?>
        <div style="background-color: #ccffcc; color: green; padding: 10px; width: 50vw; margin: 20px auto 0; text-align: center;">
            <?= htmlspecialchars($_SESSION['message1']); ?>
        </div>
        <?php unset($_SESSION['message1']);  ?>
    <?php endif; ?>

    <!-- <?php if(isset($_SESSION['message'])): ?>
            <?= htmlspecialchars($_SESSION['message']); ?>
        </div>
        <?php unset($_SESSION['message']);  ?>
    <?php endif; ?> -->


    <div class="container-fluid" style="padding-bottom: 70px;">

        <main class="container-fluid  mt-4" >

            <!-- Section de filtres et bouton d'ajout -->

            <div class="d-flex justify-content-between align-items-end mb-4">
                <!-- Filtres -->
                <form method="POST" class="d-flex">
                    <div class="me-2">
                        <select name="filter_gender" id="filter_gender" class="form-control">
                            <option value="" class="text-gray">Rechercher par genre</option>
                            <option value="Mâle" class="text-gray">Mâle</option>
                            <option value="Femelle" class="text-gray">Femelle</option>
                        </select>
                    </div>

                    <div class="me-2">
                        <select name="nameRace" id="idRace" class="form-control">
                            <option value="" class="text-gray">Rechercher par race</option>

                            <?php
                            $req = "SELECT nomRace FROM Races";
                            $res = $bdd->execReq($req);

                            if (count($res) > 0) {
                                foreach ($res as $key => $value) {
                                    echo "<option value='" . $key . " '>" . $value['nomRace'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Aucune race disponible</option>";
                            } ?>
                        </select>
                    </div>

                    <div class="me-2">
                        <button type="submit" name="filter" id="filter" class="btn btn-info text-white" style="text-decoration: none;">Filtrer</button>
                    </div>

                    <div>
                        <button type="submit" name="resetFilter" class="btn btn-warning text-white" style="text-decoration: none;">Reset</button>
                    </div>
                </form>

                <!-- Sélecteur du nombre de serpents par page et bouton valider -->
                <div class="d-flex">

                    <form action="" method="POST" class="d-flex">
                        <div class="me-2 text-gray" >
                            <select name="nbSerpents" id="" class="form-control">
                                <option value="" class="text-white">Nombre de serpents par page</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <button type="submit" name="valider" class="btn btn-info text-white" style="text-decoration: none;">Valider</button>
                    </form>
                </div>

                <!-- Bouton d'ajout -->
                <div>
                    <a href="index.php?idpage=create" class="btn btn-info text-white" style="text-decoration: none;">Créer</a>
                </div>

                <!-- Bouton Générer des serpents -->
                <div>
                    <form action="" method="POST">
                        <button type="submit" name="generateSerpents" class="btn btn-info text-white" style="text-decoration: none;">Générer </button>
                    </form>
                </div>

            </div>

            <div class="serpent-counts text-center">
                <span><i class="fas fa-mars" style="font-size: 24px;"></i> Mâles: <?= $maleCount ?></span>
                <span><i class="fas fa-venus" style="font-size: 24px;"></i> Femelles: <?= $femaleCount ?></span>
            </div>

            <!--Mon tableau liste serpents-->

            <div class="row">
                <section class="col-12">
                    <h1>Liste des serpents</h1>
                    <table class="table">
                        <thead>
                            <th class="text-center">ID</th>
                            <th class="text-center">Accouplement</th>
                            <th class="text-center">
                                <a href="index.php?idpage=listSerpents&sort=nom&direction=<?php echo ($sort == 'nom' && $direction == 'asc') ? 'desc' : 'asc'; ?>&page=<?= $currentPage ?> " style="color: black; text-decoration: none;">
                                    Nom
                                    <i class="fas <?php echo ($sort == 'nom' && $direction == 'asc') ? 'fa-sort-up' : 'fa-sort-down'; ?>"></i>
                                </a>
                            </th>
                            <th class="text-center">Durée de vie</th>
                            <th class="text-center">Date et heure de naissance</th>
                            <th class="text-center">
                                <a href="index.php?idpage=listSerpents&sort=poids&direction=<?php echo ($sort == 'poids' && $direction == 'asc') ? 'desc' : 'asc'; ?>&page=<?= $currentPage ?>" style="color: black; text-decoration: none;">
                                    Poids
                                    <i class="fas <?php echo ($sort == 'poids' && $direction == 'asc') ? 'fa-sort-up' : 'fa-sort-down'; ?>"></i>
                                </a>
                            </th>
                            <th class="text-center">
                                <a href="index.php?idpage=listSerpents&sort=genre&direction=<?php echo ($sort == 'genre' && $direction == 'asc') ? 'desc' : 'asc'; ?>&page=<?= $currentPage ?>" style="color: black; text-decoration: none;">
                                    Genre
                                    <i class="fas <?php echo ($sort == 'genre' && $direction == 'asc') ? 'fa-sort-up' : 'fa-sort-down'; ?>"></i>
                                </a>
                            </th>
                            <th class="text-center">
                                <a href="index.php?idpage=listSerpents&sort=race&direction=<?php echo ($sort == 'race' && $direction == 'asc') ? 'desc' : 'asc'; ?>&page=<?= $currentPage ?>" style="color: black; text-decoration: none;">
                                    Race
                                    <i class="fas <?php echo ($sort == 'race' && $direction == 'asc') ? 'fa-sort-up' : 'fa-sort-down'; ?>"></i>
                                </a>
                            </th>
                            <th class="text-center">Modifier</th>
                            <th class="text-center">Tuer</th>
                            <th class="text-center">Famille</th>
                        </thead>


                        <tbody>
                            <?php
                                $serpent = new Serpent();
                                
                            if (isset($serpentsAleatoires)) {
                                foreach ($serpentsAleatoires as $serpent) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($serpent['nom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($serpent['heure_et_date_naissance']) . "</td>";
                                    echo "<td>" . number_format($serpent['poids'], 1) . "</td>";
                                    echo "<td>" . htmlspecialchars($serpent['genre']) . "</td>";
                                    echo "<td>" . htmlspecialchars($serpent['idRace']) . "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>

                            <?php
                                foreach ($serpents as $serpent) {

                                // Création d'un objet DateTime à partir de la chaîne de date de la base de données
                                $dateNaissance = DateTime::createFromFormat('Y-m-d H:i:s', $serpent['heure_et_date_naissance']);
                                
                                // Vérification si l'objet DateTime a été créé avec succès
                                if ($dateNaissance) {
                                    // Formatage de la date selon le format souhaité
                                    $formattedDate = $dateNaissance->format('d/m/y \à H\hi');
                                } else {
                                    // Gestion des cas où la date est invalide ou nulle
                                    $formattedDate = 'Date inconnue';
                                }
                                echo "<tr>";
                                echo "<td class='text-center'>" . htmlspecialchars($serpent['idSerpents']) . "</td>";
                                echo "<td class='text-center'>" .
                                        "<div>" .
                                        "<form action='index.php?idpage=loveRoom' method='POST'>" .
                                        "<input type='hidden' name='idSerpents' value='" . htmlspecialchars($serpent["idSerpents"]) . "'/>" .
                                        "<button type='submit' class='btn-love' name='inLoveRoom'>Envoyer dans le LoveRoom</button>" .
                                        "</form>" .
                                        "</div>" .
                                    "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($serpent['nom']) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($serpent['dureeVie']) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($formattedDate) . "</td>";
                                echo "<td class='text-center'>" . number_format($serpent['poids'], 1) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($serpent['genre']) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($serpent['nomRace']) . "</td>";                   
                                echo "<td class='text-center'>" .
                                        "<form action='index.php?idpage=update&idSerpents=" . htmlspecialchars($serpent['idSerpents']) . "' method='POST'>" .
                                            "<button class='btn btn-success' name='delete' id='btn_edit'> <span class='fa fa-edit'></span></button>" . 
                                        "</form>" .
                                     "</td>";

                                echo "<td class='text-center'>" .
                                     "<form action='index.php?idpage=kill&idSerpents=" . htmlspecialchars($serpent['idSerpents']) . "' method='POST' onclick='return confirm(\"Êtes-vous sûr de vouloir tuer ce serpent ?\");'>" .
                                         "<button class='btn btn-danger' id='btn_delete'> <span class='fa fa-trash'></span> </button>" .   
                                     "</form>" . 
                                    "</td>";  
                                echo "<td class='text-center'>" .
                                        "<form action='index.php?idpage=profile&idSerpents=" . htmlspecialchars($serpent['idSerpents']) . "' method='POST'>" .
                                            "<button class='btn btn-secondary'> <span class='fa fa-user'></span></button>" .
                                        "</form>" .
                                    "</td>";

                                echo "</tr>";
                            // ?>
                                <?php
                                }
                                ?>
                        </tbody>
                    </table>
                </section>
            </div>

        </main>

        <nav>
            <ul class="pagination">
                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                    <a href="./?idpage=listSerpents&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                </li>
                <?php for ($page = 1; $page <= $pages; $page++) : ?>
                    <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                    <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                        <a href="./?idpage=listSerpents&page=<?= $page ?>" class="page-link"><?= $page ?></a>
                    </li>
                <?php endfor ?>
                <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                    <a href="./?idpage=listSerpents&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
  
</body>

</html>