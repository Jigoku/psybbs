<?php
/* psyBBS (login.php)
 * This file handles user sessions
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

include "include/top.php";
//session_start();

if (!(isset($_SESSION["id"]))) {

if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["code"])) {
	$user = mEscape(strtolower($_POST["username"]));
	$pass = $_POST["password"];

	if(!($_POST['code'] == @$_SESSION['code'])) {
		$errormsg = "Security code was wrong.";
		include "include/error.php";
		session_destroy();
		exit;
	}

	if (!(preg_match('#([a-zA-Z0-9]+)#is', $user))) {
		$errormsg = "Username can only contain alphanumerical symbols.";
		include "include/error.php";
		session_destroy();
		exit;
	}

	if (checkUserLocked($user)) {
		$errormsg = "Your account is locked.";
		include "include/error.php";
		session_destroy();
		exit;
	}


	if (compareLogin($user, $pass)) {
		echo "<div class=\"sub\">Logging in as <b>". $_SESSION["username"] . "</b>...<img class=\"right\"src=\"theme/".getMysqlStr("theme", "global")."/loading.gif\" /></div>\n";
		//echo "<div id=\"sub\"><img src=\"media/splash.png\" class=\"splash.png\" /></div>";
		echo "<meta http-equiv=\"refresh\" content=\"2;url=index.php\">\n";
	} else {
		$errormsg = "Login Failed.";
        include "include/error.php";
        session_destroy();
		exit;
	}


} else {
	include 'include/userbar.php';

        echo "<div class=\"sub\">\n";
                echo "<form class=\"create\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\">\n";
                echo "<label class=\"account\">Username</label> <input type=\"text\" size=\"12\" maxlength=\"20\" name=\"username\"><br />\n";
                echo "<label class=\"account\">Password</label> <input type=\"password\" size=\"12\" maxlength=\"40\" name=\"password\"><br />\n";

                echo "<label class=\"account\"><img class=\"captcha\" src=\"./scripts/captcha.php\"></label>\n";
                echo "<span class=\"small\">Please enter the captcha:</span>\n";
                echo "<br />\n";
                echo "<input type=\"text\" size=\"12\" name=\"code\"><br />\n";
                echo "<br />\n";
                echo "<input type=\"submit\" value=\"login\" name=\"submit\" class=\"button\">\n";
                echo "</form>\n";
        echo "</div>\n";


}

} else {

        echo "<div class=\"sub\">You appear to be logged in. Wrong? Try deleting cookies... or [ <a href=\"" . $_SERVER["PHP_SELF"] . "?logout\">logout</a> ]</div>";
        if (isset($_GET["logout"])) { endSession(); }

}
include "include/bottom.php";

?>
