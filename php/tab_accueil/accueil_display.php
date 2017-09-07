<?php
	$title = "Bookmark display";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionAccueilBD.class.php');
	$tpl = new TemplateEngine($home."/template_lab.html");
	$tpl->Output( $tpl->GetHTMLCode("entete_lab") );

	$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
	$util = new Util($debug);
	$util->traceVariables();

	$accueilBD = new GestionAccueilBD(0);
	if (! $accueilBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $accueilBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$accueilBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
	$selectedElem = isset($_REQUEST['selectedElem']) ? $_REQUEST['selectedElem'] : "";

	if ($selectedElem == "") {
		echo "<P><B><FONT COLOR='RED'>Please select an element</FONT></B></P>\n";
		exit (1);
	}

	if ($action != "display" && $action != "delete") {
		echo "<P><B><FONT COLOR='RED'>Incorrect action (".$action.")</FONT></B></P>\n";
		exit (1);
	}

	$resList = $accueilBD->getAllElems($accueilBD->getBookmarkTableName(), $selectedElem);
	if (! $resList) {
		echo "<P><B><FONT COLOR='RED'>No element found with id ".$selectedElem."</FONT></B></P>\n";
		exit (1);
	}

	$unElem = $accueilBD->getNextLine($resList);
	$util->trace("selectedElem: ".$selectedElem.", result bookmark id: ".$unElem->id.", name: ".$unElem->name.", id_column: ".$unElem->id_column);
	$resColumn = $accueilBD->getAllElems($accueilBD->getColumnTableName(), $unElem->id_column);
	if (! $resColumn) {
		echo "<P><B><FONT COLOR='RED'>No column found with id ".$unElem->id_column."</FONT></B></P>\n";
		exit (1);
	}
	$theColumn = $accueilBD->getNextLine($resColumn);
	$util->trace("result column id: ".$theColumn->id.", name: ".$theColumn->name.", id_chapter: ".$theColumn->id_chapter);
	$resChapter = $accueilBD->getAllElems($accueilBD->getChapterTableName(), $theColumn->id_chapter);
	if (! $resChapter) {
		echo "<P><B><FONT COLOR='RED'>No chapter found with id ".$theColumn->id_chapter."</FONT></B></P>\n";
		exit (1);
	}
	$theChapter = $accueilBD->getNextLine($resChapter);
	$util->trace("result chapter id: ".$theChapter->id.", name: ".$theChapter->name);

	if ($action == "delete") {
		echo "Please confirm the following deletion:<br>\n";
	}

	echo "<TABLE>\n
	<TR><TD>Element name</TD><TD>:</TD><TD>".$unElem->name."</TD></TR>\n
	<TR><TD>Chapter</TD><TD>:</TD><TD>".$theChapter->name."</TD></TR>\n
	<TR><TD>Column</TD><TD>:</TD><TD>".$theColumn->name."</TD></TR>\n
	</TABLE>\n";

	$tpl->Output( $tpl->GetHTMLCode('baspage_lab') );
?>
