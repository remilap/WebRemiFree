<?php
	$title = "Alchemy";
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

	$alchemyBD = new GestionAlchemyBD($debug);
	if (! $alchemyBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $alchemyBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$alchemyBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

	$resCreate = $alchemyBD->init();
	if ($resCreate) {
		echo "<P><B><FONT COLOR='RED'>ERROR ".$resCreate."<BR>".$alchemyBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

?>
<FORM METHOD='POST' ACTION='alchemy_add.php' NAME='form_lab_liste'>
<TABLE>
<TR>
<TD><INPUT TYPE='BUTTON' NAME='listElem' VALUE='List the elements' onClick='location.href="list_elem.php?typ=elem"'></TD>
<TD><INPUT TYPE='BUTTON' NAME='listCombi' VALUE='List the combinations' onClick='location.href="list_elem.php?typ=combi"'></TD>
</TR>
<TR>
<TD><INPUT TYPE='BUTTON' NAME='queryElem' VALUE='Query an element' onClick='location.href="query_elem.php?typ=elem"'></TD>
<TD><INPUT TYPE='BUTTON' NAME='queryCombi' VALUE='Query a combination' onClick='location.href="query_elem?typ=combi"'></TD>
</TR>
<TR>
<TD><INPUT TYPE='SUBMIT' NAME='addElem' VALUE='Add an element'></TD>
<TD><INPUT TYPE='SUBMIT' NAME='addCombi' VALUE='Add a combination'></TD>
</TR>
</TABLE>

<?php
	echo "\n
	<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='".$debug."' ".($debug?"CHECKED":"")."/><LABEL FOR='debug'>Debug</LABEL></P>\n
	</FORM>\n";

	$tpl->Output( $tpl->GetHTMLCode('baspage_lab') );
?>
