<?php
// �ditez les 2 variables ci-dessous en fonction du r�sultat souhait� :
$largeur = "100"; // correspond � la largeur de l'image souhait�e
$hauteur ="100"; // correspond � la hauteur de l'image souhait�e

// et voici la cr�ation de la miniature...
header("Content-Type: image/jpeg");
$img_in = imagecreatefromjpeg($pic);
$img_out = imagecreatetruecolor($largeur, $hauteur);
imagecopyresampled($img_out, $img_in, 0, 0, 0, 0, imagesx($img_out), imagesy($img_out), imagesx($img_in), imagesy($img_in));
$t = imagejpeg($img_out);
echo $t;
?>
