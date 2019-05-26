<?php
	$title = "Couleur modification";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionLaserBD.class.php');
	$tpl = new TemplateEngine($home."/template_lab.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );

	$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
	$util = new Util($debug);
	$util->traceVariables();

	$laserBD = new GestionLaserBD(0);
	if (! $laserBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $laserBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$laserBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
	$selectedCouleur = isset($_REQUEST['selectedCouleur']) ? $_REQUEST['selectedCouleur'] : "";

	$couleur = "";
	if ($action == "modify" && $selectedCouleur != "") {
		$resList = $laserBD->getAllCouleurs($selectedCouleur);
		if (! $resList) {
			echo "<P><B><FONT COLOR='RED'>No couleur found with id ".$selectedCouleur."</FONT></B></P>\n";
			exit (1);
		}

		$uneCouleur = $laserBD->getNextLine($resList);
		$id = $uneCouleur->id;
		$couleur = $uneCouleur->couleur;
	}

	// include the Iriven class
	require rtrim(dirname(__FILE__), '\\/') . DIRECTORY_SEPARATOR.$home.'/libs/Iriven_FormManager.php';

	// instantiate a Iriven object
	$form = new Iriven_FormManager('form_mod_couleur');

	// the label for the "couleur" field
	$form->add('label', 'label_couleur', 'couleur', 'Couleur :');
	// add the "couleur" field
	$obj = & $form->add('text', 'couleur');
	// set rules
	$obj->set_rule(array(
		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array('error', 'Couleur is required!')
	));
	$form->assign('couleur', $couleur);

	// the label for the "debug" field
	//$form->add('label', 'label_debug', 'debug', 'Debug');
	// add the "debug" field
	$obj = & $form->add('checkbox', 'debug', 'Debug');

	// "submit"
	$form->add('submit', 'btn_submit', 'Add');

	// validate the form
	if ($form->validate()) {
		// do stuff here
		if ($debug) {
			$util->setDebugOn();
		}
		$util->traceVariables();
		$id1 = $laserBD->getNextFreeId($laserBD->getCouleursTableName());
		$couleur = $_POST['couleur'];
		$fieldsValues = $id1."','".
			$couleur."','";
		$resInsert = $laserBD->insertARecord($laserBD->getCouleursTableName(), $fieldsValues);
		echo "Insert of ".$couleur." is done in ".$laserBD->getCouleursTableName()."<br>";

	} else {

		// auto generate output, labels to the left of form elements
		$form->render('*horizontal');
	}

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
