<?php 
ini_set('display_errors', 'on');
include "include/top.php";


//check if we're logged in
if (isset($_SESSION["id"]) && checkUserExists($_SESSION["username"])) {
		if (isset($_GET["logout"])) { endSession(); }

		//navigational links and welcome message
		echo "\t\t<div id=\"userbar\">\n";
		//echo "\t\t\t$site_title<span class=\"bannerquote\">$site_notice</span><br /><span class=\"small\">Welcome <b>". $_SESSION["username"] ."</b></span>\n";
		echo "\t\t\t<span class=\"small\">Welcome back <b>". $_SESSION["username"] ."</b></span>\n";
		echo "\t\t\t<span class=\"right\">\n";

		if (getAccountLevel() == 3) { echo "\t\t\t\t<a class=\"userbar\" href=\"settings.php\">settings</a> &brvbar; \n"; }
		echo "\t\t\t\t<a class=\"userbar\" href=\"" . $_SERVER["PHP_SELF"] . "\">topics</a>\n";
		echo "\t\t\t\t<a class=\"userbar\" href=\"" . $_SERVER["PHP_SELF"] . "?account\">account</a>\n";
		echo "\t\t\t\t<a class=\"userbar\" href=\"" . $_SERVER["PHP_SELF"] . "?stats\">stats</a>\n";
		echo "\t\t\t\t<a class=\"userbar\" href=\"" . $_SERVER["PHP_SELF"] . "?logout\">logout</a>\n";
		echo "\t\t\t</span>\n";
		echo "\t\t</div>\n\n";
		
		 //announcements (if enabled)..
		echo "<div id=\"announce\">$site_announce</div>";

		if (!(isset($_GET["topic"]))) {

			if (array_keys($_GET) === array('account')) {
				//show profile information per active account
				showAccount();

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

			} elseif (array_keys($_GET) === array('topic', 'id') && mEscape($_GET["id"]) > 0) {
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
					$topic = mEscape(strtolower($_GET["topic"]));
					listThreads($topic);
				} else {
					echo "<div class=\"sub\"><span class=\"large2\">Topic does not exist!</span></div>\n";
				}
			}

		}



} else {

	echo "<div id=\"userbar\"><a class=\"userbar\" href=\"login.php\">login</a> &brvbar; <a class=\"userbar\" href=\"create.php\">register</a>";
	echo "<span class=\"right\"><a class=\"userbar\" href=\"info.php\">?</a></span></div>\n";

	echo "<div class=\"sub\"><img class=\"splashimg\" src=\"theme/".$theme."/splash.png\" /></div>";
	echo "<div class=\"sub\"><h1 class=\"large3\">$site_title</h1></div>";
	echo "<div class=\"sub\"><p class=\"small\">Sample Text (change this)</p></div>";


}


include "include/bottom.php";
?>


