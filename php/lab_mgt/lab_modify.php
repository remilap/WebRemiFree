<?php
	$title = "Lab modification";
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
	//$util->setDebugOff();
	//if ($debug) $util->setDebugOn();
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
	$selectedLab = isset($_REQUEST['selectedLab']) ? $_REQUEST['selectedLab'] : "";

	if ($action == "modify" && $selectedLab != "") {
		$resList = $labBD->getAllLabs($selectedLab);
		if (! $resList) {
			echo "<P><B><FONT COLOR='RED'>No lab found with id ".$selectedLab."</FONT></B></P>\n";
			exit (1);
		}

		$unLab = $labBD->getNextLine($resList);
		$hostname = $unLab->hostname;
		$platform = $unLab->platform;
		$primary_ip_addr = $unLab->primary_ip_addr;
		$primary_netmask = $unLab->primary_netmask;
		$primary_gateway = $unLab->primary_gateway;
		$secondary_ip_addr = $unLab->secondary_ip_addr;
		$secondary_netmask = $unLab->secondary_netmask;
		$secondary_gateway = $unLab->secondary_gateway;
		$backup_ip_addr = $unLab->backup_ip_addr;
		$backup_netmask = $unLab->backup_netmask;
		$backup_gateway = $unLab->backup_gateway;
		$ilo2_ip_addr = $unLab->ilo2_ip_addr;
		$ilo2_netmask = $unLab->ilo2_netmask;
		$ilo2_gateway = $unLab->ilo2_gateway;
		$server_type = $unLab->server_type;
		$processor = $unLab->processor;
		$memory = $unLab->memory;
		$disks = $unLab->disks;
		$installed_release = $unLab->installed_release;
		$axadmin_password = $unLab->axadmin_password;
		$root_password = $unLab->root_password;
		//$affectation = $unLab->affectation;
		$comment = $unLab->comment;
	}

	// include the Iriven class
	require rtrim(dirname(__FILE__), '\\/') . DIRECTORY_SEPARATOR.$home.'/libs/Iriven_FormManager.php';

	// instantiate a Iriven object
	$form = new Iriven_FormManager('form_mod_lab');

	// the label for the "hostname" field
	$form->add('label', 'label_hostname', 'hostname', 'Hostname:');
	// add the "hostname" field
	$obj = & $form->add('text', 'hostname');
	// set rules
	$obj->set_rule(array(
		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array('error', 'Hostname is required!')
	));
	$form->assign('hostname', $hostname);

	// the label for the "platform" field
	$form->add('label', 'label_platform', 'platform', 'Platform:');
	// add the "platform" field
	$obj = & $form->add('select', 'platform', '', array('other' => true));
	$resPlatform = $labBD->getDistinctItemsFromLab("platform");
	while ($unePlatform = $bd->objetSuivant($resPlatform)) {
		$obj->add_options(array($unePlatform->platform));
	}
	// set rules
	//$obj->set_rule(array(
	//	// error messages will be sent to a variable called "error", usable in custom templates
	//	'required' => array('error', 'Platform is required!')
	//));
	$form->assign('platform', $platform);

	// the label for the "primary_ip_addr" field
	$form->add('label', 'label_primary_ip_addr', 'primary_ip_addr', 'Primary IP address:');
	// add the "primary_ip_addr" field
	$obj = & $form->add('text', 'primary_ip_addr');
	// set rules
	$obj->set_rule(array(
		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array('error', 'Primary IP address is required!')
	));

	// the label for the "primary_netmask" field
	$form->add('label', 'label_primary_netmask', 'primary_netmask', 'Primary netmask:');
	// add the "primary_netmask" field
	$obj = & $form->add('text', 'primary_netmask');

	// the label for the "primary_gateway" field
	$form->add('label', 'label_primary_gateway', 'primary_gateway', 'Primary gateway:');
	// add the "primary_gateway" field
	$obj = & $form->add('text', 'primary_gateway');

	// the label for the "secondary_ip_addr" field
	$form->add('label', 'label_secondary_ip_addr', 'secondary_ip_addr', 'Secondary IP address:');
	// add the "secondary_ip_addr" field
	$obj = & $form->add('text', 'secondary_ip_addr');

	// the label for the "secondary_netmask" field
	$form->add('label', 'label_secondary_netmask', 'secondary_netmask', 'Secondary netmask:');
	// add the "secondary_netmask" field
	$obj = & $form->add('text', 'secondary_netmask');

	// the label for the "secondary_gateway" field
	$form->add('label', 'label_secondary_gateway', 'secondary_gateway', 'Secondary gateway:');
	// add the "secondary_gateway" field
	$obj = & $form->add('text', 'secondary_gateway');

	// the label for the "backup_ip_addr" field
	$form->add('label', 'label_backup_ip_addr', 'backup_ip_addr', 'Backup IP address:');
	// add the "backup_ip_addr" field
	$obj = & $form->add('text', 'backup_ip_addr');

	// the label for the "backup_netmask" field
	$form->add('label', 'label_backup_netmask', 'backup_netmask', 'Backup netmask:');
	// add the "backup_netmask" field
	$obj = & $form->add('text', 'backup_netmask');

	// the label for the "backup_gateway" field
	$form->add('label', 'label_backup_gateway', 'backup_gateway', 'Backup gateway:');
	// add the "backup_gateway" field
	$obj = & $form->add('text', 'backup_gateway');

	// the label for the "ilo2_ip_addr" field
	$form->add('label', 'label_ilo2_ip_addr', 'ilo2_ip_addr', 'iLO2 IP address:');
	// add the "ilo2_ip_addr" field
	$obj = & $form->add('text', 'ilo2_ip_addr');

	// the label for the "ilo2_netmask" field
	$form->add('label', 'ilo2_netmask', 'ilo2_netmask', 'iLO2 netmask:');
	// add the "ilo2_netmask" field
	$obj = & $form->add('text', 'ilo2_netmask');

	// the label for the "ilo2_gateway" field
	$form->add('label', 'label_ilo2_gateway', 'ilo2_gateway', 'iLO2 gateway:');
	// add the "ilo2_gateway" field
	$obj = & $form->add('text', 'ilo2_gateway');

	// the label for the "server_type" field
	$form->add('label', 'label_server_type', 'server_type', 'Server type:');
	// add the "server_type" field
	$obj = & $form->add('select', 'server_type', '', array('other' => true));
	$resServerType = $labBD->getDistinctItemsFromLab("server_type");
	while ($unServTyp = $bd->objetSuivant($resServerType)) {
		$obj->add_options(array($unServTyp->server_type));
	}

	// the label for the "processor" field
	$form->add('label', 'label_processor', 'processor', 'Processor:');
	// add the "processor" field
	$obj = & $form->add('select', 'processor', '', array('other' => true));
	$resProcessor = $labBD->getDistinctItemsFromLab("processor");
	while ($unProcessor = $bd->objetSuivant($resProcessor)) {
		$obj->add_options(array($unProcessor->processor));
	}

	// the label for the "memory" field
	$form->add('label', 'label_memory', 'memory', 'Memory (GB):');
	// add the "memory" field
	$obj = & $form->add('select', 'memory', '', array('other' => true));
	$resMemory = $labBD->getDistinctItemsFromLab("memory");
	while ($unMemory = $bd->objetSuivant($resMemory)) {
		$obj->add_options(array($unMemory->memory));
	}

	// the label for the "disks" field
	$form->add('label', 'label_disks', 'disks', 'Disks:');
	// add the "disks" field
	$obj = & $form->add('select', 'disks', '', array('other' => true));
	$resDisks = $labBD->getDistinctItemsFromLab("disks");
	while ($unDisk = $bd->objetSuivant($resDisks)) {
		$obj->add_options(array($unDisk->disks));
	}

	// the label for the "installed_release" field
	$form->add('label', 'label_installed_release', 'installed_release', 'Installed release:');
	// add the "installed_release" field
	$obj = & $form->add('select', 'installed_release', '', array('other' => true));
	$resRelease = $labBD->getDistinctItemsFromLab("installed_release");
	while ($uneRelease = $bd->objetSuivant($resRelease)) {
		$obj->add_options(array($uneRelease->installed_release));
	}

	// the label for the "axadmin_password" field
	$form->add('label', 'label_axadmin_password', 'axadmin_password', 'axadmin password:');
	// add the "axadmin_password" field
	$obj = & $form->add('select', 'axadmin_password', '', array('other' => true));
	$resAxadmin = $labBD->getDistinctItemsFromLab("axadmin_password");
	while ($unPwd = $bd->objetSuivant($resAxadmin)) {
		$obj->add_options(array($unPwd->axadmin_password));
	}

	// the label for the "root_password" field
	$form->add('label', 'label_root_password', 'root_password', 'Root password:');
	// add the "root_password" field
	$obj = & $form->add('select', 'root_password', '', array('other' => true));
	$resRoot = $labBD->getDistinctItemsFromLab("root_password");
	while ($unPwd = $bd->objetSuivant($resRoot)) {
		$obj->add_options(array($unPwd->root_password));
	}

	// the label for the "affectation" field
