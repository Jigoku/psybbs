<?php
 //navigational links and welcome message
              echo "\t\t<div id=\"userbar\">\n";
                //echo "\t\t\t$site_title<span class=\"bannerquote\">$site_notice</span><br /><span class=\"small\">Welcome <b>". $_SESSION["username"] ."</b></span>\n";
                echo "\t\t\t<span class=\"small\"><span class=\"shadow\">Welcome back <b>". getUserName() ."</b></span></span>\n";
                echo "\t\t\t<span class=\"right\">\n";

                if (getAccountLevel() == 3) { echo "\t\t\t\t<a class=\"userbar\" href=\"" . $psybbs . "/settings.php\">board settings</a>\n"; }
                
                if (isset($_SESSION["id"])) {
					echo "\t\t\t\t<a class=\"userbar\" href=\"" . $psybbs . "\">topics</a>\n";
					echo "\t\t\t\t<a class=\"userbar\" href=\"" . $psybbs . "?account\">account</a>\n";
					echo "\t\t\t\t<a class=\"userbar\" href=\"" . $psybbs . "?stats\">stats</a>\n";
					echo "\t\t\t\t<a class=\"userbar\" href=\"search.php\">search</a>\n";
					echo "\t\t\t\t<a class=\"userbar\" href=\"" . $psybbs . "?logout\">logout</a>\n";
		
				} else {
					echo "<a class=\"userbar\" href=\"". $psybbs ."/login.php\">login</a>\n";
					echo "<a class=\"userbar\" href=\"". $psybbs ."/create.php\">register</a>\n";
					echo "<a class=\"userbar\" href=\"". $psybbs ."/info.php\">info</a>\n";
				}
				
                echo "\t\t\t</span>\n\t\t</div>\n";
                
                 //announcements (if enabled)..
                if (checkAnnounceEnabled()) {
                        echo "<div id=\"announce\">".getMysqlStr("site_announce", "global")."</div>\n\n";
                }
?>
