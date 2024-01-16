<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('Classes/bdd.class.php');

$bdd = new BDD();

// Vérification si l'ID du serpent est passé dans l'URL et est un entier
if (isset($_GET['idSerpents']) && filter_var($_GET['idSerpents'], FILTER_VALIDATE_INT)) {
    $idSerpents = (int) $_GET['idSerpents'];

    // Mise à jour du champ estMort pour marquer le serpent comme mort
    $reqUpdate = "UPDATE Serpents SET estMort = 1 WHERE idSerpents = ?";
    $successUpdate = $bdd->execReq($reqUpdate, [$idSerpents]);

    if ($successUpdate) {
        // Redirection vers la liste des serpents
        header("Location: index.php?idpage=listSerpents");
        exit();
    } else {
        // Gestion des erreurs
        throw new Exception("Erreur lors de la mise à jour de l'état du serpent.");
    }
} else {
    throw new Exception("ID de serpent non valide ou non fourni.");
}
?>
