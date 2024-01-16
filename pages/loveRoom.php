<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('Classes/serpent.class.php');
require_once('Classes/bdd.class.php');
require_once('Classes/race.class.php');

$bdd = new BDD();

if (!isset($_SESSION['idSerpents'])) {
    $_SESSION['idSerpents'] = [];
}

$serpents_in_loveRoom = [];

if (isset($_POST['inLoveRoom'])) {
    $idSerpents = (int)$_POST['idSerpents'];
    if (!in_array($idSerpents, $_SESSION['idSerpents']) && count($_SESSION['idSerpents']) < 2) {
        $sql = "SELECT * FROM Serpents WHERE idSerpents = ?";
        $serpent_info = $bdd->execReq($sql, [$idSerpents]);

        if ($serpent_info) {
            $serpent = $serpent_info[0];
            $_SESSION['idSerpents'][] = $idSerpents;
            $reqLove = "UPDATE Serpents SET inLoveRoom = 1 WHERE idSerpents = ?";
            $bdd->execReq($reqLove, [$idSerpents]);
            $_SESSION['message'] = "{$serpent['nom']} a été envoyé avec succès dans le LoveRoom";
        } else {
            $_SESSION['message'] = "Impossible de trouver les informations du serpent.";
        }
    } else {
        $_SESSION['message'] = "Limite atteinte, impossible d'ajouter plus de serpents à la LoveRoom";
    }

    header("Location: index.php?idpage=listSerpents");
    exit;
}

if (isset($_POST['exitLoveRoom'])) {
    $idSerpentExit = (int)$_POST['idSerpentExit'];
    $reqExitLoveRoom = "UPDATE Serpents SET inLoveRoom = 0 WHERE idSerpents = ?";
    $bdd->execReq($reqExitLoveRoom, [$idSerpentExit]);

    if (($key = array_search($idSerpentExit, $_SESSION['idSerpents'])) !== false) {
        unset($_SESSION['idSerpents'][$key]);
    }

    header("Location: index.php?idpage=listSerpents");
    exit;
}

if (!empty($_SESSION['idSerpents'])) {
    foreach ($_SESSION['idSerpents'] as $idSerpents) {
        $sql = "SELECT Serpents.*, Races.nomRace FROM Serpents INNER JOIN Races ON Serpents.idRace = Races.idRace WHERE Serpents.idSerpents = ?";
        $serpent_info = $bdd->execReq($sql, [$idSerpents]);

        if ($serpent_info) {
            $serpents_in_loveRoom[] = $serpent_info[0];
        }
    }
}

$showAccoupleButton = count($serpents_in_loveRoom) == 2;
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

        <title>Profil du Serpent</title>
        <style>
            
            .row{
                display: flex;
                justify-content:center;
                align-items:center;
                
            } 

           
            .error-message {
                color: red;
                font-weight: bold;
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

        .btn-love {
            background-color: #ff69b4; 
            border: none; 
            border-radius: 20px; 
            font-weight: bold;
            font-size: 16px; 
            padding: 10px 20px;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            cursor: pointer; 
        }

        .btn-love:hover,
        .btn-love:active,
        .btn-love:focus {
            background-color: #ff69b4; 
            outline: none; 
            box-shadow: none; 
        }

        .btn-love .fa-heart {
            color: red; 
            margin-right: 5px; /
        }


        </style>
    </head>
    <body>
        <?php if(isset($_SESSION['message'])): ?>
            <div style="background-color: #ffcccc; color: red; padding: 10px; width: 50vw; margin: 20px auto 0; text-align: center;">
                <?= htmlspecialchars($_SESSION['message']); ?>
            </div>
            <?php unset($_SESSION['message']); // Supprimer le message après l'affichage ?>
        <?php endif; ?>

        <main class="container my-3 ">
            <div class="row">
                <?php
                $count = 0;
            
                foreach ($serpents_in_loveRoom as $serpent): 
                    $count++;
                    ?>
                    <div class="col-md-3">
                        <div class="card card-custom">
                            <?php
                            $imagePath = "Assets/serpent_{$serpent['idSerpents']}.jpg";
                            if (!file_exists($imagePath)) {
                                $imagePath = 'Assets/cobra.jpg'; // Image par défaut
                            }
                            ?>
                            <img class="card-img-top" src="<?= htmlspecialchars($imagePath) ?>" alt="Image du Serpent">
                            <div class="card-body">
                                <h5 class="card-title">Nom : <?= htmlspecialchars($serpent['nom']) ?></h5>
                                <!-- Afficher d'autres informations du serpent -->
                                <p class="card-text">Durée de vie : <?= htmlspecialchars($serpent['dureeVie']) ?></p>
                                <p class="card-text">Date de naissance : <?= htmlspecialchars($serpent['heure_et_date_naissance']) ?></p>
                                <p class="card-text">Poids : <?= htmlspecialchars($serpent['poids']) ?></p>
                                <p class="card-text">Genre : <?= htmlspecialchars($serpent['genre']) ?></p>
                                <p class="card-text">Race : <?= htmlspecialchars($serpent['nomRace']) ?></p>
                            </div>

                            <!-- Ici on ajoute le formulaire de sortie pour le serpent -->
                            <div class="card-footer">
                                <form method="POST" action="index.php?idpage=loveRoom">
                                    <input type="hidden" name="idSerpentExit" value="<?= $serpent['idSerpents'] ?>">
                                    <button type="submit" name="exitLoveRoom" class="btn btn-danger">Sortir</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php if ($count == 1 && count($serpents_in_loveRoom) == 2): ?>
                        <form action="index.php?idpage=accoupler" method="POST" class="col-md-2 d-flex align-items-center justify-content-center">
                            <button class="btn btn-love text-white" style="width: 150px;">
                                <i class="fa fa-heart"></i> ACCOUPLER
                            </button>    
                        </form>
                    <?php endif ?>    
                <?php endforeach; ?>
            </div>
        </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
