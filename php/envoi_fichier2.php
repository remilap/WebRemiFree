<?php

// Verify if the ini_set() function has been deactivated...
$desactive = ini_get('disable_functions');
if (preg_match("/ini_set/i", "$desactive") == 0) {
	// If it has not been deactivated, we define ini_set in order to only display errors...
	ini_set("error_reporting" , "E_ALL & ~E_NOTICE");
}

//On commence une session pour enregistrer les variables du formulaire...

session_start();

?>

<html>
<head>
<title>Envoi de fichier</title>
<script language="JavaScript">

function verifSelection() {

} // Fin de la fonction
</script>

</head>

<body>

<?php
// repertoires sur le site
$rootWebDir = $_SERVER['DOCUMENT_ROOT'];
$erreur = "";
$msg = "";
function upload($index, $destFilename, $destDir='.', $newSubDir='', $maxsize=FALSE, $extensions=FALSE)
{
	global $erreur;
	global $msg;
	global $rootWebDir;
	//Test1: file correctly uploaded
	if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) {
		$erreur = "Transfert error";
		return FALSE;
	}
	$msg = $_FILES[$index]['name'] . " correctly uploaded<br />";
	//Test2: max size
	if ($maxsize !== FALSE ) {
		if ($_FILES[$index]['size'] > $maxsize) {
			$erreur = "File too large (> " . $maxsize/1024 . " Ko)";
			return FALSE;
		}
		$msg .= "size ok: " . $_FILES[$index]['size'] . " <= " . $maxsize . "<br />";
	} else {
		$msg .= "size not controled<br />";
	}
	//Test3: authorized extension
	$ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
	if ($extensions !== FALSE ) {
		if (!in_array($ext,$extensions)) {
			$erreur = "Extension (" . $ext . ") not supported";
			return FALSE;
		}
		$msg .= "extension " . $ext . " is authorized<br />";
	} else {
		$msg .= "extension not controled<br />";
	}
	//Test4: verify destination directory
	if (substr($destDir,0,1) == "/") {
		$destDir = $rootWebDir . $destDir;
	}
	if (!is_dir($destDir)) {
		$erreur = "Unknown " . $destDir . " directory";
		return FALSE;
	}
	$msg .= "change directory to " . $destDir . " ok<br />";
	$destFile = $destDir;
	//Test5: if new directory requested, create it
	if ($newSubDir != "") {
		if (!mkdir($newSubDir)) {
			$erreur = "Unable to create " . $newSubDir . " subdirectory";
			return FALSE;
		}
		$msg .= "creation ok of " . $newSubDir . " directory<br />";
		$destFile .= "/" . $newSubDir;
	} else {
		$msg .= "No new sub-directory<br />";
	}
	//Move file
	if ($destFilename == "") {
		$destFilename = $_FILES[$index]['name'];
	}
	$destFile .= "/" . $destFilename;
	$res = move_uploaded_file($_FILES[$index]['tmp_name'], $destFile);
	if (!$res) {
		echo "Error moving file from " . $_FILES[$index]['tmp_name'] . " to " . $destFile . "<br />";
		echo $erreur . "<br />";
		return FALSE;
	}
	$msg .= "file correctly copied into " . $destFile . "<br />";
	return TRUE;
}

// Vérifier que le formulaire a été envoyé...
if (isset($_POST['envoi'])) {

	$_SESSION['MAX_FILE_SIZE'] = $_POST['MAX_FILE_SIZE'];
	//$_SESSION['mon_fichier'] = $_POST['mon_fichier'];
	$_SESSION['destFilename'] = $_POST['destFilename'];
	$_SESSION['destDir'] = $_POST['destDir'];
	$_SESSION['newSubDir'] = $_POST['newSubDir'];

	//$theRepert = $_SESSION['destDir'];
	//if ($_SESSION['newSubDir'] != "") {
	//	echo "Nouveau sous-répertoire : " . $_SESSION['newSubDir'];
	//	$theRepert .= "/" . $_SESSION['newSubDir'];
	//}
	//$theDest = "/" . $theRepert . "/" . $_SESSION['destFilename'];
	//echo "Transfert du fichier " . $_SESSION['mon_fichier'] . "<br />";
	//echo "vers le fichier " . $theDest . "<br />";

	$erreur = "";
	//$upload1 = upload('mon_fichier', $_SESSION['destFilename'], 15360, array('png','gif','jpg','jpeg') );
	if (upload('mon_fichier', $_SESSION['destFilename'], $_SESSION['destDir'], $_SESSION['newSubDir'])) {
		echo $msg;
	} else {
		echo $erreur . "<br />";
	}


} // Fin de if POST
?>


<form name="envoi_fichier" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onSubmit="return verifSelection()">
<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
<table>
	<!--
	<label for="icone">Icone du fichier (JPG, PNG ou GIF | max. 15Ko):</label><br />
	<input type="file" name="icone" id="icone" /><br />
	//-->
	<tr>
	<td><label for="mon_fichier">Fichier : </label></td>
	<td><input type="file" name="mon_fichier" id="mon_fichier" /></td>
	<td>Fichier de votre PC, taille max: 1 Mo</td>
	</tr>
	<tr>
	<td><label for="destFilename">Fichier de destination : </label></td>
	<td><input type="text" name="destFilename" id="destFilename" /></td>
	<td>Nom du fichier destination, max 50 caractères</td>
	</tr>
	<tr>
	<td><label for="destDir">Répertoire de destination : </label></td>
	<td><select name="destDir" id="destDir" />
		<option value="/css">css</option>
		<option value="/img">img</option>
		<option value="/include">include</option>
		<option value="/javascript">javascript</option>
		<option value="/logiciels">logiciels</option>
		<option value="/Perso">Perso</option>
		<option value="/photos">photos</option>
		<option value="/php">php</option>
		<option value="/presentations">presentations</option>
		<option value="/secret">secret</option>
		<option value="/spip">spip</option>
	</select></td>
	<td>.</td>
	</tr>
	<tr>
	<td><label for="newSubDir">Nouveau sous-répertoire : </label></td>
	<td><input type="text" name="newSubDir" id="newSubDir" /></td>
	<td>.</td>
	</tr>
	<tr>
	<td colspan="3"><input type="submit" name="envoi" value="Envoyer" /></td>
	</tr>
</table>
</form>

</body>
</html>
