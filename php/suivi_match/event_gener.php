<?php
	$title = "Génération d'événements d'un match";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionMatchBD.class.php');
	$tempo_reload = 9999990;
	$tpl = new TemplateEngine($home."/template_hand.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_hand") );

	$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
	$util = new Util(0);
	$util->setDebugOff();
	if ($debug) $util->setDebugOn();
	$util->traceVariables();

	$matchBD = new GestionMatchBD(0);
	if (! $matchBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $matchBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$matchBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
	$beginTime = isset($_REQUEST['beginTime']) ? $_REQUEST['beginTime'] : time();
	$scores = array(0, 0);
	$dossard_joueur = array(array(21,                 14,                  1,                  16,                42,               38,
	                              18,                 6,                   46,                 67,                63,               32),
							array(3,                  15,                  16,                 11,                4,                20,
							      1,                  7,                   23,                 35,                17,               25));
	$nom_joueur = array(    array("Ahanda Orlane",    "Amadil Sarah",      "Aumeunier Julie",  "Berrais Lola",    "Coic Rozenn",    "Faveur Marion",
	                              "Fontaine Margot",  "Freville Juliette", "Lapointe Pauline", "Poirier Axelle",  "Ravon Nolwenn",  "Launay Mélodie"),
							array("Bocquillon Ambre", "Contau Tessa",      "Gautier Luna",     "Legardien Marie", "Loquet Orianne", "Pabois Malvyna",
							      "Racineux Emelyne", "Riot Enora",        "Azara Laura",      "Audrin Laetitia", "Pabois Yoanie",  "Jordanna Camilla"));

	// include the Iriven class
	require rtrim(dirname(__FILE__), '\\/') . DIRECTORY_SEPARATOR.$home.'/libs/Iriven_FormManager.php';

	// instantiate a Iriven object
	$form = new Iriven_FormManager('form_mod_user');

	// the label for the "debug" field
	//$form->add('label', 'label_debug', 'debug', 'Debug');
	// add the "debug" field
	$obj = & $form->add('checkbox', 'debug', 'Debug');

	// "submit"
	$form->add('submit', 'btn_submit', 'Add');


	// validate the form
	if ($form->validate()) {
		// do stuff here
		if ($_POST['debug']) {
			$util->setDebugOn();
		}
		$util->traceVariables();
		$id1 = $matchBD->getNextFreeId($matchBD->getMatchTableName());
		$now = time();
		$minutes = ($now - $beginTime) / 60;
		$secondes = ($now - $beginTime) % 60;
		$temps = $minutes . ":" . $secondes;
		$equipe = rand(1, 2);
		$ind_joueur = rand(1, 12);
		$num_joueur = $dossard_joueur[$equipe-1][$ind_joueur-1];
		$action = rand(1, 7);
		if ($action == 1 || $action == 6) {
			$scores[$equipe]++;
		}
		$score = $scores[1] . "-" . $scores[2];
		$fieldsValues = $id1."','".
			$temps."','".
			$score."','".
			$action."','".
			$equipe."','".
			$num_joueur."','".
			$nom_joueur[$equipe-1][$num_joueur-1];
		$resInsert = $matchBD->insertARecord($matchBD->getMatchTableName(), $fieldsValues);
		echo "Insert of ".$temps." is done in ".$matchBD->getMatchTableName()."<br>";

	} else {

		// auto generate output, labels to the left of form elements
		$form->render('*horizontal');
	}

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_hand') );
?>
