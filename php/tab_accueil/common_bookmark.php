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
	"URL" => ["varchar(120)", "NOT NULL default ''"],
	"id_column" => ["smallint(2)", "NOT NULL default '0'"],
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
	global $tables, $util;
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

function getBookmarks() {
	global $tables, $nbBookmarks, $booksId, $booksName, $booksURL, $booksIdColumn, $booksIconeName, $booksTabName, $booksIdUser, $util;
	$query = "SELECT * FROM `" . $tables[3]["tbl"] . "` ORDER BY name ASC";
	$results = DB::query($query);
	if ($results) {
		$nbBookmarks = 0;
		foreach ($results as $row) {
			$nbBookmarks++;
			$id = $row["id"];
			$name = $row["name"];
			$booksId[$nbBookmarks] = $id;
			$booksName[$nbBookmarks] = $name;
			$booksURL[$nbBookmarks] = $row["URL"];
			$booksIdColumn[$nbBookmarks] = $row["id_column"];
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

/*
	$listChapters = $accueilBD->getAllElems($accueilBD->getChapterTableName());
	$nbChapters = 0;
	while ($unElem = $accueilBD->getNextLine($listChapters)) {
		$nbChapters++;
		$chaptersId[$nbChapters] = $unElem->id;
		$chaptersName[$unElem->id] = $unElem->name;
		$util->trace("chaptersName[".$unElem->id."] = ".$unElem->name);
	}
	$listColumns = $accueilBD->getAllElems($accueilBD->getColumnTableName());
	$nbColumns = 0;
	while ($unElem = $accueilBD->getNextLine($listColumns)) {
		$nbColumns++;
		$columnsId[$nbColumns] = $unElem->id;
		$columnsName[$unElem->id] = $unElem->name;
		$columnsIdChapter[$unElem->id] = $unElem->id_chapter;
		$util->trace("columnsName[".$unElem->id."] = ".$unElem->name.", id_chapter = ".$unElem->id_chapter.", chapter name = ".$chaptersName[$unElem->id_chapter]);
	}
	$listUsers = $accueilBD->getAllElems($accueilBD->getUserTableName());
	$nbUsers = 0;
	while ($unElem = $accueilBD->getNextLine($listUsers)) {
		$nbUsers++;
		$usersId[$nbUsers] = $unElem->id;
		$usersName[$unElem->id] = $unElem->name;
		$util->trace("usersName[".$unElem->id."] = ".$unElem->name);
	}

	$listBookmarks = $accueilBD->getAllElems($accueilBD->getBookmarkTableName());
	$nbBookmarks = 0;
	while ($unBookmark = $accueilBD->getNextLine($listBookmarks)) {
		$nbBookmarks++;
		$options_lig = "";
		if ($nbBookmarks % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		$action_debug = "";
		if ($debug) $action_debug = "&debug=".$debug;
		echo "<TD><center><A HREF='accueil_modify.php?selectedElem=".$unBookmark->id."&action=modify".$action_debug."'><IMG SRC='../b_edit.png' ALT='modify'></A></center> </TD>\n";
		echo "<TD><center><A HREF='accueil_display.php?selectedElem=".$unBookmark->id."&action=delete".$action_debug."'><IMG SRC='../b_drop.png' ALT='delete'></A></center> </TD>\n";
		//echo "<TD>" . $unBookmark->id . "</TD>\n";
		echo "<TD><A HREF='accueil_display.php?selectedElem=".$unBookmark->id."&action=display".$action_debug."'>" . $unBookmark->name . "</A></TD>\n";
		$column_id = $unBookmark->id_column;
		$column_name = "unknown";
		if ( isset($columnsName[$column_id]) ) $column_name = $columnsName[$column_id];
		$chapter_id = 0;
		if ( isset($columnsIdChapter[$column_id]) ) $chapter_id = $columnsIdChapter[$column_id];
		$chapter_name = "unknown";
		if ( isset($chaptersName[$chapter_id]) ) $chapter_name = $chaptersName[$chapter_id];
		$user_id = $unBookmark->id_user;
		$user_name = "unknown";
		if ( isset($usersName[$user_id]) ) $user_name = $usersName[$user_id];
		$util->trace("Bookmark id/name = ".$unBookmark->id."/".$unBookmark->name.", column id/name = ".$column_id."/".$column_name.", chapter id/name = ".$chapter_id."/".$chapter_name.", user id/name = ".$user_id."/".$user_name);
		echo "<TD>" . $chapter_name . "</TD>\n";
		echo "<TD>" . $column_name . "</TD>\n";
		echo "<TD>" . $user_name . "</TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbBookmarks == 0) {
		$msg = "No element found in the database<BR />\n";
	}
	echo $msg;
*/

/*
	echo "</TABLE>\n
	<BR />\n
	<INPUT TYPE='SUBMIT' NAME='addBookmark' VALUE='Add a bookmark'>\n
	<INPUT TYPE='SUBMIT' NAME='addChapter' VALUE='Add a chapter'>\n
	<INPUT TYPE='SUBMIT' NAME='addColumn' VALUE='Add a column'>\n
	<INPUT TYPE='SUBMIT' NAME='addUser' VALUE='Add a user'>\n
	<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='".$debug."' ".($debug?"CHECKED":"")."/><LABEL FOR='debug'>Debug</LABEL></P>\n
	</FORM>\n";

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
*/
?>
