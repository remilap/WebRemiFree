<?php

require_once('common_init.php');

$title = L::txt_generpage;
$date = date( "Ymd", getlastmod());

require_once('common_bookmark.php');

$util->traceVariables();
$util->trace("host: ".DB::$host.", dbName: ".DB::$dbName.", user: ".DB::$user);

$for_user = isset($_REQUEST['for_user']) ? $_REQUEST['for_user'] : '';
if ($for_user) {
	echo "USER: " . $for_user;
} else {
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );
	// gener a dropdown list for choosing the user_error
	echo "
		<FORM METHOD='POST' ACTION='gener_page.php' NAME='form_generpage'>
		<table>
		<tr class='row'>
		<td valign='top'><label for='for_user'>" . L::form_nameuser . "<span class='required'>*</span></label></td>
		<td valign='top'>";
	getUsers();
	$found = 0;
	// add a list field for user
	echo "<select name='for_user'>";
	for ($i = 1; $i <= $nbUsers; $i++) {
		$selected = "'>";
		if ($found > 0 && $usersId[$i] == $booksIdUser[$found]) {
			$selected = "' selected>";
		}
		echo "<option value='" . $usersId[$i] . $selected . $usersName[$i] . "</option>";
	}
	echo "</td>
		</tr>
		</table>
		<P><INPUT TYPE='CHECKBOX' NAME='debug' id='debug' " . ($debug?'CHECKED':'') . "/><LABEL FOR='debug'>Debug</LABEL></P>\n
		<P><INPUT TYPE='SUBMIT' NAME='gener' onclick='document.pressed=this.name' VALUE='" . L::txt_generpage . "'></P>
		</FORM>\n";

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
}

?>
