<?php

// N'afficher que les erreurs, pas les avertissements...
ini_set("error_reporting", "E_ALL & ~E_NOTICE");

//session_start();

require_once ("Util.php");
require_once ("BDMySQL.class.php");
require_once ("UtilGestionSite.php");
//require_once ("../lib/HTML.php");  
//require_once ("NormalisationHTTP.php"); 

define ("FORM_LIST", "Liste");
define ("ANNULER", "Annuler");
define ("AJOUT_FORM", "Ajouter un élément");
define ("AJOUT_BASE", "Ajout_DB");
define ("AJOUT_VERB", "Ajouter");
define ("AJOUT_UN_DOC", "Ajouter un document déjà présent sur le site");
define ("MODIF_FORM", "Modifier la sélection");
define ("MODIF_BASE", "Modif_DB");
define ("MODIF_VERB", "Modifier");
define ("SUPPR_FORM", "Supprimer la sélection");
define ("SUPPR_BASE", "Suppr_DB");
define ("SUPPR_VERB", "Supprimer");

// Connexion à la base
$bd = Connexion (NOM, PASSE, BASE, SERVEUR);

$debug = isset($_REQUEST['debug']) ? $bd->quote($_REQUEST['debug']) : "";
$action = isset($_REQUEST['action']) ? $bd->quote($_REQUEST['action']) : FORM_LIST;
$valid = isset($_REQUEST['valid']) ? $bd->quote($_REQUEST['valid']) : "";
$num = isset($_REQUEST['num']) ? $bd->quote($_REQUEST['num']) : "0";
$verbe = isset($_REQUEST['verbe']) ? $bd->quote($_REQUEST['verbe']) : AJOUT_VERB;


function getNextFreeId($tableName) {
	global $bd;
	$requete = "SELECT max(`id`) FROM ".$tableName;
	$resId = $bd->execRequete($requete);
	if ($resId) {
		$resBrut = $bd->tableauSuivant($resId);
		$result = $resBrut[0] + 1;
		trace("getNextFreeId(".$tableName."): requete = ".$requete);
		trace("=> result = ".$result);
	} else {
		$result = 1;
	}
	return $result;
}

function retrieveLesFichiersDunRep($rep, $ext="") {
	global $lesRepDest, $tousLesFicDest, $lesFicParRep;
	$lesRepDest[] = $rep;
	$lesFicParRep[$rep] = array();
	$lesSousRep = array();
	trace("function retrieveLesFichiersDunRep(rep=".$rep.", ext=".$ext.")");
	if ($dh = opendir($rep)) {
		while (($file = readdir($dh)) !== false) {
			if ($file == "." || $file == "..") continue;
			if (filetype($rep."/".$file) == "dir") {
				$lesSousRep[] = $rep."/".$file;
			} else {
				if (strlen($ext) == 0 || substr($file,strrpos($file,'.')+1) == $ext) {
					$lesFicParRep[$rep][] = $file;
					$tousLesFicDest[] = $rep."/".$file;
					trace("Fichier trouvé dans ".$rep." : ".$tousLesFicDest[count($tousLesFicDest)-1]);
				}
			}
		}
		closedir($dh);
	}
	foreach ($lesSousRep as $unRep) {
		retrieveLesFichiersDunRep($unRep, $ext);
	}
}

function retrieveListeFichiersDest($ext="") {
	global $lesRepDest, $tousLesFicDest, $lesFicParRep, $home;
	unset( $lesRepDest, $tousLesFicDest, $lesFicParRep );
	trace("function retrieveListeFichiersDest(ext=".$ext.")");
	foreach (getListeRepAVoir() as $index => $unRep) {
		$theRep = $home.$unRep;
		trace("Répertoire à parcourir : ".$theRep." (avec index=".$index.")");
		retrieveLesFichiersDunRep($theRep, $ext);
	}
}


function getNextObj($resObj) {
	global $bd;
	if ($resObj) {
		return $bd->objetSuivant($resObj);
	} else {
		return NULL;
	}
}


?>
