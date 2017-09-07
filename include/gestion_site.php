<?php
	$home = "../";
	$date = date( "Ymd", getlastmod());
	$titre = "Gestion du site internet de la famille Lapointe de Nantes";

	require('TemplateEngine.php');
	require('Lien.php');
	$tpl = new TemplateEngine($home.'template_remi.html');
	TemplateEngine::Output( $tpl->GetHTMLCode('entete') );
?>

<!-- from http://translate.google.com/translate_tools?hl=en 
<div id="google_translate_element"></div><script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'fr'
  }, 'google_translate_element');
}
</script>
<script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
-->
<!-- from http://translate.google.fr/translate_tools?hl=fr&sl=fr&tl=en
<script src="http://www.gmodules.com/ig/ifr?url=http://www.google.com/ig/modules/translatemypage.xml&up_source_language=fr&w=160&h=60&title=&border=&output=js"></script>
-->

<ul>
<li><?
$doc = new Lien("GestionFamille.php", "Gestion des familles de signet");
echo $doc->GetHTMLCode() . ", ";
?>
</li>
<li><?
$doc = new Lien("GestionCategorie.php", "Gestion des catégories de signet");
echo $doc->GetHTMLCode() . ", ";
?>
</li>
<li><?
$doc = new Lien("GestionSignet.php", "Gestion des signets");
echo $doc->GetHTMLCode() . ", ";
?>
</li>
<br />
<li><?
$doc = new Lien("http://imp.free.fr/", "Accès au Web Mail de Free.fr", "mail_free");
echo $doc->GetHTMLCode() . ", ";
?>
</li>
<li><?
$doc = new Lien("http://ml.free.fr/", "Accès à la gestion des listes de diffusion de Free.fr", "ml_free");
echo $doc->GetHTMLCode() . ", ";
?>
</li>
<li><?
$doc = new Lien("http://sql.free.fr/phpMyAdmin/", "Accès à la base de données Free.fr", "mysql_free");
echo $doc->GetHTMLCode() . ", ";
?>
</li>
<li><?
$doc = new Lien("http://ftpperso.free.fr/", "Accès aux fichiers sur le serveur de Free.fr", "perso_free");
echo $doc->GetHTMLCode() . ", ";
?>
</li>

</ul>

<?php
	//TemplateEngine::Output( $tpl->GetHTMLCode('pdfreader') );
	TemplateEngine::Output( $tpl->GetHTMLCode('baspage') );
?>

