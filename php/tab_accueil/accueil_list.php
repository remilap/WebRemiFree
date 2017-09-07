<?php
	$title = "Table accueil";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionAccueilBD.class.php');
	$tpl = new TemplateEngine($home."/template_lab.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );

	$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
	$util = new Util($debug);
	$util->traceVariables();

	$accueilBD = new GestionAccueilBD($debug);
	if (! $accueilBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $accueilBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$accueilBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

	$resCreate = $accueilBD->init();
	if ($resCreate) {
		echo "<P><B><FONT COLOR='RED'>ERROR ".$resCreate."<BR>".$accueilBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

?>
<FORM METHOD='POST' ACTION='accueil_modify.php' NAME='form_accueil_liste'>
<INPUT TYPE='HIDDEN' NAME='selectedElem' VALUE='none' SIZE='0' MAXLENGTH='0'>
<INPUT TYPE='SUBMIT' NAME='addBookmark' VALUE='Add a bookmark'>
<TABLE BORDER="1">
<TR>
	<TH><FONT SIZE="-1">mod</FONT></TH>
	<TH><FONT SIZE="-1">del</FONT></TH>
	<TH>Bookmark name</TH>
	<TH>Chapter</TH>
	<TH>Column</TH>
	<TH>User</TH>
</TR>

<?php

	$listChapters = $accueilBD->getAllElems($accueilBD->getChapterTableName());
	$nbChapters = 0;
	while ($unElem = $accueilBD->getNextLine($listChapters)) {
		$nbChapters++;
		$chaptersId[$nbChapters] = $unElem->id;
		$chaptersName[$unElem->id] = $unElem->name;
	}
	$listColumns = $accueilBD->getAllElems($accueilBD->getColumnTableName());
	$nbColumns = 0;
	while ($unElem = $accueilBD->getNextLine($listColumns)) {
		$nbColumns++;
		$columnsId[$nbColumns] = $unElem->id;
		$columnsName[$unElem->id] = $unElem->name;
	}
	$listUsers = $accueilBD->getAllElems($accueilBD->getUserTableName());
	$nbUsers = 0;
	while ($unElem = $accueilBD->getNextLine($listUsers)) {
		$nbUsers++;
		$usersId[$nbUsers] = $unElem->id;
		$usersName[$unElem->id] = $unElem->name;
	}

	$listBookmarks = $accueilBD->getAllElems($accueilBD->getBookmarkTableName());
	$nbBookmarks = 0;
	while ($unBookmark = $accueilBD->getNextLine($listBookmarks)) {
		$nbBookmarks++;
		$options_lig = "";
		if ($nbBookmarks % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		echo "<TD><A HREF='accueil_modify.php?selectedElem=".$unElem->id."&action=modify'><IMG SRC='b_edit.png' ALT='modify'></A> </TD>\n";
		echo "<TD><A HREF='accueil_display.php?selectedElem=".$unElem->id."&action=delete'><IMG SRC='b_drop.png' ALT='delete'></A> </TD>\n";
		//echo "<TD>" . $unElem->id . "</TD>\n";
		echo "<TD><A HREF='accueil_display.php?selectedElem=".$unElem->id."&action=display'>" . $unElem->name . "</A></TD>\n";
		echo "<TD>" . $chaptersName[$unElem->id] . "</TD>\n";
		echo "<TD>" . $columnsName[$unElem->id] . "</TD>\n";
		echo "<TD>" . $usersName[$unElem->id] . "</TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbElems == 0) {
		$msg = "None element found in the database<BR />\n";
	}
?>

</TABLE>
<?=$msg?>
<BR />
<INPUT TYPE='SUBMIT' NAME='addBookmark' VALUE='Add a bookmark'>
<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='<?=$debug?>' <?=$debug?"CHECKED":""?>/><LABEL FOR='debug'>Debug</LABEL></P>
</FORM>

<?php

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
