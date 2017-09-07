<?php
	$title = "Suivi match";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionMatchBD.class.php');
	$tempo_reload = 5000;
	$tpl = new TemplateEngine($home."/template_hand.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_hand") );

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

	$resCnx = $matchBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$matchBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}
//	<TD><FONT SIZE="-1">mod</FONT></TD>
//	<TD><FONT SIZE="-1">del</FONT></TD>

?>
<FORM METHOD='POST' ACTION='user_modify.php' NAME='form_user_liste'>
<INPUT TYPE='HIDDEN' NAME='selectedUser' VALUE='none' SIZE='0' MAXLENGTH='0'>
<TABLE BORDER="1">
<TR>
	<TH>Temps</TH>
	<TH>Score</TH>
	<TH>Action</TH>
</TR>

<?php
//	<TH>equipe</TH>
//	<TH>numero joueur</TH>
//	<TH>nom joueur</TH>

	$resList = $matchBD->getAllEvents();
	$nbEvents = 0;
	$equipe_rv = "";
	while ($unEvent = $matchBD->getNextLine($resList)) {
		$nbEvents++;
		$options_lig = "";
		if ($nbEvents % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
//		echo "<TD><A HREF='user_modify.php?selectedUser=".$unEvent->id."&action=modify'><IMG SRC='b_edit.png' ALT='modify'></A> </TD>\n";
//		echo "<TD><A HREF='user_display.php?selectedUser=".$unEvent->id."&action=delete'><IMG SRC='b_drop.png' ALT='delete'></A> </TD>\n";
		//echo "<TD>" . $unEvent->id . "</TD>\n";
//		echo "<TD><A HREF='user_display.php?selectedUser=".$unEvent->id."&action=display'>" . $unEvent->username . "</A></TD>\n";
		if ($unEvent->equipe == 1) {
			echo "<TD><FONT COLOR=\"blue\">";
			$equipe_rv = "JR";
		} else {
			echo "<TD><FONT COLOR=\"red\">";
			$equipe_rv = "JV";
		}
		echo "<CENTER>" . ($unEvent->temps?$unEvent->temps:"&nbsp;") . "</CENTER></FONT></TD>\n";
		if ($unEvent->equipe == 1) {
			echo "<TD><FONT COLOR=\"blue\">";
		} else {
			echo "<TD><FONT COLOR=\"red\">";
		}
		echo "<CENTER>" . ($unEvent->score?$unEvent->score:"&nbsp;") . "</CENTER></FONT></TD>\n";
		if ($unEvent->equipe == 1) {
			echo "<TD><FONT COLOR=\"blue\">";
		} else {
			echo "<TD><FONT COLOR=\"red\">";
		}
		switch ($unEvent->action) {
			case 1:
				echo "But";
				break;
			case 2:
				echo "Tir";
				break;
			case 3:
				echo "Arrêt";
				break;
			case 4:
				echo "Avertissement";
				break;
			case 5:
				echo "2MN";
				break;
			case 6:
				echo "But 7m";
				break;
			case 7:
				echo "Temps Mort ";
				break;
			default:
				echo "action inconnue";
		}
		echo " " . $equipe_rv . " N°" . $unEvent->num_joueur . " " . $unEvent->nom_joueur . "</FONT></TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbEvents == 0) {
		$msg = "None event found in the database<BR />\n";
	}
?>

</TABLE>
<?=$msg?>
<BR />
<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='<?=$debug?>' <?=$debug?"CHECKED":""?>/><LABEL FOR='debug'>Debug</LABEL></P>
</FORM>

<?php

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_hand') );
?>
