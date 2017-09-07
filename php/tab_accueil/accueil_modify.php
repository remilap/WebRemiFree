<?php
	$title = "Modification bookmark";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionAccueilBD.class.php');
	$tpl = new TemplateEngine($home."/template_lab.html");
	$tpl->Output( $tpl->GetHTMLCode("entete_lab") );

	$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
	$util = new Util($debug);
	$util->traceVariables();

	$accueilBD = new GestionAccueilBD(0);
	if (! $accueilBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $accueilBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$accueilBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
	$selectedElem = isset($_REQUEST['selectedElem']) ? $_REQUEST['selectedElem'] : "";
	$addElem = isset($_REQUEST['addElem']) ? $_REQUEST['addElem'] : "";

	$name = "";
	$chapter = "";
	$column = "";
	$user = "";
	if ($action == "modify" && $selectedElem != "") {
		$resList = $accueilBD->getAllElems($accueilBD->getBookmarkTableName(), $selectedElem);
		if (! $resList) {
			echo "<P><B><FONT COLOR='RED'>No bookmark found with id ".$selectedElem."</FONT></B></P>\n";
			exit (1);
		}

		$unElem = $accueilBD->getNextLine($resList);
		if ($unElem) {
			$name = $unElem->name;
			$resColumn = $accueilBD->getAllElems($accueilBD->getColumnTableName(), $unElem->id_column);
			if ($resColumn) {
				$theColumn = $accueilBD->getNextLine($resColumn);
				if ($theColumn) {
					$column = $theColumn->name;
					$resChapter = $accueilBD->getAllElems($accueilBD->getChapterTableName(), $theColumn->id_chapter);
					if ($resChapter) {
						$theChapter = $accueilBD->getNextLine($resChapter);
						if ($theChapter) {
							$chapter = $theChapter->name;
						}
					}
				}
			}
			$resUser = $accueilBD->getAllElems($accueilBD->getUserTableName(), $unElem->id_user);
			if ($resUser) {
				$theUser = $accueilBD->getNextLine($resUser);
				if ($theUser) {
					$user = $theUser->name;
				}
			}
		}
	}

	// include the Iriven class
	require rtrim(dirname(__FILE__), '\\/') . DIRECTORY_SEPARATOR.$home.'/libs/Iriven_FormManager.php';

	// instantiate a Iriven object
	$form = new Iriven_FormManager('form_mod_elem');

	// the label for the "name" field
	$form->add('label', 'label_name', 'name', 'Bookmark name:');
	// add the "name" field
	$obj = & $form->add('text', 'name', $name);
	// set rules
	$obj->set_rule(array(
		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array('error', 'Bookmark name is required!')
	));

	// the label for the "chapter" field
	$form->add('label', 'label_chapter', 'chapter', 'Chapter:');
	// add the "chapter" field
	$obj = & $form->add('select', 'chapter', $chapter, array('other' => true));
	$nbChapters = 0;
	$resChapter = $accueilBD->getAllDistinctElems($accueilBD->getChapterTableName(), "name");
	while ($unChapter = $accueilBD->getBD()->objetSuivant($resChapter)) {
		$nbChapters++;
		$util->trace("chapter no ".$nbChapters.": name=".$unChapter->name);
		$arrayChapter[$nbChapters] = $unChapter->name;
	}
	if ($nbChapters == 0) {
		$arrayChapter[++$nbChapters] = 'Home';
	}
	$obj->add_options($arrayChapter);
	// set rules
	$obj->set_rule(array(
		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array('error', 'Chapter is required!')
	));

	// the label for the "column" field
	$form->add('label', 'label_column', 'column', 'Column:');
	// add the "column" field
	$obj = & $form->add('select', 'column', $column, array('other' => true));
	$nbColumns = 0;
	$resColumn = $accueilBD->getAllDistinctElems($accueilBD->getColumnTableName(), "name");
	while ($unColumn = $accueilBD->getBD()->objetSuivant($resColumn)) {
		$nbColumns++;
		$util->trace("column no ".$nbColumns.": name=".$unColumn->name);
		$arrayColumn[$nbColumns] = $unColumn->name;
	}
	if ($nbColumns == 0) {
		$arrayColumn[++$nbColumns] = 'Bank';
	}
	$obj->add_options($arrayColumn);
	// set rules
	$obj->set_rule(array(
		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array('error', 'Column is required!')
	));

	// the label for the "user" field
	$form->add('label', 'label_user', 'user', 'User:');
	// add the "user" field
	$obj = & $form->add('select', 'user', $user, array('other' => true));
	$nbUsers = 0;
	$resUser = $accueilBD->getAllDistinctElems($accueilBD->getUserTableName(), "name");
	while ($unUser = $accueilBD->getBD()->objetSuivant($resUser)) {
		$nbUsers++;
		$util->trace("user no ".$nbUsers.": name=".$unUser->name);
		$arrayUser[$nbUsers] = $unUser->name;
	}
	if ($nbUsers == 0) {
		$arrayUser[++$nbUsers] = 'Me';
	}
	$obj->add_options($arrayUser);
	// set rules
	$obj->set_rule(array(
		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array('error', 'User is required!')
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

		$idChapter = $_POST['chapter'];
		if ($idChapter == "other" || $idChapter == "Other") {
			$idChapter = $accueilBD->getNextFreeId($accueilBD->getChapterTableName());
			$chapter = $_POST['chapter_other'];
			$fieldsValues = "'" . $idChapter . "','" . $chapter . "'";
			$resInsert = $accueilBD->insertARecord($accueilBD->getChapterTableName(), $fieldsValues);
			echo "Insert of ".$chapter." (id=".$idChapter.") is done in ".$accueilBD->getChapterTableName()."<br>";
		} else {
			$chapter = $arrayChapter[$idChapter];
		}
		$util->trace("idChapter=".$idChapter.", chapter name=".$chapter);

		$idColumn = $_POST['column'];
		if ($idColumn == "other" || $idColumn == "Other") {
			$idColumn = $accueilBD->getNextFreeId($accueilBD->getColumnTableName());
			$column = $_POST['column_other'];
			$fieldsValues = "'" . $idColumn . "','" . $idChapter . "','" . $column . "'";
			$resInsert = $accueilBD->insertARecord($accueilBD->getColumnTableName(), $fieldsValues);
			echo "Insert of ".$column." (id=".$idColumn.", id_chapter=".$idChapter.") is done in ".$accueilBD->getColumnTableName()."<br>";
		} else {
			$column = $arrayColumn[$idColumn];
		}
		$util->trace("idColumn=".$idColumn.", column name=".$column);

		$idUser = $_POST['user'];
		if ($idUser == "other" || $idUser == "Other") {
			$idUser = $accueilBD->getNextFreeId($accueilBD->getUserTableName());
			$user = $_POST['user_other'];
			$fieldsValues = "'" . $idUser . "','" . $user . "'";
			$resInsert = $accueilBD->insertARecord($accueilBD->getUserTableName(), $fieldsValues);
			echo "Insert of ".$user." (id=".$idUser.") is done in ".$accueilBD->getUserTableName()."<br>";
		} else {
			$user = $arrayUser[$idUser];
		}
		$util->trace("idUser=".$idUser.", user name=".$user);

		$idBookmark = $accueilBD->getNextFreeId($accueilBD->getBookmarkTableName());
		$name = $_POST['name'];
		$fieldsValues = "'" . $idBookmark . "','" . $name . "','" . $idColumn . "','" . $idUser . "'";
		$util->trace("idBookmark=".$idBookmark.", fieldsValues=".$fieldsValues);
		$resInsert = $accueilBD->insertARecord($accueilBD->getBookmarkTableName(), $fieldsValues);
		echo "Insert of ".$name." (idBookmark=".$idBookmark.") is done in ".$accueilBD->getBookmarkTableName()."<br>";

	} else {

		// auto generate output, labels to the left of form elements
		$form->render('*horizontal');
	}

	$tpl->Output( $tpl->GetHTMLCode('baspage_lab') );
?>
