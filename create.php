<?php
/* psyBBS (create.php)
 * This file handles account creation
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
ini_set('display_errors', 'on');

include "include/top.php";
if (!(isset($_SESSION["id"]))) {

	if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password2"]) && isset($_POST["code"])) {

		$user = mEscape($_POST["username"]);
		$pass = mEscape($_POST["password"]);
		$pass2 = mEscape($_POST["password2"]);


		if(!($_POST["code"] == @$_SESSION["code"])) {
			$errormsg = "Security code was wrong.";
			include "include/error.php";
			session_destroy();
			exit;
		}

		if (!(ctype_alnum($user))) {
			$errormsg = "Username can only contain alphanumerical symbols.";
			include "include/error.php";
			session_destroy();
			exit;
		}

		if (strlen($user) < 3) {
			$errormsg = "Username must be at least 3 characters.";
			include "include/error.php";
			session_destroy();
			exit;
		}

		if (strlen($pass) < 8) {
			$errormsg = "Password must be at least 8 characters.";
			include "include/error.php";
			session_destroy();
			exit;
		}
		
		if (!($pass == $pass2)) {
			$errormsg = "Passwords do not match.";
			include "include/error.php";
			session_destroy();
			exit;
		}


		if (checkUserExists($user)) {
			$errormsg = "Username already exists.";
			include "include/error.php";
			session_destroy();
			exit;
		}

		$pass = hash('sha1', $pass.$mysql_salt);

	    mysql_query("INSERT INTO users (username, password, epoch, level) VALUES ('$user', '$pass', '".time()."', 1)" , $connection);
		unset($_SESSION["code"]);

		echo "<div id=\"userbar\"><span class=\"small\"><span class=\"shadow\">Welcome ".$user."!</span></span></div>\n";
		
		/*
		echo "<div class=\"sub\">";
				showWelcome()  / showRules();
		echo "</div>";
		*/

	        echo "<div class=\"sub\"><span class=\"large2\">Account Created</span><hr />You may now <a href=\"login.php\">login</a></div>";

	} else {


	echo "<div class=\"sub\"><span class=\"large2\">Create account</span><hr /></div>\n";
	//echo "<div class=\"sub\"><div class=\"info\">Username must be alpha-numeric and 3-20 characters long.</div>";
	//echo "<div class=\"info\">Password must be 8-40 characters long.</div>";
	
                echo "<form class=\"create\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\">\n";
                echo "<label class=\"account\">Username</label> <input type=\"text\" size=\"12\" maxlength=\"20\" name=\"username\"><br />\n";
                echo "<label class=\"account\">Password</label> <input type=\"password\" size=\"12\" maxlength=\"40\" name=\"password\"><br />\n";
                echo "<label class=\"account\">Verify Password</label> <input type=\"password\" size=\"12\" maxlength=\"40\" name=\"password2\"><br />\n";

		echo "<label class=\"account\"><img class=\"captcha\" src=\"./scripts/captcha.php\"></label>\n";
		echo "<span class=\"small\">Please enter the captcha:</span>\n";
		echo "<br />\n";
		echo "<input type=\"text\" size=\"12\" name=\"code\"><br />\n";
		echo "<br />\n";
                echo "<input type=\"submit\" value=\"create account\" name=\"submit\" class=\"button\">\n";
                echo "</form>\n";
        echo "</div>\n";


	}

} else {
	echo "<div class=\"sub\">You appear to be logged in. Wrong? Try deleting cookies... or [ <a href=\"" . $_SERVER["PHP_SELF"] . "?logout\">logout</a> ]</div>";
        if (isset($_GET["logout"])) { endSession(); }
}
include "include/bottom.php";


?>
