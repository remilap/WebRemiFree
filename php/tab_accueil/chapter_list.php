<?php

require_once('common_init.php');

$title = L::txt_lstchapter;
$date = date( "Ymd", getlastmod());

require_once('common_bookmark.php');

TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );
$util->traceVariables();
$util->trace("host: ".DB::$host.", dbName: ".DB::$dbName.", user: ".DB::$user);

$operation = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : 'lst';		// modify/delete/display/lst/add
$what = isset($_REQUEST['what']) ? $_REQUEST['what'] : 'chapter';		// chapter/column/user/bookmark
$btn_submit = isset($_REQUEST['btn_submit']) ? $_REQUEST['btn_submit'] : 'no';
$selectedElem = isset($_REQUEST['selectedElem']) ? $_REQUEST['selectedElem'] : 'no';
$util->trace("Operation: ".$operation.", what: ".$what);
$withId = 1;

if ($btn_submit == 'Add') {
	if ($what == 'chapter') {
		// Add a new chapter
		$id = getNextFreeId($tables[0]["tbl"]);
		$name = $_REQUEST['chapter_name'];
		$fields = array(
			'id' => prepSQL($id),
			'name' => prepSQL($name));
		$util->trace("insert fields: ".var_dump($fields)."</A><BR />");
		DB::insert($tables[0]["tbl"], $fields);
	} else if ($what == 'column') {
		// Add a new column
		$id = getNextFreeId($tables[1]["tbl"]);
		$name = $_REQUEST['column_name'];
		$chapter_id = $_REQUEST['chapter_id'];
		$fields = array(
			'id' => prepSQL($id),
			'name' => prepSQL($name),
			'id_chapter' => prepSQL($chapter_id));
		$util->trace("insert fields: ".var_dump($fields)."</A><BR />");
		DB::insert($tables[1]["tbl"], $fields);
	} else if ($what == 'user') {
		// Add a new user
		$id = getNextFreeId($tables[2]["tbl"]);
		$name = $_REQUEST['user_name'];
		$fields = array(
			'id' => prepSQL($id),
			'name' => prepSQL($name));
		$util->trace("insert fields: ".var_dump($fields)."</A><BR />");
		DB::insert($tables[2]["tbl"], $fields);
	} else if ($what == 'bookmark') {
		// Add a new bookmark
		$id = getNextFreeId($tables[3]["tbl"]);
		$name = $_REQUEST['bookmark_name'];
		$column_id = $_REQUEST['column_id'];
		$user_id = $_REQUEST['user_id'];
		$url = $_REQUEST['url'];
		$icone = $_REQUEST['icone'];
		$tab = $_REQUEST['tab'];
		$fields = array(
			'id' => prepSQL($id),
			'name' => prepSQL($name),
			'id_column' => prepSQL($column_id),
			'id_user' => prepSQL($user_id),
			'URL' => prepSQL($url),
			'iconeName' => prepSQL($icone),
			'tabName' => prepSQL($tab));
		$util->trace("insert fields: ".var_dump($fields)."</A><BR />");
		DB::insert($tables[3]["tbl"], $fields);
	}
}
if ($btn_submit == 'Modify') {
	if ($what == 'chapter') {
		// Modify an existent chapter
		$id = $selectedElem;
		$name = $_REQUEST['chapter_name'];
		$fields = array(
			'id' => prepSQL($id),
			'name' => prepSQL($name));
		$util->trace("update fields: ".var_dump($fields)."</A><BR />");
		DB::update($tables[0]["tbl"], $fields, 'id=%s', $id);
	} else if ($what == 'column') {
		// Modify an existent column
		$id = $selectedElem;
		$name = $_REQUEST['column_name'];
		$chapter_id = $_REQUEST['chapter_id'];
		$fields = array(
			'id' => prepSQL($id),
			'name' => prepSQL($name),
			'id_chapter' => prepSQL($chapter_id));
		$util->trace("update fields: ".var_dump($fields)."</A><BR />");
		DB::update($tables[1]["tbl"], $fields, 'id=%s', $id);
	} else if ($what == 'user') {
		// Modify an existent user
		$id = $selectedElem;
		$name = $_REQUEST['user_name'];
		$fields = array(
			'id' => prepSQL($id),
			'name' => prepSQL($name));
		$util->trace("update fields: ".var_dump($fields)."</A><BR />");
		DB::update($tables[2]["tbl"], $fields, 'id=%s', $id);
	} else if ($what == 'bookmark') {
		// Modify an existent bookmark
		$id = $selectedElem;
		$name = $_REQUEST['bookmark_name'];
		$column_id = $_REQUEST['column_id'];
		$user_id = $_REQUEST['user_id'];
		$url = $_REQUEST['url'];
		$icone = $_REQUEST['icone'];
		$tab = $_REQUEST['tab'];
		$fields = array(
			'id' => prepSQL($id),
			'name' => prepSQL($name),
			'id_column' => prepSQL($column_id),
			'id_user' => prepSQL($user_id),
			'URL' => prepSQL($url),
			'iconeName' => prepSQL($icone),
			'tabName' => prepSQL($tab));
		$util->trace("update fields: ".var_dump($fields)."</A><BR />");
		DB::update($tables[3]["tbl"], $fields, 'id=%s', $id);
	}
}

