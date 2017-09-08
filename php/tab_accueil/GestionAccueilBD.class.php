<?php

require_once('Util.class.php');
require_once('BDMySQL.class.php');
require_once('GestionBD.class.php');

class GestionAccueilBD extends GestionBD {
	// ----   Private part: the properties
	private $table_chapter = "bookmarks_chapter";
	private $table_column = "bookmarks_category";
	private $table_user = "bookmarks_user";
	private $table_bookmark = "bookmarks_list";

	// Retrieve the chapter table name
	public function getChapterTableName() {
		return $this->table_chapter;
	}

	// Retrieve the column table name
	public function getColumnTableName() {
		return $this->table_column;
	}

	// Retrieve the user table name
	public function getUserTableName() {
		return $this->table_user;
	}

	// Retrieve the bookmark table name
	public function getBookmarkTableName() {
		return $this->table_bookmark;
	}

	// Init of the database
	public function init() {
		$this->getUtil()->trace("begin");
		$res = $this->createTable($this->getChapterTableName(), "
			id smallint(2) NOT NULL default '0',
			name varchar(50) NOT NULL default '',
			PRIMARY KEY (id)
		");
		if ($res) return $res;

		$res = $this->createTable($this->getColumnTableName(), "
			id smallint(2) NOT NULL default '0',
			name varchar(50) NOT NULL default '',
			id_chapter smallint(2) NOT NULL default '0',
			PRIMARY KEY (id)
		");
		if ($res) return $res;

		$res = $this->createTable($this->getUserTableName(), "
			id smallint(2) NOT NULL default '0',
			name varchar(50) NOT NULL default '',
			PRIMARY KEY (id)
		");
		if ($res) return $res;

		$res = $this->createTable($this->getBookmarkTableName(), "
			id smallint(2) NOT NULL default '0',
			name varchar(50) NOT NULL default '',
			URL varchar(120) NOT NULL default '',
			id_column smallint(2) NOT NULL default '0',
			iconeName varchar(50) NOT NULL default '',
			tabName varchar(50) NOT NULL default '',
			id_user smallint(2) NOT NULL default '0',
			PRIMARY KEY (id)
		");
		if ($res) return $res;

		return null;
	}

	// retrieve the index for a given chapter name
	public function getIndexForChapter($chapter) {
		if (! $chapter) {
			return 0;
		}
		$reqList = "SELECT * FROM ".$this->getChapterTableName()." WHERE name='".$chapter."'";
		$this->getUtil()->trace("Request=".$reqList);
		$resChapter = $this->getBD()->execRequete($reqList);
		$unChapter = $this->getBD()->nextObject($resChapter);
		if (! $unChapter) {
			return 0;
		}
		return $unChapter->id;
	}

	// retrieve the index for a given column name
	public function getIndexForColumn($column) {
		if (! $column) {
			return 0;
		}
		$reqList = "SELECT * FROM ".$this->getColumnTableName()." WHERE name='".$column."'";
		$this->getUtil()->trace("Request=".$reqList);
		$resColumn = $this->getBD()->execRequete($reqList);
		$unColumn = $this->getBD()->nextObject($resColumn);
		if (! $unColumn) {
			return 0;
		}
		return $unColumn->id;
	}

	// retrieve the index for a given user name
	public function getIndexForUser($user) {
		if (! $user) {
			return 0;
		}
		$reqList = "SELECT * FROM ".$this->getUserTableName()." WHERE name='".$user."'";
		$this->getUtil()->trace("Request=".$reqList);
		$resUser = $this->getBD()->execRequete($reqList);
		$unUser = $this->getBD()->nextObject($resUser);
		if (! $unUser) {
			return 0;
		}
		return $unUser->id;
	}

}

?>
