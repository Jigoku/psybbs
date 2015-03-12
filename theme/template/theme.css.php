<?php
/* psyBBS (theme.css.php)
 * This file helps with dynamic theming
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
 
 
 //checks if the file is being included, this is used for processing
 //theme specific options which will be displayed in the settings.php controls
 //under "theme options". This isn't needed unless you want it to be configurable.
 if (isset($psybbs)) {
	echo "Currently using theme _______ by <a href=\"http://site.example/\">Mr X</a>\n";
	echo "<div class=\"info\">Theme Options<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setthemeopts\">\n";
	echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"themeopts\" value=\"".getMysqlStr("themeopts","global")."\">\n";	
	echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
	echo "</form></div>\n";
	 /*
	  * 
	  * 
	  * setting theme options in this example to:
	  * ?bg=#ff0000
	  *   ...would set the background to bright red.
	  * 
	  */
	 return;
 }
 

//check for an option being passed to the theme
//to set the background colour
if (isset($_GET["bg"])){
	$bg = "#" . $_GET["bg"];
}


//css starts here
echo "
body { background: $bg; }
";

 ?>
 
