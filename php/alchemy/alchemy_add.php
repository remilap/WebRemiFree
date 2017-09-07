<?php
	$title = "Alchemy adding";
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

	$addElem = isset($_REQUEST['addElem']) ? $_REQUEST['addElem'] : "";
	$addCombi = isset($_REQUEST['addCombi']) ? $_REQUEST['addCombi'] : "";

	// include the Iriven class
	require rtrim(dirname(__FILE__), '\\/') . DIRECTORY_SEPARATOR.$home.'/libs/Iriven_FormManager.php';

	// instantiate a Iriven object
	$form = new Iriven_FormManager('form_mod_elem');

	$typ = "unknown";
	if ($addElem != "") {
		$typ = "elem";
		// the label for the "name" field
		$form->add('label', 'label_name', 'element', 'Element name:');
		// add the "name" field
		$obj = & $form->add('text', 'element');
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
	}

	if ($addCombi != "") {
		$typ = "combi";
		$nbElems = 0;
		$resElem = $alchemyBD->getAllDistinctElems($alchemyBD->getElementTableName(), "name");
		while ($unElem = $alchemyBD->getBD()->objetSuivant($resElem)) {
			$nbElems++;
			$util->trace("elem no ".$nbElems.": name=".$unElem->name);
			$arrayElem[$nbElems] = $unElem->name;
		}
		if ($nbElems == 0) {
			$arrayElem[++$nbElems] = 'eau';
		}

		// the label for the "elem1" field
		$form->add('label', 'label_elem1', 'elem1', 'First element name:');
		// add the "elem1" field
		$obj = & $form->add('select', 'elem1', 'elem1', array('other' => true));
		$obj->add_options($arrayElem);
		// set rules
		$obj->set_rule(array(
			// error messages will be sent to a variable called "error", usable in custom templates
			'required' => array('error', 'First element is required!')
		));

		// the label for the "elem2" field
		$form->add('label', 'label_elem2', 'elem2', 'Second element name:');
		// add the "elem2" field
		$obj = & $form->add('select', 'elem2', 'elem2', array('other' => true));
		$obj->add_options($arrayElem);
		// set rules
		$obj->set_rule(array(
			// error messages will be sent to a variable called "error", usable in custom templates
			'required' => array('error', 'Second element is required!')
		));

		// the label for the "elem_res" field
		$form->add('label', 'label_elem_res', 'elem_res', 'Result element(s) name:');
		// add the "elem_res" field
		$obj = & $form->add('select', 'elem_res[]', array(''), array('multiple' => 'multiple'));
		$obj->add_options($arrayElem);
		// set rules
		$obj->set_rule(array(
			// error messages will be sent to a variable called "error", usable in custom templates
			'required' => array('error', 'Result element is required!')
		));
	}

	// "debug"
	$form->add('label', 'label_debug', 'debug', 'Debug');
	$obj = & $form->add('checkboxes', 'debug[]', array('debug' => ''));

	// "submit"
	$form->add('submit', 'btn_submit_'.$typ, 'Add');

	// validate the form
	if ($form->validate()) {
		// do stuff here
		$util->setDebugTo(isset($_REQUEST['debug']) ? 1 : 0);
		$util->traceVariables();

		if ( isset($_REQUEST['btn_submit_elem']) ) {
			$element = isset($_REQUEST['element']) ? $_REQUEST['element'] : "";
			$terminal = $_POST['terminal'];

			$idE1em = $alchemyBD->getNextFreeId($alchemyBD->getElementTableName());
			$fieldsValues = "'" . $idE1em . "','" . $element . "','" . $terminal . "',''";
			$util->trace("idE1em=".$idE1em.", fieldsValues=".$fieldsValues);
			$resInsert = $alchemyBD->insertARecord($alchemyBD->getElementTableName(), $fieldsValues);
			echo "Insert of ".$element." (idE1em=".$idE1em.") is done in ".$alchemyBD->getElementTableName()."<br>";
		}
		if ( isset($_REQUEST['btn_submit_combi']) ) {
			$elem1 = isset($_REQUEST['elem1']) ? $_REQUEST['elem1'] : "";
			$elem2 = isset($_REQUEST['elem2']) ? $_REQUEST['elem2'] : "";
			$elem_res = "";
			if (is_array($_REQUEST['elem_res'])) {
				$elem_res = implode(",", $_REQUEST['elem_res']);
			}

			$idCombi = $alchemyBD->getNextFreeId($alchemyBD->getCombiTableName());
			$fieldsValues = "'" . $idCombi . "','" . $elem1 . "','" . $elem2 . "','" . $elem_res . "'";
			$util->trace("idE1em=".$idCombi.", fieldsValues=".$fieldsValues);
			$resInsert = $alchemyBD->insertARecord($alchemyBD->getCombiTableName(), $fieldsValues);
		}

	} else {

		// auto generate output, labels to the left of form elements
		$form->render('*horizontal');
	}

	$tpl->Output( $tpl->GetHTMLCode('baspage_lab') );
?>
