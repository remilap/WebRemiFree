<?php

	$titre = "Liste de fichiers";
	if ( isset($_REQUEST['titre']) ) {
		$titre = strtr($_REQUEST['titre'], "_", " ");
	}
	$dir_src = ".";
	if ( isset($_REQUEST['dir_src']) ) {
		$dir_src = $_REQUEST['dir_src'];
	}
	$masque = "";
	if ( isset($_REQUEST['masque']) ) {
		$masque = $_REQUEST['masque'];
	}
	$extension = "pdf";
	if ( isset($_REQUEST['extension']) ) {
		$extension = $_REQUEST['extension'];
	}
	$sort = "a";
	if ( isset($_REQUEST['sort']) ) {
		$sort = $_REQUEST['sort'];
	}
	$liste = "br"; // or ul or ol or sl or col
	if ( isset($_REQUEST['liste']) ) {
		$liste = $_REQUEST['liste'];
		if ($liste != "br" && $liste != "ul" && $liste != "ol" && $liste != "sl" && $liste != "col") {
			$liste = "br";
		}
	}
	$nbcol = "1";
	if ( isset($_REQUEST['nbcol']) ) {
		$nbcol = $_REQUEST['nbcol'];
		if ($liste != "col" || $nbcol < 2 || $nbcol > 4) {
			$nbcol = 1;
		}
	}
	$padding = "2";
	if ( isset($_REQUEST['padding']) ) {
		$padding = $_REQUEST['padding'];
	}
	$border = "1";
	if ( isset($_REQUEST['border']) ) {
		$border = $_REQUEST['border'];
	}
	$spacing = "2";
	if ( isset($_REQUEST['spacing']) ) {
		$spacing = $_REQUEST['spacing'];
	}
	$imgsize = "25";
	if ( isset($_REQUEST['imgsize']) ) {
		$imgsize = $_REQUEST['imgsize'];
	}
	$debug = "0";
	if ( isset($_REQUEST['debug']) ) {
		$debug = $_REQUEST['debug'];
	}

	$home = "../";
	$tmpl = $home."template_remi.html";
	if ( isset($_REQUEST['tmpl']) ) {
		$tmpl = $home.$_REQUEST['tmpl'];
	}

	$date = date( "Ymd", getlastmod());

	require_once('TemplateEngine.php');
	$tpl = new TemplateEngine($tmpl);
	TemplateEngine::Output( $tpl->GetHTMLCode("entete") );
	require_once('RedimImage.php');

	$other_ext = array("mp3", "txt");
	$other_txt = array("son", "paroles");
	$other_nbr = array(0,     0);

	$curDir = getcwd();
	chdir($home.$dir_src);
	$oDir = opendir(".");
	$files = array();
	while (false !== ($entry = readdir($oDir))) {
		if ($entry == "." && $entry == "..") continue;
		if (! ereg($masque.".*.".$extension, $entry)) continue;
		if ($debug != "0") echo "<br>".$entry;
		$files[] = $entry;
		for ( $k = 0 ; $k < count($other_ext) ; $k++ ) {
			if ($debug != "0")echo ", k=".$k.", other_ext=".$other_ext[$k];
			$theExtFile = str_replace(".".$extension, ".".$other_ext[$k], $entry);
			if (file_exists($theExtFile)) {
				$other_nbr[$k]++;
				if ($debug != "0") echo ", ".$theExtFile.", other_nbr[".$k."] = ".$other_nbr[$k];
			}
		}
	}
	if ($sort == "d") {
		rsort( $files );
	} else {
		sort( $files );
	}

	if ($liste == "col") {
		echo "<table border='".$border."' cellpadding='".$padding."' cellspacing='".$spacing."'>\n";
	} else {
		echo "<".$liste.">\n";
	}

	chdir($curDir);
	for ( $i = 0 ; $i < count($files) ; $i++ ) {
		if ($liste == "br") {
			echo "<br>";
		} elseif ($liste == "col") {
			if ($i % $nbcol == 0) {
				echo "<tr>";
			}
			echo '<td style="padding-left: 20px">';
		} else {
			echo "<li>";
		}
		$theFile = $files[$i];
		if ($extension != "jpg") {
			$theJpgFile = $home.$dir_src."/".str_replace(".".$extension, ".jpg", $theFile);
			if (file_exists($theJpgFile)) {
				//redimage($theJpgFile, $home."temp/".$theJpgFile, 100, 0);
				echo "<a href='".$theJpgFile."'><img alt='".$theFile."' border='0' src='".$theJpgFile."' WIDTH='".$imgsize."'></a> &nbsp;\n";
			}
		}
		if ($liste == "col") echo "&nbsp;</td><td>";
		echo str_replace(".".$extension, "", $theFile)."\n";
		if ($liste == "col") echo "</td><td>";
		echo "&nbsp; <a href='".$home.$dir_src."/".$theFile."'>".$extension."</a>\n";

		for ( $k = 0 ; $k < count($other_ext) ; $k++ ) {
			if ($extension != $other_ext[$k]) {
				$theExtFile = $home.$dir_src."/".str_replace(".".$extension, ".".$other_ext[$k], $theFile);
				if (file_exists($theExtFile)) {
					if ($liste == "col") echo "&nbsp;</td><td>";
					echo "&nbsp;<a href='".$theExtFile."'>".$other_txt[$k]."</a>\n";
				} else {
					if ($liste == "col" && $other_nbr[$k] > 0) echo "&nbsp;</td><td>";
				}
			}
		}
		echo "&nbsp;";
		if ($liste == "col") {
			echo "</td>";
			$nbsubcol++;
			if ($i % $nbcol == $nbcol - 1) {
				echo "</tr>\n";
			}
		} elseif ($liste != "br") {
			echo "</li>\n";
		}
	}

	if ($liste == "col") {
		for ( $j = $i-1 ; $j < count($files)+$nbcol-1 ; $j++ ) {
			if ($j % $nbcol == $nbcol - 1) {
				echo "&nbsp;</td></tr>\n";
				break;
			}
			echo "&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>";
		}
	}

	if ($debug != "0") echo "<p>i=".$i."</p>\n";
	if ($liste == "col") {
		echo "</table>\n";
	} else {
		echo "</".$liste.">\n";
	}


?>

<br>

</body>
</html>