?>
<script type="text/javascript">
function OnSubmitForm() {
	form = document.form_listchapter;
	if (document.pressed == 'gener') {
		form.action = 'gener_page.php';
		return true;
	}
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
if ($withId) {
	echo '<TH><FONT SIZE="-1">Id</FONT></TH>';
}

createTables();

if ($what == 'chapter') {
	echo "<TH>" . L::tbl_chapter . "</TH>";
	echo "</TR>";

	getChapters();
	for ($i = 1; $i <= $nbChapters; $i++) {
		$options_lig = "";
		if ($i % 2 == 1) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		$id = $chaptersId[$i];
		echo "<TD ALIGN='CENTER'><A HREF='chapter_modify.php?selectedElem=".$id."&operation=modify&what=".$what.$action_debug."'><IMG SRC='../b_edit.png' ALT='modify'></A></TD>\n";
		echo "<TD ALIGN='CENTER'><A HREF='chapter_modify.php?selectedElem=".$id."&operation=delete&what=".$what.$action_debug."'><IMG SRC='../b_drop.png' ALT='delete'></A></TD>\n";
		if ($withId) {
			echo "<TD ALIGN='CENTER'>" . $id . "</TD>\n";
		}
		echo "<TD><A HREF='chapter_modify.php?selectedElem=".$id."&operation=display&what=".$what.$action_debug."'>" . $chaptersName[$i] . "</A></TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbChapters == 0) {
		$msg = L::tbl_norecord($tables[0]["tbl"]);
	}
	echo $msg;

} else if ($what == 'column') {
	echo "<TH>" . L::tbl_column . "</TH>";
	echo "<TH>" . L::tbl_chapter . "</TH>";
	echo "</TR>";

	getColumns();
	for ($i = 1; $i <= $nbColumns; $i++) {
		$options_lig = "";
		if ($i % 2 == 1) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		$id = $columnsId[$i];
		echo "<TD ALIGN='CENTER'><A HREF='chapter_modify.php?selectedElem=".$id."&operation=modify&what=".$what.$action_debug."'><IMG SRC='../b_edit.png' ALT='modify'></A></TD>\n";
		echo "<TD ALIGN='CENTER'><A HREF='chapter_modify.php?selectedElem=".$id."&operation=delete&what=".$what.$action_debug."'><IMG SRC='../b_drop.png' ALT='delete'></A></TD>\n";
		if ($withId) {
			echo "<TD ALIGN='CENTER'>" . $id . "</TD>\n";
		}
		echo "<TD><A HREF='chapter_modify.php?selectedElem=".$id."&operation=display&what=".$what.$action_debug."'>" . $columnsName[$i] . "</A></TD>\n";
		$chapName = getChapterName($columnsIdChapter[$i]);
		echo "<TD>" . $chapName . "</TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbColumns == 0) {
		$msg = L::tbl_norecord($tables[1]["tbl"]);
	}
	echo $msg;

} else if ($what == 'user') {
	echo "<TH>" . L::tbl_user . "</TH>";
	echo "</TR>";

	getUsers();
	for ($i = 1; $i <= $nbUsers; $i++) {
		$options_lig = "";
		if ($i % 2 == 1) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		$id = $usersId[$i];
		echo "<TD ALIGN='CENTER'><A HREF='chapter_modify.php?selectedElem=".$id."&operation=modify&what=".$what.$action_debug."'><IMG SRC='../b_edit.png' ALT='modify'></A></TD>\n";
		echo "<TD ALIGN='CENTER'><A HREF='chapter_modify.php?selectedElem=".$id."&operation=delete&what=".$what.$action_debug."'><IMG SRC='../b_drop.png' ALT='delete'></A></TD>\n";
		if ($withId) {
			echo "<TD ALIGN='CENTER'>" . $id . "</TD>\n";
		}
		echo "<TD><A HREF='chapter_modify.php?selectedElem=".$id."&operation=display&what=".$what.$action_debug."'>" . $usersName[$i] . "</A></TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbUsers == 0) {
		$msg = L::tbl_norecord($tables[2]["tbl"]);
	}
	echo $msg;

} else if ($what == 'bookmark') {
	echo "<TH>" . L::tbl_bookmark . "</TH>";
	echo "<TH>" . L::tbl_column . "</TH>";
	echo "<TH>" . L::tbl_user . "</TH>";
	echo "<TH>" . L::tbl_url . "</TH>";
	echo "<TH>" . L::tbl_icone . "</TH>";
	echo "<TH>" . L::tbl_tabname . "</TH>";
	echo "</TR>";

	getBookmarks('');
	for ($i = 1; $i <= $nbBookmarks; $i++) {
		$options_lig = "";
		if ($i % 2 == 1) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		$id = $booksId[$i];
		echo "<TD ALIGN='CENTER'><A HREF='chapter_modify.php?selectedElem=".$id."&operation=modify&what=".$what.$action_debug."'><IMG SRC='../b_edit.png' ALT='modify'></A></TD>\n";
		echo "<TD ALIGN='CENTER'><A HREF='chapter_modify.php?selectedElem=".$id."&operation=delete&what=".$what.$action_debug."'><IMG SRC='../b_drop.png' ALT='delete'></A></TD>\n";
		if ($withId) {
			echo "<TD ALIGN='CENTER'>" . $id . "</TD>\n";
		}
		echo "<TD><A HREF='chapter_modify.php?selectedElem=".$id."&operation=display&what=".$what.$action_debug."'>" . $booksName[$i] . "</A></TD>\n";
		echo "<TD><A HREF='chapter_modify.php?selectedElem=".$booksIdColumn[$i]."&operation=display&what=column".$action_debug."'>" . getColumnName($booksIdColumn[$i]) . "</A></TD>\n";
		echo "<TD><A HREF='chapter_modify.php?selectedElem=".$booksIdUser[$i]."&operation=display&what=user".$action_debug."'>" . getUserName($booksIdUser[$i]) . "</A></TD>\n";
		echo "<TD>" . $booksURL[$i] . "</TD>\n";
		echo "<TD>" . $booksIconeName[$i] . "</TD>\n";
		echo "<TD>" . $booksTabName[$i] . "</TD>\n";
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbBookmarks == 0) {
		$msg = L::tbl_norecord($tables[2]["tbl"]);
	}
	echo $msg;

}

echo "</TABLE>\n
	<BR />\n
	<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' " . ($debug?'CHECKED':'') . "/><LABEL FOR='debug'>Debug</LABEL></P>\n
	<P><INPUT TYPE='SUBMIT' NAME='gener' onclick='document.pressed=this.name' VALUE='" . L::txt_generpage . "'></P>
	</FORM>\n";

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
