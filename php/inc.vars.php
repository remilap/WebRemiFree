<?
  //-------------------------------------------------------------------------------
  // illusion_optique - scripts de visualisation d'illusions d'optique, bas�e PHP/mySQL
  // auteur : remi.alpointe@free.fr
  // l'usage ou la modification de ce script est libre, je souhaite cependant que 
  // la mention de l'auteur demeure, et �tre inform� des am�liorations apport�es.
  //-------------------------------------------------------------------------------
  // inc.vars.php
  //   variables globales utilis�es par ce script.
  
  // repertoire d'installation
  $installdir="D:/WEB Local/Tonioc.free.fr/gallery/"; 
  $logdir="logs/";

  $myuser  = "remi.lapointe";
  $mypwd   = "rem000";
  $mybase = "remi_lapointe";
  $tabl_illu = "illusion_optique";
  $imgDir = "../img";

  // variables concernant les cadres
  $targetFrame="mainFrame";		// nom du cadre principal
  $menuFrame="menuFrame";		// nom du cadre contenant le menu
  $targetSrc="docs/intro.html";		// page initiale dans le cadre principal
  $helpUrl="docs/gallery.html";		// page d'aide
  $menuSrc="treeMenu.html";		// page initiale dans le cadre de menu
  $mainTitle = "Phototh�que";	// titre vu sur la barre de titre du navigateur.
    
  // sous-r�pertoires de stockage des �l�ments dans chaque galerie..
  $repnorm="normal/";		// normal elements (pictures)
  $repred="reduit/";		// miniatures
  $repweb="web/";  			// web resized elements

  // database info
  $dbgall="gallery"; 		// nom de la base de donn�es 
  $tbelems="elements";  	// table des �l�ments dans la base
  $tbgalls="galeries"; 		// table des galeries table in database
  $tbusers="users"; 		// table des galeries table in database
  
  // old data base information (migration)
  $dboldphotos="photos";
  $tboldphotos="photos";
  $oldpathphotos_normal="../Galerie/normal/";
  $oldpathphotos_reduit="../Galerie/reduit/";
  $oldpathphotos_web="../Galerie/web/";
  
  // parametres d'import
  $h_vignette=60;			// hauteur vignettes
  $cmp_vignette=70;			// compression jpeg des vignettes (1-100)
  $h_imgweb=600;			// hauteur images compress�es WEB
  $cmp_imgweb=60;			// compression jpeg images compress�es WEB (1-100)
  
  // parametres navigation 
  // $max_elem_def=0;			// par d�faut pas de limitation du nombre de miniatures
  $timerValueDiapo=5000;	// valeur de tempo pour le mode diapo (en millisecondes)
  
  // parametres de securit�
  $public_profile="PUBLIC";			// profil associ� � l'utilsateur non identifi�
  									// (autorisera la navigation dans ces galeries)
  $admin_profile="ADMIN_ALL";			// profil associ� � l'utilsateur non identifi�
  $view_all_profile="VIEW_ALL";			// profil associ� � l'utilsateur non identifi�
  $localhost_view_all=true;	// supprimer les restrictions de navigation en local
  
  // divers
  $gallery_default_img="gallery_default_img.jpg";
?>
