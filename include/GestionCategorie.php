<?php


require_once('TemplateEngine.php');
require_once('UtilGestionTable.php');

$home = "../";
$date = date( "Ymd", getlastmod());

//===== champs deb =====
$elem = "categorie";
$reqCreate = "CREATE TABLE IF NOT EXISTS ".$table_categorie." (
	id smallint(2) NOT NULL default '0',
	nom varchar(50) NOT NULL default '',
	idFamille smallint(2) NOT NULL default '0',
	PRIMARY KEY (id)
	)";
$nom = isset($_POST['nom']) ? $bd->quote($_POST['nom']) : "";
$idFamille = isset($_POST['idFamille']) ? $bd->quote($_POST['idFamille']) : "";
//===== champs fin =====

$un_elem = isset($un_elem) ? $un_elem : "une ".$elem." de signet";
$le_elem = isset($le_elem) ? $le_elem : "la ".$elem." de signet";
$du_elem = isset($du_elem) ? $du_elem : "de la ".$elem." de signet";
$des_elems = isset($des_elems) ? $des_elems : "des ".$elem."s de signet";


$titre = "Gestion ".$des_elems;
$tpl = new TemplateEngine($home.'template_remi.html');
TemplateEngine::Output( $tpl->GetHTMLCode('entete') );

echo "<P><A HREF='gestion_site.php'>Retour menu administration</A></P>\n";

$resCreate = $bd->execRequete($reqCreate);
if (!$resCreate) {
?>
<P><B><FONT COLOR='RED'>Erreur de création de la base <?=$table_categorie?></FONT></B></P>
<P><B><FONT COLOR='RED'>Requête : <?=$reqCreate?></FONT></B></P>
<P><B><FONT COLOR='RED'>Erreur : <?=$bd->messageSGBD()?></FONT></B></P>
<?
	exit (1);
}

traceVariables($debug);

if ($valid == "Annuler") $action = FORM_LIST;
trace("mon action = ".$action);

$champAction = AJOUT_BASE;
$readonly = "";
if ($action == MODIF_FORM || $action == SUPPR_FORM) {
	// modification ou suppression d'un element, affichage de l'element
	$verbe = MODIF_VERB;
	$champAction = MODIF_BASE;
	if ($action == SUPPR_FORM) {
		$verbe = SUPPR_VERB;
		$champAction = SUPPR_BASE;
		$readonly = "readonly";
	}
	if (! isset($_REQUEST['Select'])) {
		echo "<P><FONT COLOR='RED'>Vous devez sélectionner un seul élément à ".$verbe."</FONT></P>\n";
		$action = FORM_LIST;
	} else {
		if (count($_REQUEST['Select']) != 1) {
			echo "<P><FONT COLOR='RED'>Vous devez sélectionner un seul élément à ".$verbe."</FONT></P>\n";
			$action = FORM_LIST;
		} else {
			$num = $_REQUEST['Select'][0];
			$requete = "SELECT ".$all_fields_categorie." FROM ".$table_categorie." WHERE `id`='".$num."'";
			trace("Recherche ".$du_elem." no ".$num.", requete=".$requete);
			$resultat = $bd->execRequete($requete);
			if (!$resultat) {
				$action = FORM_LIST;
			} else {
				$unElem = $bd->objetSuivant($resultat);
//===== champs deb =====
				$nom = $unElem->nom;
				$idFamille = $unElem->idFamille;
//===== champs fin =====
				$action = AJOUT_FORM;
			}
		}
	}
}

if ($action == AJOUT_FORM) {
	// ajout d'un element
	trace($verbe." ".$un_elem);
	switch ($verbe) {
		case AJOUT_VERB:
			echo "<P>Veuillez saisir les champs suivants</P>\n";
			break;
		case MODIF_VERB:
			echo "<P>Veuillez modifier les champs suivants</P>\n";
			break;
		case SUPPR_VERB:
			echo "<P>Veuillez confirmer la suppression ".$du_elem." suivant</P>\n";
			break;
		default:
			echo "<P>Opération inconnue</P>\n";
	}
	$laFam = getNomFamille($idFamille);
	$lesFam = getListeFamilles();
?>
<FORM METHOD="POST" ACTION="<?=$_SERVER['PHP_SELF']?>" NAME="form_<?=$elem?>" onSubmit="return verifSelection()">
<INPUT TYPE="HIDDEN" NAME="action" VALUE="<?=$champAction?>" SIZE="0" MAXLENGTH="0">
<INPUT TYPE="HIDDEN" NAME="num" VALUE="<?=$num?>">
<TABLE BORDER="0" ALIGN="CENTER">
  <TR>
    <TD><FONT FACE="Verdana" SIZE="2">Famille</FONT></TD>
<?
	switch ($verbe) {
		case AJOUT_VERB:
		case MODIF_VERB:
?>
    <TD><SELECT NAME="idFamille">
    <OPTION VALUE=''>- Select -</OPTION>
<?
			getLesOptions($lesFam, $idFamille);
?>
    </SELECT></TD>
<?			
			break;
		case SUPPR_VERB:
?>
    <TD><INPUT NAME="nom" TYPE="TEXT" VALUE="<?=$laFam?>" SIZE="50" <?=$readonly?>></TD>
<?
			break;
		default:
			echo "<P>Opération inconnue</P>\n";
	}
?>
  </TR><TR>
    <TD><FONT FACE="Verdana" SIZE="2">Catégorie</FONT></TD>
    <TD><INPUT NAME="nom" TYPE="TEXT" VALUE="<?=$nom?>" SIZE="50" <?=$readonly?>></TD>
  </TR>
</TABLE>
<CENTER>
<INPUT TYPE="SUBMIT" NAME="valid" VALUE="<?=$verbe?>">
<INPUT TYPE="SUBMIT" NAME="valid" VALUE="Annuler">
<INPUT TYPE="RESET" NAME="RESET" VALUE="Effacer les données">
</CENTER>
<P><INPUT TYPE='CHECKBOX' NAME='debug' VALUE='1' <?=$debug?"CHECKED":""?>>Debug</P>
</FORM>

<?php
}

