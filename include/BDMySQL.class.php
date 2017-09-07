<?php
// Sous-classe de la classe abstraite BD, implantant l'acc�s � MySQL

require_once("BD.class.php");

class BDMySQL extends BD {
	// Pas de propri�t�s: elles sont h�rit�es de la classe BD
	// Pas de constructeur: lui aussi est h�rit�

	// M�thode connect: connexion � MySQL
	private function connect($login, $password, $base, $serveur) {
		echo "util no ".$this->getUtil()->getId();
		$this->getUtil()->trace("BDMySQL::connect(login=".$login.", pwd=***, base=".$base.", server=".$server.")");
		// Connexion au serveur MySQL
		$this->setSGBD(self::BD_MYSQL);
		$mysqli = new mysqli($serveur, $login, $password, $base);
//		if ($mysqli->connect_errno) {
//			$this->error_code = $mysqli->connect_errno;
//			$this->error_mesg = $mysqli->connect_error;
//			return 0;
//		}
		if (mysqli_connect_error()) {
			$this->error_code = mysqli_connect_errno();
			$this->error_mesg = mysqli_connect_error();
			$this->getUtil()->trace("BDMySQL::connect Error code=".$this->error_code.", msg=".$this->error_mesg);
			return 0;
		}
		$this->getUtil()->trace("BDMySQL::connect without error");
		$this->error_code = 0;
/*
		try {
			$this->connexion = new PDO('mysql:host='.$serveur.';dbname='.$base, $login, $password);
		} catch (PDOException $e) {
			$this->error_mesg = $e->getMessage();
			return 0;
		}
*/
		return $mysqli;
	}

	// M�thode d'ex�cution d'une requ�te
	private function exec($request) {
		$this->getUtil()->trace("BDMySQL::exec begin");
		$res = $this->connexion->query($request);
		if ($res) {
			$this->error_code = 0;
		} else  {
			$this->error_code = $this->connexion->errno;
			$this->error_mesg = $this->connexion->error;
		}
		$this->getUtil()->trace("BDMySQL::exec end with error_code: " . $this->error_code);
		return $res;
	}

	// Partie publique: implantation des m�thodes abstraites

	// Acc�s � la ligne suivante, sous forme d'objet
	public function nextObject($result) {
		return  mysql_fetch_object($result);
	}

	public function firstLine($result) {
		$result->data_seek(0);
	}

	// Acc�s � la ligne suivante, sous forme de tableau associatif
	public function nextLine($result) {
		return  $result->fetch_assoc();
	}

	// Acc�s � la ligne suivante, sous forme de tableau indic�
	public function nextArray($result) {
		return  mysql_fetch_row($result);
	}

	// Echappement des apostrophes et autres pr�paration � l'insertion
	public function prepareChaine($str) {
		return mysql_real_escape_string($str);
	}

	// G�n�ration d'un identifiant
	public function genereID($sequence_name) {
		// Insertion d'un ligne pour obtenir l'auto-incr�mentation
		$this->execRequest("INSERT INTO $sequence_name VALUES()");

		// Si quelque chose s'est mal pass�, on a lev� une exception,
		// sinon on retourne l'identifiant
		return mysql_insert_id();
	}

	// Methode pour eviter les injections SQL
	public function quote($s) {
		return mysql_real_escape_string($s);
	}

	// M�thode ajout�e: renvoie le sch�ma d'une table
	public function schemaTable($nom_table) {
		// Recherche de la liste des attributs de la table
		$listeAttr = @mysql_list_fields($this->base_name, $nom_table, $this->connexion);

		if (!$listeAttr) {
			echo ("Pb d'analyse de $nom_table");
			return null;
		}

		// Recherche des attributs et stockage dans le tableau
		for ($i = 0; $i < mysql_num_fields($listeAttr); $i++) {
			$nom =  mysql_field_name($listeAttr, $i);
			$schema[$nom]['longueur'] = mysql_field_len($listeAttr, $i);
			$schema[$nom]['type'] = mysql_field_type($listeAttr, $i);
			$schema[$nom]['clePrimaire'] = substr_count(mysql_field_flags($listeAttr, $i), "primary_key");
			$schema[$nom]['notNull'] = substr_count(mysql_field_flags($listeAttr, $i), "not_null");
		}
		return $schema; 
	}

	// Fonctions d�crivant le r�sultat d'une requ�te
	public function nbAttributs($result) {
		return mysql_num_fields($result);
	}

	public function nomAttribut($result, $pos) {
		return mysql_field_name ($result, $pos);
	}

	// Destructeur de la classe: on se d�connecte
	public function __destruct () {
//		if ($this->connexion) {
//			@mysql_close ($this->connexion);
//		}
	}

	// Fin de la classe
}
?>
