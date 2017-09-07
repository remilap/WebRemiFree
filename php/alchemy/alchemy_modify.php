<?php
	$title = "Alchemy modification";
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

	$alchemyBD = new GestionAlchemyBD(0);
	if (! $alchemyBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $alchemyBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$alchemyBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
	$selectedElem = isset($_REQUEST['selectedElem']) ? $_REQUEST['selectedElem'] : "";
	$addElem = isset($_REQUEST['addElem']) ? $_REQUEST['addElem'] : "";
	$addCombi = isset($_REQUEST['addCombi']) ? $_REQUEST['addCombi'] : "";

	// include the Iriven class
	require rtrim(dirname(__FILE__), '\\/') . DIRECTORY_SEPARATOR.$home.'/libs/Iriven_FormManager.php';

	// instantiate a Iriven object
	$form = new Iriven_FormManager('form_mod_elem');

	$name = "";
	if ($addElem != "" && $action == "modify") {
		$resList = $alchemyBD->getAllElems($alchemyBD->getElementTableName(), $selectedElem);
		if (! $resList) {
			echo "<P><B><FONT COLOR='RED'>No lab found with id ".$selectedElem."</FONT></B></P>\n";
			exit (1);
		}

		$unElem = $alchemyBD->getNextLine($resList);
		$name = $unElem->name;
		$terminal = $unElem->terminal;

		// the label for the "name" field
		$form->add('label', 'label_name', 'name', 'Element name:');
		// add the "name" field
		$obj = & $form->add('text', 'element', $name);
		// set rules
		$obj->set_rule(array(
			// error messages will be sent to a variable called "error", usable in custom templates
			'required' => array('error', 'Element name is required!')
		));

		// the label for the "terminal" field
		$form->add('label', 'label_terminal', 'terminal', 'Terminal element:');
		// add the "terminal" field
		$obj = & $form->add('radios', 'terminal', array(
			'1' =>  'Yes',
			'0' =>  'No',
		));
		// set rules
		$obj->set_rule(array(
			// error messages will be sent to a variable called "error", usable in custom templates
			'required' => array('error', 'Terminal is required!')
		));

		// "debug"
		$form->add('label', 'label_debug', 'debug', 'Debug');
		$obj = & $form->add('checkboxes', 'debug[]', array('debug' => ''));
	}

	if ($addElem != "" && $action == "modify" && $selectedElem == "none") {
		$resList = $alchemyBD->getAllElems($alchemyBD->getCombiTableName(), $selectedElem);
		if (! $resList) {
			echo "<P><B><FONT COLOR='RED'>No lab found with id ".$selectedElem."</FONT></B></P>\n";
			exit (1);
		}

		$unElem = $alchemyBD->getNextLine($resList);
		$name = $unElem->name;
		$terminal = $unElem->terminal;

		// the label for the "name" field
		$form->add('label', 'label_name', 'name', 'Element name:');
		// add the "name" field
		$obj = & $form->add('text', 'element', $name);
		// set rules
		$obj->set_rule(array(
			// error messages will be sent to a variable called "error", usable in custom templates
			'required' => array('error', 'Element name is required!')
		));

		// the label for the "terminal" field
		$form->add('label', 'label_terminal', 'terminal', 'Terminal element:');
		// add the "terminal" field
		$obj = & $form->add('radios', 'terminal', array(
			'1' =>  'Yes',
			'0' =>  'No',
		));
		// set rules
		$obj->set_rule(array(
			// error messages will be sent to a variable called "error", usable in custom templates
			'required' => array('error', 'Terminal is required!')
		));

		// "debug"
		$form->add('label', 'label_debug', 'debug', 'Debug');

		$obj = & $form->add('checkboxes', 'debug[]', array('debug' => ''));
	}

	// "submit"
	$form->add('submit', 'btn_submit', 'Add');

	// validate the form
	if ($form->validate()) {
		// do stuff here
		$util->setDebugTo(isset($_REQUEST['debug']) ? 1 : 0);
		$util->traceVariables();

		$element = $_POST['element'];
		$terminal = $_POST['terminal'];

		$idE1em = $alchemyBD->getNextFreeId($alchemyBD->getElementTableName());
		$fieldsValues = "'" . $idE1em . "','" . $element . "','" . $terminal . "',''";
		$util->trace("idE1em=".$idE1em.", fieldsValues=".$fieldsValues);
		$resInsert = $alchemyBD->insertARecord($alchemyBD->getElementTableName(), $fieldsValues);
		echo "Insert of ".$element." (idE1em=".$idE1em.") is done in ".$alchemyBD->getElementTableName()."<br>";

	} else {

		// auto generate output, labels to the left of form elements
		$form->render('*horizontal');
	}

	$tpl->Output( $tpl->GetHTMLCode('baspage_lab') );
?>
