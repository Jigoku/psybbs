<?php 
ini_set('display_errors', 'on');
include 'include/top.php';

if (isset($_SESSION["id"])) {
	 if (!isset($_POST["query"])) {
		echo "<div class=\"sub\"><span class=\"large2\">Search</span><hr /></div>\n";
		echo "<div class=\"sub\">\n";
                echo "<form class=\"create\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\">\n";
                echo "<label>Query</label> <input type=\"text\" size=\"50\" maxlength=\"50\" name=\"query\"><br />\n";
                echo "<input type=\"submit\" value=\"search\" name=\"submit\" class=\"button\">\n";
                echo "</form>\n";
        echo "</div>\n";
	} else {
		
		echo "<div class=\"sub\"><span class=\"large2\">Search Results</span><hr /></div>\n";
		echo "<div class=\"sub\">\n";
		
		if (strlen($_POST["query"]) > 3) {
			
			//TODO add page filtering
			$sql = "SELECT * FROM posts WHERE content LIKE '%".mEscape($_POST["query"])."%' ORDER by epoch";
			$resultpost = mysql_query($sql);
		
			if (mysql_num_rows($resultpost) > 0) {
				while ($post = mysql_fetch_assoc($resultpost)) {
					
					//TODO currently links to thread, but doesn't highlight post. (add tags to scroll page to postid)
					$sql = "SELECT * FROM threads WHERE id = '". mEscape($post["threadid"])."'";
					$resultthread = mysql_query($sql);
					while ($thread = mysql_fetch_assoc($resultthread)) {
							echo "<a class=\"threaditem\" href=\"index.php?topic=".$thread["topic"]."&id=".$thread["id"]."\">
									<div class=\"threaditem\">". $thread["subject"] ."<span class=\"author\"> - by ".$thread["author"]."</span>".
									"</div>\n";
					}
					///////////
				}
			} else {
				echo "No Results!\n";
			}
		} else {
			echo "Search query must be at least 4 characters in length!\n";
		}
		echo "</div>\n";
	}
} else {
	// not logged in (no access)
}
include 'include/bottom.php';
?>
