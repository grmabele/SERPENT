<?php
class Race {

    private $tbl = "Races";
    private $id = "";
    private $bdd;

    public function __construct($myid = "vide"){
        if($myid != "vide") $this->id = $myid;
        else $this->id = $myid;
    }

    public function select($idRace, $nomRace){
        $bdd = new BDD();
        $requete = " INSERT INTO Races (idRace, nomRace) VALUES ($idRace, $nomRace)";
        $params = array($idRace, $nomRace);

        $result = $this->bdd->execReq($requete, $params);
        return $result;
    }

}