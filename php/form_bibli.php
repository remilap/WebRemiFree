<?php

// On verifie si la fonction ini_set() a ete desactivee...
$desactive = ini_get('disable_functions');
if (preg_match("/ini_set/i", "$desactive") == 0) {
	// Si elle n'est pas desactivee, on definit ini_set de maniere a n'afficher que les erreurs...
	ini_set("error_reporting" , "E_ALL & ~E_NOTICE");
}


// Definir l\'icone apparaissant en cas d\'erreur...

// Definir sur 0 pour afficher un petit x de couleur rouge.
// Definir sur 1 pour afficher l\'image d\'une croix rouge telle que celle utilisee dans l\'assistant
// Si vous utilisez l\'option 1, l\'image de la croix rouge \'icone.gif\' doit se trouver dans le repertoire \'images\',
// ce dernier devant se trouver au meme niveau que votre formulaire...
$flag_icone = 0;
// On verifie si $flag_icone est defini sur 0 ou 1...
if ($flag_icone == 0) {
	$icone = "<b><font size=\"3\" face=\"Arial, Verdana, Helvetica, sans-serif\" color=\"#CC0000\">x</font></b>";
} else {
	$icone = "<img src=\"images/icone.gif\"";
}


function retrieveTextField($fieldName) {
	$_SESSION[$fieldName] = $_POST[$fieldName];
}

function verifyTextField($fieldName, $message) {
	if (document.mail_form.$fieldName.value == "") {
		//alert($message);
		$erreur[$fieldName] = $message;
		return false;
	}
}

function getHTMLLabelField($labelName, $labelSize="140") {
	$line = '<tr>\n
		<td width="' . $labelSize . '"><div align="right">\n
		<font face="Verdana" size="2">' . $labelName . '</font></div>\n
		</td>\n
		<td align="center" valign="middle" width="30">\n';
	if ($erreur[$fieldName]) {
		$line .= $icone;
	} else {
		$line .= ' ';
	}
	$line .= '</td>\n';
	return $line;
}

function getHTMLTextField($labelName, $fieldName, $fieldSize="50", $fieldMaxLength="100", $labelSize="140" ) {
	$line = getHTMLLabelField($labelName, $labelSize);
	$line .= '<td><input name="' . $fieldName . '" type="text" value="' .
		stripslashes($_SESSION[$fieldName]) . '" size="' .
		$fieldSize . '" maxlength="' . $fieldMaxLength . '"></td>\n
		</tr>\n';

	return $line;
}

function retrieveRadioField($fieldName) {
	$_SESSION[$fieldName] = $_POST[$fieldName];
}

function verifyRadioField($fieldName, $message) {
	nbButtons = document.mail_form.$fieldName.length;
	flag = 0;
	for (i = 0; i < nbButtons; i++) {
		if (document.mail_form.$fieldName[i].checked) {
			flag = 1;
		}
	}
	if (flag == 0) {
		//alert($message);
		$erreur[$fieldName] = $message;
		return false;
	}
}

function getHTMLRadioField($labelName, $fieldName, $fieldValues, $labelSize="140", $align="VERTI") {
	$line = getHTMLLabelField($labelName, $labelSize);
	$values = explode(";", $fieldValues);
	for ($i = 0; $i < $values.length; $i++) {
		$line .= '</td>\n
			<td><input type="radio" name="' . $fieldName .
			'" value="' . $values[$i] . '"';
		if ($_SESSION[$fieldName] == $values[$i]) {
			$line .= ' checked';
		}
		$line .= '<font face="Verdana" size="2">' . $values[$i] . '</font>\n';
		if ($align == "VERTI") {
			$line .= '<br>\n';
		} else {
			$line .= ' \n';
		}
	}
	$line .= '</td>\n</tr>\n';

	return $line;
}




