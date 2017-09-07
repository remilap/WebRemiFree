<?php
	$title = "User modification";
	$date = date( "Ymd", getlastmod());
	$home = "../..";
	$homeDir = $_SERVER['DOCUMENT_ROOT'] ."/";

	require_once('TemplateEngine.php');
	require_once('Util.class.php');
	require_once('GestionLabBD.class.php');
	$tpl = new TemplateEngine($home."/template_lab.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete_lab") );

	$debug = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : "";
	$util = new Util($debug);
	$util->traceVariables();

	$labBD = new GestionLabBD(0);
	if (! $labBD) {
		echo "<P><B><FONT COLOR='RED'>ERROR unable to connect to the database</FONT></B></P>\n";
		exit (1);
	}

	$resCnx = $labBD->connexion();
	if ($resCnx) {
		echo "<P><B><FONT COLOR='RED'>".$resCnx."<BR>".$labBD->messageSGBD()."</FONT></B></P>\n";
		exit (1);
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
	$selectedUser = isset($_REQUEST['selectedUser']) ? $_REQUEST['selectedUser'] : "";

	if ($action == "modify" && $selectedUser != "") {
		$resList = $labBD->getAllUsers($selectedUser);
		if (! $resList) {
			echo "<P><B><FONT COLOR='RED'>No user found with id ".$selectedUser."</FONT></B></P>\n";
			exit (1);
		}

		$unUser = $labBD->getNextLine($resList);
		$username = $unUser->username;
		$email = $unUser->email;
		$phone = $unUser->phone;
	}

	// include the Iriven class
	require rtrim(dirname(__FILE__), '\\/') . DIRECTORY_SEPARATOR.$home.'/libs/Iriven_FormManager.php';

	// instantiate a Iriven object
	$form = new Iriven_FormManager('form_mod_user');

	// the label for the "username" field
	$form->add('label', 'label_username', 'username', 'Username:');
	// add the "username" field
	$obj = & $form->add('text', 'username');
	// set rules
	$obj->set_rule(array(
		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array('error', 'Username is required!')
	));
	$form->assign('username', $username);

	// the label for the "email" field
	$form->add('label', 'label_email', 'email', 'e-mail:');
	// add the "email" field
	$obj = & $form->add('text', 'email');
	// set rules
	$obj->set_rule(array(
		// error messages will be sent to a variable called "error", usable in custom templates
	//	'required' => array('error', 'email is required!')
        'email'     =>  array('error', 'Email address seems to be invalid!'),
	));
	$form->assign('email', $email);

	// the label for the "phone" field
	$form->add('label', 'label_phone', 'phone', 'Phone:');
	// add the "phone" field
	$obj = & $form->add('text', 'phone');
	// set rules
	//$obj->set_rule(array(
	//	// error messages will be sent to a variable called "error", usable in custom templates
	//	'required' => array('error', 'Phone is required!')
	//));

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
		$id1 = $labBD->getNextFreeId($labBD->getUserTableName());
		$username = $_POST['username'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$fieldsValues = $id1."','".
			$username."','".
			$email."','".
			$phone;
		$resInsert = $labBD->insertARecord($labBD->getUserTableName(), $fieldsValues);
		echo "Insert of ".$username." is done in ".$labBD->getUserTableName()."<br>";

	} else {

		// auto generate output, labels to the left of form elements
		$form->render('*horizontal');
	}

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
