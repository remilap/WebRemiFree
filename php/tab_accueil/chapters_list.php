<?php

require_once('common_init.php');

$title = L::txt_listchapters;
$date = date( "Ymd", getlastmod());

require_once('common_bookmark.php');

TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );
$util->traceVariables();
$util->trace("host: ".DB::$host.", dbName: ".DB::$dbName.", user: ".DB::$user);

?>
<script type="text/javascript">
function OnSubmitForm() {
	form = document.form_listchapters;
	if (document.pressed == 'listchapters') {
		form.action = 'chapters_list.php';
		form.operation = 'list';
	} else if document.pressed == 'listcolumns') {
		form.action = 'columns_list.php';
		form.operation = 'list';
	} else if document.pressed == 'listusers') {
		form.action = 'users_list.php';
		form.operation = 'list';
	} else if document.pressed == 'listbookmarks') {
		form.action = 'bookmarks_list.php';
		form.operation = 'list';
	} else if document.pressed == 'addChapter') {
		form.action = 'chapter_modify.php';
		form.operation = 'add';
	} else if document.pressed == 'addColumn') {
		form.action = 'column_modify.php';
		form.operation = 'add';
	} else if document.pressed == 'addUser') {
		form.action = 'user_modify.php';
		form.operation = 'add';
	} else if document.pressed == 'addBookmark') {
		form.action = 'bookmark_modify.php';
		form.operation = 'add';
		form.debug = 1;
	}
	return true;
}
</script>

<FORM METHOD='POST' ACTION='chapter_modify.php' NAME='form_listchapters' onsubmit='return onsubmitform();'>
<TABLE>
<TR>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='listchapters'  onclick="document.pressed=this.name" VALUE='<?php echo L::form_listchapters; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='listcolumns'   onclick="document.pressed=this.name" VALUE='<?php echo L::form_listcolumns; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='listusers'     onclick="document.pressed=this.name" VALUE='<?php echo L::form_listusers; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='listbookmarks' onclick="document.pressed=this.name" VALUE='<?php echo L::form_listbookmarks; ?>'></TD>
</TR>
<TR>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='addChapter'    onclick="document.pressed=this.name" VALUE='<?php echo L::form_addchapter; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='addColumn'     onclick="document.pressed=this.name" VALUE='<?php echo L::form_addcolumn; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='addUser'       onclick="document.pressed=this.name" VALUE='<?php echo L::form_adduser; ?>'></TD>
<TD ALIGN="CENTER"><INPUT TYPE='SUBMIT' NAME='addBookmark'   onclick="document.pressed=this.name" VALUE='<?php echo L::form_addbookmark; ?>'></TD>
</TR>
</TABLE>
<INPUT TYPE='HIDDEN' NAME='debug' id='debug' VALUE='<?php echo $debug; ?>'>
<INPUT TYPE='HIDDEN' NAME='selectedElem' VALUE='none' SIZE='0' MAXLENGTH='0'>
<TABLE BORDER="1" CELLPADDING="3">
<TR>
	<TH><FONT SIZE="-1"><?php echo L::tbl_mod; ?></FONT></TH>
	<TH><FONT SIZE="-1"><?php echo L::tbl_del; ?></FONT></TH>
	<TH><?php echo L::tbl_chapter; ?></TH>
</TR>

<?php

getChapters();
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
	$msg = L::tbl_norecord;
}
echo $msg;

echo "</TABLE>\n
	<BR />\n
	<INPUT TYPE='SUBMIT' NAME='addBookmark' VALUE='Add a bookmark'>\n
	<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' VALUE='".$debug."' ".($debug?"CHECKED":"")."/><LABEL FOR='debug'>Debug</LABEL></P>\n
	</FORM>\n";

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
