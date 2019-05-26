<?php

require_once('Util.class.php');
require_once('BDMySQL.class.php');

define ("NOM", "remi.lapointe");
define ("PASSE", "rem000");
define ("SERVER", "localhost");
define ("BASE", "remi_lapointe");

class GestionLaserBD {
	// ----   Private part: the properties
	private $bd;
	private $debug;
	private $util;

	private $table_tailles = "laser_tailles";
	private $table_couleurs = "laser_couleurs";
	private $table_places_logo = "laser_places_logo";

	// Constructor
	public function __construct($debug = 0) {
		$this->debug = $debug;
		$this->util = new Util($this->debug);
		$this->util->trace("constructeur de GestionLaserBD avec debug=".$this->debug);
	}

	// Set ON the debug mode of this class
	public function setDebugOn() {
		$this->debug = 1;
	}

	// Set OFF the debug mode of this class
	public function setDebugOff() {
		$this->debug = 0;
	}

	// Get the debug status of this class
	public function getDebugStatus() {
		return $this->debug;
	}

	// Get the BD access reference
	public function getBD() {
		return $this->bd;
	}

	// Get message from database
	public function messageSGBD() {
		if ($this->bd) {
			return $this->bd->messageSGBD();
		} else {
			return "No connexion to the database";
		}
	}

	// Retrieve the couleurs table name
	public function getCouleursTableName() {
		return $this->table_couleurs;
	}

	// Retrieve the tailles table name
	public function getTaillesTableName() {
		return $this->table_tailles;
	}

	// Retrieve the places_logo table name
	public function getPlacesLogoTableName() {
		return $this->table_places_logo;
	}

	// Connexion to the database
	public function connexion() {
		$typ = "MySQL";
		$this->util->trace("Connexion to the ".$typ." database named ".BASE." on the ".SERVER." server");
		$bd = Connexion ($typ, NOM, PASSE, BASE, SERVER);
		if (! $bd) {
			$error = "Unable to access to the ".BASE." database";
			return $error;
		}
		$this->bd = $bd;
		return null;
	}

	// Init of the database
	public function init() {
		$this->connexion();

		$reqCreate = "CREATE TABLE IF NOT EXISTS ".$this->getCouleursTableName()." (
			id int(2) NOT NULL default '0',
			couleur varchar(20) NOT NULL default '',
			PRIMARY KEY (id)
		)";

		$this->util->trace("Request: ".$reqCreate);
		$resCreate = $this->bd->execRequest($reqCreate);
		if (!$resCreate) {
			$error = "Error during ".$this->getCouleursTableName()." database creation";
			return $error;
		}

		$this->util->trace("Request: ".$reqCreate);
		$reqCreate = "CREATE TABLE IF NOT EXISTS ".$this->getTaillesTableName()." (
			id int(2) NOT NULL default '0',
			taille varchar(10) NOT NULL default '',
			PRIMARY KEY (id)
		)";

		$this->util->trace("Request: ".$reqCreate);
		$resCreate = $this->bd->execRequest($reqCreate);
		if (!$resCreate) {
			$error = "Error during ".$this->getTaillesTableName()." database creation";
			return $error;
		}

		$reqCreate = "CREATE TABLE IF NOT EXISTS ".$this->getPlacesLogoTableName()." (
			id int(2) NOT NULL default '0',
			place_logo varchar(20) NOT NULL default '',
			PRIMARY KEY (id)
		)";

		$this->util->trace("Request: ".$reqCreate);
		$resCreate = $this->bd->execRequest($reqCreate);
		if (!$resCreate) {
			$error = "Error during ".$this->getPlacesLogoTableName()." database creation";
			return $error;
		}

		return null;
	}

	// Get all couleurs (from laser_couleurs table) or a particular couleur
	public function getAllCouleurs($idCouleur = 0) {
		$reqList = "SELECT * FROM ".$this->getCouleursTableName();
		if ($idCouleur) {
			$reqList .= " WHERE id='".$idCouleur."'";
		}
		$this->util->trace("Request=".$reqList);
		return $this->bd->execRequest($reqList);
	}

	// Get all tailles (from laser_tailles table) or a particular taille
	public function getAllTailles($idTaille = 0) {
		$reqList = "SELECT * FROM ".$this->getTaillesTableName();
		if ($idTaille) {
			$reqList .= " WHERE id='".$idTaille."'";
		}
		$this->util->trace("Request=".$reqList);
		return $this->bd->execRequest($reqList);
	}

	// Get all places logo (from laser_places_logo table) or a particular place logo
	public function getAllPlacesLogo($idPlaceLogo = 0) {
		$reqList = "SELECT * FROM ".$this->getPlacesLogoTableName();
		if ($idPlaceLogo) {
			$reqList .= " WHERE id='".$idPlaceLogo."'";
		}
		$this->util->trace("Request=".$reqList);
		return $this->bd->execRequest($reqList);
	}

	// Get next line from last request
	public function getNextLine($resList) {
		return $this->bd->nextObject($resList);
	}

	// Returns the next free id for a table
	public function getNextFreeId($tableName) {
		$requete = "SELECT max(`id`) FROM ".$tableName;
		$this->util->trace("Request=".$requete);
		$resId = $this->bd->execRequest($requete);
		if ($resId) {
			$resBrut = $this->bd->nextArray($resId);
			$result = $resBrut[0] + 1;
			$this->util->trace("getNextFreeId(".$tableName."): requete = ".$requete);
			$this->util->trace("=> result = ".$result);
		} else {
			$result = 1;
		}
		return $result;
	}

	// Insert a new record in a table
	public function insertARecord($tableName, $fieldsValues) {
		$reqInsert = "INSERT INTO ".$tableName." VALUES ('".$fieldsValues."')";
		$this->util->trace("Request=".$reqInsert);
		$resInsert = $this->bd->execRequest($reqInsert);

	}

}

?>
