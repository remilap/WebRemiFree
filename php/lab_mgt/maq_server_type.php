<?php
	$titre = "Essai de PHP + MySQL";
	$date = date( "Ymd", getlastmod());
	$home = "../../";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('BDMySQL.class.php');
	$tpl = new TemplateEngine($home."template_remi.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_mysql") );

	$util = new Util(0);

	$imgAbsDir = $siteWeb . "/img/";
	$imgDir = "../img";
	$jvsAbsDir = $siteWeb . "/javascript/";
	$phpDir = $siteWeb . "/php/";

define ("NOM","remi.lapointe");
define ("PASSE", "rem000");
define ("SERVEUR", "localhost");
define ("BASE", "remi_lapointe");

// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

	$debug = "";
	if ( isset($_REQUEST['debug']) ) $debug = $_REQUEST['debug'];
	$action = "";
	if ( isset($_REQUEST['action']) ) $action = $_REQUEST['action'];

	$tab_maq_s_t = "maq_server_type";

	$util->debugOff();
	if ($debug) $util->debugOn();
	//$res = $util->is_PDO_mysql_extension_supported();
	//if ($res === FALSE) {
	//	echo "PDO extension is not supported on the server<br>";
	//}
	//$res = $util->get_ip();
	//echo "Votre adresse IP : ".$res."<br>";


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
