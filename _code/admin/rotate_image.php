<?php
if(isset($_GET['image'])){
	$filename = urldecode($_GET['image']);
}

// Content type
//header('Content-type: image/jpeg');
 
// Chargement
$source = imagecreatefromjpeg($filename);
 
// Rotation
$rotate = imagerotate($source, 90, 0);
 
// Affichage
imagejpeg($rotate, $filename);


 
// Libération de la mémoire
imagedestroy($source);
imagedestroy($rotate);