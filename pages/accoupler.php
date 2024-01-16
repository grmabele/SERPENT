<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('Classes/serpent.class.php');
require_once('Classes/bdd.class.php');
require_once('Classes/race.class.php');

$bdd = new BDD();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (count($_SESSION['idSerpents']) == 2) {
    $serpent1 = $bdd->execReq("SELECT * FROM Serpents WHERE idSerpents = ?", [$_SESSION['idSerpents'][0]])[0];
    $serpent2 = $bdd->execReq("SELECT * FROM Serpents WHERE idSerpents = ?", [$_SESSION['idSerpents'][1]])[0];

        if ($serpent1['idRace'] == $serpent2['idRace'] && $serpent1['genre'] != $serpent2['genre']) {
            $baby = $bdd->generateBabySerpent();
            
            // Déclaration initiale des variables
            $idPere = "";
            $idMere = "";

            // Vérification du genre de serpent1
            if ($serpent1['genre'] == "Mâle") {
                $idPere = $serpent1['idSerpents'];
                // Vérification si serpent2 est Femelle
                if ($serpent2['genre'] == "Femelle") {
                    $idMere = $serpent2['idSerpents'];
                }
            } elseif ($serpent1['genre'] == "Femelle") {
                $idMere = $serpent1['idSerpents'];
                // Vérification si serpent2 est Mâle
                if ($serpent2['genre'] == "Mâle") {
                    $idPere = $serpent2['idSerpents'];
                }
            }

            $nomBebe = $baby['nom'];
            echo "Un nouveau bébé vient de naître : " .$baby['nom'];

            // Préparation de la requête d'insertion avec des paramètres
            $reqInsert = "INSERT INTO Serpents (nom, dureeVie, heure_et_date_naissance, poids, genre, idRace, idPere, idMere) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $bdd->execReq($reqInsert, [
                $baby['nom'],
                $baby['dureeVie'],
                date('Y-m-d H:i:s'), // Date et heure actuelles
                //date('Y-m-d H:i:s'),
                $baby['poids'],
                $baby['genre'],
                $serpent1['idRace'],
                $idPere,
                $idMere
            ]);
        } else {
            //echo "Les serpents ne sont pas compatibles pour l'accouplement.";
            $_SESSION['message'] = "Les serpents ne sont pas compatibles pour l'accouplement.";
            header("Location: index.php?idpage=loveRoom");
            exit;
        }
 }
?>
