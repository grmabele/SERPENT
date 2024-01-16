<?php

class Serpent {

    private $tbl = "Serpents";
    private $id = "";
    private $bdd;
    public $nom;

    public function __construct($myid = "vide"){
        if($myid != "vide") $this->id = $myid;
        else $this->id = $myid;

       $this->bdd = new BDD();
    }

    public function createSerpent($nom, $dureeVie, $dateNaissance, $poids, $genre, $idRace) {
       
        $req = "INSERT INTO Serpents (nom, dureeVie, heure_et_date_naissance, poids, genre, idRace, estMort) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $result = $this->bdd->execReq($req, [$nom, $dureeVie, $dateNaissance, $poids, $genre, $idRace, 0]);
    
        return $result;
    }

    
    public function updateSerpent( $idSerpents,$nom, $dureeVie, $dateNaissance, $poids, $genre, $idRace) {
        $sql = "UPDATE Serpents SET nom = ?, dureeVie = ?, heure_et_date_naissance = ?, poids = ?, genre = ?, idRace = ? WHERE idSerpents = ?";
        $params = [$nom, $dureeVie, $dateNaissance, $poids, $genre, $idRace, $idSerpents];
        return $this->bdd->execReq($sql, $params);
    }


    public function generateRandomSerpents($count = 15) {
         $noms = ['Serpy', 'Bella', 'Boa', 'Slinky', 'koko', 'Ba', 'Zara', 'Okan', 'Sou', 'Kaa', 'Noa', 'Elie', 'Draco', 'Monty', 'Scales', 'Lizzie', 'Side', 'Herly', 'Cleo', 'Azur', 'Celtel', 'Serpentia', 'Fang', 'Vitra', 'Apep', 'Tamara', 'Hypnos', 'Meline', 'Toto', 'Tati', 'meu', 'Oko', 'Tata', 'Lys', 'Loa', 'Zoe', 'Sely', 'Sel', 'Voga', 'Mao'];
         
         $nomIndex = array_rand($noms);
         $nom = $noms[$nomIndex];
         unset($noms[$nomIndex]); // Supprime le nom utilisé du tableau
        $dureeVieMin = 3;
        $dureeVieMax = 5;
        $genres = ["Mâle", "Femelle"];

        $races = [
            1,  // Python royal
            2,  // Boa constrictor
            3,  // Cobra royal
            4,  // Serpent des blés
            5,  // Serpent ratier
            6,  // Serpent de maïs
            7,  // Python tapis
            8,  // Serpent à sonnette
            9,  // Serpent de lait
            10, // Anaconda vert
            11, // Serpent des buissons
            12, // Serpent de mer
            13, // Serpent arc-en-ciel
            14, // Serpent de Garter
            15, // Serpent à nez de cochon
            16, // Serpent du désert
            17, // Serpent venimeux à tête cuivrée
            18, // Serpent taureau
            19, // Serpent de corail
            20, // Serpent des arbres
            21, // Serpent des montagnes rocheuses
            22, // Serpent-lézard
            23, // Serpent volant
            20, // Serpent des arbres
            25  // Serpent vert de vigne
        ];
        
        // Génération de serpents aléatoires
        $serpents = [];
    
        for ($i = 0; $i < $count; $i++) {
            date_default_timezone_set('Europe/Paris'); 
            $nom = $noms[array_rand($noms)];
            $dureeVie = rand($dureeVieMin, $dureeVieMax);

            $heure_et_date_naissance = date("Y-m-d H:i:s"); // date et l'heure actuelles

            $poids = rand(3, 10) / 10; // Poids aléatoire entre 0.1 et 1.0
            
            $genre = $genres[array_rand($genres)];
            $idRace = $races[array_rand($races)];

            $estMort = 0;
    
            // Création d'un tableau associatif pour chaque serpent
            $serpent = [
                'nom' => $nom,
                'dureeVie' => $dureeVie,
                'heure_et_date_naissance' => $heure_et_date_naissance,
                'poids' => $poids,
                'genre' => $genre,
                'idRace' => $idRace,
                'estMort' => $estMort // Ajout de cette ligne
            ];
    
            $serpents[] = $serpent;
        }
    
        return $serpents;
    }

    public function getSerpentById($idSerpents) {
        $bdd = new BDD(); 
        $sql = "SELECT * FROM Serpents WHERE idSerpents = ?";
        $res = $bdd->execReq($sql, [$idSerpents]);
        return $res ? $res[0] : null; 
    }
    
    public function insertSerpent($serpentData) {
        $req = "INSERT INTO " . $this->tbl . " (nom, dureeVie, heure_et_date_naissance, poids, genre, idRace, estMort) VALUES (?, ?, ?, ?, ?, ?,? )";
        $values = [
            $serpentData['nom'], 
            $serpentData['dureeVie'], 
            $serpentData['heure_et_date_naissance'], 
            $serpentData['poids'], 
            $serpentData['genre'], 
            $serpentData['idRace'],
            $serpentData['estMort']
        ];
        
        $result = $this->bdd->execReq($req, $values);
        
        return $result;
    }
 

}

