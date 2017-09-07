<?php

require_once('Util.class.php');
require_once('BDMySQL.class.php');

define ("NOM", "remi.lapointe");
define ("PASSE", "rem000");
define ("SERVER", "localhost");
define ("BASE", "remi_lapointe");

class GestionMatchBD {
	// ----   Private part: the properties
	private $bd;
	private $debug;
	private $util;

	private $table_match = "hand_essai";
	private $table_users = "lab_users";
	private $table_affect = "lab_affect";

	// Constructor
	public function __construct($debug = 0) {
		$this->debug = $debug;
		$this->util = new Util($this->debug);
		$this->util->trace("constructeur de GestionMatchBD avec debug=".$this->debug);
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

	// Retrieve the lab table name
	public function getMatchTableName() {
		return $this->table_match;
	}

	// Retrieve the user table name
	public function getUserTableName() {
		return $this->table_users;
	}

	// Retrieve the affectation table name
	public function getAffectationTableName() {
		return $this->table_affect;
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
		//$this->connexion();

		$reqCreate = "CREATE TABLE IF NOT EXISTS ".$this->getMatchTableName()." (
			id smallint(2) NOT NULL default '0',
			temps varchar(6) NOT NULL default '',
			score varchar(6) NOT NULL default '',
			action smallint(11) default '0',
			equipe smallint(11) default '0',
			num_joueur smallint(11) default '0',
			nom_joueur varchar(50) default '',
			PRIMARY KEY (id)
		)";

		$this->util->trace("Request: ".$reqCreate);
		$resCreate = $this->bd->execRequete($reqCreate);
		if (!$resCreate) {
			$error = "Error during ".$this->getMatchTableName()." database creation";
			return $error;
		}
/*
		$this->util->trace("Request: ".$reqCreate);
		$reqCreate = "CREATE TABLE IF NOT EXISTS ".$this->getUserTableName()." (
			id smallint(2) NOT NULL default '0',
			username varchar(50) NOT NULL default '',
			email varchar(50) NOT NULL default '',
			phone varchar(20) NOT NULL default '',
			PRIMARY KEY (id)
		)";

		$this->util->trace("Request: ".$reqCreate);
		$resCreate = $this->bd->execRequete($reqCreate);
		if (!$resCreate) {
			$error = "Error during ".$this->getUserTableName()." database creation";
			return $error;
		}

		$reqCreate = "CREATE TABLE IF NOT EXISTS ".$this->getAffectationTableName()." (
			id smallint(2) NOT NULL default '0',
			lab_index varchar(50) NOT NULL default '',
			user_index varchar(50) NOT NULL default '',
			resa_begin datetime NOT NULL default '2010-11-12',
			resa_end datetime NOT NULL default '2010-11-12',
			PRIMARY KEY (id)
		)";

		$this->util->trace("Request: ".$reqCreate);
		$resCreate = $this->bd->execRequete($reqCreate);
		if (!$resCreate) {
			$error = "Error during ".$this->getAffectationTableName()." database creation";
			return $error;
		}
*/
		return null;
	}

	// Get all events (from hand_essai table) or a particular event
	public function getAllEvents($idEvent = 0) {
		$reqList = "SELECT * FROM ".$this->getMatchTableName();
		if ($idEvent) {
			$reqList .= " WHERE id='".$idEvent."'";
		}
		$this->util->trace("Request=".$reqList);
		return $this->bd->execRequete($reqList);
	}

	// Get all distinct items (from hand_essai table)
	public function getDistinctItemsFromLab($field_name) {
		$reqList = "SELECT DISTINCT ".$field_name." FROM ".$this->getMatchTableName();
		$this->util->trace("Request=".$reqList);
		return $this->bd->execRequete($reqList);
	}

/*
	// Get all users (from lab_users table)
	public function getAllUsers($idUser = 0) {
		$reqList = "SELECT * FROM ".$this->getUserTableName();
		if ($idUser) {
			$reqList .= " WHERE id='".$idUser."'";
		}
		$this->util->trace("Request=".$reqList);
		return $this->bd->execRequete($reqList);
	}

	// Get all affectations for all labs or a particular lab
	public function getAllAffects($idLab = 0) {
		$reqList = "SELECT * FROM ".$this->getAffectationTableName();
		if ($idLab) {
			$reqList .= " WHERE lab_index='".$idLab."'";
		}
		$this->util->trace("Request=".$reqList);
		return $this->bd->execRequete($reqList);
	}
*/

	// Get next line from last request
	public function getNextLine($resList) {
		return $this->bd->objetSuivant($resList);
	}

	// Returns the next free id for a table
	public function getNextFreeId($tableName) {
		$requete = "SELECT max(`id`) FROM ".$tableName;
		$this->util->trace("Request=".$requete);
		$resId = $this->bd->execRequete($requete);
		if ($resId) {
			$resBrut = $this->bd->tableauSuivant($resId);
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
		$resInsert = $this->bd->execRequete($reqInsert);

	}

}

?>
