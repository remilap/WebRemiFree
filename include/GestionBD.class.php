<?php

require_once('Util.class.php');
require_once('BDMySQL.class.php');

define ("NOM", "remi.lapointe");
define ("PASSE", "rem000");
define ("SERVER", "localhost");
define ("BASE", "remi_lapointe");

abstract class GestionBD {
	// ----   Private part: the properties
	private $bd;
	private $debug;
	private $util;
	private $typ;
	private $nbTables = 0;
	private $tablesName;
	private $tablesFields;

	// Constructor
	public function __construct($debug = 0, $typ = "MySQL") {
		$this->setDebugTo($debug);
		$this->typ = $typ;
		$this->util = new Util($this->debug);
		$this->util->trace("constructor of GestionBD with type BD=".$this->typ." and debug=".$this->debug);
	}

	// Set ON the debug mode of this class
	public function setDebugOn() {
		$this->debug = 1;
	}

	// Set OFF the debug mode of this class
	public function setDebugOff() {
		$this->debug = 0;
	}

	// Set the debug status
	public function setDebugTo($debug) {
		if ($debug == 0)
			$this->setDebugOff();
		else
			$this->setDebugOn();
	}

	// Get the debug status of this class
	public function getDebugStatus() {
		return $this->debug;
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
		$this->util->trace("Connexion to the ".$this->typ." database named ".BASE." on the ".SERVER." server");
		$bd = Connexion ($this->typ, NOM, PASSE, BASE, SERVER);
		if (! $bd) {
			$error = "Unable to access to the ".BASE." database";
			return $error;
		}
		$this->bd = $bd;
		return null;
	}

	// Add a table name with its fields
	public function createTable($tableName, $fields) {
		$this->nbTables++;
		$this->tablesName[$this->nbTables] = $tableName;
		$this->tablesFields[$this->nbTables] = $fields;
		$reqCreate = "CREATE TABLE IF NOT EXISTS ".$tableName." (".$fields.")";
	
		$this->util->trace("Request: ".$reqCreate);
		$resCreate = $this->bd->execRequete($reqCreate);
		if (!$resCreate) {
			$error = "Error during ".$tableName()." database creation";
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
		$this->util->trace("Request=".$reqList);
		return $this->bd->execRequete($reqList);
	}

	// Get all distinct elements (from the given table) or a particular element
	public function getAllDistinctElems($tableName, $fieldName, $idElem = 0) {
		$reqList = "SELECT DISTINCT ".$fieldName." FROM ".$tableName;
		if ($idElem) {
			$reqList .= " WHERE id='".$idElem."'";
		}
		$this->util->trace("Request=".$reqList);
		return $this->bd->execRequete($reqList);
	}

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
		$reqInsert = "INSERT INTO ".$tableName." VALUES (".$fieldsValues.")";
		$this->util->trace("Request=".$reqInsert);
		$resInsert = $this->bd->execRequete($reqInsert);

	}

}

?>
