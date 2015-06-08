<?php
 //navigational links and welcome message
              echo "\t\t<div id=\"userbar\">\n";
                //echo "\t\t\t$site_title<span class=\"bannerquote\">$site_notice</span><br /><span class=\"small\">Welcome <b>". $_SESSION["username"] ."</b></span>\n";
                echo "\t\t\t<span class=\"small\"><span class=\"shadow\">Welcome back <b>". $_SESSION["username"] ."</b></span></span>\n";
                echo "\t\t\t<span class=\"right\">\n";

                if (getAccountLevel() == 3) { echo "\t\t\t\t<a class=\"userbar\" href=\"settings.php\">settings</a> &brvbar; \n"; }
                echo "\t\t\t\t<a class=\"userbar\" href=\"" . $psybbs . "\">topics</a>\n";
                echo "\t\t\t\t<a class=\"userbar\" href=\"" . $psybbs . "?account\">account</a>\n";
                echo "\t\t\t\t<a class=\"userbar\" href=\"" . $psybbs . "?stats\">stats</a>\n";
                echo "\t\t\t\t<a class=\"userbar\" href=\"search.php\">search</a>\n";
                echo "\t\t\t\t<a class=\"userbar\" href=\"" . $psybbs . "?logout\">logout</a>\n";
                echo "\t\t\t</span>\n";
                echo "\t\t</div>\n\n";
                
                 //announcements (if enabled)..
                if (checkAnnounceEnabled()) {
                        echo "<div id=\"announce\">".getMysqlStr("site_announce", "global")."</div>";
                }
?>
