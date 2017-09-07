<?php
	$title = "Lab display";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionLabBD.class.php');
	$tpl = new TemplateEngine($home."/template_lab.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );

	$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
	$util = new Util($debug);
	//$util->setDebugOff();
	//if ($debug) $util->setDebugOn();
	$util->traceVariables();

	$labBD = new GestionLabBD(0);
	if (! $labBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $labBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$labBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
	$selectedUser = isset($_REQUEST['selectedUser']) ? $_REQUEST['selectedUser'] : "";

	if ($selectedUser == "") {
		echo "<P><B><FONT COLOR='RED'>Please select a user</FONT></B></P>\n";
		exit (1);
	}

	if ($action != "display" && $action != "delete") {
		echo "<P><B><FONT COLOR='RED'>Incorrect action (".$action.")</FONT></B></P>\n";
		exit (1);
	}

	$resList = $labBD->getAllUsers($selectedUser);
	if (! $resList) {
		echo "<P><B><FONT COLOR='RED'>No user found with id ".$selectedUser."</FONT></B></P>\n";
		exit (1);
	}

	$unUser = $labBD->getNextLine($resList);

	if ($action == "delete") {
		echo "Please confirm the following deletion:<br>\n";
	}
?>
<TABLE>
	<TR><TD>User name</TD><TD>:</TD><TD><?=$unUser->username?></TD></TR>
	<TR><TD>e-mail</TD><TD>:</TD><TD><?=$unUser->email?></TD></TR>
	<TR><TD>Phone</TD><TD>:</TD><TD><?=$unUser->phone?></TD></TR>
</TABLE>
<?
	if ($action == "delete") {
		echo "Please confirm the following deletion:<br>\n";
	}

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
