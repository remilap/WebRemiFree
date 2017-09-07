<?
  //-------------------------------------------------------------------------------
  // illusion_optique - scripts de visualisation d'illusions d'optique, basée PHP/mySQL
  // auteur : remi.alpointe@free.fr
  // l'usage ou la modification de ce script est libre, je souhaite cependant que 
  // la mention de l'auteur demeure, et être informé des améliorations apportées.
  //-------------------------------------------------------------------------------
  // inc.vars.php
  //   variables globales utilisées par ce script.
  
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
  $mainTitle = "Photothèque";	// titre vu sur la barre de titre du navigateur.
    
  // sous-répertoires de stockage des éléments dans chaque galerie..
  $repnorm="normal/";		// normal elements (pictures)
  $repred="reduit/";		// miniatures
  $repweb="web/";  			// web resized elements

  // database info
  $dbgall="gallery"; 		// nom de la base de données 
  $tbelems="elements";  	// table des éléments dans la base
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
  $h_imgweb=600;			// hauteur images compressées WEB
  $cmp_imgweb=60;			// compression jpeg images compressées WEB (1-100)
  
  // parametres navigation 
  // $max_elem_def=0;			// par défaut pas de limitation du nombre de miniatures
  $timerValueDiapo=5000;	// valeur de tempo pour le mode diapo (en millisecondes)
  
  // parametres de securité
  $public_profile="PUBLIC";			// profil associé à l'utilsateur non identifié
  									// (autorisera la navigation dans ces galeries)
  $admin_profile="ADMIN_ALL";			// profil associé à l'utilsateur non identifié
  $view_all_profile="VIEW_ALL";			// profil associé à l'utilsateur non identifié
  $localhost_view_all=true;	// supprimer les restrictions de navigation en local
  
  // divers
  $gallery_default_img="gallery_default_img.jpg";
?>
