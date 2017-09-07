<?php

require_once 'meekrodb.2.3.class.php';
DB::$user = 'remi.lapointe';
DB::$password = 'rem001';
DB::$dbName = 'remi_lapointe';
DB::$host = 'localhost';

$table = 'bookmark_chapter';

DB::debugMode();

$current_db_tables = DB::tableList();
foreach ($current_db_tables as $table) {
	echo "Table name: $table<br />";
}

DB::query("CREATE TABLE IF NOT EXISTS ".$table." (
	id smallint(2) NOT NULL default '0',
	nom varchar(50) NOT NULL default '',
	PRIMARY KEY (id)
	)");

DB::insert($table, array('id' => 0, 'name' => 'toto'));
DB::insert($table, array('id' => 1, 'name' => 'tutu'));


?>
