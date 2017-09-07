<?php
	$titre = "Essai de PHP + MySQL";
	$date = date( "Ymd", getlastmod());
	$home = "../../";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	$tpl = new TemplateEngine($home."template_remi.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_mysql") );

	$util = new Util(0);

	$imgAbsDir = $siteWeb . "/img/";
	$imgDir = "../img";
	$jvsAbsDir = $siteWeb . "/javascript/";
	$phpDir = $siteWeb . "/php/";

	$debug = "";
	if ( isset($_REQUEST['debug']) ) $debug = $_REQUEST['debug'];
	$action = "";
	if ( isset($_REQUEST['action']) ) $action = $_REQUEST['action'];

	$mybase = "remi_lapointe";
	$tab_maq_s_t = "maq_server_type";

	$res = $util->get_supported_extensions('PDO');
	echo $res;
	$util->debugOff();
	$res = $util->get_ip();
	echo "Votre adresse IP : ".$res."<br>";

	// initialiser une connexion au serveur SQL, elle va servir tout du long.
	try {
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO('mysql:host=sql.free.fr;dbname='.$mybase, 'remi.lapointe', 'rem000');

	//$lsql = new conSQL( $_SERVER['SERVER_NAME'], $mybase, $debug );

		$reponse = $bdd->query( "SELECT * FROM $tab_maq_s_t ORDER BY Name ASC" );
		$debut = "<table border=\"2\" cellspacing=\"1\" cellpadding=\"4\">\n
			<th>\n
			<td><center><b>Index</b></center></td>\n
			<td><center><b>Name</b></center></td>\n
			</tr>\n";

		if ( $debug > 1 ) print "document_root=$siteWeb ; nombre de lignes : $nbl<br>\n";

		echo $debut;

		$nlig = 0;
		while ($donnees = $reponse->fetch()) {
			$line = "<tr id=\"".$donnees['index']."\">\n
				<td>".$donnees['index']."</td>\n
				<td>".$donnees['name']."</td>\n
				</tr>\n";
			$nlig++;
		}
		print "</table>\n";

		$reponse->closeCursor(); // Termine le traitement de la requête

	} catch (Exception $e) {
		die('Erreur : ' . $e->getMessage());
	}

	// include the Iriven class
	require rtrim(dirname(__FILE__), '\\/') . DIRECTORY_SEPARATOR.$home.'libs/Iriven_FormManager.php';

	// instantiate a Iriven object
	$form = new Iriven_FormManager('form');

	// the label for the "name" field
	$form->add('label', 'label_name', 'name', 'Server type name:');
	// add the "name" field
	$obj = & $form->add('text', 'name');
	// set rules
	$obj->set_rule(array(
		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array('error', 'Name is required!')
	));

	// "submit"
	$form->add('submit', 'btn_submit', 'Add');

	// validate the form
	if ($form->validate()) {
		// do stuff here
		$sql = "INSERT ";
		echo "Bonjour ".$_POST['name'].", c'est fini";

	} else {

		// auto generate output, labels to the left of form elements
		$form->render('*horizontal');
	}

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage') );
?>
