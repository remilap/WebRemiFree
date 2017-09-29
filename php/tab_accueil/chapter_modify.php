<?php
require_once('common_init.php');

$title = L::txt_modchapter;
$date = date( "Ymd", getlastmod());

$operation = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : 'lst';		// modify/delete/display/lst/add
$what = isset($_REQUEST['what']) ? $_REQUEST['what'] : 'chapter';		// chapter/column/user/bookmark
$selectedElem = isset($_REQUEST['selectedElem']) ? $_REQUEST['selectedElem'] : 'none';		// selected id
$addElem = isset($_REQUEST['addElem']) ? $_REQUEST['addElem'] : '';
$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : '0';
$util->setDebugTo($debug);

require_once('common_bookmark.php');

?>
<script type="text/javascript">
function OnSubmitForm() {
	form = document.form_modchapter;
	s = document.pressed.substring(4, 7);
	form.action = 'chapter_list.php';
//	alert("document.pressed="+document.pressed+", form.action="+form.action+", s="+s);
	return true;
}
function OnCancelForm() {
	form = document.form_modchapter;
	form.action = 'chapter_list.php';
	form.submit();
}
</script>
<?php

if ($operation == "modify") {
	$title = L::txt_modify . $what;
} else if ($operation == "delete") {
	$title = L::txt_delete . $what;
} else if ($operation == "display") {
	$title = L::txt_display . $what;
} else if ($operation == "lst") {
	$title = L::txt_list . $what;
} else if ($operation == "add") {
	$title = L::txt_add . $what;
}

TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );
//$util->setDebugOn();
$util->traceVariables();
$util->trace("host: ".DB::$host.", dbName: ".DB::$dbName.", user: ".DB::$user);

$util->trace("Operation: ".$operation.", what: ".$what.", selectedElem: ".$selectedElem.", addElem: ".$addElem);

$name = '';
$found = -1;
$btn_submit = ucfirst($operation);
$disable = '';
if ($operation == 'display') {
	$btn_submit = L::btn_ok;
	$disable = ' disabled';
}

if ($what == 'chapter') {
	getChapters();
	if ($operation != 'add' && $selectedElem != 'none') {
		$found = 0;
		for ($i = 1; $i <= $nbChapters; $i++) {
			if ($chaptersId[$i] == $selectedElem) {
				$found = $i;
				$name = $chaptersName[$i];
			}
		}
	}
} else if ($what == 'column') {
	getColumns();
	if ($operation != 'add' && $selectedElem != 'none') {
		$found = 0;
		for ($i = 1; $i <= $nbColumns; $i++) {
			if ($columnsId[$i] == $selectedElem) {
				$found = $i;
				$name = $columnsName[$i];
			}
		}
	}
} else if ($what == 'user') {
	getUsers();
	if ($operation != 'add' && $selectedElem != 'none') {
		$found = 0;
		for ($i = 1; $i <= $nbUsers; $i++) {
			if ($usersId[$i] == $selectedElem) {
				$found = $i;
				$name = $usersName[$i];
			}
		}
	}
} else if ($what == 'bookmark') {
	getBookmarks('');
	if ($operation != 'add' && $selectedElem != 'none') {
		$found = 0;
		for ($i = 1; $i <= $nbBookmarks; $i++) {
			if ($booksId[$i] == $selectedElem) {
				$found = $i;
				$name = $booksName[$i];
			}
		}
	}
}

if ($found == 0 && $selectedElem != "none") {
	echo "<P><B><FONT COLOR='RED'>No ".$what." found with id ".$selectedElem."</FONT></B></P>\n";
	exit (1);
}

// Create the beginning of the form
echo "
	<form method='POST' action='chapter_list.php' name='form_modchapter' id='form_modchapter' class='Iriven_FormManager' onsubmit='return OnSubmitForm();'>
	<input type='hidden' name='form' value='form_modchapter'>
	<input type='hidden' name='selectedElem' value='".$selectedElem."'>
	<input type='hidden' name='what' value='".$what."'>
	<table>";

