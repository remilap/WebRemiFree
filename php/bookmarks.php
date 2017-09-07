<?php

//session_start();

include("clas_consql.php");
$myscript = "bookmarks.php";
$mybase = "remi_lapointe";
$tab_chapter = "bookmarks_chapter";
$tab_category = "bookmarks_category";
$tab_bookmarks = "bookmarks_list";

$siteWeb = $_SERVER['DOCUMENT_ROOT'];
$imgAbsDir = $siteWeb . "/img/";
$imgDir = "../img";
$jvsDir = $siteWeb . "/javascript/";

$page_l_chapter = "liste_chapter";
$page_l_categorie = "liste_categorie";
$page_l_bookmark  = "liste_bookmark";
$page_l_bookmark2 = "liste_bookmark2";
$page_e_categorie = "edit_categorie";
$page_e_bookmark  = "edit_bookmark";
$page = $page_l_chapter;
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

// initialiser une connexion au serveur SQL, elle va servir tout du long.
$lsql = new conSQL( $_SERVER['SERVER_NAME'], $mybase, $debug );


// recuperer la liste des chapitres
$resu = NULL;
$nbChapter = $lsql->selectSQL( "SELECT * FROM $tab_chapter ORDER BY `index` ASC", $resu );
for ( $i = 0 ; $i < $nbChapter ; $i++ ) {
  $elem = mysql_fetch_row($resu);
  $id = 0;
  $index = $elem[$id++];      // index
  $nom = $elem[$id++];        // nom
  $chapterNam[$index] = $nom;
  $chapterNbr[$index] = 0;
  $chapterMaxLink[$index] = 0;
  if ( $debug > 0 ) print "index=$index, nom=$nom<br>\n";
}

// recuperer la liste des categories
$resu = NULL;
$nbCateg = $lsql->selectSQL( "SELECT * FROM $tab_category ORDER BY `index` ASC", $resu );
for ( $i = 0 ; $i < $nbCateg ; $i++ ) {
  $elem = mysql_fetch_row($resu);
  $id = 0;
  $index = $elem[$id++];      // index
  $nom = $elem[$id++];        // nom
  $categNam[$index] = $nom;
  $categNbr[$index] = 0;
  $chapterIdx = $elem[$id++];   // chapterIdx
  $categchapterIdx[$index] = $chapterIdx;
  $chapterNbr[$chapterIdx]++;
  if ( $debug > 0 ) print "index=$index, nom=$nom, chapterNbr[$chapterIdx]={$chapterNbr[$chapterIdx]}<br>\n";
}

$resu = NULL;
$nbLink = $lsql->selectSQL( "SELECT * FROM $tab_bookmarks ORDER BY `index` ASC", $resu );
for ( $i = 0 ; $i < $nbLink ; $i++ ) {
  $elem = mysql_fetch_row($resu);
  $id = 0;
  $index = $elem[$id++];              // index
  $linkTit[$index] = $elem[$id++];    // title
  $linkURL[$index] = $elem[$id++];    // URL
  $categIdx = $elem[$id++];           // categIdx
  $linkCategIdx[$index] = $categIdx;
  $categNbr[$categIdx]++;
  $chapterIdx = $categchapterIdx[$categIdx];
  if ( $categNbr[$categIdx] > $chapterMaxLink[$chapterIdx] ) {
    $chapterMaxLink[$chapterIdx] = $categNbr[$categIdx];
  }
  $linkIco[$index] = $elem[$id++];    // iconeName
  $linkTab[$index] = $elem[$id++];    // tabName
  if ( $debug > 0 ) print "index=$index, title={$linkTit[$index]}, categIdx=$categIdx, categNam={$categNam[$categIdx]}, nb={$categNbr[$categIdx]}, maxLinkInchapter={$chapterMaxLink[$chapterIdx]}<br>\n";
}

if ( $page == $page_l_chapter ) {
  $title = "Liste des chapitres des bookmarks";
  $debut = "\n";

} elseif ( $page == $page_l_categorie ) {
  $title = "Liste des bookmarks";
  $debut = "\n";

} elseif ( $page == $page_l_bookmark ) {
  $title = "Liste des bookmarks";
  $debut = "\n";

} elseif ( $page == $page_l_bookmark2 ) {
  $title = "Liste des bookmarks";
  $debut = "\n";

} elseif ( $page == $page_e_categorie ) {
  $title = "Edition des cat&eacute;gories des bookmarks";
  $debut = "\n";

} elseif ( $page == $page_e_bookmark ) {
  $title = "Edition des bookmarks";
  $debut = "\n";

}

