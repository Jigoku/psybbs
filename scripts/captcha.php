<?php
/* psyBBS (captcha.php)
 * This file generates a security code image
 * 
 * Copyright (C) 2015 Ricky K. Thomson
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * u should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>. 
 */
 
 
/*
	captcha todo
	* add inherited colours from theme
	* font file selection in settings
*/
	header ("Content-type: image/png"); // define image type

	session_start(); // start a session

	$code = substr(md5(microtime()), 0, 6);

	$_SESSION["code"] = ($code); //add the random number to session 'code'

	//image size
	$im = imagecreatetruecolor(150,50);

	//background colour
	$bg = imagecolorallocate($im, rand(20,30), rand(20,30), rand(20,30));
	$text1 = imagecolorallocate($im, rand(80,100), rand(0,20), rand(0,20));
	$text2 = imagecolorallocate($im, rand(150,200), rand(0,100), rand(30,100));

	$rand1 = imagecolorallocate($im, rand(100,140), rand(0,40), rand(0,80));
	$rand2 = imagecolorallocate($im, rand(100,140), rand(0,40), rand(0,80));
	$rand3 = imagecolorallocate($im, rand(100,140), rand(0,40), rand(0,80));
	$rand4 = imagecolorallocate($im, rand(100,140), rand(0,40), rand(0,80));
	$rand5 = imagecolorallocate($im, rand(100,140), rand(0,40), rand(0,80));
	$rand6 = imagecolorallocate($im, rand(100,140), rand(0,40), rand(0,80));

	imagefilledrectangle($im, 0, 0, 149, 49, $bg);
	imagefilledrectangle($im, rand(0,149), rand(0,49), rand(0,149), rand(0,49), $rand1);
	imagefilledrectangle($im, rand(0,149), rand(0,49), rand(0,149), rand(0,49), $rand2);
	imagefilledrectangle($im, rand(0,149), rand(0,49), rand(0,149), rand(0,49), $rand3);
	imagefilledrectangle($im, rand(0,149), rand(0,49), rand(0,149), rand(0,49), $rand4);
	imagefilledrectangle($im, rand(0,149), rand(0,49), rand(0,149), rand(0,49), $rand5);
	imagefilledrectangle($im, rand(0,149), rand(0,49), rand(0,149), rand(0,49), $rand6);


	$font = "../media/font.ttf";
	imagettftext($im, rand(20,40), rand(-10,10), rand(0,100), rand(0,49), $rand1, $font, $code); //shadow
	imagettftext($im, rand(20,40), rand(-10,10), rand(0,100), rand(0,49), $rand2, $font, $code); //shadow
	imagettftext($im, rand(20,40), rand(-10,10), rand(0,100), rand(0,49), $rand3, $font, $code); //shadow
	imagettftext($im, rand(20,40), rand(-10,10), rand(0,100), rand(0,49), $rand4, $font, $code); //shadow
	imagettftext($im, rand(20,40), rand(-10,10), rand(0,100), rand(0,49), $rand5, $font, $code); //shadow
	imagettftext($im, rand(20,40), rand(-10,10), rand(0,100), rand(0,49), $rand6, $font, $code); //shadow

	$txtsize = rand(14,20);
	$txtrot = rand(-20-20);
	$txtx = rand(10, 60);
	$txty = rand(20, 50);

	imagettftext($im, $txtsize, $txtrot, $txtx, $txty, $text1, $font, $code);	//readable shadow
	imagettftext($im, $txtsize, $txtrot + rand(-5,5), $txtx, $txty, $text1, $font, $code); //final text
	imagettftext($im, $txtsize, $txtrot + rand(-2,2), $txtx, $txty, $text2, $font, $code); //final text
	


	imagepng($im); //display image as PNG
	imagedestroy($im);



?>