//	$form->add('label', 'label_affectation', 'affectation', 'Affectation:');
	// add the "affectation" field
//	$obj = & $form->add('select', 'affectation', '', array('other' => true));
//	$resAffect = $labBD->getDistinctItemsFromLab("affectation");
//	while ($uneAffec = $bd->objetSuivant($resAffect)) {
//		$obj->add_options(array($uneAffec->affectation));
//	}

	// the label for the "comment" field
	$form->add('label', 'label_comment', 'comment', 'Comments:');
	// add the "comment" field
	$obj = & $form->add('textarea', 'comment');

	// the label for the "debug" field
	$form->add('label', 'label_debug', 'debug', 'Debug');
	// add the "debug" field
	$obj = & $form->add('checkboxes', 'debugs[]', array('debug' => 'Debug'));

	// "submit"
	$form->add('submit', 'btn_submit', 'Add');

	// validate the form
	if ($form->validate()) {
		// do stuff here
		$util->traceVariables();
		$id1 = $labBD->getNextFreeId($labBD->getLabTableName());
		$hostname = $_POST['hostname'];
		$platform = $_POST['platform'];
		if ($platform == "other" || $platform == "Other") {
			$platform = $_POST['platform_other'];
		}
		if ($server_type == "other" || $server_type == "Other") {
			$server_type = $_POST['server_type_other'];
		}
		if ($processor == "other" || $processor == "Other") {
			$processor = $_POST['processor_other'];
		}
		if ($memory == "other" || $memory == "Other") {
			$memory = $_POST['memory_other'];
		}
		if ($disks == "other" || $disks == "Other") {
			$disks = $_POST['disks_other'];
		}
		if ($installed_release == "other" || $installed_release == "Other") {
			$installed_release = $_POST['installed_release_other'];
		}
		if ($axadmin_password == "other" || $axadmin_password == "Other") {
			$axadmin_password = $_POST['axadmin_password_other'];
		}
		if ($root_password == "other" || $root_password == "Other") {
			$root_password = $_POST['root_password_other'];
		}
		if ($affectation == "other" || $affectation == "Other") {
			$disks = $_POST['affectation_other'];
		}
		$fieldsValues = $id1."','".
			$hostname."','".
			$platform."','".
			$_POST['primary_ip_addr']."','".
			$_POST['primary_netmask']."','".
			$_POST['primary_gateway']."','".
			$_POST['secondary_ip_addr']."','".
			$_POST['secondary_netmask']."','".
			$_POST['secondary_gateway']."','".
			$_POST['backup_ip_addr']."','".
			$_POST['backup_netmask']."','".
			$_POST['backup_gateway']."','".
			$_POST['ilo2_ip_addr']."','".
			$_POST['ilo2_netmask']."','".
			$_POST['ilo2_gateway']."','".
			$server_type."','".
			$processor."','".
			$memory."','".
			$disks."','".
			$installed_release."','".
			$axadmin_password."','".
			$root_password."','".
			$affectation."','".
			$_POST['comment'];
		$resInsert = $labBD->insertARecord($labBD->getLabTableName(), $fieldsValues);
		echo "Insert of ".$hostname." is done in ".$labBD->getLabTableName()."<br>";

	} else {

		// auto generate output, labels to the left of form elements
		$form->render('*horizontal');
	}

	TemplateEngine::Output( $tpl->GetHTMLCode('baspage_lab') );
?>
