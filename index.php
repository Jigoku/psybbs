<?php 
/* psyBBS (index.php)
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


//check if we're logged in
if (isset($_SESSION["id"]) && checkUserExists($_SESSION["username"])) {
		if (isset($_GET["logout"])) { endSession(); }

		//navigational links and welcome message
		echo "\t\t<div id=\"userbar\">\n";
		//echo "\t\t\t$site_title<span class=\"bannerquote\">$site_notice</span><br /><span class=\"small\">Welcome <b>". $_SESSION["username"] ."</b></span>\n";
		echo "\t\t\t<span class=\"small\"><span class=\"shadow\">Welcome back <b>". $_SESSION["username"] ."</b></span></span>\n";
		echo "\t\t\t<span class=\"right\">\n";

		if (getAccountLevel() == 3) { echo "\t\t\t\t<a class=\"userbar\" href=\"settings.php\">settings</a> &brvbar; \n"; }
		echo "\t\t\t\t<a class=\"userbar\" href=\"" . $_SERVER["PHP_SELF"] . "\">topics</a>\n";
		echo "\t\t\t\t<a class=\"userbar\" href=\"" . $_SERVER["PHP_SELF"] . "?account\">account</a>\n";
		echo "\t\t\t\t<a class=\"userbar\" href=\"" . $_SERVER["PHP_SELF"] . "?stats\">stats</a>\n";
		echo "\t\t\t\t<a class=\"userbar\" href=\"search.php\">search</a>\n";
		echo "\t\t\t\t<a class=\"userbar\" href=\"" . $_SERVER["PHP_SELF"] . "?logout\">logout</a>\n";
		echo "\t\t\t</span>\n";
		echo "\t\t</div>\n\n";
		
		 //announcements (if enabled)..
		if (checkAnnounceEnabled()) {
			echo "<div id=\"announce\">".getMysqlStr("site_announce", "global")."</div>";
		}

		if (!(isset($_GET["topic"]))) {

			if (array_keys($_GET) === array('account')) {
				//show profile information per active account
				showAccount();

			} elseif (array_keys($_GET) === array('account', 'password')) {
				changePasswordPrompt();
			
			} elseif (array_keys($_GET) === array('changepassword')) {
				if (isset($_POST["currentpassword"]) && isset($_POST["newpassword"])) {
					setNewPassword(
						mEscape($_POST["currentpassword"]), 
						mEscape($_POST["newpassword"])
					);
				}
				
			} elseif (array_keys($_GET) === array('stats')) {
				//show server (forum) stats
				showStats();

			/**** ADMIN ****/
			//main page
			} elseif (array_keys($_GET) === array('admin') && getAccountLevel() == 3) {
				showSettings();
			//delete post
			} elseif (array_keys($_GET) === array('deletepost') && getAccountLevel() == 3) {
				delReply(mEscape($_GET["deletepost"]));
			//delete thread
			} elseif (array_keys($_GET) === array('deletethread') && getAccountLevel() == 3) {
				delThread(mEscape($_GET["deletethread"]));
			//add topic
			} elseif (array_keys($_GET) === array('admin', 'addtopic') && isset($_POST["title"], $_POST["pagename"], $_POST["description"]) && getAccountLevel() == 3) {
				addTopic(
					mEscape($_POST["title"]),
					mEscape($_POST["pagename"]),
					mEscape($_POST["description"])
				);
			/**** END ADMIN ****/

			} else {
				//list the topics
				listTopics();

			}


		} else {

			//post a new thread
			if(array_keys($_GET) === array('topic', 'newthread') && isset($_POST["subject"], $_POST["body"])) {
				if (checkTopicExists(mEscape($_GET["topic"]))) {
					createThread(
						mEscape($_GET["topic"]),
						mEscape($_POST["subject"]),
						mEscape($_POST["body"]),
						mEscape($_SESSION["username"])
					);
				} else {
					echo "<div class=\"sub\"><span class=\"large2\">Topic does not exist!</span></div>\n";
				}
			}

			if(array_keys($_GET) === array('topic', 'id', 'reply') && isset($_POST["body"]) && !(mEscape($_GET["id"])) == 0) {
				//add reply to database (posts)
				if (checkThreadExists(mEscape($_GET["id"]))) {
					createReply(
						mEscape($_SESSION["username"]),
						mEscape($_GET["id"]),
						mEscape($_GET["topic"]),
						mEscape($_POST["body"])
					);
				} else {
					echo "<div class=\"sub\"><span class=\"large2\">Thread does not exist!</span></div>\n";
				}

			} elseif (array_keys($_GET) === array('topic', 'newthread')) {
				if (checkTopicExists(mEscape($_GET["topic"]))) {

				//show the form for a new thread
				        echo "<div class=\"sub\">\n";
				                echo "<form class=\"\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?topic=" .$_GET["topic"] . "&amp;newthread\">\n";
				                echo "<label>Subject</label> <input type=\"text\" size=\"60\" maxlength=\"60\" name=\"subject\"><br />\n";
				                echo "<label>Message</label><br /><textarea style=\"width:100%\" cols=\"50\" rows=\"12\" name=\"body\"></textarea><br />\n";
				                echo "<input type=\"submit\" value=\"Post\" name=\"submit\" class=\"button\">\n";
				                echo "</form>\n";
				        echo "</div>\n";
				} else {
					echo "<div class=\"sub\"><span class=\"large2\">Topic does not exist!</span></div>\n";
				}

			} elseif (array_keys($_GET) === array('topic', 'id','page') && (mEscape($_GET["id"])) > 0 ) {
				//list replies to a thread
				if (checkTopicExists(mEscape($_GET["topic"]))) {
					listReplies(
						mEscape($_GET["topic"]),
						mEscape($_GET["id"])
					);
				} else {
					echo "<div class=\"sub\"><span class=\"large2\">Topic does not exist!</span></div>\n";
				}

			} else {
				if (checkTopicExists(mEscape($_GET["topic"]))) {
					//list the threads in selected topic
					listThreads(mEscape(strtolower($_GET["topic"])));
				} else {
					echo "<div class=\"sub\"><span class=\"large2\">Topic does not exist!</span></div>\n";
				}
			}

		}



} else {

	echo "<div id=\"userbar\"><a class=\"userbar\" href=\"login.php\">login</a> &brvbar; <a class=\"userbar\" href=\"create.php\">register</a>";
	echo "<span class=\"right\"><a class=\"userbar\" href=\"info.php\">?</a></span></div>\n";

	echo "<div class=\"sub\"><img class=\"splashimg\" src=\"theme/".getMysqlStr("theme", "global")."/splash.png\" />";
	echo "<h1 class=\"large3\">".getMysqlStr("site_title", "global")."</h1><br /></div>";
}


include "include/bottom.php";
?>


