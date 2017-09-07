<?php


require_once('TemplateEngine.php');
require_once('UtilGestionTable.php');

$home = "../";
$date = date( "Ymd", getlastmod());

//===== champs deb =====
$elem = "signet";
$reqCreate = "CREATE TABLE IF NOT EXISTS ".$table_signet." (
	id smallint(2) NOT NULL default '0',
	texte varchar(120) NOT NULL default '',
	ordre smallint(2) NOT NULL default '0',
	idCateg smallint(2) NOT NULL default '0',
	href varchar(200) NOT NULL default '',
	target varchar(20) NOT NULL default '',
	icone varchar(120) NOT NULL default '',
	PRIMARY KEY (id)
	)";
$texte = isset($_POST['texte']) ? $bd->quote($_POST['texte']) : "";
$ordre = isset($_POST['ordre']) ? $bd->quote($_POST['ordre']) : "";
$idCateg = isset($_POST['idCateg']) ? $bd->quote($_POST['idCateg']) : "";
$href = isset($_POST['href']) ? $bd->quote($_POST['href']) : "";
$target = isset($_POST['target']) ? $bd->quote($_POST['target']) : "";
$icone = isset($_POST['icone']) ? $bd->quote($_POST['icone']) : "default.ico";
//===== champs fin =====

$un_elem = isset($un_elem) ? $un_elem : "un ".$elem;
$le_elem = isset($le_elem) ? $le_elem : "le ".$elem;
$du_elem = isset($du_elem) ? $du_elem : "du ".$elem;
$des_elems = isset($des_elems) ? $des_elems : "des ".$elem."s";


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
			$requete = "SELECT ".$all_fields_signet." FROM ".$table_signet." WHERE `id`='".$num."'";
			trace("Recherche ".$du_elem." no ".$num.", requete=".$requete);
			$resultat = $bd->execRequete($requete);
			if (!$resultat) {
				$action = FORM_LIST;
			} else {
				$unElem = $bd->objetSuivant($resultat);
//===== champs deb =====
				$texte = $unElem->texte;
				$idCateg = $unElem->idCateg;
				$ordre = $unElem->ordre;
				$href = $unElem->href;
				$target = $unElem->target;
				$icone = $unElem->icone;
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
	$laCat = getNomCategorie($idCateg);
	$lesCat = getToutesLesCategories();
?>
<FORM METHOD="POST" ACTION="<?=$_SERVER['PHP_SELF']?>" NAME="form_<?=$elem?>" onSubmit="return verifSelection()">
<INPUT TYPE="HIDDEN" NAME="action" VALUE="<?=$champAction?>" SIZE="0" MAXLENGTH="0">
<INPUT TYPE="HIDDEN" NAME="num" VALUE="<?=$num?>">
<TABLE BORDER="0" ALIGN="CENTER">
  <TR>
    <TD><FONT FACE="Verdana" SIZE="2">Categorie</FONT></TD>
<?
	switch ($verbe) {
		case AJOUT_VERB:
		case MODIF_VERB:
?>
    <TD><SELECT NAME="idCateg">
    <OPTION VALUE=''>- Select -</OPTION>
<?
			getLesOptions($lesCat, $idCateg);
?>
    </SELECT></TD>
<?			
			break;
		case SUPPR_VERB:
?>
    <TD><INPUT NAME="nom" TYPE="TEXT" VALUE="<?=$laCat?>" SIZE="50" <?=$readonly?>></TD>
<?
			break;
		default:
			echo "<P>Opération inconnue</P>\n";
	}
?>
  </TR><TR>
    <TD><FONT FACE="Verdana" SIZE="2">Texte</FONT></TD>
    <TD><INPUT NAME="texte" TYPE="TEXT" VALUE="<?=$texte?>" SIZE="50" MAXLENGTH="120" <?=$readonly?>></TD>
  </TR><TR>
    <TD><FONT FACE="Verdana" SIZE="2">Ordre</FONT></TD>
    <TD><INPUT NAME="ordre" TYPE="TEXT" VALUE="<?=$ordre?>" SIZE="5" <?=$readonly?>></TD>
  </TR><TR>
    <TD><FONT FACE="Verdana" SIZE="2">Lien internet</FONT></TD>
    <TD><INPUT NAME="href TYPE="TEXT" VALUE="<?=$href?>" SIZE="50" MAXLENGTH="200" <?=$readonly?>></TD>
  </TR><TR>
    <TD><FONT FACE="Verdana" SIZE="2">Icone</FONT></TD>
    <TD><INPUT NAME="icone" TYPE="TEXT" VALUE="<?=$icone?>" SIZE="50" MAXLENGTH="120" <?=$readonly?>></TD>
  </TR><TR>
    <TD><FONT FACE="Verdana" SIZE="2">Nom de l'onglet</FONT></TD>
    <TD><INPUT NAME="target" TYPE="TEXT" VALUE="<?=$target?>" SIZE="20" <?=$readonly?>></TD>
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
		$reqDelete = "DELETE FROM ".$table_signet." WHERE `id`='".$num."'";
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
		$reqUpdate = "UPDATE ".$table_signet." SET texte='".$texte."',ordre='".$ordre."',idCateg='".$idCateg."',href='".$href."',target='".$target."',icone='".$icone."' WHERE `id`='".$num."'";
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
	$reqInsert = "INSERT INTO ".$table_signet." (".$all_fields_signet.") VALUES ('".getNextFreeId($table_categorie)."','".$texte."','".$ordre."','".$idCateg."','".$href."','".$target."','".$icone."')";
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
	<TH>Catégorie</TH>
	<TH>Texte</TH>
	<TH>Ordre</TH>
	<TH>Lien internet</TH>
	<TH>Nom de l'onglet</TH>
	<TH>Icone</TH>
</TR>
<?php
	$nbElem = 0;
//===== champs deb =====
	$reqListe = "SELECT ".$all_fields_signet." FROM ".$table_signet." ORDER BY `idCateg` ASC, `ordre` ASC";
	$resListe = $bd->execRequete($reqListe);
//===== champs fin =====
	while ($unSignet = $bd->objetSuivant($resListe)) {
		$nbElem++;
		$options_lig = "";
		if ($nbElem % 2 == 0) $options_lig = ' BGCOLOR="Silver" ';
		echo "<TR" . $options_lig . ">\n";
		echo '<TD><INPUT TYPE="CHECKBOX" NAME="Select[]" VALUE="' . $unSignet->id . '"></TD>'."\n";
//===== champs deb =====
		echo "<TD>" . $unSignet->id . "</TD>\n";
		echo "<TD>" . getNomCategorie($unSignet->idCateg) . "</TD>\n";
		echo "<TD>" . $unSignet->texte . "</TD>\n";
		echo "<TD>" . $unSignet->ordre . "</TD>\n";
		echo "<TD>" . $unSignet->href . "</TD>\n";
		echo "<TD>" . $unSignet->target . "</TD>\n";
		echo "<TD>" . $unSignet->icone . "</TD>\n";
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
