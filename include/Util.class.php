<?php

class Util {
	// ----   Private part: the properties
	private $debug = 0;
	private $htmlFormat = 1;
	private $id = 0;
	private static $nextId = 1;

	// Constructor
	public function __construct($debug = 0, $htmlFormat = 1) {
		$this->setDebugTo($debug);
		$this->htmlFormat = $htmlFormat;
		$this->id = self::$nextId++;
		$this->trace("constructeur de Util no ".$this->id." avec debug=".$this->getDebugStatus()." et htmlFormat=".$this->htmlFormat);
	}

	// Set the debug ON
	public function setDebugOn() {
		$this->debug = 1;
	}

	// Set the debug OFF
	public function setDebugOff() {
		$this->debug = 0;
	}

	// Set the debug status
	public function setDebugTo($debug) {
		if ($debug == 0)
			$this->setDebugOff();
		else
			$this->setDebugOn();
	}

	// Get the debug status
	public function getDebugStatus() {
		return $this->debug;
	}

	// Displays a texte if debug is ON, by default in HTML format
	public function trace($texte, $html=1) {
		if ($this->getDebugStatus()) {
			if ($html) {
				echo "<FONT BGCOLOR='white' COLOR='black'>TRACE ".$texte."</FONT><BR />\n";
			} else {
				echo $texte."\n";
			}
		}
	}

	// Displays a variable and its value
	public function traceUneVar($uneVar) {
		$this->trace("traceUneVar: ".$uneVar." = ".$GLOBALS[$uneVar]);
	}

	// Displays (if debug is ON) an array and its contents
	public function traceTableau($nomTab, $leTab) {
		if ($this->getDebugStatus() && $leTab) {
			$nb1 = 0;
			foreach ($leTab as $key => $value) {
				$nb1++;
				$this->trace($nomTab."[" . $key . "] = " . $value);
				if (is_array($value)) {
					$nb2 = 0;
					foreach ($value as $key2 => $val2) {
						$nb2++;
						$this->trace("&nbsp; &nbsp; => " . $key . "[" . $key2 . "] = " . $val2);
					}
					if ($nb2 > 0) $this->trace("&nbsp; -> nombre de sous-élément(s) : ".$nb2);
				}
			}
			if ($nb1 > 0) $this->trace("&nbsp; -> nombre d'élément(s) dans ".$nomTab." : ".$nb1);
		}
	}

	// Displays (if debug is ON) the different php arrays
	public function traceVariables($enplus='') {
		if ($this->getDebugStatus()) {
			if (isset($_REQUEST)) $this->traceTableau("_REQUEST", $_REQUEST);
			if (isset($_FILES)) $this->traceTableau("_FILES", $_FILES);
			if (isset($_SESSION)) $this->traceTableau("_SESSION", $_SESSION);
			if ((strpos($enplus,'SERVER')>0) && isset($_SERVER)) {
				$this->traceTableau("_SERVER", $_SERVER);
			}
			if ((strpos($enplus,'GLOBALS')>0) && isset($GLOBALS)) {
				$this->traceTableau("GLOBALS", $GLOBALS);
			}
		}
	}

	// Returns the supported php extension
	public function get_supported_extensions($ext='') {
		$this->trace("get_supported_extensions(".$ext.")");
		$res = "Supported ".$ext." extensions:<br>";
		foreach(get_loaded_extensions() as $extension) {
			if(strpos(strtolower($extension), strtolower($ext)) !== FALSE) {
				$this->trace("found extension:".$extension);
				$res .= $extension.'<br>';
			}
		}
		return $res;
	}

	// Indicates if PDO MySQL extension is supported
	public function is_PDO_mysql_extension_supported() {
		$res = $this->get_supported_extensions('PDO');
		$ret = strpos(strtolower($res), 'mysql');
		if ($ret === FALSE) {
			$this->trace("PDO mysql extension is not supported on the server<br>");
		}
		return $ret;
	}

	// Returns the IP address of the client
	public function get_ip() {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] . " (for)";
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'] . " (http)";
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$this->trace("get_ip result = " . $ip);
		return $ip;
	}

	// ???
	public function getHTMLCodeForScripts($tpl, $listScripts) {
		global $home, $onescript, $lesscripts;
		$lesscripts = "";
		foreach ($listScripts as $onescript) {
			$res = $tpl->GetHTMLCode('OneJavaScript');
			//trace("one script=".$onescript." => HTML=".$res);
			$lesscripts = $lesscripts.$res;
			//trace("lesscripts=".$lesscripts);
		}
		return $lesscripts;
	}

	// ???
	public function getLesOptions($leTab, $keySelected="", $valSelected="") {
		foreach ($leTab as $key => $value) {
			echo '<OPTION VALUE="'.$key.'" ';
			if ($keySelected == $key || $valSelected == $value) {
				echo 'SELECTED';
			}
			echo "> ".$value."</OPTION>\n";
		}
	}

	// Retrieve a file after its selection
	public function upload($index, $destination, $maxsize=FALSE, $extensions=FALSE) {
		$this->trace("function upload(index=".$index.", destination=".$destination.")");
		$erreur = "";
		//Test1: fichier correctement uploade
		if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) {
			$erreur = "Erreur de transfert, fichier $index non trouvé";
			return $erreur;
		}
		//Test2: taille limite
		if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) {
			$erreur = "Fichier ".$_FILES[$index]['name']." trop gros (> ".$maxsize.")";
			return $erreur;
		}
		//Test3: extension
		$ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
		if ($extensions !== FALSE AND !in_array($ext,$extensions)) {
			$erreur = "Extension ".$ext." non supportée";
			return $erreur;
		}
		//Deplacement
		$res = move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
		if (!$res) {
			$erreur = "Erreur lors du déplacement du fichier ".$_FILES[$index]['name']." vers ".$destination;
			return $erreur;
		}
		return $erreur;
	}

	// Returns the next free id for a table
	public function getNextFreeId($bd, $tableName) {
		$requete = "SELECT max(`id`) FROM ".$tableName;
		$resId = $bd->execRequete($requete);
		if ($resId) {
			$resBrut = $bd->tableauSuivant($resId);
			$result = $resBrut[0] + 1;
			$this->trace("getNextFreeId(".$tableName."): requete = ".$requete);
			$this->trace("=> result = ".$result);
		} else {
			$result = 1;
		}
		return $result;
	}

}

?>
