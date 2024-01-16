<?php
global $title;
session_start();
require_once('Classes/bdd.class.php');
include('pages/menu.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
</head>
<body>
    
    <div id="content">
            <?php
            // Test $_GET est vide

            if(!isset($_GET['idpage']) || $_GET['idpage'] == '') $_GET['idpage'] = 'acceuil'; //$fileimport = 'pages/' .$_GET['page'].' .php';
            $fileimport = 'pages/' . $_GET['idpage'] . '.php';
            //echo var_dump($fileimport);

            // Test si le fichier existe s
            if(file_exists($fileimport)){
                include($fileimport);
            } else {
                echo "Oups, la page n'est pas disponible.";
            }
            ?>
        </div>
        <?php
        include('pages/footer.php');
        ?>
    </body>
</html>