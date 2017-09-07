<?php

//session_start();

include("clas_consql.php");
$myscript = "logiciels.php";
$mybase = "remi_lapointe";
$tab_evaluation = "logiciels_evaluation";
$tab_genre = "logiciels_genre";
$tab_installes_sur = "logiciels_installes_sur";
$tab_logiciel = "logiciels_liste";
$tab_provenance = "logiciels_provenance";
$tab_type_provenance = "logiciels_type_provenance";

$siteWeb = $_SERVER['DOCUMENT_ROOT'];
$imgAbsDir = $siteWeb . "/img/";
$imgDir = "../img";
$jvsDir = $siteWeb . "/javascript/";

$page = "";
$page_l_evaluation = "liste_evaluation";
$page_l_genre = "liste_genre";
$page_l_type_provenance = "liste_type_provenance";
$page_l_provenance = "liste_provenance";
$page_l_logiciel = "liste_logiciel";
if ( isset($_REQUEST['page']) ) {
  $page = $_REQUEST['page'];
}
$id = "";
if ( isset($_REQUEST['id']) ) {
  $id = $_REQUEST['id'];
}
$debug = "";
if ( isset($_REQUEST['debug']) ) {
  $debug = $_REQUEST['debug'];
}
if ( isset($_REQUEST['ncol_trombi']) ) {
  $ncol_trombi = $_REQUEST['ncol_trombi'];
}

// initialiser une connexion au serveur SQL, elle va servir tout du long.
$lsql = new conSQL( $_SERVER['SERVER_NAME'], $mybase, $debug );


$resu = NULL;
if ( $page == $page_l_evaluation ) {
  $nbl = $lsql->selectSQL( "SELECT * FROM $tab_evaluation ORDER BY `Note` ASC", $resu );
  $title = "Liste des &eacute;valuations des logiciels";
  $debut = "<ul>\n";

} elseif ( $page == $page_l_genre ) {
  $nbl = $lsql->selectSQL( "SELECT * FROM $tab_genre ORDER BY `nom` ASC", $resu );
  $title = "Liste des genres de logiciels";
  $debut = "<ul>\n";

} elseif ( $page == $page_l_type_provenance ) {
  $nbl = $lsql->selectSQL( "SELECT * FROM $tab_type_provenance ORDER BY `nom` ASC", $resu );
  $title = "Liste des types de provenance des logiciels";
  $debut = "<ul>\n";

} elseif ( $page == $page_l_provenance ) {
  $nbl = $lsql->selectSQL( "SELECT * FROM $tab_provenance ORDER BY `nom` ASC", $resu );
  $title = "Liste des provenances des logiciels";
  $debut = "<ul>\n";

} elseif ( $page == $page_l_logiciel ) {
  $nbl = $lsql->selectSQL( "SELECT * FROM $tab_logiciel", $resu );
  $title = "Liste des logiciels";
  $debut = "<ul>\n";

}

if ( $debug > 1 ) print "document_root=$siteWeb ; nombre de lignes : $nbl<br>\n";

// afficher les informations d'entete html
print "
<html><head>\n
<title>$title</title>\n
<script type=\"text/javascript\" language=\"JavaScript\" src=\"$jvsDir/preload.js\"></script>\n
</head>\n
<body bgcolor=\"#c8cf8b\">\n
<br><center><font size=+2 color=\"black\">$title</font></center>\n
<br><a href=\"../accueil.html\">Accueil</a><br>\n
\n
$debut";


for ( $i = 1 ; $i <= $nbl ; $i++ ) {
  $elem = mysql_fetch_row($resu);
  $id = 0;
  $index = $elem[$id++];
  $nom = $elem[$id++];

  if ( $page == $page_l_evaluation ) {
    $note = $elem[$id++];
    print "  <li>$note : $nom\n";

  } elseif ( $page == $page_l_genre ) {
    print "  <li>$nom\n";

  } elseif ( $page == $page_l_type_provenance ) {
    print "  <li>$nom\n";

  } elseif ( $page == $page_l_provenance ) {
    print "  <li>$nom\n";

  } elseif ( $page == $page_l_logiciel ) {
  	$version = $elem[$id++];
  	$genre_id = $elem[$id++];
  	$contenu = $elem[$id++];
  	$provenance = $elem[$id++];
  	$date = $elem[$id++];
  	$site_web = $elem[$id++];
  	$telechargement = $elem[$id++];
  	$sur_monsite = $elem[$id++];
  	$installe_id = $elem[$id++];
  	$install_id = $elem[$id++];
  	$evaluation = $elem[$id++];
  	$eval_id = $elem[$id++];
  	
    print "  <li>$nom\n";
  }

}


if ( $page == $page_l_evaluation ) {
  print "</ul>\n";

} elseif ( $page == $page_l_genre ) {
  print "</ul>\n";

} elseif ( $page == $page_l_type_provenance ) {
  print "</ul>\n";

} elseif ( $page == $page_l_provenance ) {
  print "</ul>\n";

} elseif ( $page == $page_l_logiciel ) {
  print "</ul>\n";

}

print "<br>\n";

print "</body></html>\n";

$lsql->closeSQL();

?>
