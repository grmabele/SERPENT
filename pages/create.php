<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('Classes/bdd.class.php');
require_once('Classes/serpent.class.php');
require_once('Classes/race.class.php');

$bdd = new BDD();

$req = "SELECT nomRace FROM Races";
$res = $bdd->execReq($req);

// Vérifiez si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serpent = new Serpent();
   
    $nom = $_POST['nom'];
    $dureeVie = $_POST['dureeVie'];
    $dateNaissance = $_POST['dateNaissance'] . ':00'; // Ajoute les secondes
    $poids = $_POST['poids'];
    $genre = $_POST['genre'];
    $idRace = $_POST['nomRace']+1;

    $serpent->createSerpent($nom, $dureeVie, $dateNaissance, $poids, $genre, $idRace, 0);
   
    header('Location: index.php?idpage=listSerpents'); // Redirige vers la liste des serpents après la création.
}
   
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Créer un nouveau serpent</title>
    <style>
        .valid{
            background-color: green;
            color: #ffffff;
            font-size: 16px;
        }
    </style>
   
</head>
<body>
      
    <form action="" method="POST" class="vh-100" style="background-color: #d9d9d9;">
        <?php
            if(isset($_GET['res']) && $_GET['res']=="true"){
                echo "
                <div class='valid'>
                <p>Votre nouveau serpent a bien été ajouté dans la base de données</p>
                </div>"; 
                echo "ici ". $_GET['res']; 
            }
        ?>
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-6 col-lg-4"> <!-- Taille de la colonne ajustée -->
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <div class="form-outline mb-4">
                                <label class="form-label" for="nom">Nom</label>
                                <input type="text" id="nom" name="nom" class="form-control" />   
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="dureeVie">Durée de vie</label>
                                <input type="number" id="dureeVie" name="dureeVie" class="form-control" />    
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="dateNaissance">Date et heure de naissance: </label>
                                <input type="datetime-local" id="dateNaissance" name="dateNaissance" class="form-control" required/>  
                            </div>


                            <div class="form-outline mb-4">
                                <label class="form-label" for="poids">Poids</label>
                                <input type="number" id="poids" name="poids" class="form-control" />  
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="genre">Genre</label>
                                <select name="genre" id="genre" class="form-control">
                                    <option value="Mâle">Mâle</option>
                                    <option value="Femelle">Femelle</option>
                                </select>      
                            </div>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="nomRace">Race</label>
                                <select name="nomRace" id="nomRace" class="form-control">
                                    <option value="">--Choix</option>
                                        <?php

                                            if (count($res) > 0){
                                                foreach($res as $key => $value){
                                                    echo "<option value='" . $key." '>" .$value['nomRace'] . "</option>";
                                                }
                                            }else{ echo "<option value=''>Aucune race disponible</option>";}?>
                                    </option>
                                </select>       
                            </div>

                            <button class="btn btn-primary btn-lg btn-block" type="submit">Creer</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
</body>
</html>










