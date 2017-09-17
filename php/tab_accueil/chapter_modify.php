<?php
require_once('common_init.php');

$title = L::txt_modchapter;
$date = date( "Ymd", getlastmod());

require_once('common_bookmark.php');

$operation = isset($_REQUEST['operation']) ? $_REQUEST['operation'] : "lst";		// modify/delete/display/lst/add
$what = isset($_REQUEST['what']) ? $_REQUEST['what'] : "chapter";		// chapter/column/user/bookmark
$selectedElem = isset($_REQUEST['selectedElem']) ? $_REQUEST['selectedElem'] : "none";		// chapter's id
$addElem = isset($_REQUEST['addElem']) ? $_REQUEST['addElem'] : "";
$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "0";
echo "<A>debug: ".$debug."</A></BR />";
$util->setDebugTo($debug);

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
$util->setDebugOn();
$util->traceVariables();
$util->trace("host: ".DB::$host.", dbName: ".DB::$dbName.", user: ".DB::$user);

$util->trace("Operation: ".$operation.", what: ".$what.", selectedElem: ".$selectedElem.", addElem: ".$addElem);

$name = "";
if ($operation == "modify" || $operation == "add") {

	getChapters();
	if ($what == "chapter") {
		$found = 0;
		if ($operation == "modify" && $selectedElem != "none") {
			for ($i = 1; $i <= $nbChapters; $i++) {
				if ($chaptersId[$i] == $selectedElem) {
					$found = $i;
					$name = $chaptersName[$i];
				}
			}
		}
		if ($found == 0 && $selectedElem != "none") {
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
			if ($operation == "display") {
				$obj = & $form->add('label', 'label_value', 'chapter', $name);
			} else {
				$obj = & $form->add('text', 'chapter', $name);
				// set rules
				$obj->set_rule(array(
					// error messages will be sent to a variable called "error", usable in custom templates
					'required' => array('error', 'Chapter name is required!')
				));
			}

			// "debug"
			$form->add('label', 'label_debug', 'debug', 'Debug');

			$obj = & $form->add('checkbox', 'debug', $util->getDebugStatus());

			// "submit"
			$form->add('submit', 'btn_submit', 'Add');

			// validate the form
			if ($form->validate()) {
				// do stuff here
				echo "<A>validate form</A><BR />";
				$util->setDebugTo($debug);
				$util->traceVariables();

				$name = $_POST['chapter'];
				if ($selectedElem == "none") {
					$id = getNextFreeId($tables[0]["tbl"]);
					$fields = array('id' => $id, 'name' => $name);
					echo "<A>insert fields: ".var_dump($fields)."</A><BR />";
					DB::insert($tables[0]["tbl"], $fields);
				} else {
					$id = $selectedElem;
					$fields = array('id=%d' => $id, 'name' => $name);
					echo "<A>update fields: ".var_dump($fields)."</A><BR />";
					DB::update($tables[0]["tbl"], $fields);
				}
			} else {
				echo "<A>render form</A><BR />";

				// auto generate output, labels to the left of form elements
				$form->render('*horizontal');
			}
		}
	}

} else if ($operation == "display" || $operation == "add") {

} else {
	echo "operation=".$operation."<br />";
}

$tpl->Output( $tpl->GetHTMLCode('baspage_lab') );

?>
