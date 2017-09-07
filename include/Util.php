<?php

function trace($texte) {
	global $debug;
	if ($debug) {
		echo "<A><FONT COLOR='black'>".$texte."</FONT></A><BR />\n";
		//echo "<script language='JavaScript' writeTrace('".$texte."'); />";
	}
}


function traceUneVar($uneVar) {
	global $debug;
	if ($debug) trace("traceUneVar: ".$uneVar." = ".$GLOBALS[$uneVar]);
}


function get_ip() {
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] . " (for)";
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'] . " (http)";
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function traceTableau($nomTab, $leTab, $debug=0) {
	if ($debug && $leTab) {
		$nb1 = 0;
		foreach ($leTab as $key => $value) {
			$nb1++;
			trace($nomTab."[" . $key . "] = " . $value);
			if (is_array($value)) {
				$nb2 = 0;
				foreach ($value as $key2 => $val2) {
					$nb2++;
					trace("&nbsp; &nbsp; => " . $key . "[" . $key2 . "] = " . $val2);
				}
				if ($nb2 > 0) trace("&nbsp; -> nombre de sous-élément(s) : ".$nb2);
			}
		}
		if ($nb1 > 0) trace("&nbsp; -> nombre d'élément(s) dans ".$nomTab." : ".$nb1);
	}
}

function traceVariables($debug=0, $enplus='') {
	if ($debug) {
		traceTableau("_REQUEST", $_REQUEST, $debug);
		traceTableau("_FILES", $_FILES, $debug);
		traceTableau("_SESSION", $_SESSION, $debug);
		if ((strpos($enplus,'SERVER')>0) && isset($_SERVER)) {
			traceTableau("_SERVER", $_SERVER, $debug);
		}
		if ((strpos($enplus,'GLOBALS')>0) && isset($GLOBALS)) {
			traceTableau("GLOBALS", $GLOBALS, $debug);
		}
	}
}


function getHTMLCodeForScripts($tpl, $listScripts) {
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


function getLesOptions($leTab, $keySelected="", $valSelected="") {
	foreach ($leTab as $key => $value) {
		echo '<OPTION VALUE="'.$key.'" ';
		if ($keySelected == $key || $valSelected == $value) {
			echo 'SELECTED';
		}
		echo "> ".$value."</OPTION>\n";
	}
}


function upload($index, $destination, $maxsize=FALSE, $extensions=FALSE) {
	trace("function upload(index=".$index.", destination=".$destination.")");
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


?>
