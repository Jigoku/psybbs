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
include 'include/top.php';
include 'include/userbar.php';

//check if we're logged in
if (isset($_SESSION["id"]) && checkUserExists($_SESSION["username"]) && getAccountLevel() == 3) {
	
	
	
		echo "<div class=\"sub\">\n";
		/**** ADMIN ****/

		//create topic
		if (array_keys($_GET) === array('createtopic')) {
					echo "<div class=\"sub\"><span class=\"large2\">Create Topic</span><hr /></div>\n";
					echo "<div class=\"sub\">\n";
							echo "<form class=\"\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?createtopic&amp;do\">\n";
							echo "<label class=\"createtopic\">Topic Title</label> <input type=\"text\" size=\"30\" maxlength=\"255\" name=\"title\"><br />\n";
							echo "<label class=\"createtopic\">Topic Pagename</label> <input type=\"text\" size=\"30\" maxlength=\"30\" name=\"pagename\"><br />\n";
							echo "<label class=\"createtopic\">Topic Description</label> <input type=\"text\" size=\"30\" maxlength=\"255\" name=\"description\"><br />\n";
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
				echo "<meta http-equiv=\"refresh\" content=\"2;url=index.php?logout\">\n";
				
				
		//delete a topic
		} elseif (array_keys($_GET) === array('deletetopic')) {

			$result = mysql_query("SELECT * FROM topics");

			if (mysql_num_rows($result) == 0) {
				echo "<div class=\"sub\"><span class=\"large2\">Error</span><hr />There are no topics to delete!</div>\n";
				
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
			
			$result = mysql_query("SELECT * FROM users");
			echo "<div class=\"sub\">\n";
			echo "<form class=\"\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?lockuser&amp;do\">\n";
			echo "<label>User</label><br />\n";
			echo "<select name=\"username\">\n";
			
			while ($user = mysql_fetch_array($result)) {
				echo "\t<option value=\"" . $user["username"] . "\" />" . $user["username"] . 
						" id(" .$user["id"] . ") level(". $user["level"] .") locked(".$user["locked"].")</option>\n";
			}
			
			echo "</select>\n";
			echo "<input type=\"submit\" value=\"Toggle Lock\" name=\"submit\" class=\"button\">\n";
			echo "</form>\n";
			echo "</div>\n";
			
		//user control
		} elseif (array_keys($_GET) === array('announce_on')) {
			enableAnnouncement();
		} elseif (array_keys($_GET) === array('announce_off')) {
			disableAnnouncement();

		} elseif (array_keys($_GET) === array('deluser')) {
			echo "<div class=\"sub\"><span class=\"large2\">Delete User</span><hr /></div>\n";
			echo "<div class=\"sub\"><span class=\"large1\">WARNING: This will delete all posts and threads started by the user</span></div>\n";
			
			$result = mysql_query("SELECT * FROM users");
			echo "<div class=\"sub\">\n";
			echo "<form class=\"\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?deluser&amp;do\">\n";
			echo "<label>User</label><br />\n";
			echo "<select name=\"username\">\n";
			
			while ($user = mysql_fetch_array($result)) {
				echo "\t<option value=\"" . $user["username"] . "\" />" . $user["username"] . 
						" id(" .$user["id"] . ") level(". $user["level"] .")</option>\n";
			}
			
			echo "</select>\n";
			echo "<input type=\"submit\" value=\"Delete User\" name=\"submit\" class=\"button\">\n";
			echo "</form>\n";
			echo "</div>\n";

		} elseif (array_keys($_GET) === array('deluser', 'do') && isset($_POST["username"])) {
			delUser(
				mEscape($_POST["username"])
			);
			
		} elseif (array_keys($_GET) === array('lockuser', 'do') && isset($_POST["username"])) {
			toggleUserLock(
				mEscape($_POST["username"])
			);



		} elseif (array_keys($_GET) === array('moduser')) {
			echo "<div class=\"sub\"><span class=\"large2\">Modify User</span><hr /></div>\n";


		} elseif (array_keys($_GET) === array('setboardname')) {
			setMysqlStr(
				"site_name", $_POST["boardname"], "global"
			);
			
		} elseif (array_keys($_GET) === array('setboardquote')) {
			setMysqlStr(
				"site_quote", $_POST["boardquote"], "global"
			);
			
		} elseif (array_keys($_GET) === array('setboardannouncement')) {
			setMysqlStr(
				"site_announce", $_POST["boardannouncement"], "global"
			);
			
		} elseif (array_keys($_GET) === array('setboardtitle')) {
			setMysqlStr(
				"site_title", $_POST["boardtitle"], "global"
			);
			
		} elseif (array_keys($_GET) === array('setdateformat')) {
			setMysqlStr(
				"date_format", $_POST["date_format"], "global"
			);			
			
		} elseif (array_keys($_GET) === array('settheme')) {
			setMysqlStr(
				"theme", $_POST["theme"], "global"
			);
			
		} elseif (array_keys($_GET) === array('setthemeopts')) {
			setMysqlStr(
				"themeopts", $_POST["themeopts"], "global"
			);
			
			
		} elseif (array_keys($_GET) === array('setpagelimitthreads')) {
			setMysqlStr(
				"page_limit_threads", $_POST["page_limit_threads"], "global"
			);
			
		} elseif (array_keys($_GET) === array('setpagelimitposts')) {
			setMysqlStr(
				"page_limit_posts", $_POST["page_limit_posts"], "global"
			);
			
		} elseif (array_keys($_GET) === array('userlevels')) {
			echo "<div class=\"frame\"><span class=\"large2\">User Aliases</span><hr />\n";
			echo "<div class=\"info\">Admin (level 3)<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setlevel_admin\">\n";
			echo "<input type=\"text\" size=\"20\" maxlength=\"20\" name=\"level_admin\" value=\"".getMysqlStr("level_admin","user_settings")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";
			
			echo "<div class=\"info\">Moderator (level 2)<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setlevel_moderator\">\n";
			echo "<input type=\"text\" size=\"20\" maxlength=\"20\" name=\"level_moderator\" value=\"".getMysqlStr("level_moderator","user_settings")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";

			echo "<div class=\"info\">Member (level 1)<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setlevel_member\">\n";
			echo "<input type=\"text\" size=\"20\" maxlength=\"20\" name=\"level_member\" value=\"".getMysqlStr("level_member","user_settings")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";
			
			echo "<div class=\"info\">Banned (level 0)<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setlevel_banned\">\n";
			echo "<input type=\"text\" size=\"20\" maxlength=\"20\" name=\"level_banned\" value=\"".getMysqlStr("level_banned","user_settings")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";
			echo "</div>\n";
		} elseif (array_keys($_GET) === array('setlevel_admin')) {
			setMysqlStr(
				"level_admin", $_POST["level_admin"], "user_settings"
			);
		} elseif (array_keys($_GET) === array('setlevel_moderator')) {
			setMysqlStr(
				"level_moderator", $_POST["level_moderator"], "user_settings"
			);
		} elseif (array_keys($_GET) === array('setlevel_member')) {
			setMysqlStr(
				"level_member", $_POST["level_member"], "user_settings"
			);
		} elseif (array_keys($_GET) === array('setlevel_banned')) {
			setMysqlStr(
				"level_banned", $_POST["level_banned"], "user_settings"
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
			
			echo "<div class=\"info\">Date Format<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setdateformat\">\n";
			echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"date_format\" value=\"".getMysqlStr("date_format","global")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";					
		
			echo "<div class=\"info\"><img src=\"theme/".getMysqlStr("theme", "global")."/icon/theme.png\" /><span class=\"large4\">Theme Settings</span>\n";	
			echo "<hr />\n";
			
			//theme name
			echo "<div class=\"info\">Theme Name<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?settheme\">\n";
			echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"theme\" value=\"".getMysqlStr("theme","global")."\">\n";	
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
			echo "</form></div>\n";
			

			
			include 'theme/'.getMysqlStr("theme","global").'/theme.css.php';
													
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
		
			//user control
			echo "<div class=\"info\"><img src=\"theme/".getMysqlStr("theme", "global")."/icon/users.png\" /><span class=\"large4\">User Control</span></div>\n";
			echo "<hr />\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?lockuser\">Lock User</a></div>\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?deluser\">Delete User</a></div>\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?moduser\">Modify User</a></div>\n";
			echo "<div class=\"info\"><a href=\"". $_SERVER["PHP_SELF"] ."?userlevels\">User Aliases</a></div>\n";



			echo "</div>\n";
			
			
		}
		
		echo "</div>\n";


} else {
	echo "<div class=\"sub\"><span class=\"large2\">Access Denied</span></div>\n";
}


include "include/bottom.php";
?>

