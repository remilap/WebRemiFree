<?php

function redimage($img_src,$img_dest,$dst_w,$dst_h) {
   // Lit les dimensions de l'image
   $size = GetImageSize($img_src);  
   $src_w = $size[0]; $src_h = $size[1];  
   // Teste les dimensions tenant dans la zone
   $test_h = round(($dst_w / $src_w) * $src_h);
   $test_w = round(($dst_h / $src_h) * $src_w);
   // Si Height final non précisé (0)
   if(!$dst_h) $dst_h = $test_h;
   // Sinon si Width final non précisé (0)
   elseif(!$dst_w) $dst_w = $test_w;
   // Sinon teste quel redimensionnement tient dans la zone
   elseif($test_h>$dst_h) $dst_w = $test_w;
   else $dst_h = $test_h;

   // Crée une image vierge aux bonnes dimensions
   $dst_im = ImageCreate($dst_w,$dst_h);
   // Copie dedans l'image initiale redimensionnée
   $src_im = ImageCreateFromJpeg($img_src);
   ImageCopyResized($dst_im,$src_im,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);
   // Sauve la nouvelle image
   ImageJpeg($dst_im,$img_dest);
   // Détruis les tampons
   ImageDestroy($dst_im);  
   ImageDestroy($src_im);

   // Affiche le descritif de la vignette
   echo "SRC='".$img_dest."' WIDTH=".$dst_w." HEIGHT=".$dst_h;
}

?>
