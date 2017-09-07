<?php

session_start();

//include("inc.vars.php");
include("clas_consql.php");
$myscript = "illusion_opt.php";
$mybase = "remi_lapointe";
$tabl_illu = "illusion_optique";
$imgDir = "../img";
$jvsDir = "../javascript";
$title = "Illusions d'optique";

$id = "0";
if ( isset($_REQUEST['id']) ) {
  $id = $_REQUEST['id'];
}
$debug = "0";
if ( isset($_REQUEST['debug']) ) {
  $debug = $_REQUEST['debug'];
}
$reponse = "non";
if ( isset($_REQUEST['reponse']) ) {
  $reponse = $_REQUEST['reponse'];
}

// initialiser une connexion au serveur SQL, elle va servir tout du long.
$lsql = new conSQL( $_SERVER['SERVER_NAME'], $mybase, $debug );

$id_prec = $id - 1;
$id_suiv = $id + 1;
$resu = NULL;
$id_max = $lsql->selectSQL( "select * from $tabl_illu", $resu );


// afficher les informations d'entete html
print "
<html><head>\n
<title>$title</title>\n
<script type=\"text/javascript\" language=\"JavaScript\" src=\"$jvsDir/preload.js\"></script>\n
</head>\n
<body onload=\"MM_preloadImages('$imgDir/bouton_retour_B.jpg', '$imgDir/bouton_suivant_M.jpg', '$imgDir/bouton_precedent_R.jpg')\" \n
bgcolor=\"#000000\" text=\"#FFFFFF\" link=\"#EEEEEE\" vlink=\"#777777\" alink=\"#555555\" >\n
<br><center><font size=+2 color=\"orange\">$title</font></center>\n
<br><a href=\"../accueil.html\">Accueil</a>\n";

if ( $id > 0 ) {
  print "<center>\n";
  $resu = NULL;
  $nbl = $lsql->selectSQL( "select * from $tabl_illu where id='$id'", $resu );
  if ( $debug != "0" ) print "nombre de lignes : $nbl";
  for ( $i = 1 ; $i <= $nbl ; $i++ ) {
    $elem = mysql_fetch_row($resu);
    if ( $debug != "0" ) print "row $i : $elem";
    $id = $elem[0];
    $titre = $elem[1];
    $images = explode( '!', $elem[2] );
    $solutions = explode( '!', $elem[3] );
    $texte = $elem[4];
  }

  $n = 0;
  foreach ( $solutions as $solution ) {
    $n++;
    $sol[$n] = $solution;
  }
  $n = 0;
  foreach ( $images as $image ) {
    $n++;
    print "<img src=\"$imgDir/$image\" alt=\"illusion optique\">\n";
    if ( $reponse != "non" ) {
      //list( $nom, $ext ) = explode( '.', $image );
      //$img_rep = $nom."B.".$ext;
      print " &nbsp; <img src=\"$imgDir/$sol[$n]\" alt=\"illusion reponse\">\n";
    } else {
      if ( isset($sol[$n]) && $sol[$n] != "" ) {
        print " &nbsp; <a href=\"$myscript?id=$id&reponse=oui\">R&eacute;ponse</a>\n";
      }
    }
    print "<br><br>\n";
  }
  print "$texte<br>\n
  
  <table align=\"center\" cellspacing=\"10\" width=\"1%\">\n
    <tbody><tr>\n";
  if ( $id_prec >= 1 ) {
  print "    <td height=\"77\" width=\"48%\"><a href=\"$myscript?id=$id_prec\" onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('Image6','','$imgDir/bouton_precedent_R.jpg',1)\"><img src=\"$imgDir/bouton_precedent_W.jpg\" name=\"Image6\" border=\"0\" height=\"70\" width=\"78\"></a></td> \n";
  }
  print "    <td height=\"77\" width=\"42%\"><a href=\"$myscript?id=0\" onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('Image4','','$imgDir/bouton_retour_B.jpg',1)\"><img src=\"$imgDir/bouton_retour_W.jpg\" name=\"Image4\" border=\"0\" height=\"77\" width=\"71\"></a></td> \n";
  if ( $id_suiv <= $id_max ) {
  print "    <td height=\"77\" width=\"10%\"><a href=\"$myscript?id=$id_suiv\" onmouseout=\"MM_swapImgRestore()\" onmouseover=\"MM_swapImage('Image5','','$imgDir/bouton_suivant_M.jpg',1)\"><img src=\"$imgDir/bouton_suivant_W.jpg\" name=\"Image5\" border=\"0\" height=\"75\" width=\"72\"></a></td> \n";
  }
  print "  </tr> \n
  </tbody></table> \n";
} else {
  print "<br><a href=\"http://ophtasurf.free.fr/illusions.htm\" target=\"autres_illusions\">Autres illusions d'optique</a>\n
  <br><a href=\"http://www.mcescher.com/Gallery/gallery-recogn.htm\" target=\"mc_escher\">Illusions artistiques de M.C. Escher</a>\n
  <center>\n";
  // gestion du sommaire
  $resu = NULL;
  $nbl = $lsql->selectSQL( "select id,titre from $tabl_illu", $resu );
  for ( $i = 1 ; $i <= $nbl ; $i++ ) {
    $elem = mysql_fetch_row($resu);
    if ( $debug != "0" ) print "row $i : $elem";
    $id = $elem[0];
    $titre = $elem[1];
    print "<br>$i <a href=\"$myscript?id=$id\">$titre</a> \n";
  }
  
}

print "  </center>\n
</body></html>\n";

$lsql->closeSQL();

?>
