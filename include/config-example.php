<?php
	$default_admin_username = ""; //you must change set this
	$default_admin_password = ""; //you must change set this

	$mysql_host 	= "localhost";
	$mysql_user 	= "";
	$mysql_pass 	= "";
	$mysql_database = "";
	$mysql_salt 	= "";

	//text logo for the board header
	$site_title = "<span class=\"bannertext\">Bulletin</span><span class=\"banneremphasis\">Board</span>";
	
	//small announcement for header area
	$site_announce = "Announcement: Hello world";
	$theme = "mintphosphor";

	//$themeopts = "?fixed";
	$themeopts = "";

	//global <title>
	$site_name = "psyBBS";
	$site_quote = "psyBBS 0.3";


	$items_per_page = 10;

	//user level aliases shown on posts/profile
	$userLevel = [
		"banned" => "banned user",
		"member" => "registered user",
		"moderator" => "mod",
		"admin" => "board admin",
	];
	
?>
