<?php

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

$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

$date = (isset($date) ? $date : date( "Ymd", getlastmod()));
$title = (isset($title) ? $title : L::txt_common);

$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
$util = new Util($debug);

//DB::$host = 'sql.free.fr';
//DB::$host = '127.0.0.1';
DB::$host = $_SERVER['SERVER_NAME'];
DB::$dbName = 'remi_lapointe';
#DB::$encoding = 'utf8';
DB::$user = 'remi.lapointe';
DB::$password = 'rem001';

$action_debug = "";
if ($util->getDebugStatus()) $action_debug = "&debug=".$util->getDebugStatus();

// Create all the tables if needed
function createTables() {
	global $tables, $util;
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
}

// Returns the next free id for a table
function getNextFreeId($tableName) {
	global $util;
	$result = 1;
	$requete = "SELECT max(`id`) FROM ".$tableName;
	$util->trace("requete: ".$requete);
	$results = DB::queryFirstRow($requete);
	$util->trace("results: ".var_dump($row));
	$result = $row["max(`id`)"] + 1;
	return $result;
}

// Insert a new record in a table
function insertARecord($tableName, $fieldsNameValue) {
	$reqInsert = "INSERT INTO ".$tableName." VALUES (".$fieldsValues.")";
	$this->getUtil()->trace("Request=".$reqInsert);
	$resInsert = DB::insert($tableName, $fieldsNameValue);
}


?>
