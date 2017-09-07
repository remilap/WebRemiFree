<?php
	$title = "User list";
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

?>
<FORM METHOD='POST' ACTION='user_modify.php' NAME='form_user_liste'>
<INPUT TYPE='HIDDEN' NAME='selectedUser' VALUE='none' SIZE='0' MAXLENGTH='0'>
<INPUT TYPE='SUBMIT' NAME='addUser' VALUE='Add a user'>
<TABLE BORDER="1">
<TR>
	<TD><FONT SIZE="-1">mod</FONT></TD>
	<TD><FONT SIZE="-1">del</FONT></TD>
	<TH>User name</TH>
	<TH>email</TH>
	<TH>Phone</TH>
</TR>

<?php

	$resList = $labBD->getAllUsers();
	$nbUsers = 0;
	while ($unUser = $labBD->getNextLine($resList)) {
		$nbUsers++;
		$options_lig = "";
		if ($nbUsers % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		echo "<TD><A HREF='user_modify.php?selectedUser=".$unUser->id."&action=modify'><IMG SRC='b_edit.png' ALT='modify'></A> </TD>\n";
		echo "<TD><A HREF='user_display.php?selectedUser=".$unUser->id."&action=delete'><IMG SRC='b_drop.png' ALT='delete'></A> </TD>\n";
		//echo "<TD>" . $unUser->id . "</TD>\n";
		echo "<TD><A HREF='user_display.php?selectedUser=".$unUser->id."&action=display'>" . $unUser->username . "</A></TD>\n";
		echo "<TD>" . ($unUser->email?$unUser->email:"&nbsp;") . "</TD>\n";
		echo "<TD>" . ($unUser->phone?$unUser->phone:"&nbsp;") . "</TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbUsers == 0) {
		$msg = "None user found in the database<BR />\n";
	}
?>

</TABLE>
<?=$msg?>
<BR />
<INPUT TYPE='SUBMIT' NAME='addUser' VALUE='Add a user'>
<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='<?=$debug?>' <?=$debug?"CHECKED":""?>/><LABEL FOR='debug'>Debug</LABEL></P>
</FORM>

<?php

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