// Vérifier que le formulaire a été envoyé...
if (isset($_POST['envoi'])) {


	//On commence une session pour enregistrer les variables du formulaire...

	session_start();

	$_SESSION['champ1'] = $_POST['champ1'];
	$_SESSION['champ2'] = $_POST['champ2'];
	$_SESSION['champ3'] = $_POST['champ3'];
	$_SESSION['zone_email1'] = $_POST['zone_email1'];
	$_SESSION['liste1'] = $_POST['liste1'];

	//Evaluation du bouton 1 ...
	switch($_POST['bouton1']) {
		case "Mlle":
			$_SESSION['bouton1'] = "Mlle";
			break;
		case "Mme":
			$_SESSION['bouton1'] = "Mme";
			break;
		case "M.":
			$_SESSION['bouton1'] = "M.";
			break;
		default:
			$_SESSION['bouton1'] = "";
	} // Fin du switch...

	//Enregistrement des paramètres de la case 1...
	$_SESSION['case1_'][0] = "";
	if (isset($_POST['case1_'][0])) {
		$_SESSION['case1_'][0] = $_POST['case1_'][0];
	} // Fin du if...

	$_SESSION['case1_'][1] = "";
	if (isset($_POST['case1_'][1])) {
		$_SESSION['case1_'][1] = $_POST['case1_'][1];
	} // Fin du if...

	$_SESSION['case1_'][2] = "";
	if (isset($_POST['case1_'][2])) {
		$_SESSION['case1_'][2] = $_POST['case1_'][2];
	} // Fin du if...

	$_SESSION['case1_'][3] = "";
	if (isset($_POST['case1_'][3])) {
		$_SESSION['case1_'][3] = $_POST['case1_'][3];
	} // Fin du if...

	$_SESSION['case1_'][4] = "";
	if (isset($_POST['case1_'][4])) {
		$_SESSION['case1_'][4] = $_POST['case1_'][4];
	} // Fin du if...

	//Enregistrement des zones de texte...
	$_SESSION['zone_texte1'] = $_POST['zone_texte1'];

	// Définir l'indicateur d'erreur sur zéro...
	$flag_erreur = 0;
	// N'envoyer le formulaire que s'il n'y a pas d'erreurs...
	if ($flag_erreur == 0) {					

		// Addresse de réception du formulaire
		$email_dest = "remi.lapointe@gmail.com";
		$sujet = "Questionnaire personne";
		$entetes ="MIME-Version: 1.0 \n";
		$entetes .="From: Remi<remi.lapointe@free.fr>\n";
		$entetes .="Return-Path: Remi<remi.lapointe@free.fr>\n";
		$entetes .="Reply-To: Remi<remi.lapointe@free.fr>\n";
		$entetes .="Content-Type: text/html; charset=iso-8859-1 \n";
		$partie_entete = "<html>\n<head>\n<title>Formulaire</title>\n<meta http-equiv=Content-Type content=text/html; charset=iso-8859-1>\n</head>\n<body bgcolor=#FFFFFF>\n";


		//Partie HTML de l'e-mail...
		$partie_champs_texte .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Nom = " . $_SESSION['champ1'] . "</font><br>\n";
		$partie_champs_texte .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Prénom = " . $_SESSION['champ2'] . "</font><br>\n";
		$partie_champs_texte .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Tel = " . $_SESSION['champ3'] . "</font><br>\n";
		$partie_zone_email .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">email = " . $_SESSION['zone_email1'] . "</font><br>\n";
		$partie_listes .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Ville = " . $_SESSION['liste1'] . "</font><br>\n";
		$partie_boutons .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Genre = " . $_SESSION['bouton1'] . "</font><br>\n";
		$partie_cases .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Interêts</font><br>\n";
		$partie_cases .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Case 1 = " . $_SESSION['case1_'][0] . "</font><br>\n";
		$partie_cases .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Case 2 = " . $_SESSION['case1_'][1] . "</font><br>\n";
		$partie_cases .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Case 3 = " . $_SESSION['case1_'][2] . "</font><br>\n";
		$partie_cases .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Case 4 = " . $_SESSION['case1_'][3] . "</font><br>\n";
		$partie_cases .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Case 5 = " . $_SESSION['case1_'][4] . "</font><br>\n";
		$partie_zone_texte .= "<font face=\"Verdana\" size=\"2\" color=\"#003366\">Message = " . $_SESSION['zone_texte1'] . "</font><br>\n";


		// Fin du message HTML
		$fin = "</body></html>\n\n";

		$sortie = $partie_entete . $partie_champs_texte . $partie_zone_email . $partie_listes . $partie_boutons . $partie_cases . $partie_zone_texte . $fin;


		// Send the e-mail
		if (@!mail($email_dest,$sujet,$sortie,$entetes)) {
			echo("Envoi du formulaire impossible");
			exit();
		} else {

			// Rediriger vers la page de remerciement
			header("Location:../index.php");
			exit();
		} // Fin else
	} // Fin du if ($flag_erreur == 0) {
} // Fin de if POST
?>

