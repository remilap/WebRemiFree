<?php
// Sous-classe de la classe abstraite BD, implantant l'acc�s � MySQL

require_once("BD.class.php");

class BDMySQL extends BD
{
  // Pas de propri�t�s: elles sont h�rit�es de la classe BD
  // Pas de constructeur: lui aussi est h�rit�

  // M�thode connect: connexion � MySQL
   function connect ($login, $mot_de_passe, $base, $serveur)
  {
    // Connexion au serveur MySQL 
    if (!$this->connexion = mysql_pconnect ($serveur, $login, $mot_de_passe))
      return 0;

    // Connexion � la base 
    if (!mysql_select_db ($this->nom_base, $this->connexion)) 
      return 0;

    $this->setSGBD("MySQL");

    return $this->connexion;
  }

  // M�thode d'ex�cution d'une requ�te. 
   function exec ($requete) 
    {return @mysql_query ($requete, $this->connexion);  }

  // Partie publique: implantation des m�thodes abstraites
  // Acc�s � la ligne suivante, sous forme d'objet
   function objetSuivant ($resultat)
    {return  mysql_fetch_object ($resultat);    } 
  // Acc�s � la ligne suivante, sous forme de tableau associatif
   function ligneSuivante ($resultat)
    {   return  mysql_fetch_assoc ($resultat);  }
  // Acc�s � la ligne suivante, sous forme de tableau indic�
   function tableauSuivant ($resultat)
    {   return  mysql_fetch_row ($resultat);  }
 
  // Echappement des apostrophes et autres pr�paration � l'insertion
   function prepareChaine($chaine)
  {return mysql_real_escape_string($chaine);  }
  
  // G�n�ration d'un identifiant
   function genereID($nom_sequence)
  {
    // Insertion d'un ligne pour obtenir l'auto-incr�mentation
    $this->execRequete("INSERT INTO $nom_sequence VALUES()");

    // Si quelque chose s'est mal pass�, on a lev� une exception,
    // sinon on retourne l'identifiant
    return mysql_insert_id();
  }

  // Retour du message d'erreur
   function messageSGBD ()
    { return mysql_error($this->connexion);}

  // Methode pour eviter les injections SQL
  function quote($s)
  { return mysql_real_escape_string($s); }

  // M�thode ajout�e: renvoie le sch�ma d'une table
   function schemaTable($nom_table)
  {
    // Recherche de la liste des attributs de la table
    $listeAttr = @mysql_list_fields($this->nom_base, 
				    $nom_table, $this->connexion);
    
    if (!$listeAttr) echo ("Pb d'analyse de $nom_table"); 
    
    // Recherche des attributs et stockage dans le tableau
    for ($i = 0; $i < mysql_num_fields($listeAttr); $i++) {
	$nom =  mysql_field_name($listeAttr, $i);
	$schema[$nom]['longueur'] = mysql_field_len($listeAttr, $i);
	$schema[$nom]['type'] = mysql_field_type($listeAttr, $i);
	$schema[$nom]['clePrimaire'] = 
	  substr_count(mysql_field_flags($listeAttr, $i), "primary_key");
	$schema[$nom]['notNull'] = 
	  substr_count(mysql_field_flags($listeAttr, $i), "not_null");
      }
    return $schema; 
  }

  // Fonctions d�crivant le r�sultat d'une requ�te
   function nbAttributs($resultat)  {
    return mysql_num_fields($resultat);
  }
   function nomAttribut($resultat, $pos)  {
    return mysql_field_name ($resultat, $pos);
  }

  // Destructeur de la classe: on se d�connecte
  function __destruct ()
  {if ($this->connexion)  @mysql_close ($this->connexion);    }
  // Fin de la classe
}
?>
