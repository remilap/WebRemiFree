<?php
	$title = "User display";
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
	$selectedLab = isset($_REQUEST['selectedLab']) ? $_REQUEST['selectedLab'] : "";

	if ($selectedLab == "") {
		echo "<P><B><FONT COLOR='RED'>Please select a lab</FONT></B></P>\n";
		exit (1);
	}

	if ($action != "display" && $action != "delete") {
		echo "<P><B><FONT COLOR='RED'>Incorrect action (".$action.")</FONT></B></P>\n";
		exit (1);
	}

	$resList = $labBD->getAllLabs($selectedLab);
	if (! $resList) {
		echo "<P><B><FONT COLOR='RED'>No lab found with id ".$selectedLab."</FONT></B></P>\n";
		exit (1);
	}

	$unLab = $labBD->getNextLine($resList);

	if ($action == "delete") {
		echo "Please confirm the following deletion:<br>\n";
	}
?>
<TABLE>
	<TR><TD>Hostname</TD><TD>:</TD><TD><?=$unLab->hostname?></TD></TR>
	<TR><TD>Platform</TD><TD>:</TD><TD><?=$unLab->platform?></TD></TR>
	<TR><TD>Primary interface</TD><TD>:</TD><TD><?=$unLab->primary_ip_addr?> / netmask=<?=$unLab->primary_netmask?> / gateway=<?=$unLab->primary_gateway?></TD></TR>
	<TR><TD>Secondary interface</TD><TD>:</TD><TD><?=$unLab->secondary_ip_addr?> / netmask=<?=$unLab->secondary_netmask?> / gateway=<?=$unLab->secondary_gateway?></TD></TR>
	<TR><TD>Backup interface</TD><TD>:</TD><TD><?=$unLab->backup_ip_addr?> / netmask=<?=$unLab->backup_netmask?> / gateway=<?=$unLab->backup_gateway?></TD></TR>
	<TR><TD>iLO2 interface</TD><TD>:</TD><TD><?=$unLab->ilo2_ip_addr?> / netmask=<?=$unLab->ilo2_netmask?> / gateway=<?=$unLab->ilo2_gateway?></TD></TR>
	<TR><TD>Server type</TD><TD>:</TD><TD><?=$unLab->server_type?></TD></TR>
	<TR><TD>Processor</TD><TD>:</TD><TD><?=$unLab->processor?></TD></TR>
	<TR><TD>Memory</TD><TD>:</TD><TD><?=$unLab->memory?> GB</TD></TR>
	<TR><TD>Disks</TD><TD>:</TD><TD><?=$unLab->disks?></TD></TR>
	<TR><TD>Installed Release</TD><TD>:</TD><TD><?=$unLab->installed_release?></TD></TR>
	<TR><TD>axadmin password</TD><TD>:</TD><TD><?=$unLab->axadmin_password?></TD></TR>
	<TR><TD>root password</TD><TD>:</TD><TD><?=$unLab->root_password?></TD></TR>
	<TR><TD>Comments</TD><TD>:</TD><TD><?=$unLab->comment?></TD></TR>
</TABLE>
<?

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