if ($action == SUPPR_BASE) {
	// suppression d'un element de la base
	if (! isset($num)) {
		echo "<P><FONT COLOR='RED'>Vous devez sélectionner un seul élément à ".$verbe."</FONT></P>\n";
		$action = FORM_LIST;
	} else {
		$reqDelete = "DELETE FROM ".$table_categorie." WHERE `id`='".$num."'";
		trace("Suppression ".$du_elem." no ".$num );
		trace("Requete = ".$reqDelete);
		$resDelete = $bd->execRequete($reqDelete);
	}
	$action = FORM_LIST;
}

if ($action == MODIF_BASE) {
	// modification d'un element dans la base
	if (! isset($num)) {
		echo "<P><FONT COLOR='RED'>Vous devez sélectionner un seul élément à ".$verbe."</FONT></P>\n";
		$action = FORM_LIST;
	} else {
//===== champs deb =====
		$reqUpdate = "UPDATE ".$table_categorie." SET nom='".$nom."',idFamille='".$idFamille."' WHERE `id`='".$num."'";
//===== champs fin =====
		trace("Modification ".$du_elem." no ".$num);
		trace("Requete = ".$reqUpdate);
		$resUpdate = $bd->execRequete($reqUpdate);
	}
	$action = FORM_LIST;
}

if ($action == AJOUT_BASE) {
	// ajout d'un element dans la base
//===== champs deb =====
	//$all_fields_famille = "id,nom";
	$reqInsert = "INSERT INTO ".$table_categorie." (".$all_fields_categorie.") VALUES ('".getNextFreeId($table_categorie)."','".$nom."','".$idFamille."')";
//===== champs fin =====
	trace("Requete = ".$reqInsert);
	$resultat = $bd->execRequete($reqInsert);
	
	$action = FORM_LIST;
}

if ($action == FORM_LIST) {
	// affichage de la liste des elements de la table
	echo "Liste ".$des_elems." de la table<BR>\n";

?>
<FORM METHOD="POST" ACTION="<?=$_SERVER['PHP_SELF']?>" NAME="form_liste">
<INPUT TYPE="HIDDEN" NAME="action" VALUE="liste" SIZE="0" MAXLENGTH="0">
<CENTER>
<INPUT TYPE='SUBMIT' NAME='action' VALUE='<?=SUPPR_FORM?>'>
<INPUT TYPE='SUBMIT' NAME='action' VALUE='<?=MODIF_FORM?>'>
<INPUT TYPE='SUBMIT' NAME='action' VALUE='<?=AJOUT_FORM?>'>
<TABLE  BORDER="1" >
<TR>
	<TH>Select</TH>
	<TH>Id</TH>
	<TH>Famille</TH>
	<TH>Catégorie</TH>
</TR>
<?php
	$nbElem = 0;
//===== champs deb =====
	$reqListe = "SELECT ".$all_fields_categorie." FROM ".$table_categorie." ORDER BY `nom` ASC";
	$resListe = $bd->execRequete($reqListe);
//===== champs fin =====
	while ($uneCategorie = $bd->objetSuivant($resListe)) {
		$nbElem++;
		$options_lig = "";
		if ($nbElem % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		echo '<TD><INPUT TYPE="CHECKBOX" NAME="Select[]" VALUE="' . $uneCategorie->id . '"></TD>'."\n";
//===== champs deb =====
		echo "<TD>" . $uneCategorie->id . "</TD>\n";
		echo "<TD>" . getNomFamille($uneCategorie->idFamille) . "</TD>\n";
		echo "<TD>" . $uneCategorie->nom . "</TD>\n";
//===== champs fin =====
		echo "<TR>\n";
	}
	$msg = "";
	if ($nbElem == 0) {
		$msg = "Auc".$un_elem." dans la table<BR />\n";
	}
?>

</TABLE>
<?=$msg?>
<BR />
<? if ($nbElem > 0) { ?>
<INPUT TYPE='SUBMIT' NAME='action' VALUE='<?=SUPPR_FORM?>'>
<INPUT TYPE='SUBMIT' NAME='action' VALUE='<?=MODIF_FORM?>'>
<? } ?>
<INPUT TYPE='SUBMIT' NAME='action' VALUE='<?=AJOUT_FORM?>'>
</CENTER>
<P><INPUT TYPE='CHECKBOX' NAME='debug' VALUE='1' <?=$debug?"CHECKED":""?>>Debug</P>
</FORM>

<?php

}

?>
</BODY>
</HTML>
