<?php

// On vérifie si la fonction ini_set() a été désactivée...
$desactive = ini_get('disable_functions');
if (preg_match("/ini_set/i", "$desactive") == 0) {
	// Si elle n'est pas désactivée, on définit ini_set de manière à n'afficher que les erreurs...
	ini_set("error_reporting" , "E_ALL & ~E_NOTICE");
}

$erreur = "";
function upload($index, $destination, $maxsize=FALSE, $extensions=FALSE)
{
	//Test1: fichier correctement uploade
	if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) {
		$erreur = "Erreur de transfert";
		return FALSE;
	}
	//Test2: taille limite
	if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) {
		$erreur = "Fichier trop gros";
		return FALSE;
	}
	//Test3: extension
	$ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
	if ($extensions !== FALSE AND !in_array($ext,$extensions)) {
		$erreur = "Extension non supportee";
		return FALSE;
	}
	//Deplacement
	$res = move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
	if (!$res) {
		$erreur = "Erreur lors du deplacement du fichier";
		return FALSE;
	}
	return TRUE;
}

// Vérifier que le formulaire a été envoyé...
if (isset($_POST['envoi'])) {

	//On commence une session pour enregistrer les variables du formulaire...

	session_start();

	$_SESSION['MAX_FILE_SIZE'] = $_POST['MAX_FILE_SIZE'];
	$_SESSION['mon_fichier'] = $_POST['mon_fichier'];
	$_SESSION['destination'] = $_POST['destination'];
	$_SESSION['description'] = $_POST['description'];

	$erreur = "";
	//$upload1 = upload('mon_fichier', $_SESSION['destination'], 15360, array('png','gif','jpg','jpeg') );
	$upload1 = upload('mon_fichier', $_SESSION['destination'] );

	
} // Fin de if POST
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

<form name="envoi_fichier" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data" onSubmit="return verifSelection()">
	<!--
	<label for="icone">Icone du fichier (JPG, PNG ou GIF | max. 15Ko):</label><br />
	<input type="file" name="icone" id="icone" /><br />
	//-->
	<label for="mon_fichier">Fichier (Tous formats | max. 1Mo):</label><br />
	<input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
	<input type="file" name="mon_fichier" id="mon_fichier" /><br />
	<label for="destination">Destination du fichier (max 50 caractères):</label><br />
	<input type="text" name="destination" value="." id="titre" /><br />
	<label for="description">Description de votre fichier (max 255 caractères):</label><br />
	<textarea name="description" id="description"></textarea><br />
	<input type="submit" name="envoi" value="Envoyer" />
</form>

</body>
</html>
