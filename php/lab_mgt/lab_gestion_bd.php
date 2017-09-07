<?php
	$title = "Lab list";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('BDMySQL.class.php');
	$tpl = new TemplateEngine($home."/template_lab.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );

	$util = new Util(0);

	$imgAbsDir = $homeDir . "/img/";
	$imgRelDir = "../img";
	$jvsAbsDir = $siteWeb . "/javascript/";
	$phpDir = $siteWeb . "/php/";

	define ("NOM","remi.lapointe");
	define ("PASSE", "rem000");
	define ("SERVEUR", "localhost");
	define ("BASE", "remi_lapointe");

	$table_labs = "lab_liste";
	$reqCreate = "CREATE TABLE IF NOT EXISTS ".$table_labs." (
		id smallint(2) NOT NULL default '0',
		hostname varchar(50) NOT NULL default '',
		platform varchar(10) NOT NULL default '',
		primary_ip_addr varchar(50) default '',
		primary_netmask varchar(50) default '',
		primary_gateway varchar(50) default '',
		secondary_ip_addr varchar(50)default '',
		secondary_netmask varchar(50) default '',
		secondary_gateway varchar(50) default '',
		backup_ip_addr varchar(50) default '',
		backup_netmask varchar(50) default '',
		backup_gateway varchar(50) default '',
		ilo2_ip_addr varchar(50) default '',
		ilo2_netmask varchar(50) default '',
		ilo2_gateway varchar(50) default '',
		server_type varchar(20) default '',
		processor varchar(20) default '',
		memory smallint(2) default '0',
		disks varchar(50) default '',
		installed_release varchar(20) default '',
		axadmin_password varchar(20) default '',
		root_password varchar(20) default '',
		affectation varchar(50) default '',
		comment varchar(500) default '',
		PRIMARY KEY (id)
	)";

	// Connexion à la base
	$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

	$debug = isset($_REQUEST['debug']) ? $bd->quote($_REQUEST['debug']) : "";

	$resCreate = $bd->execRequete($reqCreate);
	if (!$resCreate) {
?>
<P><B><FONT COLOR='RED'>Error during <?=$table_labs?> database creation</FONT></B></P>
<P><B><FONT COLOR='RED'>Request: <?=$reqCreate?></FONT></B></P>
<P><B><FONT COLOR='RED'>Error: <?=$bd->messageSGBD()?></FONT></B></P>
<?
		exit (1);
	}

	$util->debugOff();
	if ($debug) $util->debugOn();
	$util->traceVariables();

	$reqList = "SELECT * FROM ".$table_labs;
	$util->trace("List request=".$reqList);
	$resList = $bd->execRequete($reqList);

?>
<FORM METHOD='POST' ACTION='lab_modify.php' NAME='form_liste'>
<INPUT TYPE='HIDDEN' NAME='selectedLab' VALUE='none' SIZE='0' MAXLENGTH='0'>
<INPUT TYPE='SUBMIT' NAME='addLab' VALUE='Add a lab'>
<TABLE BORDER="1">
<TR>
	<TD ROWSPAN="2"><FONT SIZE="-1">mod</FONT></TD>
	<TD ROWSPAN="2"><FONT SIZE="-1">del</FONT></TD>
	<TH ROWSPAN="2">Hostname</TH>
	<TH ROWSPAN="2">Platform</TH>
	<TH COLSPAN="3">Primary interface</TH>
	<TH COLSPAN="3">Secondary interface</TH>
	<TH COLSPAN="3">Backup interface</TH>
	<TH COLSPAN="3">iLO2 interface</TH>
	<TH ROWSPAN="2">Server type</TH>
	<TH ROWSPAN="2">Processor</TH>
	<TH ROWSPAN="2">Memory (GB)</TH>
	<TH ROWSPAN="2">Disk(s)</TH>
	<TH ROWSPAN="2">Installed release</TH>
	<TH ROWSPAN="2">axadmin password</TH>
	<TH ROWSPAN="2">root password</TH>
	<TH ROWSPAN="2">Affectation</TH>
	<TH ROWSPAN="2">Comments</TH>
