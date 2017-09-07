<?php

require_once('Util.class.php');
require_once('BDMySQL.class.php');
require_once('GestionBD.class.php');

class GestionAlchemyBD extends GestionBD {
	// ----   Private part: the properties
	private $table_elem = "alchemy_elem";
	private $table_combi = "alchemy_combi";

	// Retrieve the elements table name
	public function getElementTableName() {
		return $this->table_elem;
	}

	// Retrieve the combinations table name
	public function getCombiTableName() {
		return $this->table_combi;
	}

	// Init of the database
	public function init() {
		$res = $this->createTable($this->getElementTableName(), "
			id smallint(2) NOT NULL default '0',
			name varchar(50) NOT NULL default '',
			terminal smallint(2) NOT NULL default '0',
			list_combi varchar(200) NOT NULL default '',
			PRIMARY KEY (id)
		");
		if ($res) return $res;

		$res = $this->createTable($this->getCombiTableName(), "
			id smallint(2) NOT NULL default '0',
			elem1 smallint(2) NOT NULL default '0',
			elem2 smallint(2) NOT NULL default '0',
			list_result varchar(20) NOT NULL default '',
			PRIMARY KEY (id)
		");
		if ($res) return $res;

		return null;
	}

	// retrieve the index for a given element name
	public function getIndexForElement($element) {
		if (! $element) {
			return 0;
		}
		$reqList = "SELECT * FROM ".$this->getElementTableName()." WHERE name='".$element."'";
		$this->getUtil()->trace("Request=".$reqList);
		$resElement = $this->getBD()->execRequete($reqList);
		$unElement = $this->getBD()->objetSuivant($resElement);
		if (! $unElement) {
			return 0;
		}
		return $unElement->id;
	}

	// Get all combinations (from alchemy_combi table)
	public function getAllCombis($idElem = 0) {
		$reqList = "SELECT * FROM ".$this->getCombiTableName();
		if ($idElem) {
			$reqList .= " WHERE id='".$idElem."'";
		}
		$this->util->trace("Request=".$reqList);
		return $this->bd->execRequete($reqList);
	}

}

?>
