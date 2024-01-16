<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Accueil - Snake-GIGI</title>
    <style>
        .card-custom {
            height: 70vh; /* Hauteur fixe pour toutes les cartes */
            
        }
        .card-img-top {
            height: 50vh; /* Hauteur fixe pour les images */
            object-fit: cover; /* Assure que l'image couvre bien la zone sans être déformée */
        }
        .card-deck .card {
            margin-bottom: 15px; /* Espacement entre les cartes */
            
        }

        .card-body {
            height: 10vh;
        }

        p.card-text {
            overflow: hidden; /* On cache le texte qui dépasse de la hauteur défini */
        }

        .container {
            margin-top: 60px;
            width: 70vw;
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

        
    </style>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-custom">
                    <img class="card-img-top" src="Assets/mohan.jpg" alt="Card image cap">
                    <div class="card-body">
                        <p class="card-text">Bienvenue dans la Ferme à Serpents <b>Snake-GIGI</b> Créer 15 serpents aléatoirement, Modifier les caractéristiques d'un serpent et rallonger sa durée de vie, Voir son profil et sa famille</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom">
                    <img class="card-img-top" src="Assets/cobra.jpg" alt="Card image cap">
                    <div class="card-body">
                        
                        <p class="card-text">Envoyer des serpents dans la love room pour qu'ils s'accouplent. Retrouver les serpents qui s'amusent dans la love room en y accédant par le menu. Ils peuvent même avoir des bébés ! </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom">
                    <img class="card-img-top" src="Assets/TuerSerpent.jpg" alt="Card image cap">
                    <div class="card-body">
                        
                        <p class="card-text">Attention ! Les serpents meurent rapidement ! Des alertes en haut de leur profil sont là pour vous aider. Vous pouvez également Tuer un serpent manuellement</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>





<!--<div class="centered-content text-center">
    Image réduite et centrée 
    <img src="Assets/cobra.jpg" alt="Cobra Image" class="img-fluid" style="max-width: 40%; height: auto;">

    <div class="hero-text">
         Taille de titre ajustée 
        <h1 class="display-2">Bienvenue dans la Ferme à Serpents <i>Snake-GIGI</i></h1>
        <p class="lead">Découvrez notre incroyable variété de serpents et apprenez-en plus sur ces créatures fascinantes.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>-->