if ( $debug > 1 ) print "document_root=$siteWeb ; nombre de lignes : $nbl<br>\n";

// afficher les informations d'entete html
print "
<html>
  <head>
  <title>$title</title>
  <script type=\"text/javascript\" src=\"../javascript/cookies.js\"></script>
</head>
<body bgcolor=\"#c8cf8b\">
<br><center><font size=+2 color=\"black\">$title</font></center>
<br><a href=\"../accueil.html\">Accueil</a><br>

$debut";


if ( $page == $page_l_chapter ) {
  print "<ul>\n";
  foreach ( $chapterNam as $gKey=>$gNam ) {
    echo "chapter[$gKey]=$gNam, nb categ={$chapterNbr[$gKey]}, max={$chapterMaxLink[$gKey]}<br>\n";
  }

} elseif ( $page == $page_l_categorie ) {
  print "<ul>\n";
  foreach ( $chapterNam as $gKey=>$gNam ) {
    echo "chapter[$gKey]=$gNam, nb categ={$chapterNbr[$gKey]}, max={$chapterMaxLink[$gKey]}<br>\n";
    foreach ( $categNam as $cKey=>$cNam ) {
      if ( $gKey == $categchapterIdx[$cKey] ) {
        print "  <li>$cKey : $cNam ($gNam), nb_link={$categNbr[$cKey]}</li>";
      }
    }
  }

} elseif ( $page == $page_l_bookmark ) {
  $index = 0;
  print "<ul>\n";
  for ( $c = 1 ; $c <= $nbCateg ; $c++ ) {
    $nom = $categNam[$c];
    print "<li><a name=\"$nom\" OnClik=\"ToggleValueInCookie('CategorieBookmark', '$nom')\">";
    $endb = "";
    if ( $categAff[$c] ) {
      print "<b>";
      $endb = "</b>";
    }
    print "$nom</a>$endb";
//print"\nCategorieBookmark=$CategorieBookmark<br />\n";
    if ( strpos($CategorieBookmark, "${nom},") > 0 ) {
      print "</li>";
      break;
    }
    $aff_ul = "<ul>\n";
//print "nbLink=$nbLink<br />\n";
    for ( $l = 1 ; $l <= $nbLink ; $l++ ) {
//print "l=$l<br />\n";
//print "totototo c=$c ; categ={$link['categoryIndex'][$l]}<br />\n";
      if ( $linkCategIdx[$l] == $c /*&& $categ['display'][$c]*/ ) {
        print $aff_ul;
        $aff_ul = "";
        print "  <li><img src=\"../icones/{$linkIco[$l]}\" border=\"0\" height=\"20\" /> <a target=\"{$linkTab[$l]}\" href=\"{$linkURL[$l]}\">{$linkTit[$l]}</a>\n";
//print ", categorie={$link['categName'][$l]} (index={$categoryIndex})";
      }
      print "</li>\n";
    }
    if ( $aff_ul == "" ) {
      print "</ul>\n";
    }
  }

} elseif ( $page == $page_l_bookmark2 ) {
  foreach ( $chapterNam as $gKey=>$gNam ) {
    print "<p>$gNam\n";
    if ( $debug > 1 ) echo "chapter[$gKey]=$gNam, nb categ={$chapterNbr[$gKey]}, max={$chapterMaxLink[$gKey]}<br>\n";
    print "<table border=\"1\" cellspacing=\"1\" cellpadding=\"5\">\n";
    foreach ( $categNam as $cKey=>$cNam ) {
      if ( $gKey == $categchapterIdx[$cKey] ) {
        print "  <li>$cKey : $cNam ($gNam), nb_link={$categNbr[$cKey]}</li>";
      }
    }
  }
    foreach ( $categTab[$chapterNam] as $categKey=>$categNam ) {
    for ( $c = 1 ; $c <= $nbCateg ; $c++ ) {
      $categNam = $categ['nom'][$c];
      $chapterVal = $categ['chapter'][$c];
      if ( $chapterVal == $chapterNam ) {
        print "  <li>$c : $nom ($chapterVal), nb_link={$categ['nb'][$c]}</li>";
      }
    }
  }

} elseif ( $page == $page_e_categorie ) {
  print "<br>Cat&eacute;gorie :\n";

}


if ( $page == $page_l_evaluation ) {
  print "</ul>\n";

} elseif ( $page == $page_l_chapter ) {
  print "</ul>\n";

}

print "<br>\n";

print "</body></html>\n";

$lsql->closeSQL();

?>
