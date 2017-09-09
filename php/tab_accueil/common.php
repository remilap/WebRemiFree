<?php
$title = "Table accueil";
$date = date( "Ymd", getlastmod());
$home = "../..";
$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

require_once('TemplateEngine.php');
require_once('Util.class.php');
require_once('meekrodb.2.3.class.php');
// include i18n class and initialize it
require_once 'i18n.class.php';
$i18n = new i18n('lang/lang_{LANGUAGE}.json', 'langcache/', 'en');
// Parameters: language file path, cache dir, default language (all optional)
// init object: load language files, parse them if not cached, and so on.

$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : "en";
$i18n->setForcedLang($lang);
$i18n->init();
$tpl = new TemplateEngine($home."/template_lab.html");

TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );

$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
$util = new Util($debug);
$util->traceVariables();

$tables[] = [
	"tbl" => "bookmarks_chapter",
	"id" => ["smallint(2)", "NOT NULL default '0'", "PRIMARY"],
	"name" => ["varchar(50)", "NOT NULL default ''"],
];
$tables[] = [
	"tbl" => "bookmarks_category",
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
	"tbl" => "bookmarks_list",
	"id" => ["smallint(2)", "NOT NULL default '0'", "PRIMARY"],
	"name" => ["varchar(50)", "NOT NULL default ''"],
	"URL" => ["varchar(120)", "NOT NULL default ''"],
	"id_column" => ["smallint(2)", "NOT NULL default '0'"],
	"iconeName" => ["varchar(50)", "NOT NULL default ''"],
	"tabName" => ["varchar(50)", "NOT NULL default ''"],
	"id_user" => ["smallint(2)", "NOT NULL default '0'"],
];
$util->trace("tables[0]: ".$tables[0]["tbl"]);

//DB::$host = 'sql.free.fr';
//DB::$host = '127.0.0.1';
DB::$host = $_SERVER['SERVER_NAME'];
DB::$dbName = 'remi_lapointe';
#DB::$encoding = 'utf8';
DB::$user = 'remi.lapointe';
DB::$password = 'rem001';
$util->trace("host: ".DB::$host.", dbName: ".DB::$dbName.", user: ".DB::$user);

foreach ($tables as $tbl => $fields) {
	$reqCreate = "CREATE TABLE IF NOT EXISTS ".$fields["tbl"]." (";
	$primKey = "";
	foreach ($fields as $f => $args) {
		if ($f == "tbl") continue;
		$reqCreate .= $f." ".$args[0]." ".$args[1].", ";
		if (isset($args[2])) {
			$primKey = "PRIMARY KEY (".$f.")";
		}
	}
	$reqCreate .= $primKey.")";
	$util->trace("reqCreate: ".$reqCreate);
	$results = DB::query($reqCreate);
	$util->trace("results: ".$results);
}

$action_debug = "";
if ($debug) $action_debug = "&debug=".$debug;

function getChapters() {
	global $tables, $nbChapters, $chaptersId, $chaptersName, $util;
	$results = DB::query('SELECT * FROM '.$tables[0]["tbl"]);
	if ($results) {
		$nbChapters = 0;
		foreach ($results as $row) {
//		echo "<P>Id: " . $row['id'] . "<BR />\n";
//		echo "Name: " . $row['name'] . "<BR />\n";
//		echo "-------------</P>\n";
			$nbChapters++;
			$id = $row['id'];
			$name = $row['name'];
			$chaptersId[$nbChapters] = $id;
			$chaptersName[$id] = $name;
			$util->trace("chaptersName[".$id."] = ".$name);
		}
		$res = "";
	} else {
		$res = L::tbl_norecord($tables[0]["tbl"]);
	}
	return $res;
}

function getColumns() {
	global $tables, $nbColumns, $columnsId, $columnsName, $columnsIdChapter, $util;
	$results = DB::query('SELECT * FROM '.$tables[1]["tbl"]);
	if ($results) {
		$nbColumns = 0;
		foreach ($results as $row) {
			$nbColumns++;
			$id = $row['id'];
			$name = $row['name'];
			$id_chap = $row['id_chapter'];
			$columnsId[$nbChapters] = $id;
			$columnsName[$id] = $name;
			$columnsIdChapter[$id] = $id_chap;
			$util->trace("columnsName[".$id."] = ".$name.", id_chap = ".$id_chap);
		}
		$res = "";
	} else {
		$res = L::tbl_norecord($tables[1]["tbl"]);
	}
	return $res;
}

function getUsers() {
	global $tables, $nbUsers, $usersId, $usersName, $util;
	$results = DB::query('SELECT * FROM '.$tables[2]["tbl"]);
	if ($results) {
		$nbUsers = 0;
		foreach ($results as $row) {
			$nbUsers++;
			$id = $row['id'];
			$name = $row['name'];
			$usersId[$nbChapters] = $id;
			$usersName[$id] = $name;
			$util->trace("usersName[".$id."] = ".$name);
		}
		$res = "";
	} else {
		$res = L::tbl_norecord($tables[2]["tbl"]);
	}
	return $res;
}

function getBookmarks() {
	global $tables, $nbBookmarks, $booksId, $booksName, $booksURL, $booksIdColumn, $booksIconeName, $booksTabName, $booksIdUser, $util;
	$results = DB::query('SELECT * FROM '.$tables[3]["tbl"]);
	if ($results) {
		$nbBookmarks = 0;
		foreach ($results as $row) {
			$nbBookmarks++;
			$id = $row['id'];
			$name = $row['name'];
			$booksId[$nbBookmarks] = $id;
			$booksName[$id] = $name;
			$booksURL[$id] = $row['URL'];
			$booksIdColumn[$id] = $row['id_column'];
			$booksIconeName[$id] = $row['iconeName'];
			$booksTabName[$id] = $row['tabName'];
			$booksIdUser[$id] = $row['id_user'];
			$util->trace("booksmarks[".$id."] = ".$name);
/*
		$options_lig = "";
		if ($nbBookmarks % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		echo "<TD><center><A HREF='accueil_modify.php?selectedElem=".$row['id']."&action=modify".$action_debug."'><IMG SRC='../b_edit.png' ALT='modify'></A></center> </TD>\n";
		echo "<TD><center><A HREF='accueil_display.php?selectedElem=".$row['id']."&action=delete".$action_debug."'><IMG SRC='../b_drop.png' ALT='delete'></A></center> </TD>\n";
		echo "<TD>" . $id . "</TD>\n";
		echo "<TD>" . $id . "</TD>\n";
		echo "<TD>" . $name . "</TD>\n";
		$user_name = "unknown";
		echo "<TD>" . $user_name . "</TD>\n";
		echo "<TR>\n";
*/
		}
		$res = "";
	} else {
		$res = L::tbl_norecord($tables[3]["tbl"]);
	}
	return $res;
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
