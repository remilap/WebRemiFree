<?php

$home = "../..";
$tpl = new TemplateEngine($home."/template_lab.html");

$title = isset($title) ? $title : L::txt_common;
$date = isset($date) ? $date : date( "Ymd", getlastmod());

$tables[] = [
	"tbl" => "bookmarks_chapter",
	"id" => ["smallint(2)", "NOT NULL default '0'", "PRIMARY"],
	"name" => ["varchar(50)", "NOT NULL default ''"],
];
$tables[] = [
	"tbl" => "bookmarks_column",
	"id" => ["smallint(2)", "NOT NULL default '0'", "PRIMARY"],
	"name" => ["varchar(50)", "NOT NULL default ''"],
	"id_chapter" => ["smallint(2)", "NOT NULL default '0'"],
];
$tables[] = [
	"tbl" => "bookmarks_user",
	"id" => ["smallint(2)", "NOT NULL default '0'", "PRIMARY"],
	"name" => ["varchar(50)", "NOT NULL default ''"],
];
$tables[] = [
	"tbl" => "bookmarks_bookmark",
	"id" => ["smallint(2)", "NOT NULL default '0'", "PRIMARY"],
	"name" => ["varchar(50)", "NOT NULL default ''"],
	"id_column" => ["smallint(2)", "NOT NULL default '0'"],
	"order_in_column" => ["smallint(2)", "NOT NULL default '0'"],
	"URL" => ["varchar(120)", "NOT NULL default ''"],
	"iconeName" => ["varchar(50)", "NOT NULL default ''"],
	"tabName" => ["varchar(50)", "NOT NULL default ''"],
	"id_user" => ["smallint(2)", "NOT NULL default '0'"],
];


function getChapters() {
	global $tables, $nbChapters, $chaptersId, $chaptersName, $util;
	$query = "SELECT * FROM `" . $tables[0]["tbl"] . "` ORDER BY name ASC";
	$results = DB::query($query);
	if ($results) {
		$nbChapters = 0;
		foreach ($results as $row) {
			$nbChapters++;
			$id = $row["id"];
			$name = $row["name"];
			$chaptersId[$nbChapters] = $id;
			$chaptersName[$nbChapters] = $name;
			$util->trace("chaptersName[".$nbChapters."] = ".$name);
		}
		$res = "";
	} else {
		$res = L::tbl_norecord($tables[0]["tbl"]);
	}
	return $res;
}

function getRecordName($tbl, $id) {
	global $util;
	$query = "SELECT * FROM `" . $tbl . "` WHERE `id`='" . $id . "'";
	$row = DB::queryFirstRow($query);
	if ($row) {
		$name = $row["name"];
		$util->trace("name: ".$name);
	} else {
		echo "<A>No ". $tbl . " found with id ". $id . "</A>";
		$name = "";
	}
	return $name;
}

function getChapterName($id) {
	global $tables, $util;
	return getRecordName($tables[0]["tbl"], $id);
}

function getColumns() {
	global $tables, $nbColumns, $columnsId, $columnsName, $columnsIdChapter, $util;
	$query = "SELECT * FROM `" . $tables[1]["tbl"] . "` ORDER BY name ASC";
	$results = DB::query($query);
	if ($results) {
		$nbColumns = 0;
		foreach ($results as $row) {
			$nbColumns++;
			$id = $row["id"];
			$name = $row["name"];
			$id_chap = $row["id_chapter"];
			$columnsId[$nbColumns] = $id;
			$columnsName[$nbColumns] = $name;
			$columnsIdChapter[$nbColumns] = $id_chap;
			$util->trace("columnsName[".$nbColumns."] = ".$name.", id_chap = ".$id_chap);
		}
		$res = "";
	} else {
		$res = L::tbl_norecord($tables[1]["tbl"]);
	}
	return $res;
}

function getColumnName($id) {
	global $tables, $util;
	return getRecordName($tables[1]["tbl"], $id);
}

function getChapterIdOfColumn($id_col) {
	global $tables, $util;
	$query = "SELECT * FROM `" . $tables[1]["tbl"] . "` WHERE `id`='" . $id_col . "'";
	$row = DB::queryFirstRow($query);
	if ($row) {
		$id_chapter = $row["id_chapter"];
		$util->trace("id_chapter: ".$id_chapter);
	} else {
		echo "<A>No ". $tbl . " found with id ". $id_col . "</A>";
		$id_chapter = 0;
	}
	return $id_chapter;
}

