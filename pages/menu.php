
<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <title>MENU</title>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-info navbar-dark">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Navbar brand -->
        <a class="navbar-brand" href="#">
            <img src="Assets/cobra.jpg" alt="Logo" height="50" class="d-inline-block align-middle">
            <span class="fs-4"><i>Snake-GIGI</i></span> <!-- fs-4 is a Bootstrap class for font size -->
        </a>

        <!-- Toggle button for mobile nav -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-3">
                    <a class="nav-link fs-5 text-white" href="index.php?idpage=acceuil">Accueil</a> <!-- fs-5 is a Bootstrap class for font size -->
                </li>

                <li class="nav-item me-3">
                    <a class="nav-link fs-5 text-white" href="index.php?idpage=listSerpents">Liste serpents</a>
                </li>

                <li class="nav-item me-3">
                    <a class="nav-link fs-5 text-white" href="index.php?idpage=loveRoom">LoveRoom</a> <!--Mettre un Button de sortie qui vas me diriger sur ma page listserpent-->
                </li>
            </ul>
        </div>
    </div>
    <!-- Container wrapper -->
</nav>

</body>
</html>
