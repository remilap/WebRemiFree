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
		$this->trace("constructor of ".$this->getInfo());
	}

	// Set the debug ON
	public function setDebugOn() {
		$this->debug = 1;
	}

	// Set the debug OFF
	public function setDebugOff() {
		$this->debug = 0;
	}

	// Set the debug to a given state
	public function setDebugTo($debug = 0) {
		$res = 0;
		if (isset($debug)) {
			if (is_array($debug)) {
				$test = $debug[0];
			} else {
				$test = $debug;
			}
			if (is_int($test) && $test != 0) {
				$res = 1;
			}
			if (is_string($test) && $test == "on") {
				$res = 1;
			}
		}
		$this->debug = $res;
	}

	// Get the debug status
	public function getDebugStatus() {
		return $this->debug;
	}

	// Get the class id
	public function getId() {
		return $this->id;
	}

	// Get some information about the current instance
	public function getInfo() {
		$txt = "Util=".$this->getId()."-debug=".$this->getDebugStatus()."-htmlFormat=".$this->htmlFormat;
		return $txt;
	}

	// Displays a texte if debug is ON, by default with calling class/function in HTML format
	public function trace($texte, $ctx=1, $html=1) {
		if ($this->getDebugStatus()) {
			$txt = "TRACE".$this->id." ";
			if ($ctx) {
				if (isset(debug_backtrace()[1])) {
					$caller = debug_backtrace()[1];
					if ($caller["file"]) {
						$txt .= "[".$caller["file"].":".$caller["line"]."] ";
					}
					if (isset($caller["class"]) && $caller["class"]) {
						$txt .= $caller["class"].$caller["type"].$caller["function"]." ";
					}
				}
			}
			$txt .= $texte;
			if ($html) {
				echo "<A CLASS='trace'>".$txt."</A><BR />\n";
			} else {
				echo $txt."\n";
			}
		}
	}

	// Displays a variable and its value
	public function traceUneVar($uneVar) {
		$this->trace("traceUneVar: ".$uneVar." = ".$GLOBALS[$uneVar]);
	}

	// Displays (if debug is ON) a variable (maybe a simple type or an array)
	public function traceAVariable($nomVar, $theVar) {
		if (is_array($theVar)) {
			$this->traceTableau($nomVar, $theVar);
		} else {
			$this->trace($nomVar." = ".$theVar);
		}
	}

	// Displays (if debug is ON) an array and its contents
	public function traceTableau($nomTab, $leTab) {
		if ($this->getDebugStatus() && $leTab) {
			$nb1 = 0;
			foreach ($leTab as $key => $value) {
				$nb1++;
				if (is_array($value)) {
					$this->trace($nomTab."[" . $key . "] = array");
					$nb2 = 0;
					foreach ($value as $key2 => $val2) {
						$nb2++;
						$this->trace("&nbsp; &nbsp; => " . $key . "[" . $key2 . "] = " . $val2);
					}
					if ($nb2 > 0) $this->trace("&nbsp; -> number of sub-element(s) : ".$nb2);
				} else {
					$this->trace($nomTab."[" . $key . "] = " . $value);
				}
			}
			if ($nb1 > 0) $this->trace("&nbsp; -> number of element(s) in ".$nomTab." : ".$nb1);
		}
	}

	// Displays (if debug is ON) the different php arrays
	public function traceVariables($enplus='') {
		if ($this->getDebugStatus()) {
			$this->traceTableau("_REQUEST", $_REQUEST);
			$this->traceTableau("_FILES", $_FILES);
			if (isset($_SESSION)) {
				$this->traceTableau("_SESSION", $_SESSION);
			}
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
		$error = "";
		//Test1: fichier correctement uploade
		if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) {
			$error = "Transfer error, $index file not found";
			return $error;
		}
		//Test2: taille limite
		if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) {
			$error = "File ".$_FILES[$index]['name']." is too big (> ".$maxsize.")";
			return $error;
		}
		//Test3: extension
		$ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
		if ($extensions !== FALSE AND !in_array($ext,$extensions)) {
			$error = "Extension ".$ext." not supported";
			return $error;
		}
		//Deplacement
		$res = move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
		if (!$res) {
			$error = "Error during file move from ".$_FILES[$index]['name']." to ".$destination;
			return $error;
		}
		return $error;
	}

	// Returns the next free id for a table
	public function getNextFreeId($bd, $tableName) {
		$request = "SELECT max(`id`) FROM ".$tableName;
		$resId = $bd->execRequete($request);
		if ($resId) {
			$resBrut = $bd->tableauSuivant($resId);
			$result = $resBrut[0] + 1;
			$this->trace("getNextFreeId(".$tableName."): request = ".$request);
			$this->trace("=> result = ".$result);
		} else {
			$result = 1;
		}
		return $result;
	}

}

?>
