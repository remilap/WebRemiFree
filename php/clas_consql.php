<?php
//--------------------------------------------------------------------------
// Debut classe conSQL 
// Gestion du lien avec mySQL
//--------------------------------------------------------------------------
class conSQL
{
				
  var $sqlid = 0;          	// id SQL utilis� partout
 
//--------------------------------------------------------------------------
// function conSQL
// �tablissement d'une connexion SQL.
// - $psrv : nom du serveur sur lequel est ex�cut� le PHP 
//           (par ex d�sign� par la variable d'environnement $SERVER_NAME)
// - $pdb  : nom de la base SQL. NB: chez free, une seule base par utilisateur
//           donc on modifie la base donn�e par l'appelant.
//--------------------------------------------------------------------------
function conSQL($psrv, $pdb, $debug)
{  
  if ($_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'])
  {
    // si le serveur est la machine locale
    if ( $debug == "1" ) print "base locale<br>\n";
    $srv  = "localhost";
    $db   = $pdb;		
    $user = "root";
    $pw   = "";
  }
  else
  {
    // base de donn�es sur sql.free.fr (une seule base sur ce serveur, nom=login)
    $srv  = "sql.free.fr";
    if ( $debug == "1" ) print "base Free: $srv <br>\n";
    $db   = $pdb;		
    $user = "remi.lapointe";
    $pw   = "rem000";
  }
    
  // 128=CLIENT_LOCAL_FILES
  $this->sqlid =  mysql_pconnect($srv , $user, $pw, 128);
  if ( ! $this->sqlid )
    die( "Impossible d'�tablir une connexion avec MySQL ($srv, $user, ".$_SERVER['SERVER_ADDR'].")" );
  mysql_select_db( $db, $this->sqlid )
    or die ( "Impossible d'ouvrir la base de donn�es: ".mysql_error() );
}
 
//--------------------------------------------------------------------------
// function selectSQL
// ex�cution d'une requete de s�lection, retour du r�sultat et nombre 
// d'�l�ments obtenus.
//--------------------------------------------------------------------------
function selectSQL($preq, &$resu)
{  
  $resu = mysql_query($preq, $this->sqlid );
  if ( ! $resu )
    die ( "Requete: \n\t$preq\nErreur fatale avec select: ".mysql_error());

  return (mysql_num_rows($resu));
}

 
//--------------------------------------------------------------------------
// function sqlquery
// ex�cution d'une requete de s�lection, retour du r�sultat et nombre 
// d'�l�ments obtenus.
//--------------------------------------------------------------------------
function sqlquery($preq, &$resu)
{  
  $resu = mysql_query($preq, $this->sqlid );
  if ( ! $resu )
  {
    print "<p>La requete<br>: $preq<br>\n";
    die ( "a provoque l'erreur: ".mysql_error());
  }
}

 
//--------------------------------------------------------------------------
// function closeSQL
// fermeture connexion si existe
//--------------------------------------------------------------------------
function closeSQL()
{  
  if ($this->sqlid)
    mysql_close( $this->sqlid );
}

}
//--------------------------------------------------------------------------
// Fin classe conSQL
//--------------------------------------------------------------------------

?>
