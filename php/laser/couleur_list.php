<?php
	$title = "Couleur list";
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

?>
<FORM METHOD='POST' ACTION='couleur_modify.php' NAME='form_couleur_liste'>
<INPUT TYPE='HIDDEN' NAME='selectedCouleur' VALUE='none' SIZE='0' MAXLENGTH='0'>
<INPUT TYPE='SUBMIT' NAME='addCouleur' VALUE='Add a couleur'>
<TABLE BORDER="1">
<TR>
	<TD><FONT SIZE="-1">mod</FONT></TD>
	<TD><FONT SIZE="-1">del</FONT></TD>
	<TH>Id</TH>
	<TH>Couleur</TH>
</TR>

<?php

	$resList = $laserBD->getAllCouleurs();
	$nbCouleurs = 0;
	while ($uneCouleur = $laserBD->getNextLine($resList)) {
		$nbCouleurs++;
		$options_lig = "";
		if ($nbCouleurs % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		echo "<TD><A HREF='couleur_modify.php?selectedUser=".$uneCouleur->id."&action=modify'><IMG SRC='b_edit.png' ALT='modify'></A> </TD>\n";
		echo "<TD><A HREF='couleur_display.php?selectedUser=".$uneCouleur->id."&action=delete'><IMG SRC='b_drop.png' ALT='delete'></A> </TD>\n";
		echo "<TD>" . $uneCouleur->id . "</TD>\n";
		echo "<TD><A HREF='couleur_display.php?selectedUser=".$uneCouleur->id."&action=display'>" . $uneCouleur->username . "</A></TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbCouleurs == 0) {
		$msg = "None couleur found in the database<BR />\n";
	}
?>

</TABLE>
<?=$msg?>
<BR />
<INPUT TYPE='SUBMIT' NAME='addCouleur' VALUE='Add a couleur'>
<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='<?=$debug?>' <?=$debug?"CHECKED":""?>/><LABEL FOR='debug'>Debug</LABEL></P>
</FORM>

<?php

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
