<?php

/* psyBBS (func.php)
 * This file contains all the functions
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
ini_set('date.timezone', 'UTC');


	//short alias for escaping
	function mEscape($str) {
		return mysql_real_escape_string($str);
	}


	//get information for active user
	function showAccount() {
		echo "<div class=\"sub\"><span class=\"large2\">Account Information</span>\n\t<hr />\n";
		//echo "\t\t<img class=\"right\" src=\"media/avatar/".md5($_SESSION["username"]).".png\" class=\"avatar\" alt=\"\" />\n";
		
		//date of account creation
		$result = mysql_query("SELECT * FROM users WHERE id = '". $_SESSION["id"] . "'");
		$account = mysql_fetch_array($result);
		echo "\t<div class=\"info\">User since " .  date('M/d/Y', $account['epoch']) . "</div>\n";

		//show access level (as group name)
		echo "\t<div class=\"info\">Access Level <span class=\"hl\">";
		echo formatUserLevel(getAccountLevel());
		echo "</span>\n\t</div>\n";

		//user posts total
		$resultposts= mysql_query("SELECT * FROM posts WHERE author = '". $_SESSION["username"] ."'");
		$numposts = mysql_num_rows($resultposts);

		//user threads total
		$resultthreads= mysql_query("SELECT * FROM threads WHERE author = '". $_SESSION["username"] ."'");
		$numthreads = mysql_num_rows($resultthreads);

		echo "\t<div class=\"info\">You have posted <span class=\"hl\">" .  ($numposts - $numthreads) . "</span> replies.</div>\n";
		echo "\t<div class=\"info\">You have started <span class=\"hl\">" .  $numthreads . "</span> threads.</div>\n</div>\n";
		echo "\t<div class=\"sub\"><span class=\"large2\">Account Settings</span>\n\t<hr />\n";
		echo "\t<div class=\"info\"><a href=\"".$_SERVER["PHP_SELF"]."?account&amp;password\">Change Password</a></div>\n";
		//echo "\t<div class=\"info\"><a href=\"".$_SERVER["PHP_SELF"]."?account&amp;avatar\">Change Avatar</a></div>\n</div>\n";
		echo "</div>";

	}

	function changePasswordPrompt() {
		echo "<div class=\"sub\"><span class=\"large2\">Change Password</span><hr />\n";
			echo "<form class=\"\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?changepassword\">\n";
			echo "<label class=\"account\">Current password</label> <input type=\"password\" size=\"20\" maxlength=\"40\" name=\"currentpassword\"><br />\n";
			echo "<label class=\"account\">New password</label> <input type=\"password\" size=\"20\" maxlength=\"40\" name=\"newpassword\"><br />\n";
			echo "<label class=\"account\">Verify new password</label> <input type=\"password\" size=\"20\" maxlength=\"40\" name=\"newpassword2\"><br />\n";
			echo "<input type=\"submit\" value=\"Update\" name=\"submit\" class=\"button\">\n";
			echo "</form>\n";
		echo "</div>\n";
		
	}
	
	function changeAvatarPrompt() {
		echo "<div class=\"sub\"><span class=\"large2\">Change Avatar</span><hr />\n";
		
		echo "<form enctype=\"multipart/form-data\" action=\"".$_SERVER["PHP_SELF"]."?account&amp;avatar\" method=\"post\">
				<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000000\" />
				Supported filetypes [png] <br /><input name=\"upload\" type=\"file\" />
				<input type=\"submit\" value=\"Upload\" />
				</form> ";
		
		echo "</div>\n";
		
	}
	
	
	
	function setNewPassword($currentpassword, $newpassword, $newpassword2) {
		include 'config.php';
		if ($newpassword == $newpassword2) {
			if (compareLogin($_SESSION["username"], $currentpassword)) {
				mysql_query("UPDATE users SET password='".hash('sha1', $newpassword.$mysql_salt)."' WHERE username='".$_SESSION["username"]."'");
				
				echo "<div class=\"sub\"><span class=\"large2\">Success!</span><hr />Your password has been updated. Redirecting...</div>\n";
				echo "<meta http-equiv=\"refresh\" content=\"2;url=index.php?account\">\n";
	
			} else {
				echo "<div class=\"sub\"><span class=\"large2\">Error</span><hr />Password does not match!</span>\n";
			}
		} else {
			echo "<div class=\"sub\"><span class=\"large2\">Error</span><hr />Password does not match!</span>\n";
		}
	}

	//get information about the database
	function showStats() {
		echo "<div class=\"sub\"><span class=\"large2\">Forum Stats</span>\n";
		echo "\t<hr />\n";

		//total topics
		$result= mysql_query("SELECT * FROM topics");
		$rows = mysql_num_rows($result);
		echo "\t<div class=\"info\">There are a total of <span class=\"hl\">" .  $rows . "</span> topics</div>\n";

		// total threads
		$result= mysql_query("SELECT * FROM threads");
		$rows = mysql_num_rows($result);
		echo "\t<div class=\"info\">There are a total of <span class=\"hl\">" .  $rows . "</span> threads</div>\n";

		//total replies/posts
		$result= mysql_query("SELECT * FROM posts");
		$rows = mysql_num_rows($result);
		echo "\t<div class=\"info\">There are a total of <span class=\"hl\">" .  $rows . "</span> posts</div>\n";

		//total replies/posts
		$result= mysql_query("SELECT * FROM users");
		$rows = mysql_num_rows($result);
		echo "\t<div class=\"info\">There are <span class=\"hl\">" .  $rows . "</span> users</div>\n";
		echo "</div>\n";

	}



	//delete a topic and all associated posts
	function delTopic($pagename) {
		//todo cleanup posts/threads along with it
		mysql_query("DELETE FROM topics WHERE pagename = '". $pagename ."'");

		//delete posts under threads filed in "topic"
		$result = mysql_query("SELECT * FROM threads WHERE topic = '". $pagename ."'");
		while ($thread = mysql_fetch_array($result)) {
			mysql_query("DELETE FROM posts WHERE threadid = '". $thread["id"] ."'");
		}

		//delete the threads too
		mysql_query("DELETE FROM threads WHERE topic = '". $pagename ."'");
		header("Location: " . $_SERVER["PHP_SELF"]);

	}

	function delUser($username) {
		mysql_query("DELETE FROM users WHERE username = '". $username ."'");
		mysql_query("DELETE FROM posts WHERE author = '". $username ."'");
		mysql_query("DELETE FROM threads WHERE author = '". $username ."'");
		header("Location: " . $_SERVER["PHP_SELF"]);

	}

	function enableAnnouncement() {
		mysql_query("UPDATE global SET announce='Y'");
		header("Location: " . $_SERVER["PHP_SELF"]);
	}
	
	function disableAnnouncement() {
		mysql_query("UPDATE global SET announce='N'");
		header("Location: " . $_SERVER["PHP_SELF"]);
	}
	
	//delete a post from a thread
	function delReply($postid) {
		mysql_query("DELETE FROM posts WHERE id = '". $postid ."'");
		header("Location: " . $_SERVER["PHP_SELF"]);
	}

	//delete a thread (and all associated posts)
	function delThread($threadid) {
		mysql_query("DELETE FROM threads WHERE id = '". $threadid ."'");
		mysql_query("DELETE FROM posts WHERE threadid = '". $threadid ."'");
		header("Location: " . $_SERVER["PHP_SELF"]);

	}

	function addTopic($title, $pagename, $description) {
	        if (preg_match('#([a-z0-9]+)#is', $pagename) && !(checkTopicExists($pagename)) ) {

			$title = mEscape($title);
			$pagename = mEscape($pagename);
			$description = mEscape($description);

		        mysql_query("
				INSERT INTO topics (title, pagename, description)
				VALUES ('".$title."', '".strtolower($pagename)."', '".$description."')
			");

			header("Location: " . $_SERVER["PHP_SELF"]);

		} else {
			echo "<div class=\"sub\"><span class=\"large2\">Failed to create topic. Does it exist already? Is pagename a-z0-9?</span></div>";
		}

	}


	function toggleUserLock($username) {
		$result = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE username = '".$username."'"));
		if ($result["locked"] == "N") {
			mysql_query("UPDATE users SET locked='Y' WHERE username = '".$username."'");
		} else {
			mysql_query("UPDATE users SET locked='N' WHERE username = '".$username."'");
		}
		
		header("Location: " . $_SERVER["PHP_SELF"] . "?lockuser");
	}

	function checkUserLocked($username) {
		$result = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE username = '".$username."'"));
		if ($result["locked"] == "Y") {
			return true;
		}

	}

	function createThread($topic, $subject, $message, $author) {

		if (!(empty($subject)) && !(empty($message))) {
			//remove html
			$subject = strip_tags($subject);
			$message = strip_tags($message);

			$epoch = time();

			//add thread
			mysql_query("
				INSERT INTO threads (topic, subject, author, epoch, sticky, locked, lastpostepoch)
				VALUES ('" . $topic . "','" . $subject ."','". $author ."','" . $epoch ."','0','0', '". $epoch ."')
			");

			//get the new threadid
			$thread =  mysql_fetch_array(mysql_query("SELECT id FROM threads ORDER BY epoch DESC LIMIT 1"));

			//add first post to the thread
			mysql_query("
				INSERT INTO posts (author, threadid, epoch, content)
				VALUES ('" . $author . "','".$thread["id"]."','". $epoch."','". $message . "')
			");

			//redirect to new thread
			header("Location: " . $_SERVER["PHP_SELF"] . "?topic=" . $topic .  "&id=" . $rows . "&page=1");
		} else {
			echo "<div class=\"sub\"><p>Subject and/or message cannot be empty.</p></div>";
		}
	}

	function createReply($author, $threadid, $topic, $message) {

		if (checkTopicExists($topic) && checkThreadExists($threadid)) {
			//remove html
			$message = strip_tags($message);

			$epoch = time();

			//add reply
			mysql_query("
				INSERT INTO posts (author, threadid, epoch, content)
				VALUES ('" . $author . "','" . $threadid ."','". $epoch."','". $message . "')
			");

			//update latest post epoch
			mysql_query("
				UPDATE threads SET lastpostepoch = '".$epoch."' WHERE id = '".$threadid."'
			");

			//redirect to same page
			header("Location: " . $_SERVER["PHP_SELF"] . "?topic=" . $topic .  "&id=" . $threadid . "&page=1");

		} else {

			echo "<div class=\"sub\"><span class=\"large2\">Thread does not exist!</span></div>";

		}

	}


	// display and format sorted items for table 'topics' with page split
	function listTopics() {

		$sql = "SELECT * FROM topics ORDER by title ASC"; 
		$result = mysql_query($sql);


		if (mysql_num_rows($result) > 0) {

			while ($topic = mysql_fetch_assoc($result)) {

				echo "\n<a class=\"topic\" href=\"?topic=" . $topic['pagename'] . "\">\n";
					echo "\t\t<img class=\"topicimg\" src=\"theme/".getMysqlStr("theme", "global")."/icon/folder.png\" alt=\"\" />\n";
					//echo "\t\t<img class=\"topicimg\" src=\"media/glyphs/" .$topic['pagename'] . ".png\"/>\n";
					echo "\t\t<span class=\"topictitle\">" .$topic['title']."</span>\n";
					echo "\t\t<span class=\"lastpost\">" . getLatestPost($topic['pagename']) ."</span>\n";
					echo "\t\t<br />\n";
					echo "\t\t<span class=\"topicdescription\">" . $topic['description']. "</span>\n";
					echo "\t\t<span class=\"postcount\">" . countTopics($topic['pagename']) . "&nbsp;threads</span>\n";
				echo "</a>\n";
		  	}

		} else {
			echo "<div class=\"sub\"><span class=\"large2\">There are no topics!</span></div>";
		}
	}



	

	function getMysqlStr($column, $table) {
		$sql = "SELECT ".$column." FROM ".$table."";
		$result = mysql_fetch_assoc(mysql_query($sql));
		return $result[$column];
	}
	
	function setMysqlStr($row, $str, $table) {
		mysql_query("UPDATE ".$table." SET ".$row."='".mEscape($str)."'");
		header("Location: " . $_SERVER["PHP_SELF"]);
	}


	function getCurrentPage() {
		if (isset($_GET["page"]) && is_numeric($_GET["page"]) ) { 
			if ($_GET["page"] <= 0) { $page = 1; } else { $page = $_GET["page"]; }
		} else { 
			$page = 1; 
		}
		return $page;
	}

	function listReplies($topic, $id) {

		
		$items_per_page = getMysqlStr('page_limit_posts', 'global');

		$sql = "SELECT COUNT(id) FROM posts WHERE threadid = '".$id."'";
		$result = mysql_query($sql);
		$row = mysql_fetch_row($result);
		$total_items = $row[0];
		$total_pages = ceil($total_items / $items_per_page);
	
		$page = getCurrentPage();
	
		$start_from = ($page-1) * $items_per_page;
		
		$result = mysql_query("SELECT author, subject, epoch FROM threads WHERE id = '" . $id."'" )
			or trigger_error(mysql_error());

		//make sure thread exists
		if (mysql_num_rows($result) > 0) {
			$subject = mysql_fetch_array($result);
			
			//check access level for additional functions
			if (getAccountLevel() == 3) {
				echo "<div class=\"threadopts\">Thread Options | <a href=\"?deletethread=". $id ."\">delete thread</a> &brvbar; lock &brvbar; sticky &brvbar; edit</div>"; 
			}
			
			echo "\t<div id=\"threadtitle\">Subject: <span class=\"title\">". $subject["subject"]."</span></div>\n";


			//get and format the replies to the thread

			$result = mysql_query("SELECT * FROM posts WHERE threadid = '" . $id."' ORDER by epoch ASC LIMIT $start_from, ". $items_per_page)
				or trigger_error(mysql_error());
			
			if ($start_from >= 0 && mysql_num_rows($result) > 0) {
				showPageNav($total_items, $total_pages, $items_per_page, "index.php?topic=" . $topic."&amp;id=$id");

				while ($post = mysql_fetch_array($result)) {
					if (getAccountLevel() > 1) { echo "<div class=\"threadopts\">Post Options | <a href=\"?deletepost=". $post["id"] ."\">delete post</a> &brvbar; edit</div>\n"; }

					//get user level
					$result2 = mysql_query("SELECT level FROM users WHERE username= '" . $post["author"] ."'")
						or trigger_error(mysql_error());
					
					while ($user = mysql_fetch_array($result2)) {
						$level = $user["level"];
					}
					//end user level

					formatPost($post["author"], $post["content"], $post["epoch"], $level, $post["id"]);
				}
				
                        //show the reply form below
                        echo "<div class=\"sub\">\n";
                         showBBinfo();
                                 echo "<form class=\"post\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?topic=" .$_GET["topic"] . "&amp;id=" . $_GET["id"] . "&amp;reply\">\n";
                                 echo "<label id=\"maxchars\">5000</label> remaining characters.";
                                 echo "<br /><textarea id=\"message\" style=\"width:100%\" cols=\"50\" rows=\"12\" onkeyup=\"maxChars(this,5000,'maxchars');\" name=\"body\"></textarea><br />\n";
                                 echo "<input type=\"submit\" value=\"Post Reply\" name=\"submit\" class=\"button\" />\n";
                                 echo "</form>\n";
                        echo "</div>\n";
                        
				showPageNav($total_items, $total_pages, $items_per_page, "index.php?topic=" . $topic."&amp;id=$id");
			} else { 
				echo "<div class=\"sub\"><span class=\"large2\">Page does not exist!</span></div>";
			}
		} else {
			//invalid thread
			echo "<div class=\"sub\"><span class=\"large2\">Thread does not exist!</span></div>";
		}
	}

	function getLatestPost($topic) {
		//BROKEN FIXME
	/*	$sql = "SELECT * FROM posts WHERE threadid = '".$topicid."' ORDER by epoch DESC LIMIT 1";
		$result = mysql_query($sql);
		
		if (mysql_num_rows($result) > 0) {
			while ($post = mysql_fetch_assoc($result)) {
				//latest post
				$author =  $post['author'];
				$postid =  $post['id'];
				$threadid = $post['threadid'];	
			}

		}
	*/
		$sql = "SELECT * FROM threads WHERE topic = '".$topic."' ORDER by lastpostepoch DESC LIMIT 1";
		$result = mysql_query($sql);
		
		if (mysql_num_rows($result) > 0) {
			while ($thread = mysql_fetch_assoc($result)) {
				$subject = $thread['subject'];
			}
		} else {
			return "Active thread: N/A";
		}
		
			//BROKEN FIXME
		//return "<a href=\"index.php?topic=".$topic."&amp;id=".$topicid."&amp;page=1\">$subject</a>";

		return "Active thread: \"" . $subject. "\"";
	}

	function showPageNav($total_items, $total_pages, $items_per_page, $href) {
		if ($total_items > $items_per_page) {
			echo "<div class=\"pagenav\"><span class=\"small\">Page:</span>\n";
			
			for ($i=1; $i<=$total_pages; $i++) {
				echo "<a class=\"pagebutton\" href=\"".$href."&amp;page=$i\">$i</a>&nbsp;";
			}
			
			echo "</div>\n";
		}
	}

	// display and format items for table 'threads' with page split
	function listThreads($topic) {
		
	$items_per_page = getMysqlStr('page_limit_threads', 'global');
		
	$sql = "SELECT COUNT(id) FROM threads WHERE topic = '".$topic."'";
	$result = mysql_query($sql);
	$row = mysql_fetch_row($result);
	$total_items = $row[0];
	$total_pages = ceil($total_items / $items_per_page);

	$page = getCurrentPage();

		$start_from = ($page-1) * $items_per_page;
		$sql = "SELECT * FROM threads WHERE topic = '".$topic."' ORDER by lastpostepoch DESC LIMIT $start_from, ". $items_per_page; 
		$result = mysql_query($sql);

	
		echo "<div class=\"topicitem\">Category: <span class=\"title\">" . getTopicTitle($topic) . "</span>" .
					"<span class=\"newthread\"><a href=\"?topic=". $topic ."&amp;newthread\">New Thread</a></span></div>";
		
		if (mysql_num_rows($result) > 0) {
			while ($thread = mysql_fetch_assoc($result)) {
						echo "<a class=\"threaditem\" href=\"?topic=" . $topic ."&id=" .
						$thread['id'] ."&page=1\"><img class=\"thread\" src=\"theme/".getMysqlStr("theme", "global")."/icon/thread.png\"/><div class=\"threaditem\"><span class=\"subject\">". $thread['subject'] .
						"</span><span class=\"author\"> - by " . $thread['author'] .
						"</span><span class=\"right\"><span class=\"replies\">(". countReplies($thread['id']) .
						" replies)</span>&nbsp;<span class=\"date\">".formatDate($thread['epoch']) ."</span></span></div></a>";
			
		  	}

			showPageNav($total_items, $total_pages, $items_per_page, "index.php?topic=" . $topic);
				
		} else {
			echo "<div class=\"topicitem\">Nothing has been posted yet!</div>";
		}

	}

	function formatDate($epoch) {
		$date =  date(getMysqlStr("date_format", "global"), $epoch);
		return $date;
	}

	function getTopicTitle($topic) {
		$result = mysql_query("
			SELECT * FROM topics WHERE pagename = '". $topic ."'"
		) or trigger_error(mysql_error());

		if (mysql_num_rows($result) > 0) { 
			while ($topic = mysql_fetch_assoc($result)) {
				return $topic['title']; 	
			}
		}
	}


	//post formatting
	function formatPost($author, $content, $epoch, $level, $postid) {
			echo "<a name=\"".$postid."\"></a><div class=\"post\">\n";

			echo "\t<div class=\"postinfo\">\n";

				echo "\t\t<span class=\"postauthor\">". $author ."</span><br />\n";
				echo "\t\t<span class=\"small\">". formatUserLevel($level) ."</span>\n";
				echo "\t\t<hr class=\"thread\" />\n";
				//echo "\t\t<img src=\"media/avatar/".md5($author).".png\" class=\"avatar\" alt=\"\" />\n";
				echo "\t\t<img src=\"media/avatar/default.png\" class=\"avatar\" alt=\"\" />\n";

			echo "\t</div>\n";
			echo "\t<div class=\"postbody\"><span class=\"postdate\">Posted on ". formatDate($epoch) ." </span><br />". formatBB(nl2br($content)) ."</div>"; //convert \n to <br />

			echo "</div>\n";

	}

	function formatUserLevel($level) {
		if ($level == 0) { return getMysqlStr("level_banned", "user_settings"); }
		if ($level == 1) { return getMysqlStr("level_member", "user_settings"); }
		if ($level == 2) { return getMysqlStr("level_moderator", "user_settings"); }
		if ($level == 3) { return getMysqlStr("level_admin", "user_settings"); }
	}

	//return whether thread is locked
	function threadIsLocked() {
		
	}

	//return whether thread is stickied
	function threadIsSticky() {

	}

	//return the total number of posts
	function countReplies($threadid) {
		$result = mysql_query("SELECT * FROM posts WHERE threadid = '". $threadid ."'")
			or trigger_error(mysql_error());

		$rows = mysql_num_rows($result) -1;
		return $rows;
	}

	//return topic title via pagename
	function getCurrentTopicTitle($pagename) {
		$result = mysql_query("SELECT * FROM topics WHERE pagename = '".mEscape($pagename)."'")
			or trigger_error(mysql_error());

		while ($pagename = mysql_fetch_array($result)) {
			return getMysqlStr("site_name", "global") . " | " .$pagename["title"];
		}
	}


	// return the total number of threads in a topic
	function countTopics($topic) {

		$result = mysql_query("SELECT * FROM threads WHERE topic = '". $topic ."'")
			or trigger_error(mysql_error());

		$rows = mysql_num_rows($result);
		return $rows;
	}

	//start active session when user and pass are a match
	function compareLogin($username, $password) {
		include "config.php";

		$result = mysql_query("
			SELECT id FROM users WHERE username = '" .
			mEscape($username) . "' AND password = '". hash('sha1', $password.$mysql_salt) ."'"
		) or trigger_error(mysql_error());

		if(mysql_num_rows($result) > 0) {
			$userid = mysql_fetch_assoc($result);
			$_SESSION["id"] =  $userid["id"];
			$_SESSION["username"] = mEscape($username);
			return true;
		}

	}

	//check if queried username already exists
	function checkUserExists($username) {
		$result = mysql_query("
			SELECT id FROM users WHERE username = '" . mEscape($username) . "'"
		) or trigger_error(mysql_error());
		
		if(mysql_num_rows($result) > 0) { return true; }
	}

	//check if announcement is enabled
	function checkAnnounceEnabled() {
		if(getMysqlStr("announce", "global") == "Y") { return true; }
	}

	//check if thread exists
	function checkThreadExists($threadid) {
		$result = mysql_query("
			SELECT * FROM threads WHERE id = '". $threadid ."'"
		) or trigger_error(mysql_error());

		if (mysql_num_rows($result) > 0) { return true; }
	}

	//check if topic exists
	function checkTopicExists($topic) {
		$result = mysql_query("
			SELECT * FROM topics WHERE pagename = '". $topic ."'"
		) or trigger_error(mysql_error());

		if (mysql_num_rows($result) > 0) { return true; }
	}



	//used on logout
	function endSession() {
		session_destroy();
		header("Location: " . $_SERVER["PHP_SELF"]);
	}

	//return the access level for active user
	function getAccountLevel() {
		$result = mysql_query("SELECT level FROM users WHERE id = '". $_SESSION["id"] . "'");
		$row = mysql_fetch_assoc($result);
		return $row['level'];
	}
	
	

	function formatBB($text) {
		//notes for iframe embeds:
		// youtube = video tag after ?v= (bJ9r8LMU9bQ)
		// vimeo = video number (vimeo.com/26179832)
		// soundcloud = band/track (l7theband/wargasm)

		// BBcode array
		$find = array(
			'~\[b\](.*?)\[/b\]~s',
			'~\[i\](.*?)\[/i\]~s',
			'~\[u\](.*?)\[/u\]~s',
			'~\[s\](.*?)\[/s\]~s',
			'~\[quote\](.*?)\[/quote\]~s',
			'~\[size=(.*?)\](.*?)\[/size\]~s',
			'~\[color=(.*?)\](.*?)\[/color\]~s',
			'~\[youtube\](.*?)\[/youtube\]~s',
			'~\[vimeo\](.*?)\[/vimeo\]~s',
			'~\[url\]((?:ftp|https?)://.*?)\[/url\]~s',
			'~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
			'~\[soundcloud\](.*?)\[/soundcloud\]~s',
		);

		// HTML tags to replace BBcode
		$replace = array(
			'<b>$1</b>',
			'<i>$1</i>',
			'<span style="text-decoration:underline;">$1</span>',
			'<span style="text-decoration:line-through;">$1</span>',
			'<p class="quote">$1</p>',
			'<span style="font-size:$1px;">$2</span>',
			'<span style="color:$1;">$2</span>',
			'<iframe width="500" height="281" src="https://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
			'<iframe width="500" height="281" src="https://player.vimeo.com/video/$1" frameborder="0" allowfullscreen></iframe>',
			'<a href="$1">$1</a>',
			'<a href="$1"><img src="$1" width="100%" alt="" /></a>',
			'<iframe width="500" height="150" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https://soundcloud.com/$1&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>'
		);
		return preg_replace($find,$replace,$text);
	}

	function showBBinfo() {

		echo "<span onclick=\"appendText('[b][/b]', 'message');\"><img title=\"[b]\" class=\"bbcode\" src=\"media/icons/text_bold.png\" /></span>";
		echo "<span onclick=\"appendText('[i][/i]', 'message');\"><img title=\"[i]\" class=\"bbcode\" src=\"media/icons/text_italic.png\" /></span>";
		echo "<span onclick=\"appendText('[u][/u]', 'message');\"><img title=\"[u]\" class=\"bbcode\" src=\"media/icons/text_underline.png\" /></span>";
		echo "<span onclick=\"appendText('[s][/s]', 'message');\"><img title=\"[s]\" class=\"bbcode\" src=\"media/icons/text_strikethrough.png\" /></span>";
		echo "<span onclick=\"appendText('[size=14][/size]', 'message');\"><img title=\"[size=*]\" class=\"bbcode\" src=\"media/icons/text_size.png\" /></span>";
		echo "<span onclick=\"appendText('[color=#ff0000][/color]', 'message');\"><img title=\"[color=#??????]\" class=\"bbcode\" src=\"media/icons/color_wheel.png\" /></span>";
		echo "<span onclick=\"appendText('[quote][/quote]', 'message');\"><img title=\"[quote]\" class=\"bbcode\" src=\"media/icons/comment.png\" /></span>";
		echo "<span onclick=\"appendText('[img][/img]', 'message');\"><img title=\"[img]\" class=\"bbcode\" src=\"media/icons/picture.png\" /></span>";
		echo "<span onclick=\"appendText('[url][/url]', 'message');\"><img title=\"[url=*]\" class=\"bbcode\" src=\"media/icons/world_link.png\" /></span>";
		echo "<span onclick=\"appendText('[soundcloud][/soundcloud]', 'message');\"><img title=\"[soundcloud]\" class=\"bbcode\" src=\"media/icons/sound.png\" /></span>";
		echo "<span onclick=\"appendText('[vimeo][/vimeo]', 'message');\"><img title=\"[vimeo]\" class=\"bbcode\" src=\"media/icons/film.png\" /></span>";
		echo "<span onclick=\"appendText('[youtube][/youtube]', 'message');\"><img title=\"[youtube]\" class=\"bbcode\" src=\"media/icons/film.png\" /></span>";

		echo "<hr />\n";

	}

?>

