<?php 
/* psyBBS (search.php)
 * This file allows users to perform searches
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

if (isset($_SESSION["id"])) {
	 if (!isset($_POST["query"])) {
		echo "<div class=\"sub\"><span class=\"large2\">Search</span><hr /></div>\n";
		echo "<div class=\"sub\">\n";
                echo "<form class=\"search\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\">\n";
                echo "<label class=\"search\">Query</label> <input type=\"text\" size=\"50\" maxlength=\"50\" name=\"query\">\n";
                echo "<input type=\"submit\" value=\"Submit\" name=\"submit\" >\n";
                echo "</form>\n";
        echo "</div>\n";
	} else {
		
		echo "<div class=\"sub\"><span class=\"large2\">Search Results</span><hr />\n";
		
		if (strlen($_POST["query"]) > 3) {
			
			//TODO add page filtering
			$sql = "SELECT * FROM posts WHERE content LIKE '%".mEscape($_POST["query"])."%' ORDER by epoch";
			$resultpost = mysql_query($sql);
			
			echo "<div class=\"sub\">Query returned ".mysql_num_rows($resultpost) ." results.</div>\n";
			
			if (mysql_num_rows($resultpost) > 0) {
				while ($post = mysql_fetch_assoc($resultpost)) {

					$sql = "SELECT * FROM threads WHERE id = '". mEscape($post["threadid"])."'";
					$resultthread = mysql_query($sql);
					while ($thread = mysql_fetch_assoc($resultthread)) {
							//TODO fix page request
							echo "<a class=\"threaditem\" href=\"index.php?topic=".$thread["topic"]."&id=".$thread["id"]."&page=1#".$post["id"]."\">
									<div class=\"threaditem\"><span class=\"author\">".$post["author"]. " in </span> '". $thread["subject"] .
									"'</div>\n";
					}
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