<html>
<!-- 
Assistant de création de formulaires PHP pour les nuls - Version gratuite 1.6
Auteur : Frédéric Ménard (assistant@f1-fantasy.net)
Site : http://www.f1-fantasy.net/assistant
 -->
<head>
<title>Formulaire</title>
<script language="JavaScript">

function verifSelection() {

	if (document.mail_form.champ1.value == "") {
		alert("Veuillez saisir votre nom")
		return false
	} 

	if (document.mail_form.champ2.value == "") {
		alert("Veuillez saisir votre prénom")
		return false
	}

	nbreboutons1 = document.mail_form.bouton1.length

	flag = 0

	for (i = 0; i < nbreboutons1 ; i++) {

		if (document.mail_form.bouton1[i].checked) {

			flag = 1

		}

	}


	if (flag == 0) {

		alert("Veuillez indiquer votre genre")
		return false;
	}


} // Fin de la fonction
</script>

</head>

<body>
<form name="mail_form" method="post" action="<?=$_SERVER['PHP_SELF']?>" onSubmit="return verifSelection()">
	<div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif, Tahoma">
	<strong>Formulaire de contact</strong></font></div><br>
	<table align="center" width="566" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td height="16"><div align="center">
			<font color="#CC0000" size="2" face="Verdana, Arial, Helvetica, sans-serif, Tahoma"><strong>
			<?php
			if ($erreur_champ1) {
				echo(stripslashes($erreur_champ1));
			}
			if ($erreur_champ2) {
				echo(stripslashes($erreur_champ2));
			}
			if ($erreur_champ3) {
				echo(stripslashes($erreur_champ3));
			}
			if ($erreur_email1) {
				echo(stripslashes($erreur_email1));
			}
			if ($erreur_liste1) {
				echo(stripslashes($erreur_liste1));
			}
			if ($erreur_bouton1) {
				echo(stripslashes($erreur_bouton1));
			}
			if ($erreur_case1) {
				echo(stripslashes($erreur_case1));
			}
			if ($erreur_texte1) {
				echo(stripslashes($erreur_texte1));
			}
			?>
			</strong></font></div>
			</td>
		</tr>
	</table>
	<p align="center"></p>
	<table width="566" border="0" align="center">
		<tr>
			<td width="140"><div align="right">
			<font face="Verdana" size="2">Nom</font></div>
			</td>
			<td align="center" valign="middle" width="30">
			<?php
			if ($erreur_champ1) {
				echo($icone);
			}
			?>
			</td>
			<td><input name="champ1" type="text" value="<?=stripslashes($_SESSION['champ1']);?>"></td>
		</tr>
	</table>
	<table width="566" border="0" align="center">
		<tr>
			<td width="140"><div align="right">
			<font face="Verdana" size="2">Prénom</font></div>
			</td>
			<td align="center" valign="middle" width="30">
			<?php
			if ($erreur_champ2) {
				echo($icone);
			}
			?>
			</td>
			<td><input name="champ2" type="text" value="<?=stripslashes($_SESSION['champ2']);?>"></td>
		</tr>
	</table>
	<table width="566" border="0" align="center">
		<tr>
			<td width="140"><div align="right">
			<font face="Verdana" size="2">Tel</font></div>
			</td>
			<td align="center" valign="middle" width="30">
			<?php
			if ($erreur_champ3) {
				echo($icone);
			}
			?>
			</td>
			<td><input name="champ3" type="text" value="<?=stripslashes($_SESSION['champ3']);?>"></td>
		</tr>
	</table>
	<table width="566" border="0" align="center">
		<tr>
			<td width="140"><div align="right">
			<font face="Verdana" size="2">email</font></div>
			</td>
			<td width="30" align="center" valign="middle">
			<?php
			if ($erreur_email1) {
				echo($icone);
			}
			?>
			</td>
			<td><input name="zone_email1" type="text" value="<?=stripslashes($_SESSION['zone_email1']);?>"></td>
		</tr>
	</table>
	<table width="566" border="0" align="center">
		<tr>
			<td width="140"><div align="right">
			<font face="Verdana" size="2">Ville</font></div>
			</td>
			<td width="30" align="center" valign="middle">
			<?php
			if ($erreur_liste1) {
				echo($icone);
			}
			?>
			</td>
			<td><select name="liste1" style="width:146">
				<option value="">Sélectionner...</option>
				<option value="Paris"<?php
				if ($_SESSION['liste1'] == "Paris") {
					echo(" selected");
				}
				?>>Paris</option>
				<option value="Nantes"<?php
				if ($_SESSION['liste1'] == "Nantes") {
					echo(" selected");
				}
				?>>Nantes</option>
				<option value="Marseille"<?php
				if ($_SESSION['liste1'] == "Marseille") {
					echo(" selected");
				}
				?>>Marseille</option>
				<option value="Lyon"<?php
				if ($_SESSION['liste1'] == "Lyon") {
					echo(" selected");
				}
				?>>Lyon</option>
				<option value="Lille"<?php
				if ($_SESSION['liste1'] == "Lille") {
					echo(" selected");
				}
				?>>Lille</option>
			</select>
			</td>
		</tr>
	</table>
	<table width="566" border="0" align="center">
		<tr>
			<td width="140"><div align="right">
			<font face="Verdana" size="2">Genre</font></div>
			</td>
			<td width="30" align="center" valign="middle">
			<?php
			if ($erreur_bouton1) {
				echo($icone);
			}
			?>
			</td>
			<td><input type="radio" name="bouton1" value="Mlle"<?php
			if ($_SESSION['bouton1'] == "Mlle") {
				echo(" checked");
			}
			?>>
			<font face="Verdana" size="2">Mlle</font><br>
			<input type="radio" name="bouton1" value="Mme"<?php
			if ($_SESSION['bouton1'] == "Mme") {
				echo(" checked");
			}
			?>>
			<font face="Verdana" size="2">Mme</font><br>
			<input type="radio" name="bouton1" value="M."<?php
			if ($_SESSION['bouton1'] == "M.") {
				echo(" checked");
			}
			?>>
			<font face="Verdana" size="2">M.</font>
			</td>
		</tr>
	</table>
	<table width="566" border="0" align="center">
		<tr>
			<td width="140"><div align="right">
			<font face="Verdana" size="2">Interêts</font></div>
			</td>
			<td width="30" align="center" valign="middle">
			<?php
			if ($erreur_case1) {
				echo($icone);
			}
			?>
			</td>
			<td><input type="checkbox" name="case1_[0]" id="case1_" value="Sport"<?php
			if ($_SESSION['case1_'][0] == "Sport") {
				echo(" checked");
			}
			?>><font face="Verdana" size="2">Sport</font><br>
			<input type="checkbox" name="case1_[1]" id="case1_" value="Informatique"<?php
			if ($_SESSION['case1_'][1] == "Informatique") {
				echo(" checked");
			}
			?>><font face="Verdana" size="2">Informatique</font><br>
			<input type="checkbox" name="case1_[2]" id="case1_" value="Lecture"<?php
			if ($_SESSION['case1_'][2] == "Lecture") {
				echo(" checked");
			}
			?>><font face="Verdana" size="2">Lecture</font><br>
			<input type="checkbox" name="case1_[3]" id="case1_" value="Musique"<?php
			if ($_SESSION['case1_'][3] == "Musique") {
				echo(" checked");
			}
			?>><font face="Verdana" size="2">Musique</font><br>
			<input type="checkbox" name="case1_[4]" id="case1_" value="Théâtre"<?php
			if ($_SESSION['case1_'][4] == "Théâtre") {
				echo(" checked");
			}
			?>><font face="Verdana" size="2">Théâtre</font>
			</td>
		</tr>
	</table>
	<table width="566" border="0" align="center">
		<tr>
			<td width="140" valign="top"><div align="right">
			<font face="Verdana" size="2">Message</font></div>
			</td>
			<td width="30" align="center" valign="top">
			<?php
			if ($erreur_texte1) {
				echo($icone);
			}
			?>
			</td>
			<td><textarea name="zone_texte1" cols="45" rows="10">
			<?=stripslashes($_SESSION['zone_texte1']);?></textarea>
			</td>
		</tr>
	</table>
	<table width="566" border="0" align="center">
		<tr>
			<td valign="top"><div align="center">
			<input type="reset" name="Reset" value=" Effacer ">
			<input type="submit" name="envoi" value="Envoyer">
			</div>
			</td>
		</tr>
	</table>
	<div align="center">
	<input name="nbre_fichiers" type="hidden" id="nbre_fichiers" value="">
	</div>
</form>

</body>
</html>