</TR>
	<TH>IP Address</TH>
	<TH>Netmask</TH>
	<TH>Gateway</TH>
	<TH>IP Address</TH>
	<TH>Netmask</TH>
	<TH>Gateway</TH>
	<TH>IP Address</TH>
	<TH>Netmask</TH>
	<TH>Gateway</TH>
	<TH>IP Address</TH>
	<TH>Netmask</TH>
	<TH>Gateway</TH>
<TR>
</TR>

<?php

	$nbLabs = 0;
	while ($unLab = $bd->objetSuivant($resList)) {
		$nbLabs++;
		$options_lig = "";
		if ($nbLabs % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		echo "<TD><A HREF='lab_modify.php?selectedLab=".$unLab->id."&action=modify'><IMG SRC='b_edit.png' ALT='modify'></A> </TD>\n";
		echo "<TD><A HREF='lab_display.php?selectedLab=".$unLab->id."&action=delete'><IMG SRC='b_drop.png' ALT='delete'></A> </TD>\n";
		//echo "<TD>" . $unLab->id . "</TD>\n";
		echo "<TD><A HREF='lab_display.php?selectedLab=".$unLab->id."&action=display'>" . $unLab->hostname . "</A></TD>\n";
		echo "<TD>" . $unLab->platform . "</TD>\n";
		if ($unLab->primary_ip_addr) {
			echo "<TD>" . $unLab->primary_ip_addr . "</TD>\n";
			echo "<TD>" . $unLab->primary_netmask . "</TD>\n";
			echo "<TD>" . $unLab->primary_gateway . "</TD>\n";
		} else {
			echo "<TD COLSPAN='3'>&nbsp;</TD>\n";
		}
		if ($unLab->secondary_ip_addr) {
			echo "<TD>" . $unLab->secondary_ip_addr . "</TD>\n";
			echo "<TD>" . $unLab->secondary_netmask . "</TD>\n";
			echo "<TD>" . $unLab->secondary_gateway . "</TD>\n";
		} else {
			echo "<TD COLSPAN='3'>&nbsp;</TD>\n";
		}
		if ($unLab->backup_ip_addr) {
			echo "<TD>" . $unLab->backup_ip_addr . "</TD>\n";
			echo "<TD>" . $unLab->backup_netmask . "</TD>\n";
			echo "<TD>" . $unLab->backup_gateway . "</TD>\n";
		} else {
			echo "<TD COLSPAN='3'>&nbsp;</TD>\n";
		}
		if ($unLab->ilo2_ip_addr) {
			echo "<TD>" . $unLab->ilo2_ip_addr . "</TD>\n";
			echo "<TD>" . $unLab->ilo2_netmask . "</TD>\n";
			echo "<TD>" . $unLab->ilo2_gateway . "</TD>\n";
		} else {
			echo "<TD COLSPAN='3'>&nbsp;</TD>\n";
		}
		echo "<TD>" . ($unLab->server_type?$unLab->server_type:"&nbsp;") . "</TD>\n";
		echo "<TD>" . ($unLab->processor?$unLab->processor:"&nbsp;") . "</TD>\n";
		echo "<TD>" . ($unLab->memory?$unLab->memory:"&nbsp;") . "</TD>\n";
		echo "<TD>" . ($unLab->disks?$unLab->disks:"&nbsp;") . "</TD>\n";
		echo "<TD>" . ($unLab->installed_release?$unLab->installed_release:"&nbsp;") . "</TD>\n";
		echo "<TD>" . ($unLab->axadmin_password?$unLab->axadmin_password:"&nbsp;") . "</TD>\n";
		echo "<TD>" . ($unLab->root_password?$unLab->root_password:"&nbsp;") . "</TD>\n";
		echo "<TD>" . ($unLab->affectation?$unLab->affectation:"&nbsp;") . "</TD>\n";
		echo "<TD>" . ($unLab->comment?$unLab->comment:"&nbsp;") . "</TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbLabs == 0) {
		$msg = "None lab found in the database<BR />\n";
	}
?>

</TABLE>
<?=$msg?>
<BR />
<INPUT TYPE='SUBMIT' NAME='addLab' VALUE='Add a lab'>
<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='1' <?=$debug?"CHECKED":""?>/><LABEL FOR='debug'>Debug</LABEL></P>
</FORM>

<?php

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
