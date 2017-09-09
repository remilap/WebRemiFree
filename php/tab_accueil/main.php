<?php

require_once('common.php');
$title = "Table accueil";
$date = date( "Ymd", getlastmod());

?>
<FORM METHOD='POST' ACTION='bookmarks_list.php' NAME='form_listbookmarks'>
<INPUT TYPE='SUBMIT' NAME='listbookmarks' VALUE='<?php echo L::form_listbookmarks; ?>'>
<INPUT TYPE='HIDDEN' NAME='debug' id='debug' VALUE='<?php echo $debug; ?>'>
</FORM>

<FORM METHOD='POST' ACTION='chapters_list.php' NAME='form_listchapters'>
<INPUT TYPE='SUBMIT' NAME='listchapters' VALUE='<?php echo L::form_listchapters; ?>'>
<INPUT TYPE='HIDDEN' NAME='debug' id='debug' VALUE='<?php echo $debug; ?>'>
</FORM>

<FORM METHOD='POST' ACTION='columns_list.php' NAME='form_listcolumns'>
<INPUT TYPE='SUBMIT' NAME='listcolumns' VALUE='<?php echo L::form_listcolumns; ?>'>
<INPUT TYPE='HIDDEN' NAME='debug' id='debug' VALUE='<?php echo $debug; ?>'>
</FORM>

<FORM METHOD='POST' ACTION='users_list.php' NAME='form_listusers'>
<INPUT TYPE='SUBMIT' NAME='listusers' VALUE='<?php echo L::form_listusers; ?>'>
<INPUT TYPE='HIDDEN' NAME='debug' id='debug' VALUE='<?php echo $debug; ?>'>
</FORM>

<FORM METHOD='POST' ACTION='bookmark_modify.php' NAME='form_modifybookmark'>
<INPUT TYPE='HIDDEN' NAME='selectedElem' VALUE='none' SIZE='0' MAXLENGTH='0'>
<INPUT TYPE='SUBMIT' NAME='addBookmark' VALUE='<?php echo L::form_addbookmark; ?>'>
<TABLE BORDER="1">
<TR>
	<TH><FONT SIZE="-1"><?php echo L::tbl_mod; ?></FONT></TH>
	<TH><FONT SIZE="-1"><?php echo L::tbl_del; ?></FONT></TH>
	<TH><?php echo L::tbl_bookmark; ?></TH>
	<TH><?php echo L::tbl_chapter; ?></TH>
	<TH><?php echo L::tbl_column; ?></TH>
	<TH><?php echo L::tbl_user; ?></TH>
</TR>

<?php

getBookmarks();

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

	echo "</TABLE>\n
	<BR />\n
	<INPUT TYPE='SUBMIT' NAME='addBookmark' VALUE='Add a bookmark'>\n
	<INPUT TYPE='SUBMIT' NAME='addChapter' VALUE='Add a chapter'>\n
	<INPUT TYPE='SUBMIT' NAME='addColumn' VALUE='Add a column'>\n
	<INPUT TYPE='SUBMIT' NAME='addUser' VALUE='Add a user'>\n
	<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='".$debug."' ".($debug?"CHECKED":"")."/><LABEL FOR='debug'>Debug</LABEL></P>\n
	</FORM>\n";

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
