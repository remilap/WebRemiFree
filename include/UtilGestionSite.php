<?php

define ("NOM","remi.lapointe");
define ("PASSE", "rem000");
define ("SERVEUR", "localhost");
define ("BASE", "remi_lapointe");

$table_famille = "bookmark_famille";
$all_fields_famille = "id,nom";
$table_categorie = "bookmark_categorie";
$all_fields_categorie = "id,nom,idFamille";
$table_signet = "bookmark_signet";
$all_fields_signet = "id,texte,ordre,idCateg,href,target,icone";


function getNomFamille($idFam) {
	global $bd,$table_famille,$all_fields_famille;
	$requete = "SELECT ".$all_fields_famille." FROM ".$table_famille." WHERE `id`='".$idFam."'";
	trace("getNomFamille(".$idFam."): requete = ".$requete);
	$resFam = $bd->execRequete($requete);
	if (!$resFam) {
		$famille = "inconnu";
	} else {
		$uneFam = $bd->objetSuivant($resFam);
		$famille = $uneFam->nom;
	}
	return $famille;
}

function getListeFamilles() {
	global $bd,$table_famille,$all_fields_famille;
	$laListe = array();
	$requete = "SELECT ".$all_fields_famille." FROM ".$table_famille." ORDER BY `nom` ASC";
	trace("getListeFamilles(): requete = ".$requete);
	$resListe = $bd->execRequete($requete);
	if ($resListe) {
		while ($unElem = $bd->objetSuivant($resListe)) {
			$laListe[$unElem->id] = $unElem->nom;
			trace("=> indice ".$unElem->id." = ".$unElem->nom);
		}
	}
	return $laListe;
}


function getNomCategorie($idCat) {
	global $bd,$table_categorie,$all_fields_categorie;
	$requete = "SELECT ".$all_fields_categorie." FROM ".$table_categorie." WHERE `id`='".$idCat."' ORDER BY `nom` ASC";
	trace("getNomCategorie(".$idCat."): requete = ".$requete);
	$resCat = $bd->execRequete($requete);
	if (!$resCat) {
		$categorie = "inconnu";
	} else {
		$uneCat = $bd->objetSuivant($resCat);
		$categorie = $uneCat->nom;
	}
	return $categorie;
}

function getListeCategories($idFam) {
	global $bd,$table_categorie,$all_fields_categorie;
	$laListe = array();
	$requete = "SELECT ".$all_fields_categorie." FROM ".$table_categorie." WHERE `idFamille`='".$idFam."'";
	trace("getListeCategories(".$idFam."): requete = ".$requete);
	$resListe = $bd->execRequete($requete);
	if ($resListe) {
		while ($unElem = $bd->objetSuivant($resListe)) {
			$laListe[$unElem->id] = $unElem->nom;
			trace("=> indice ".$unElem->id." = ".$unElem->nom);
		}
	}
	return $laListe;
}

function getToutesLesCategories() {
	global $bd,$table_categorie,$all_fields_categorie;
	$laListe = array();
	$requete = "SELECT ".$all_fields_categorie." FROM ".$table_categorie." ORDER BY `idFamille` ASC";
	trace("getListeCategories(): requete = ".$requete);
	$resListe = $bd->execRequete($requete);
	if ($resListe) {
		while ($unElem = $bd->objetSuivant($resListe)) {
			$laListe[$unElem->id] = $unElem->nom;
			trace("=> indice ".$unElem->id." = ".$unElem->nom);
		}
	}
	return $laListe;
}


?>
