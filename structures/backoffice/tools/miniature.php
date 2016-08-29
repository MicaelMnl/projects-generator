<?php

// ***** ***** **** *** ** *
// ===> Conversion de couleurs hexa vers RGB (Rouge, vert, bleu)
// => Couleur hexa (Formats possibles : #fff, #ffffff, fff, ffffff)
// ***** ***** **** *** ** *
function hex2rgb($color)
{
	if(strlen($color) >= 3)
		if($color[0] == '#')
			$color = substr($color, 1);

	if(strlen($color) == 6)
		list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
	elseif(strlen($color) == 3)
		list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	else
		return false;

	return array(hexdec($r), hexdec($g), hexdec($b));
}

// ***** ***** **** *** ** *
// ===> Création de la minuature de l'image
// => Chemin de l'image source (chemin)
// => Largeur maximum de la miniature (taille)
// => Hauteur maximum de la miniature (taille)
// => Sauvegarder l'image (true ou false)
// => Chemin de l'image de destination (vide si vous ne souhaitez pas enregistrer votre image)
// => Fix les dimensions de l'image : permet d'avoir une image qui à toujours la même taille. Si la minuature est plus petite que les tailles maximum, alors le fond est remplie avec la couleur définit en paramètre 7.
// => Couleur au format Hexa si vous souhaitez "fixer" l'image
// ***** ***** **** *** ** *
function redimensionner_image($image_source, $largeur_max, $hauteur_max, $sauvegarder=false, $image_destination='', $fix=false, $fix_couleur='f00')
{
	list($largeur_source, $hauteur_source, $type_source,) = getimagesize($image_source);
	$mime_source = image_type_to_mime_type($type_source);
	$largeur_final = $largeur_max;
	$hauteur_final = $hauteur_max;

	/* Origine de l'image : Par défaut à zéro */
	$origine_x = 0;
	$origine_y = 0;

	if($largeur_source < $largeur_final)
		$largeur_final = $largeur_source;

	// Teste les dimensions tenant dans la zone
	$test_h = round(($largeur_final / $largeur_source) * $hauteur_source);
	$test_w = round(($hauteur_final / $hauteur_source) * $largeur_source);

	if(!$hauteur_final) // Si Height final non précisé (0)
		$hauteur_final = $test_h;
	elseif(!$largeur_final) // Sinon si Width final non précisé (0)
		$largeur_final = $test_w;
	elseif($test_h>$hauteur_final) // Sinon test quel redimensionnement tient dans la zone
		$largeur_final = $test_w;
	else
		$hauteur_final = $test_h;

	if($mime_source == 'image/jpeg')
	   $img_in = imagecreatefromjpeg($image_source);
	elseif($mime_source == 'image/png')
	   $img_in = imagecreatefrompng($image_source);
	elseif($mime_source == 'image/gif')
	   $img_in = imagecreatefromgif($image_source);
	else
		return false;

	if($fix)
	{
		$img_out = imagecreatetruecolor($largeur_max, $hauteur_max);

		if($largeur_final < $largeur_max)
			$origine_x = round(($largeur_max-$largeur_final)/2);
		if($hauteur_final < $hauteur_max)
			$origine_y = round(($hauteur_max-$hauteur_final)/2);

		$couleurs = hex2rgb($fix_couleur);
		$fond = ImageColorAllocate($img_out, $couleurs[0], $couleurs[1], $couleurs[2]);
		imagefill($img_out, 0, 0, $fond);
	}
	else
		$img_out = imagecreatetruecolor($largeur_final, $hauteur_final);

	imagecopyresampled($img_out, $img_in, $origine_x, $origine_y, 0, 0, $largeur_final, $hauteur_final, imagesx($img_in), imagesy($img_in));

	if($sauvegarder)
		imagejpeg($img_out, $image_destination);
	else
		imagejpeg($img_out, null, 100);
}

$image = isset($_GET['image']) ? trim($_GET['image']) : exit();

$w = isset($_GET['w']) ? intval(trim($_GET['w'])) : 80;
$h = isset($_GET['h']) ? intval(trim($_GET['h'])) : 70;
$fix = (isset($_GET['fix']) && $_GET['fix'] == 1) ? true : false;
$c = isset($_GET['color']) ? trim($_GET['color']) : 'fff';

header('Content-type: image/jpeg');
redimensionner_image($image, $w, $h, false, '', $fix, $c);

