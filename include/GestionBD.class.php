<?php

require_once('Util.class.php');
require_once('BDMySQL.class.php');

define ("NOM", "remi.lapointe");
define ("PASSE", "rem001");
define ("SERVER", "127.0.0.1");
define ("BASE", "remi_lapointe");

abstract class GestionBD {
	// ----   Private part: the properties
	private $bd;
	private $util;
	private $typ;
	private $nbTables = 0;
	private $tablesName;
	private $tablesFields;

	// Constructor
	public function __construct($debug = 0, $typ = "MySQL") {
		$this->typ = $typ;
		$this->util = new Util($debug);
		$this->util->trace("GestionBD::__construct: with type BD=".$typ." and debug=".$debug);
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

	// Connexion to the database
	public function connexion() {
		$this->getUtil()->trace("GestionBD::connexion: to the ".$this->typ." database named ".BASE." on the ".SERVER." server");
		$bd = Connexion ($this->typ, NOM, PASSE, BASE, SERVER, $this->getDebugStatus());
		if (! $bd) {
			$error = "Unable to access to the ".BASE." database";
			return $error;
		}
		$this->bd = $bd;
		$this->getUtil()->trace("GestionBD::connexion end with debug=".$this->getDebugStatus()." util no ".$this->getUtil()->getId());
		return null;
	}

	// Add a table name with its fields
	public function createTable($tableName, $fields) {
		$this->getUtil()->trace("GestionBD::createTable(table=".$tableName.", fields=".$fields.")");
		$this->nbTables++;
		$this->tablesName[$this->nbTables] = $tableName;
		$this->tablesFields[$this->nbTables] = $fields;
		$reqCreate = "CREATE TABLE IF NOT EXISTS ".$tableName." (".$fields.")";
	
		$this->getUtil()->trace("GestionBD::createTable: ".$reqCreate);
		$resCreate = $this->bd->execRequest($reqCreate);
		echo "resCreate: ".$resCreate;
		if (! $resCreate) {
			$error = "Error during ".$tableName." database creation<br />".$this->messageSGBD();
			return $error;
		}
		return null;
	}

	// Init of the database
	public function init() {

		return null;
	}

	// Get all elements (from the given table) or a particular element
	public function getAllElems($tableName, $idElem = 0) {
		$reqList = "SELECT * FROM ".$tableName;
		if ($idElem) {
			$reqList .= " WHERE id='".$idElem."'";
		}
		$this->getUtil()->trace("Request=".$reqList);
		return $this->bd->execRequest($reqList);
	}

	// Get all distinct elements (from the given table) or a particular element
	public function getAllDistinctElems($tableName, $fieldName, $idElem = 0) {
		$reqList = "SELECT DISTINCT ".$fieldName." FROM ".$tableName;
		if ($idElem) {
			$reqList .= " WHERE id='".$idElem."'";
		}
		$this->getUtil()->trace("Request=".$reqList);
		return $this->bd->execRequest($reqList);
	}

	// Get next line from last request
	public function getNextLine($resList) {
		return $this->bd->objetSuivant($resList);
	}

	// Returns the next free id for a table
	public function getNextFreeId($tableName) {
		$requete = "SELECT max(`id`) FROM ".$tableName;
		$this->getUtil()->trace("Request=".$requete);
		$resId = $this->bd->execRequest($requete);
		if ($resId) {
			$resBrut = $this->bd->tableauSuivant($resId);
			$result = $resBrut[0] + 1;
			$this->getUtil()->trace("getNextFreeId(".$tableName."): requete = ".$requete);
			$this->getUtil()->trace("=> result = ".$result);
		} else {
			$result = 1;
		}
		return $result;
	}

	// Insert a new record in a table
	public function insertARecord($tableName, $fieldsValues) {
		$reqInsert = "INSERT INTO ".$tableName." VALUES (".$fieldsValues.")";
		$this->getUtil()->trace("Request=".$reqInsert);
		$resInsert = $this->bd->execRequest($reqInsert);

	}

}

?>
