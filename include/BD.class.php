<?php
// Classe abstraite d�finissant une interface g�n�rique d'acc�s
// � une base de donn�es. Version compl�te


function Connexion ($sgbd, $login, $passe, $base, $serveur)
{
  // define ("SGBD", "PostgreSQL"); // Ou PostgreSQL, ou SQLite
  // define ("SGBD", "MySQL"); // Ou PostgreSQL, ou SQLite
  // define ("SGBD", "SQLite"); // Ou PostgreSQL, ou SQLite

  // Instanciation d'un objet instance de BD. 
  // Choix de la sous-classe en fonction de la configuration
  switch ($sgbd) {
    case "PostgreSQL":
      $bd = new BDPostgreSQL ($login, $passe, $base, $serveur);
      break;
 
    case "SQLite":
      $bd = new BDSQLite ($login, $passe, $base, $serveur);
      break;

    default: // MySQL par d�faut
      $bd = new BDMySQL ($login, $passe, $base, $serveur);
      break;
  }
  return $bd;
}


class BD
{
  // ----   Partie priv�e : les propri�t�s
  var $sgbd, $connexion, $nom_base;
  var $code_erreur, $message_erreur;

  // Constructeur de la classe
  function BD ($login, $mot_de_passe, $base, $serveur)
  {
    // Initialisations
    $this->nom_base = $base;
    $this->code_erreur = 0;
    $this->message_erreur = "";
    $this->sgbd = "Inconnu?";

    // Connexion au serveur par appel � une m�thode priv�e
    $this->connexion = $this->connect($login, $mot_de_passe, 
				      $base, $serveur);

    // Lanc� d'exception en cas d'erreur
    if ($this->connexion == 0) 
      echo "Erreur de connexion au SGBD" . 
			   $this->sgbd . ": " . $this->code_erreur;

    // Fin du constructeur
  }

  // M�thodes priv�es
    function connect ($login, $mot_de_passe, $base, $serveur) {}
    function exec ($requete) {}

  // M�thodes publiques

  // M�thode d'ex�cution d'une requ�te
   function execRequete ($requete)
  {
    if (!$resultat = $this->exec ($requete)) 
      echo "Probl�me dans l'ex�cution de la requ�te : $requete.<br> "
	. $this->messageSGBD();

    return $resultat;
  }

  // M�thodes abstraites
  // Acc�s � la ligne suivante, sous forme d'objet
    function objetSuivant ($resultat) {}
  // Acc�s � la ligne suivante, sous forme de tableau associatif
    function ligneSuivante ($resultat) {}
  // Acc�s � la ligne suivante, sous forme de tableau indic�
    function tableauSuivant ($resultat) {}

  // Echappement des apostrophes et autres pr�parations � l'insertion
    function prepareChaine($chaine) {}

  // G�n�ration d'un identifiant
    function genereID($nom_sequence) {}

  // M�thode indiquant le nombre d'attributs dans le r�sultat
   function nbAttributs ($res) {}

  // M�thode donnant le nom d'un attribut dans un r�sultat
   function nomAttribut ($res, $position) {}

  // Retour du message d'erreur
    function messageSGBD () {}

  // Nom du SGBD
   function getSGBD()
  {    return $this->sgbd;  }
   function setSGBD($sgbd)
  { $this->sgbd = $sgbd;  }

  // Fin de la classe
}
?>
