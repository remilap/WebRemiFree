<?php

require_once('common_init.php');

$title = L::txt_lstchapter;
$date = date( "Ymd", getlastmod());

require_once('common_bookmark.php');

TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );
$util->traceVariables();
$util->trace("host: ".DB::$host.", dbName: ".DB::$dbName.", user: ".DB::$user);

$operation = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : "lst";		// modify/delete/display/lst/add
$what = isset($_REQUEST['what']) ? $_REQUEST['what'] : "chapter";		// chapter/column/user/bookmark
$util->trace("Operation: ".$operation.", what: ".$what);

?>
<script type="text/javascript">
function OnSubmitForm() {
	form = document.form_listchapter;
	form.operation.value = document.pressed.substring(0, 3);
	action = document.pressed.substring(3);
	form.what.value = action;
	action = 'chapter_';
	if (form.operation.value == 'lst') {
		action = action + 'list';
	} else {
		action = action + 'modify';
	}
	form.action = action + '.php';
//	alert("document.pressed="+document.pressed+", form.action="+form.action+", form.operation="+form.operation.value+", form.what="+form.what.value);
	return true;
}
</script>

<FORM METHOD='POST' ACTION='chapter_modify.php' NAME='form_listchapter' onsubmit='return OnSubmitForm();'>
<TABLE>
<TR>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='lstchapter'  onclick="document.pressed=this.name" VALUE='<?php echo L::form_lstchapter; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='lstcolumn'   onclick="document.pressed=this.name" VALUE='<?php echo L::form_lstcolumn; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='lstuser'     onclick="document.pressed=this.name" VALUE='<?php echo L::form_lstuser; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='lstbookmark' onclick="document.pressed=this.name" VALUE='<?php echo L::form_lstbookmark; ?>'></TD>
</TR>
<TR>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='addchapter'  onclick="document.pressed=this.name" VALUE='<?php echo L::form_addchapter; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='addcolumn'   onclick="document.pressed=this.name" VALUE='<?php echo L::form_addcolumn; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='adduser'     onclick="document.pressed=this.name" VALUE='<?php echo L::form_adduser; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='addbookmark' onclick="document.pressed=this.name" VALUE='<?php echo L::form_addbookmark; ?>'></TD>
</TR>
</TABLE>
<!--INPUT TYPE='HIDDEN' NAME='debug' id='debug' VALUE='<?php echo $debug; ?>' -->
<INPUT TYPE='HIDDEN' NAME='operation' id='operation' VALUE='<?php echo $operation; ?>'>
<INPUT TYPE='HIDDEN' NAME='what' id='what' VALUE='<?php echo $what; ?>'>
<INPUT TYPE='HIDDEN' NAME='selectedElem' VALUE='none' SIZE='0' MAXLENGTH='0'>
<TABLE BORDER="1" CELLPADDING="3">
<TR>
	<TH><FONT SIZE="-1"><?php echo L::tbl_mod; ?></FONT></TH>
	<TH><FONT SIZE="-1"><?php echo L::tbl_del; ?></FONT></TH>
<?php

createTables();

getChapters();
if ($what == 'chapter') {

	echo "<TH>" . L::tbl_chapter . "</TH>";
	echo "</TR>";

	for ($i = 1; $i <= $nbChapters; $i++) {
		$options_lig = "";
		if ($i % 2 == 1) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		$id = $chaptersId[$i];
		echo "<TD ALIGN='CENTER'><A HREF='chapter_modify.php?selectedElem=".$id."&operation=modify".$action_debug."'><IMG SRC='../b_edit.png' ALT='modify'></A></TD>\n";
		echo "<TD ALIGN='CENTER'><A HREF='chapter_modify.php?selectedElem=".$id."&operation=delete".$action_debug."'><IMG SRC='../b_drop.png' ALT='delete'></A></TD>\n";
		//echo "<TD>" . $unBookmark->id . "</TD>\n";
		echo "<TD><A HREF='chapter_modify.php?selectedElem=".$id."&operation=display".$action_debug."'>" . $chaptersName[$id] . "</A></TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbChapters == 0) {
		$msg = L::tbl_norecord($tables[0]["tbl"]);
	}
	echo $msg;
}
if ($what == 'column') {
	getColumns();

	echo "<TH>" . L::tbl_column . "</TH>";
	echo "<TH>" . L::tbl_chapter . "</TH>";
	echo "</TR>";

	for ($i = 1; $i <= $nbColumns; $i++) {
		$options_lig = "";
		if ($i % 2 == 1) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		$id = $columnsId[$i];
		echo "<TD ALIGN='CENTER'><A HREF='column_modify.php?selectedElem=".$id."&operation=modify".$action_debug."'><IMG SRC='../b_edit.png' ALT='modify'></A></TD>\n";
		echo "<TD ALIGN='CENTER'><A HREF='column_modify.php?selectedElem=".$id."&operation=delete".$action_debug."'><IMG SRC='../b_drop.png' ALT='delete'></A></TD>\n";
		//echo "<TD>" . $unBookmark->id . "</TD>\n";
		echo "<TD><A HREF='column_modify.php?selectedElem=".$id."&operation=display".$action_debug."'>" . $columnsName[$id] . "</A></TD>\n";
		echo "<TD>" . $chaptersName[$columnsIdChapter[$id]] . "</TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbColumns == 0) {
		$msg = L::tbl_norecord($tables[1]["tbl"]);
	}
	echo $msg;
}

echo "</TABLE>\n
	<BR />\n
	<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' ".($debug?"CHECKED":"")."/><LABEL FOR='debug'>Debug</LABEL></P>\n
	</FORM>\n";

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
