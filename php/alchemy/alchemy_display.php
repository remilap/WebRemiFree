<?php
	$title = "User display";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionAlchemyBD.class.php');
	$tpl = new TemplateEngine($home."/template_lab.html");
	$tpl->Output( $tpl->GetHTMLCode("entete_lab") );

	$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
	$util = new Util($debug);
	$util->traceVariables();

	$alchemyBD = new GestionAlchemyBD(0);
	if (! $alchemyBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $alchemyBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$alchemyBD->messageSGBD()."</FONT></B></P>\n";
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

	$resList = $alchemyBD->getAllElems($selectedElem);
	if (! $resList) {
		echo "<P><B><FONT COLOR='RED'>No element found with id ".$selectedElem."</FONT></B></P>\n";
		exit (1);
	}

	$unElem = $alchemyBD->getNextLine($resList);

	if ($action == "delete") {
		echo "Please confirm the following deletion:<br>\n";
	}
?>
<TABLE>
	<TR><TD>Element name</TD><TD>:</TD><TD><?=$unElem->name?></TD></TR>
	<TR><TD>Terminal element</TD><TD>:</TD><TD><?=$unElem->terminal?></TD></TR>
</TABLE>
<?

	$tpl->Output( $tpl->GetHTMLCode('baspage_lab') );
?>
