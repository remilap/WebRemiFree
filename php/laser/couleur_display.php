<?php
	$title = "Laser display";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionLaserBD.class.php');
	$tpl = new TemplateEngine($home."/template_lab.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );

	$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
	$util = new Util($debug);
	//$util->setDebugOff();
	//if ($debug) $util->setDebugOn();
	$util->traceVariables();

	$laserBD = new GestionLaserBD(0);
	if (! $laserBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $laserBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$laserBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
	$selectedCouleur = isset($_REQUEST['selectedCouleur']) ? $_REQUEST['selectedCouleur'] : "";

	if ($selectedCouleur == "") {
		echo "<P><B><FONT COLOR='RED'>Please select a couleur</FONT></B></P>\n";
		exit (1);
	}

	if ($action != "display" && $action != "delete") {
		echo "<P><B><FONT COLOR='RED'>Incorrect action (".$action.")</FONT></B></P>\n";
		exit (1);
	}

	$resList = $laserBD->getAllCouleurs($selectedCouleur);
	if (! $resList) {
		echo "<P><B><FONT COLOR='RED'>No couleur found with id ".$selectedCouleur."</FONT></B></P>\n";
		exit (1);
	}

	$uneCouleur = $laserBD->getNextLine($resList);

	if ($action == "delete") {
		echo "Please confirm the following deletion:<br>\n";
	}
?>
<TABLE>
	<TR><TD>Id</TD><TD>:</TD><TD><?=$uneCouleur->id?></TD></TR>
	<TR><TD>Couleur</TD><TD>:</TD><TD><?=$uneCouleur->couleur?></TD></TR>
</TABLE>
<?
	if ($action == "delete") {
		echo "Please confirm the following deletion:<br>\n";
	}

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
