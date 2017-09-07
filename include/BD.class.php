<?php
// Classe abstraite définissant une interface générique d'accès
// à une base de données. Version complète


function Connexion ($sgbd, $login, $passe, $base, $serveur, $debug = 0) {

	// Instanciation d'un objet instance de BD. 
	// Choix de la sous-classe en fonction de la configuration
	switch ($sgbd) {
	case BD::BD_POSTGRESQL:
		$theBd = new BDPostgreSQL ($login, $passe, $base, $serveur, $debug);
		break;
 
	case BD::BD_SQLITE:
		$theBd = new BDSQLite ($login, $passe, $base, $serveur, $debug);
		break;

	default: // MySQL par défaut
		$theBd = new BDMySQL ($login, $passe, $base, $serveur, $debug);
		break;
	}
	return $theBd;
}


class BD {
	const BD_POSTGRESQL = "PostgreSQL";		// PostgreSQL
	const BD_MYSQL = "MySQL";				// MySQL
	const BD_SQLITE = "SQLite";				// SQLite

	// ----   Partie privée : les propriétés
	private $sgbd, $connexion, $base_name;
	private $error_code, $error_mesg, $util;

	// Constructeur de la classe
	public function __construct($login, $password, $base, $server, $debug = 0) {
		// Initialisations
		$this->base_name = $base;
		$this->error_code = 0;
		$this->error_mesg = "";
		$this->sgbd = "Unknown";
		$this->util = new Util($debug);
		$this->getUtil()->trace("BD::__construct(login=".$login.", pwd=***, base=".$base.", server=".$server.")");

		// Connexion au serveur par appel à une méthode privée
		$this->connexion = $this->connect($login, $password, $base, $server);

		// Lancé d'exception en cas d'erreur
		if ($this->error_code != 0) {
			echo "Connection error to SGBD " . $this->sgbd . ": " . $this->error_mesg . "<br/>";
		}

		// Fin du constructeur
	}

	// Méthodes privées
	private function connect($login, $password, $base, $server) {}
	private function exec($request) {}

	// Méthodes publiques

	// Méthode d'exécution d'une requête
	public function execRequest($request) {
		$this->getUtil()->trace("BD::execRequest begin");
		$result = $this->exec($request);
		if ($this->error_code != 0) {
			echo "Error during request execution: ".$request."<br /> " . $this->getErrorMessage();
			$this->getUtil()->trace("BD::execRequest, " . $this->getErrorMessage());
		}

		$this->getUtil()->trace("BD::execRequest end with error_code: " . $this->getErrorCode());
		return $result;
	}

	// Méthodes abstraites
	// Accès à la ligne suivante, sous forme d'objet
	public function nextObject($result) {}

	// Accès à la ligne suivante, sous forme de tableau associatif
	public function nextLine($result) {}

	// Accès à la ligne suivante, sous forme de tableau indicé
	public function nextArray($result) {}

	// Echappement des apostrophes et autres préparations à l'insertion
	public function prepareChaine($str) {}

	// Génération d'un identifiant
	public function genereID($sequence_name) {}

	// Méthode indiquant le nombre d'attributs dans le résultat
	public function nbAttributs($res) {}

	// Méthode donnant le nom d'un attribut dans un résultat
	public function nomAttribut($res, $position) {}

	// Retour du message d'erreur
	public function messageSGBD() {}

	// Nom du SGBD
	public function getSGBD() {
		return $this->sgbd;
	}

	public function setSGBD($sgbd) {
		$this->sgbd = $sgbd;
	}

	// Error code
	public function getErrorCode() {
		return $this->error_code;
	}

	// Error message
	public function getErrorMessage() {
		return $this->error_mesg;
	}

	// Set ON the debug mode of this class
	public function setDebugOn() {
		$this->util->setDebugOn();
	}

	// Set OFF the debug mode of this class
	public function setDebugOff() {
		$this->util->setDebugOff();
	}

	// Set the debug status
	public function setDebugTo($debug) {
		$this->util->setDebugTo($debug);
	}

	// Get the debug status of this class
	public function getDebugStatus() {
		return $this->util->getDebugStatus();
	}

	// Get the Util class reference
	public function getUtil() {
		return $this->util;
	}

	// Fin de la classe
}
?>