if ($what == 'chapter') {
	// Add the different fields for a chapter
	echo "
		<tr class='row'>
		<td valign='top'><label for='chapter_name'>" . L::form_namechapter . "<span class='required'>*</span></label></td>
		<td valign='top'><input type='text' name='chapter_name' class='control text validate[required]' value='" . $name . "'" . $disable . "></td>
		</tr>";
} else if ($what == 'column') {
	// Add the different fields for a column
	echo "
		<tr class='row'>
		<td valign='top'><label for='column_name'>" . L::form_namecolumn . "<span class='required'>*</span></label></td>
		<td valign='top'><input type='text' name='column_name' class='control text validate[required]' value='" . $name . "'" . $disable . "></td>
		</tr>
		<tr class='row'>
		<td valign='top'><label for='chapter_id'>" . L::form_namechapter . "<span class='required'>*</span></label></td>
		<td valign='top'>";
	getChapters();
	if ($operation == 'add' || $operation == 'modify') {
		// add a list field for chapter
		echo "<select name='chapter_id'>";
		for ($i = 1; $i <= $nbChapters; $i++) {
			$selected = "'>";
			if ($found > 0 && $chaptersId[$i] == $columnsIdChapter[$found]) {
				$selected = "' selected>";
			}
			echo "<option value='" . $chaptersId[$i] . $selected . $chaptersName[$i] . "</option>";
		}
	} else {
		// add a simple text field for chapter
		echo "<input type='text' name='chapter_id' class='control text validate[required]' value='" . $chaptersName[$found] . "'" . $disable . ">";
	}
	echo "</td>
		</tr>";
} else if ($what == 'user') {
	// Add the different fields for a user
	echo "
		<tr class='row'>
		<td valign='top'><label for='user_name'>" . L::form_nameuser . "<span class='required'>*</span></label></td>
		<td valign='top'><input type='text' name='user_name' class='control text validate[required]' value='" . $name . "'" . $disable . "></td>
		</tr>";
} else if ($what == 'bookmark') {
	// Add the different fields for a bookmark
	echo "
		<tr class='row'>
		<td valign='top'><label for='bookmark_name'>" . L::form_namebookmark . "<span class='required'>*</span></label></td>
		<td valign='top'><input type='text' name='bookmark_name' class='control text validate[required]' value='" . $name . "'" . $disable . "></td>
		</tr>
		<tr class='row'>
		<td valign='top'><label for='column_id'>" . L::form_namecolumn . "<span class='required'>*</span></label></td>
		<td valign='top'>";
	getColumns();
	if ($operation == 'add' || $operation == 'modify') {
		// add a list field for column
		echo "<select name='column_id'>";
		for ($i = 1; $i <= $nbColumns; $i++) {
			$selected = "'>";
			if ($found > 0 && $columnsId[$i] == $booksIdColumn[$found]) {
				$selected = "' selected>";
			}
			echo "<option value='" . $columnsId[$i] . $selected . $columnsName[$i] . "</option>";
		}
	} else {
		// add a simple text field for column
		echo "<input type='text' name='column_id' class='control text validate[required]' value='" . $columnsName[$found] . "'" . $disable . ">";
	}
	echo "
		</tr>
		<tr class='row'>
		<td valign='top'><label for='column_order'>" . L::form_orderincolumn . "<span class='required'>*</span></label></td>
		<td valign='top'>";
	// add a simple text field for order in column
	$val = '';
	if ($found > 0) {
		$val = $booksOrderInColumn[$found];
	}
	echo "<input type='text' name='column_order' class='control text validate[required]' value='" . $val . "'" . $disable . "></td>";
	echo "
		</tr>
		<tr class='row'>
		<td valign='top'><label for='user_id'>" . L::form_nameuser . "<span class='required'>*</span></label></td>
		<td valign='top'>";
	getUsers();
	if ($operation == 'add' || $operation == 'modify') {
		// add a list field for user
		echo "<select name='user_id'>";
		for ($i = 1; $i <= $nbUsers; $i++) {
			$selected = "'>";
			if ($found > 0 && $usersId[$i] == $booksIdUser[$found]) {
				$selected = "' selected>";
			}
			echo "<option value='" . $usersId[$i] . $selected . $usersName[$i] . "</option>";
		}
	} else {
		// add a simple text field for user
		echo "<input type='text' name='user_id' class='control text validate[required]' value='" . $usersName[$found] . "'" . $disable . ">";
	}
	echo "
		</tr>
		<tr class='row'>
		<td valign='top'><label for='url'>" . L::form_nameurl . "<span class='required'>*</span></label></td>
		<td valign='top'>";
	// add a simple text field for URL
	$val = '';
	if ($found > 0) {
		$val = $booksURL[$found];
	}
	echo "<input type='text' name='url' class='control text validate[required]' value='" . $val . "'" . $disable . ">";
	echo "
		</tr>
		<tr class='row'>
		<td valign='top'><label for='icone'>" . L::form_nameicone . "<span class='required'>*</span></label></td>
		<td valign='top'>";
	// add a simple text field for icone
	$val = '';
	if ($found > 0) {
		$val = $booksIconeName[$found];
	}
	echo "<input type='text' name='icone' class='control text validate[required]' value='" . $val . "'" . $disable . ">";
	echo "
		</tr>
		<tr class='row'>
		<td valign='top'><label for='tab'>" . L::form_nametab . "<span class='required'>*</span></label></td>
		<td valign='top'>";
	// add a simple text field for tab
	$val = '';
	if ($found > 0) {
		$val = $booksTabName[$found];
	}
	echo "<input type='text' name='tab' class='control text validate[required]' value='" . $val . "'" . $disable . ">";
	echo "</td>
		</tr>";
}

// Add the end of the form
echo "
	<tr class='row even'>
	<td valign='top'><label for='debug'>Debug</label></td>
	<td valign='top'><input type='checkbox' name='debug' " . ($debug ? 'selected=\'selected\'' : '') . " class='control checkbox'></td>
	</tr>
	<tr class='row last'>
	<td valign='top'><input type='button' name='btn_cancel' id='btn_cancel' value='" . L::btn_cancel . "' class='submit' onclick='OnCancelForm()'</td>
	<td valign='top'><input type='submit' name='btn_submit' id='btn_submit' value='" . $btn_submit . "' class='submit' onclick='document.pressed=this.name'></td>
	</tr>
	</table>
	</form>
	";


$tpl->Output( $tpl->GetHTMLCode('baspage_lab') );

?>
