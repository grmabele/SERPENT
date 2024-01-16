<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('Classes/bdd.class.php');
require_once('Classes/serpent.class.php');
require_once('Classes/race.class.php');

$bdd = new BDD();
$serpent = new Serpent();

$req = "SELECT nomRace FROM Races";
$res = $bdd->execReq($req);

// Récupération de l'ID du serpent à partir de l'URL
$idSerpent = isset($_GET['idSerpents']) ? (int)$_GET['idSerpents'] : 0;

$serpentData = $serpent->getSerpentById($idSerpent);

// Vérification si le formulaire a été soumis

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $idSerpent = isset($_POST['idSerpents']) ? (int)$_POST['idSerpents'] : null;
    $nom = $_POST['nom'] ?? null;
    
    $dureeVie = isset($_POST['dureeVie']) && is_numeric($_POST['dureeVie']) ? (int)$_POST['dureeVie'] : null;
    $dateNaissance = $_POST['heure_et_date_naissance'] ?? null; 
   
    $poids = isset($_POST['poids']) && is_numeric($_POST['poids']) ? (float)$_POST['poids'] : null;
    $genre = $_POST['genre'] ?? null;
    
    // Avant la vérification des données
    $idRace = null;
    if (isset($_POST['nomRace']) && is_numeric($_POST['nomRace'])) {
        $idRace = (int)$_POST['nomRace']+1; 
    }

// Vérification des données
if ($idSerpent !== null && $nom !== null && $dureeVie !== false && $dateNaissance !== null && $poids !== false && $genre !== null && $idRace !== false) {
    // Mise à jour des informations du serpent
    $success = $serpent->updateSerpent($idSerpent, $nom, $dureeVie, $dateNaissance, $poids, $genre, $idRace);
    
    
    if ($success) {
        header('Location: index.php?idpage=listSerpents');
        exit;}

}

}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Créer un nouveau serpent</title>
   
</head>
<body>
    <form action="" method="POST" class="vh-100" >
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-6 col-lg-4"> <!-- Taille de la colonne ajustée -->
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <!-- Utilisez PHP pour définir la valeur de chaque champ --> 
                            <input type="hidden" name="idSerpents" value="<?= $serpentData['idSerpents'] ?? '' ?>" />


                            <div class="form-outline mb-4">
                                <label class="form-label" for="nom">Nom</label>
                                <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($serpentData['nom'] ?? '') ?>" />   
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="dureeVie">Durée de vie</label>
                                <input type="number" id="dureeVie" name="dureeVie" class="form-control" value="<?= $serpentData['dureeVie'] ?? 0 ?>" />    
                            </div>

                            <?php
                            if (isset($serpentData['heure_et_date_naissance']) && !empty($serpentData['heure_et_date_naissance'])) {
                               
                                $dateNaissance = new DateTime($serpentData['heure_et_date_naissance']);
                                
                                $formattedDate = $dateNaissance->format('Y-m-d\TH:i');
                            } else {
                               
                                $formattedDate = ''; // valeur par défaut
                            }
                        
                            echo '<div class="form-outline mb-4">' .
                                 '<label class="form-label" for="heure_et_date_naissance">Date et heure de naissance: </label>' .
                                 '<input type="datetime-local" id="heure_et_date_naissance" name="heure_et_date_naissance" class="form-control" value="' . $formattedDate . '" />' .
                                 '</div>';
                            
                            ?>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="poids">Poids</label>
                                <input type="number" id="poids" name="poids" class="form-control" value="<?= htmlspecialchars($serpentData['poids'] ?? '') ?>" />
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="genre">Genre</label>
                                <select name="genre" id="genre" class="form-control">
                                    <option value="Mâle" <?= (isset($serpentData['genre']) && $serpentData['genre'] == 'Mâle') ? 'selected' : '' ?>>Mâle</option>
                                    <option value="Femelle" <?= (isset($serpentData['genre']) && $serpentData['genre'] == 'Femelle') ? 'selected' : '' ?>>Femelle</option>
                                </select>      
                            </div>


                            <div class="form-outline mb-4">
                                <label class="form-label" for="nomRace">Race</label>
                                
                                 <select name="nomRace" id="nomRace" class="form-control">
                                    <option value="">--Choix--</option>
                                    <?php
                                    foreach ($res as $key => $value) {
                                        $selected = ($serpentData['idRace']-1 ?? 0) == $key ? 'selected' : '';
                                        echo "<option value='" . $key . "' $selected>" . $value['nomRace'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <button class="btn btn-primary btn-lg btn-block" type="submit">Modifier</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
</body>
</html>










