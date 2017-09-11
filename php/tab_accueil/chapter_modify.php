<?php
require_once('common_init.php');

$title = L::txt_modchapter;
$date = date( "Ymd", getlastmod());

require_once('common_bookmark.php');

TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );
$util->traceVariables();
$util->trace("host: ".DB::$host.", dbName: ".DB::$dbName.", user: ".DB::$user);

$operation = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : "";		// modify/delete/display
$selectedElem = isset($_REQUEST['selectedElem']) ? $_REQUEST['selectedElem'] : "";		// chapter's id
$addElem = isset($_REQUEST['addElem']) ? $_REQUEST['addElem'] : "";
$name = "";

if ($operation == "modify" && $selectedElem != "") {
	getChapters();
	$found = 0;
	for ($i = 1; $i <= $nbChapters; $i++) {
		if ($chaptersId[$i] == $selectedElem) {
			$found = $i;
		}
	}
	if ($found == 0) {
		echo "<P><B><FONT COLOR='RED'>No chapter found with id ".$selectedElem."</FONT></B></P>\n";
		exit (1);
	} else {

		// include the Iriven class
		require rtrim(dirname(__FILE__), '\\/') . DIRECTORY_SEPARATOR.$home.'/libs/Iriven_FormManager.php';

		// instantiate a Iriven object
		$form = new Iriven_FormManager('form_mod_elem');

		// the label for the "chapter" field
		$form->add('label', 'label_name', 'chapter', 'Chapter name:');
		// add the "chapter" field
		$obj = & $form->add('text', 'chapter', $name);
		// set rules
		$obj->set_rule(array(
			// error messages will be sent to a variable called "error", usable in custom templates
			'required' => array('error', 'Chapter name is required!')
		));

		// "debug"
		$form->add('label', 'label_debug', 'debug', 'Debug');

		$obj = & $form->add('checkboxes', 'debug[]', array('debug' => ''));

		// "submit"
		$form->add('submit', 'btn_submit', 'Add');

		// validate the form
		if ($form->validate()) {
			// do stuff here
			$util->setDebugTo(isset($_REQUEST['debug']) ? 1 : 0);
			$util->traceVariables();

			$name = $_POST['name'];
		/*
		$idBookmark = $accueilBD->getNextFreeId($accueilBD->getBookmarkTableName());
		$fieldsValues = "'" . $idBookmark . "','" . $name . "','" . $idColumn . "','" . $idUser . "'";
		$util->trace("idBookmark=".$idBookmark.", fieldsValues=".$fieldsValues);
		$resInsert = $accueilBD->insertARecord($accueilBD->getBookmarkTableName(), $fieldsValues);
		*/
			echo "Update of ".$name." (idChapter=".$found.") is done in ".$tables[0]["tbl"]."<br>";

		} else {

			// auto generate output, labels to the left of form elements
			$form->render('*horizontal');
		}
	}

} else if ($operation == "modify") {
} else {
	echo "operation=".$operation."<br />";
}

$tpl->Output( $tpl->GetHTMLCode('baspage_lab') );

?>
