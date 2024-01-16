<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('Classes/bdd.class.php');
require_once('Classes/serpent.class.php');

$bdd = new BDD();

$serpent_info = null;
$parents_info = null;
$enfants_info = [];
$isDeceased = false; // Variable pour déterminer si le serpent est décédé

$idSerpents = isset($_GET['idSerpents']) ? (int) $_GET['idSerpents'] : null;

if ($idSerpents > 0) {
    $serpent_id = intval($_GET['idSerpents']);

            // Requête pour les informations du serpent
            $sql = "SELECT Serpents.*, Races.nomRace FROM Serpents 
                    INNER JOIN Races ON Serpents.idRace = Races.idRace 
                    WHERE Serpents.idSerpents = $idSerpents";
            $serpent_info = $bdd->execReq($sql)[0]; // Récupération des informations du serpent

            // Mise à jour de l'état du serpent
            $isDeceased = $serpent_info['estMort'] == 1; 
         

            // Requête pour les noms du père et de la mère
            $sqlParents = "SELECT p.nom AS nomPere, m.nom AS nomMere 
                FROM Serpents s 
                LEFT JOIN Serpents p ON s.idPere = p.idSerpents 
                LEFT JOIN Serpents m ON s.idMere = m.idSerpents 
                WHERE s.idSerpents = $idSerpents;
            ";
               $result = $bdd->execReq($sqlParents);
               $parents_info = $result ? $result[0] : null; // Vérification si le résultat n'est pas vide avant d'accéder à [0]
               
           
            // Requête pour les grands-parents
            $sqlGrandsParents = "SELECT 
            gp.nom AS nomGrandPereP, gm.nom AS nomGrandMereP,
            gmp.nom AS nomGrandPereM, gmm.nom AS nomGrandMereM
            FROM Serpents s
            LEFT JOIN Serpents p ON s.idPere = p.idSerpents
            LEFT JOIN Serpents m ON s.idMere = m.idSerpents
            LEFT JOIN Serpents gp ON p.idPere = gp.idSerpents
            LEFT JOIN Serpents gm ON p.idMere = gm.idSerpents
            LEFT JOIN Serpents gmp ON m.idPere = gmp.idSerpents
            LEFT JOIN Serpents gmm ON m.idMere = gmm.idSerpents
            WHERE s.idSerpents = $idSerpents";

            $grandsParents_info = $bdd->execReq($sqlGrandsParents);
            $grandsParents_info = $grandsParents_info ? $grandsParents_info[0] : null;

            // Requête pour les enfants
            $sqlEnfants = "SELECT nom FROM Serpents WHERE idPere = $idSerpents OR idMere = $idSerpents";
            $enfants_info = $bdd->execReq($sqlEnfants);


        }


    $imagePath = "Assets/serpent_{$idSerpents}.jpg";

    if (!file_exists($imagePath)) {
        $imagePath = 'Assets/cobra.jpg';
    }
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Profil du Serpent</title>
        <style>
            .row{
                display: flex;
                justify-content:center;
                align-items:center;
            }

            body {
            /* Application de l'image en fond */
            background-image: url('Assets/ferme-serpent.jpg');
            background-size: cover;
            background-position: center;

            /* Effet pour assombrir l'image */
            background-color: rgba(0, 0, 0, 0.5);
            background-blend-mode: darken;

            /* Animation de fond */
            animation: backgroundZoom 30s infinite alternate;
        }

        /* Animation keyframes */
        @keyframes backgroundZoom {
            from {
                background-size: 100% 100%;
            }
            to {
                background-size: 110% 110%;
            }
        }

        .deceased-banner {
            position: absolute;
            top: 0;
            left: 0;
            background-color: red;
            color: white;
            padding: 5px 10px;
            z-index: 10; 
            
        }

        </style>
    </head>
    <body>

    <main class="container my-3 ">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-custom">
                    <!-- état décédé si le serpent est dans Serpents_Morts -->
                    <?php if ($isDeceased): ?>
                    <div class="deceased-banner">DÉCÉDÉ</div>
                    <?php endif; ?>
                    <img class="card-img-top" src="<?= htmlspecialchars($imagePath) ?>" alt="Image du Serpent">
                    <div class="card-body">
                        <h5 class="card-title">Nom : <?php echo $serpent_info['nom']; ?></h5>
                        <p class="card-text">Duree de vie : <?php echo $serpent_info['dureeVie']; ?></p>
                        <p class="card-text">Date de naissance : <?php echo $serpent_info['heure_et_date_naissance']; ?></p>
                        <p class="card-text">Poids : <?php echo $serpent_info['poids']; ?></p>
                        <p class="card-text">Genre : <?php echo $serpent_info['genre']; ?></p>
                        <p class="card-text">Race : <?php echo $serpent_info['nomRace']; ?></p> 
                       

                        <!-- Informations sur les parents -->
                        <h6 class="card-subtitle mb-2 text-muted">Parents :</h6>
                        <p class="card-text">Père : <?= isset($parents_info) && isset($parents_info['nomPere']) ? htmlspecialchars($parents_info['nomPere']) : 'Aucun' ?></p>
                        <p class="card-text">Mère : <?= isset($parents_info) && isset($parents_info['nomMere']) ? htmlspecialchars($parents_info['nomMere']) : 'Aucun' ?></p>

                        <!-- Informations sur les grands-parents -->
                        <h6 class="card-subtitle mb-2 text-muted">Grands-Parents :</h6>
                        <p class="card-text">Grand-père paternel : <?= $grandsParents_info['nomGrandPereP'] ?? 'Aucun' ?></p>
                        <p class="card-text">Grand-mère paternelle : <?= $grandsParents_info['nomGrandMereP'] ?? 'Aucune' ?></p>
                        <p class="card-text">Grand-père maternel : <?= $grandsParents_info['nomGrandPereM'] ?? 'Aucun' ?></p>
                        <p class="card-text">Grand-mère maternelle : <?= $grandsParents_info['nomGrandMereM'] ?? 'Aucune' ?></p>

                        <!-- Informations sur les enfants -->
                        <h6 class="card-subtitle mb-2 text-muted">Enfants :</h6>
                        <?php if ($enfants_info): ?>
                            <ul>
                                <?php foreach ($enfants_info as $enfant): ?>
                                    <li><?= htmlspecialchars($enfant['nom']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Ce serpent n'a pas de descendance.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
   </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
