<?php 
/* psyBBS (settings.php)
 * This file is used for administering psyBBS
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
if (isset($_SESSION["id"]) && checkUserExists($_SESSION["username"]) && getAccountLevel() == 3) {
		echo "<div class=\"sub\">\n";
		/**** ADMIN ****/

		//create topic
		if (array_keys($_GET) === array('createtopic')) {
					echo "<div class=\"sub\"><span class=\"large2\">Create Topic</span><hr /></div>\n";
					echo "<div class=\"sub\">\n";
							echo "<form class=\"\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?createtopic&amp;do\">\n";
							echo "<label>Topic Title</label> <input type=\"text\" size=\"20\" maxlength=\"255\" name=\"title\"><br />\n";
							echo "<label>Topic Pagename</label> <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"pagename\"><br />\n";
							echo "<label>Topic Description</label> <input type=\"text\" size=\"20\" maxlength=\"255\" name=\"description\"><br />\n";
							echo "<input type=\"submit\" value=\"Create\" name=\"submit\" class=\"button\">\n";
							echo "</form>\n";
					echo "</div>\n";
		} elseif (array_keys($_GET) === array('createtopic', 'do') && isset($_POST["title"], $_POST["pagename"], $_POST["description"])) {
			addTopic(
				$_POST["title"],
				$_POST["pagename"],
				$_POST["description"]
			);

		//drop database / reset
		} elseif (array_keys($_GET) === array('dropdb')) {
			echo "<div class=\"sub\"><span class=\"large2\">Drop Database</span><hr /></div>\n";
			echo "<div class=\"sub\"><span class=\"large2\">WARNING: This will delete everything and restore to default configuration!</span></div>\n";
			echo "<div class=\"sub\">\n";
				echo "<form class=\"\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?dropdb&amp;do\">\n";
				echo "<label>Continue?</label><br />\n";
				echo "<input type=\"submit\" value=\"Accept\" name=\"submit\" class=\"button\">\n";
				echo "</form>\n";
			echo "</div>\n";
		} elseif (array_keys($_GET) === array('dropdb', 'do')) {
				mysql_query("DROP DATABASE " . $mysql_database);
				echo "<div class=\"sub\"><span class=\"large3\">Done!</span><hr />Clearing your session...</div>\n";
				echo "<meta http-equiv=\"refresh\" content=\"3;url=index.php?logout\">\n";
				
				
		//delete a topic
		} elseif (array_keys($_GET) === array('deletetopic')) {

			$result = mysql_query("SELECT * FROM topics");

			if (mysql_num_rows($result) == 0) {
				echo "<div class=\"sub\"><span class=\"large2\">There are no topics to delete!</span></div>\n";
			} else {
				echo "<div class=\"sub\"><span class=\"large2\">Delete Topic</span><hr /></div>\n";
				echo "<div class=\"sub\"><span class=\"large1\">WARNING: This will recursively delete all threads/posts in the selected topic!</span></div>\n";
				echo "<div class=\"sub\">\n";
					echo "<form class=\"\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?deletetopic&amp;do\">\n";
					echo "<label>Topic</label><br />\n";
					echo "<select name=\"pagename\">\n";
						while ($topic = mysql_fetch_array($result)) {
							echo "\t<option value=\"" . $topic["pagename"] . "\" />" . $topic["title"] . "</option>\n";
						}
					echo "</select>\n";
					echo "<input type=\"submit\" value=\"Accept\" name=\"submit\" class=\"button\">\n";
					echo "</form>\n";
				echo "</div>\n";

			}

		} elseif (array_keys($_GET) === array('deletetopic', 'do') && isset($_POST["pagename"])) {
			delTopic(
				mEscape($_POST["pagename"])
			);

		//edit topic
		} elseif (array_keys($_GET) === array('edittopic')) {
			echo "<div class=\"sub\"><span class=\"large2\">Edit Topic</span><hr /></div>\n";
			echo "TODO: not implemented";


		//user control
		} elseif (array_keys($_GET) === array('lockuser')) {
			echo "<div class=\"sub\"><span class=\"large2\">Lock User</span><hr /></div>\n";

		//user control
		} elseif (array_keys($_GET) === array('announce_on')) {
			enableAnnouncement();
		} elseif (array_keys($_GET) === array('announce_off')) {
			disableAnnouncement();

		} elseif (array_keys($_GET) === array('deluser')) {
			echo "<div class=\"sub\"><span class=\"large2\">Delete User</span><hr /></div>\n";

			$result = mysql_query("SELECT * FROM users");

			if (mysql_num_rows($result) == 0) {
				die(); // we should have at least one user, to make the request..... derp
			} else {
				echo "<div class=\"sub\"><span class=\"large1\">WARNING: This will delete all posts and threads started by the user</span></div>\n";
				echo "<div class=\"sub\">\n";
					echo "<form class=\"\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?deluser&amp;do\">\n";
					echo "<label>User</label><br />\n";
					echo "<select name=\"username\">\n";
						while ($user = mysql_fetch_array($result)) {
							echo "\t<option value=\"" . $user["username"] . "\" />" . $user["username"] . 
									" id(" .$user["id"] . ") level(". $user["level"] .")</option>\n";
						}
					echo "</select>\n";
					echo "<input type=\"submit\" value=\"Accept\" name=\"submit\" class=\"button\">\n";
					echo "</form>\n";
				echo "</div>\n";

			}
		} elseif (array_keys($_GET) === array('deluser', 'do') && isset($_POST["username"])) {
			delUser(
				mEscape($_POST["username"])
			);



		} elseif (array_keys($_GET) === array('moduser')) {
			echo "<div class=\"sub\"><span class=\"large2\">Modify User</span><hr /></div>\n";


		} elseif (array_keys($_GET) === array('setboardname')) {
			setGlobalStr(
				"site_name", $_POST["boardname"]
			);
			
		} elseif (array_keys($_GET) === array('setboardquote')) {
			setGlobalStr(
				"site_quote", $_POST["boardquote"]
			);
			
		} elseif (array_keys($_GET) === array('setboardannouncement')) {
			setGlobalStr(
				"site_announce", $_POST["boardannouncement"]
			);
			
		} elseif (array_keys($_GET) === array('setboardtitle')) {
			setGlobalStr(
				"site_title", $_POST["boardtitle"]
			);
			
		} elseif (array_keys($_GET) === array('settheme')) {
			setGlobalStr(
				"theme", $_POST["theme"]
			);
			
		} elseif (array_keys($_GET) === array('setthemeopts')) {
			setGlobalStr(
				"themeopts", $_POST["themeopts"]
			);
			
			
		} elseif (array_keys($_GET) === array('setpagelimitthreads')) {
			setGlobalStr(
				"page_limit_threads", $_POST["page_limit_threads"]
			);
			
		} elseif (array_keys($_GET) === array('setpagelimitposts')) {
			setGlobalStr(
				"page_limit_posts", $_POST["page_limit_posts"]
			);
			
			
		} else {
			echo "<div class=\"sub\">";
		
			echo "<div class=\"info\"><img src=\"theme/".getMysqlStr("theme", "global")."/icon/settings.png\" /><span class=\"large4\">Board Settings</span><span class=\"right\"><a href=\"index.php\">Exit Settings</a></span></div>\n";
			echo "<hr />\n";

			//board title
			echo "<div class=\"info\">Board Title<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setboardtitle\">\n";
			echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"boardtitle\" value=\"".htmlspecialchars(getMysqlStr("site_title","global"))."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";

			//board name
			echo "<div class=\"info\">Board Name<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setboardname\">\n";
			echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"boardname\" value=\"".getMysqlStr("site_name","global")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";
		
			//board quote
			echo "<div class=\"info\">Board Quote<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setboardquote\">\n";
			echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"boardquote\" value=\"".getMysqlStr("site_quote","global")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";
		
			//board announcement
			echo "<div class=\"info\">Board Announcement [ <a href=\"". $_SERVER["PHP_SELF"] ."?announce_on\">on</a>".
													" | <a href=\"". $_SERVER["PHP_SELF"] ."?announce_off\">off</a> ]\n";
													
			echo "<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setboardannouncement\">\n";
			echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"boardannouncement\" value=\"".getMysqlStr("site_announce","global")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";							
		
			echo "<div class=\"info\"><img src=\"theme/".getMysqlStr("theme", "global")."/icon/theme.png\" /><span class=\"large4\">Theme Settings</span>\n";	
			echo "<hr />\n";
			
			//theme name
			echo "<div class=\"info\">Theme Name<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?settheme\">\n";
			echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"theme\" value=\"".getMysqlStr("theme","global")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";
			
			echo "<div class=\"info\">Theme Options<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setthemeopts\">\n";
			echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"themeopts\" value=\"".getMysqlStr("themeopts","global")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";
													
		/*	echo "<div class=\"info\">Board Rules [edit]</div>\n";
			echo "<div class=\"info\">Board Lockdown [public|private|locked]</div>\n";
			echo "<div class=\"info\">Board Logo [image|disable]</div>\n";
			echo "<div class=\"info\">Board splash [image|disable]</div>\n";
			echo "<div class=\"info\">Login Captcha [on|off]</div>\n";
			echo "<div class=\"info\">Post Captcha [on|off]</div><br />\n";*/
		
			echo "<div class=\"info\"><img src=\"theme/".getMysqlStr("theme", "global")."/icon/topics.png\" /><span class=\"large4\">Topic Control</span></div>\n";
			echo "<hr />\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?createtopic\">Create Topic</a></div>\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?edittopic\">Edit Topic</a></div>\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?deletetopic\">Delete Topic</a></div><br />\n";
		
			echo "<div class=\"info\">Threads per page<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setpagelimitthreads\">\n";
			echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"page_limit_threads\" value=\"".getMysqlStr("page_limit_threads","global")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";
			
			echo "<div class=\"info\">Posts per page<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setpagelimitposts\">\n";
			echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"page_limit_posts\" value=\"".getMysqlStr("page_limit_posts","global")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";
		
			echo "<div class=\"info\"><img src=\"theme/".getMysqlStr("theme", "global")."/icon/database.png\" /><span class=\"large4\">Database</span></div>\n";
			echo "<hr />\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?dropdb\">Delete database</a></div><br />\n";
		
			echo "<div class=\"info\"><img src=\"theme/".getMysqlStr("theme", "global")."/icon/users.png\" /><span class=\"large4\">User Control</span></div>\n";
			echo "<hr />\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?lockuser\">Lock User</a></div>\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?deluser\">Delete User</a></div>\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?moduser\">Modify User</a></div>\n";

			echo "</div>";
			
			
		}
		
		echo "</div>\n";


} else {
	echo "<div class=\"sub\"><span class=\"large2\">Access Denied</span></div>\n";
}


include "include/bottom.php";
?>