function getUsers() {
	global $tables, $nbUsers, $usersId, $usersName, $util;
	$query = "SELECT * FROM `" . $tables[2]["tbl"] . "` ORDER BY name ASC";
	$results = DB::query($query);
	if ($results) {
		$nbUsers = 0;
		foreach ($results as $row) {
			$nbUsers++;
			$id = $row["id"];
			$name = $row["name"];
			$usersId[$nbUsers] = $id;
			$usersName[$nbUsers] = $name;
			$util->trace("usersName[".$nbUsers."] = ".$name);
		}
		$res = "";
	} else {
		$res = L::tbl_norecord($tables[2]["tbl"]);
	}
	return $res;
}

function getUserName($id) {
	global $tables, $util;
	return getRecordName($tables[2]["tbl"], $id);
}

function getBookmarks($id_user) {
	global $tables, $nbBookmarks, $booksId, $booksName, $booksURL, $booksIdColumn, $booksIconeName, $booksTabName, $booksIdUser, $nbBooksInCol, $nbColsInChap, $tabIdBooks, $booksOrderInColumn, $util;
	$query = "SELECT * FROM `" . $tables[3]["tbl"] . "`";
	if ($id_user) {
		$query = $query . " WHERE `id_user`='" . $id_user . "'";
	}
	$query = $query . " ORDER BY name ASC";
	$results = DB::query($query);
	if ($results) {
		$nbBookmarks = 0;
		foreach ($results as $row) {
			$nbBookmarks++;
			$id = $row["id"];
			$name = $row["name"];
			$booksId[$nbBookmarks] = $id;
			$booksName[$nbBookmarks] = $name;
			$idCol = $row["id_column"];
			$booksIdColumn[$nbBookmarks] = $idCol;
			$idChap = getChapterIdOfColumn($idCol);
			//echo "== id=".$id.", name=".$name.", nb=".$nbBookmarks.", idCol=".$idCol.", idChap=".$idChap;
			if (isset($nbBooksInCol[$idCol])) {
				//echo "++ books in Col";
				$nbBooksInCol[$idCol]++;
			} else {
				//echo "new book in Col";
				$nbBooksInCol[$idCol] = 1;
				if (isset($nbColsInChap[$idChap])) {
					//echo "++ cols in Chap";
					$nbColsInChap[$idChap]++;
				} else {
					//echo "new col in Chap";
					$nbColsInChap[$idChap] = 1;
				}
			}
			$n = $nbBooksInCol[$idCol];
			$tabIdBooks[$idChap][$idCol][$n] = $id;
			$booksOrderInColumn[$nbBookmarks] = $row["order_in_column"];
			$booksURL[$nbBookmarks] = $row["URL"];
			$booksIconeName[$nbBookmarks] = $row["iconeName"];
			$booksTabName[$nbBookmarks] = $row["tabName"];
			$booksIdUser[$nbBookmarks] = $row["id_user"];
			$util->trace("booksmarks[".$nbBookmarks."] = ".$name);
		}
		$res = "";
	} else {
		$res = L::tbl_norecord($tables[3]["tbl"]);
	}
	return $res;
}

function getBookmarkName($id) {
	global $tables, $util;
	return getRecordName($tables[3]["tbl"], $id);
}

function getBookmarksInColumn($idCol) {
	global $tables, $nbIdCol, $colIdBooks, $util;
	$query = "SELECT * FROM `" . $tables[3]["tbl"] . "`";
	if ($id_user) {
		$query = $query . " WHERE `id_column`='" . $idCol . "'";
	}
	$query = $query . " ORDER BY order_in_column ASC";
	$results = DB::query($query);
	if ($results) {
		$nbIdCol = 0;
		foreach ($results as $row) {
			$nbIdCol++;
			$id = $row["id"];
			$colIdBooks[$nbIdCol] = $id;
			$util->trace("nb booksmarks in column ".$idCol.": ".$nbIdCol);
		}
		$res = "";
	} else {
		$res = L::tbl_norecord($tables[3]["tbl"]);
	}
	return $res;
}

?>
