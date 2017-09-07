<?php

	$titre = "";
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
	$extension = "jpg";
	if ( isset($_REQUEST['extension']) ) {
		$extension = $_REQUEST['extension'];
	}

	$home = "../";
	$date = date( "Ymd", getlastmod());
	//$titre = "Accélérez le surf sur internet";

	require_once('TemplateEngine.php');
	$tpl = new TemplateEngine($home."template_remi.html");
	TemplateEngine::Output( $tpl->GetHTMLCode("entete") );
	require_once('RedimImage.php');

	$curDir = getcwd();
	chdir($home.$dir_src);
	$oDir = opendir(".");
	$files = array();
	while (false !== ($entry = readdir($oDir))) {
		if ($entry == "." && $entry == "..") continue;
		if (! ereg($masque.".*.".$extension, $entry)) continue;
		//echo "<br>".$entry;
		$files[] = $entry;
	}
	sort( $files );

	echo "<br>";

	chdir($curDir);
	for ( $i = 0 ; $i < count($files) ; $i++ ) {
		$theFile = $files[$i];
		$video = "";
		if ($extension == "jpg") {
			$video = str_replace(".".$extension, ".mov", $theFile);
			if (file_exists($home.$dir_src."/".$video)) {
				//echo "<table><tr><td>";
			} else {
				$video = "";
			}
		}
		echo "<a href='".$home.$dir_src."/".$theFile."'><img ";
		if ($extension != "jpg") {
			$theFile = str_replace(".".$extension, ".jpg", $theFile);
			if (!file_exists($home.$dir_src."/".$theFile)) {
				continue;
			}
		}
		redimage($home.$dir_src."/".$theFile, $home."temp/".$theFile, 150, 0);
		echo " alt='".$theFile."' border='0'></a>&nbsp; &nbsp;";
		if (strlen($video) > 1) {
			//echo "</td></tr><tr><td>";
			echo "<a href='".$home.$dir_src."/".$video."'>video</a>&nbsp; &nbsp;";
			//echo "</td></tr></table>&nbsp; &nbsp;";
		}
	}


?>

<br>

</body>
</html>
