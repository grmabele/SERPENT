<?php
class BDD {

	//pramas
	private $conn;

    public $nomRace; 
    public $dureeVie;
    public $heure_et_date_naissance;
    public $poids;
    public $genre;
    public $idRace;

	//construc
	public function __construct($h = 'localhost', $db = 'ElevageSerpents', $u = 'root', $pw = '') {
        $host = "127.0.0.1";
        $port = "8889";
        $dbname = "ElevageSerpents";
        $username = "root";
        $password = "root";
        $socket = "/Applications/MAMP/tmp/mysql/mysql.sock";

        $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8;unix_socket=$socket", $username, $password);
    }

    public function execReq($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            if (strpos($sql, 'SELECT') === 0) {
                return $stmt->fetchAll();
            }
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
            return false;
        }
    }
    
    public function fetchSingleColumn($sql, $params = [], $columnNumber = 0) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn($columnNumber);
    }
 
    public function deleteSerpent($idSerpents) {
        $sql = "DELETE FROM Serpents WHERE idSerpents = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idSerpents]);
    }

    public function getSerpent($idSerpents) {
        $sql = "SELECT * FROM Serpents WHERE idSerpents = $idSerpents";
        echo $sql;
        $res = $this->execReq($sql);
        return $res;
    }

    public function escapeString($value) {
        return $this->conn->quote($value);
    }

    public function getProfil() {
        $sql = "SELECT Serpents.*, Races.nomRace FROM Serpents INNER JOIN Races ON Serpents.idRace = Races.idRace WHERE Serpents.idSerpents = ?";
        $res = $this->execReq($sql);

        return $res;
    }

    public function generateBabySerpent() {
        date_default_timezone_set('Europe/Paris'); 
        $nameBaby = ['Squeezer', 'Crawler', 'Twister', 'Whisper', 'Shimmer', 'Flicker', 'Coiler', 'Blink','Sly', 'Hiss', 'Cassie', 'Slither', 'Naga', 'Viper', 'Rattle'];

        // Génerer aléatoirement un nom du bébé
        $randomName = $nameBaby[array_rand($nameBaby)];
        $randomDureeVie = rand(5, 15); // Entre min 5 minutes et maximum 15 minutes
        $randomPoids = rand(1, 10); // Poids entre 1 et 10 kg
        $randomGenre = rand(0, 1) ? 'Mâle' : 'Femelle'; //Genre aléatoire
        $randomIdRace = rand(1, 10);

        $currentDateTime = date('Y-m-d H:i:s');

        return [
            'nom' => $randomName,
            'dureeVie' => $randomDureeVie,
            'poids' => $randomPoids,
            'genre' => $randomGenre,
            'idRace' => $randomIdRace,
            'heure_et_date_naissance' => $currentDateTime
        ];
    }

}


?>
