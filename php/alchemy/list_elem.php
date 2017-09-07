<?php
	$title = "Alchemy list";
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

	$typ = isset($_REQUEST['typ']) ? $_REQUEST['typ'] : "";
?>
<FORM METHOD='POST' ACTION='alchemy_add.php' NAME='form_lab_liste'>
<INPUT TYPE='HIDDEN' NAME='selectedElem' VALUE='none' SIZE='0' MAXLENGTH='0'>
<INPUT TYPE='SUBMIT' NAME='addElem' VALUE='Add an element'>
<INPUT TYPE='SUBMIT' NAME='addCombi' VALUE='Add a combination'>
<TABLE BORDER="1">
<TR>
	<TH><FONT SIZE="-1">mod</FONT></TH>
	<TH><FONT SIZE="-1">del</FONT></TH>

<?php
	$tabElemName = array();
	$tabElemTerm = array();
	$listElems = $alchemyBD->getAllElems($alchemyBD->getElementTableName());
	while ($unElem = $alchemyBD->getNextLine($listElems)) {
		$tabElemName[$unElem->id] = $unElem->name;
		$tabElemTerm[$unElem->id] = $unElem->terminal;
	}

	if ( $typ == "elem" ) {
		echo "\n
		<TH>Element name</TH>\n
		<TH>Terminal element</TH>\n
		<TH>Combinaisons</TH>\n
		</TR>";

		$nbElems = 0;
		foreach ($tabElemName as $key => $val) {
			$nbElems++;
			$options_lig = "";
			if ($nbElems % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
			echo "<TR" . $options_lig . ">\n";
			echo "<TD><A HREF='alchemy_modify.php?selectedElem=".$key."&action=modify'><IMG SRC='../b_edit.png' ALT='modify'></A> </TD>\n";
			echo "<TD><A HREF='alchemy_display.php?selectedElem=".$key."&action=delete'><IMG SRC='../b_drop.png' ALT='delete'></A> </TD>\n";
			//echo "<TD>" . $unElem->id . "</TD>\n";
			echo "<TD><A HREF='alchemy_display.php?selectedElem=".$key."&action=display'>" . $tabElemName[$key] . "</A></TD>\n";
			echo "<TD>" . ($tabElemTerm[$key]=='1'?'*':'&nbsp;') . "</TD>\n";
			echo "<TD><A HREF='alchemy_display.php?selectedElem=".$key."&action=listCombi'>Liste</A> </TD>\n";
			echo "</TR>\n";
		}
		$msg = "";
		if ($nbElems == 0) {
			$msg = "None element found in the database<BR />\n";
		}

	} else if ( $typ == "combi" ) {
		echo "\n
		<TH>First element name</TH>\n
		<TH>Second element name</TH>\n
		<TH>Result element name</TH>\n
		</TR>";

		$listCombis = $alchemyBD->getAllElems($alchemyBD->getCombiTableName());
		$nbCombis = 0;
		while ($uneCombi = $alchemyBD->getNextLine($listElems)) {
			$nbCombis++;
			$options_lig = "";
			if ($nbCombis % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
			echo "<TR" . $options_lig . ">\n";
			echo "<TD><A HREF='alchemy_modify.php?selectedElem=".$uneCombi->id."&action=modify'><IMG SRC='../b_edit.png' ALT='modify'></A> </TD>\n";
			echo "<TD><A HREF='alchemy_display.php?selectedElem=".$uneCombi->id."&action=delete'><IMG SRC='../b_drop.png' ALT='delete'></A> </TD>\n";
			//echo "<TD>" . $uneCombi->id . "</TD>\n";
			echo "<TD><A HREF='alchemy_display.php?selectedElem=".$uneCombi->elem1."&action=display'>" . $tabElemName[$uneCombi->elem1] . "</A></TD>\n";
			echo "<TD><A HREF='alchemy_display.php?selectedElem=".$uneCombi->elem2."&action=display'>" . $tabElemName[$uneCombi->elem2] . "</A></TD>\n";
			echo "<TD>";
			foreach (explode(",", $uneCombi->list_result) as $elemId) {
			    echo $tabElemName[$elemId]." ";
			}
			echo "</TD></TR>\n";
		}
		$msg = "";
		if ($nbCombis == 0) {
			$msg = "None combination found in the database<BR />\n";
		}

	}

	echo "</TABLE>\n
	<BR />\n
	<INPUT TYPE='SUBMIT' NAME='addElem' VALUE='Add an element'>
	<INPUT TYPE='SUBMIT' NAME='addCombi' VALUE='Add a combination'>
	<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='".$debug."' ".($debug?"CHECKED":"")."/><LABEL FOR='debug'>Debug</LABEL></P>\n
	</FORM>\n";

	$tpl->Output( $tpl->GetHTMLCode('baspage_lab') );
?>
