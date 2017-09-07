<?php
	$title = "Suivi de match de handball";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionMatchBD.class.php');
	$tpl = new TemplateEngine($home."/template_hand.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_hand") );

	$imgAbsDir = $homeDir . "/img/";
	$imgRelDir = "../img";
	$jvsAbsDir = $siteWeb . "/javascript/";
	$phpDir = $siteWeb . "/php/";

	$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
	$util = new Util($debug);
	//$util->setDebugOff();
	//if ($debug) $util->setDebugOn();
	$util->traceVariables();

	$matchBD = new GestionMatchBD(0);
	if (! $matchBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

/*
	$resCreate = $matchBD->init();
	if ($resCreate) {
		echo "<P><B><FONT COLOR='RED'>ERROR ".$resCreate."<BR>".$matchBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}
*/

?>
<A HREF="event_list.php"><BUTTON NAME='listEvent' VALUE='Suivi du match' onClick='event_list.php'>Suivi du match</BUTTON></A>
<A HREF="event_gener.php"><BUTTON NAME='generEvent' VALUE='Generer des evenements' onClick='event_gener.php'>Generer des evenements</BUTTON></A>
<FORM METHOD='POST' ACTION='event_affec.php' NAME='form_affec'>
<INPUT TYPE='HIDDEN' NAME='selectedMatch' VALUE='none' SIZE='0' MAXLENGTH='0'>
<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='<?=$debug?>' <?=$debug?"CHECKED":""?>/><LABEL FOR='debug'>Debug</LABEL></P>
</FORM>

<?php

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_hand') );
?>
