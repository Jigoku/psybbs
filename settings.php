<?php 
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
			setBoardName(
				mEscape($_POST["boardname"])
			);
			
		} elseif (array_keys($_GET) === array('setboardquote')) {
			setBoardQuote(
				mEscape($_POST["boardquote"])
			);
			
		} else {
			showSettings();
		}
		echo "</div>\n";


} else {
	echo "<div class=\"sub\"><span class=\"large2\">Access Denied</span></div>\n";
}


include "include/bottom.php";
?>

