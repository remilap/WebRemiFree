<?php
	$title = "Lab list";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionLabBD.class.php');
	$tpl = new TemplateEngine($home."/template_lab.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );

	$imgAbsDir = $homeDir . "/img/";
	$imgRelDir = "../img";
	$jvsAbsDir = $siteWeb . "/javascript/";
	$phpDir = $siteWeb . "/php/";

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

	$resCreate = $labBD->init();
	if ($resCreate) {
		echo "<P><B><FONT COLOR='RED'>ERROR ".$resCreate."<BR>".$labBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

?>
<A HREF="lab_list.php"><BUTTON NAME='listLab' VALUE='Display lab list' onClick='lab_list.php'>Display lab list</BUTTON></A>
<A HREF="user_list.php"><BUTTON NAME='listUser' VALUE='Display user list' onClick='user_list.php'>Display user list</BUTTON></A>
<FORM METHOD='POST' ACTION='lab_affec.php' NAME='form_affec'>
<INPUT TYPE='HIDDEN' NAME='selectedLab' VALUE='none' SIZE='0' MAXLENGTH='0'>
<TABLE BORDER="1">
<TR>
	<TH ROWSPAN="2">Hostname</TH>
	<TH COLSPAN="7">Lundi</TH>
	<TH COLSPAN="7">Mardi</TH>
	<TH COLSPAN="7">Mercredi</TH>
	<TH COLSPAN="7">Jeudi</TH>
	<TH COLSPAN="7">Vendredi</TH>
</TR>
	<TH>00:00</TH><TH>08:00</TH><TH>10:00</TH><TH>12:00</TH><TH>14:00</TH><TH>16:00</TH><TH>18:00</TH>
	<TH>00:00</TH><TH>08:00</TH><TH>10:00</TH><TH>12:00</TH><TH>14:00</TH><TH>16:00</TH><TH>18:00</TH>
	<TH>00:00</TH><TH>08:00</TH><TH>10:00</TH><TH>12:00</TH><TH>14:00</TH><TH>16:00</TH><TH>18:00</TH>
	<TH>00:00</TH><TH>08:00</TH><TH>10:00</TH><TH>12:00</TH><TH>14:00</TH><TH>16:00</TH><TH>18:00</TH>
	<TH>00:00</TH><TH>08:00</TH><TH>10:00</TH><TH>12:00</TH><TH>14:00</TH><TH>16:00</TH><TH>18:00</TH>
<TR>
</TR>

<?php

	$resList = $labBD->getAllAffects();
	$nbLabs = 0;
	while ($unLab = $labBD->getNextLine($resList)) {
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
<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='<?=$debug?>' <?=$debug?"CHECKED":""?>/><LABEL FOR='debug'>Debug</LABEL></P>
</FORM>

<?php

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
