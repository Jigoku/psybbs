<?php
/* psyBBS (top.php)
 * This file is called by all psyBBS pages
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
 
session_start();

if (file_exists("include/config.php")) {
	include "include/config.php";
} else {
	echo "ERROR: Could not find your psyBBS configuration, ensure you have edited and renamed the example file.\n";
	exit;
}
include "include/func.php";

$psybbs =  dirname($_SERVER['PHP_SELF']);

// connect to database server
$connection = mysql_connect($mysql_host, $mysql_user, $mysql_pass);

// check the connection state
if (!$connection) {
	die('Could not connect: ' . mysql_error());
}

//try to select the database
$db_selected = mysql_select_db($mysql_database, $connection);

if (!$db_selected) {
  // If we couldn't, then it either doesn't exist, or we can't see it.
  // ...so we create it! (or at least try to)
  $sql = "CREATE DATABASE $mysql_database";

  // defaults / testing tables
	//REPLACCE THIS LATER WITH WEB CONTROLS VIA ADMIN/INSTALLATION PANEL
	if (mysql_query($sql, $connection)) {
        mysql_select_db($mysql_database, $connection);

	//create users table
	mysql_query("
		CREATE TABLE users (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		epoch int(11),
		username varchar(20) NOT NULL,
		password varchar(40) NOT NULL,
		level int(1) UNSIGNED,
		locked char(1) NOT NULL,
		email varchar(150) NOT NULL,
		gravatar varchar(10) NOT NULL
	)", $connection) or trigger_error(mysql_error());

	//insert initial operator account (used for setup)
	mysql_query("INSERT INTO users (username, password, level, epoch, locked, email, gravatar) VALUES ('".$default_admin_username."', '". hash('sha1', $default_admin_password.$mysql_salt) ."', 3,'".time()."', 'N', 'n/a', 'mm')" , $connection);

	/*
		Access Levels
		0 = banned	(view only)
		1 = user 	(create, reply, edit own items)
		2 = moderator	(create, reply, delete, edit, sticky, ban user)
		3 = operator	(create, reply, delete, edit, sticky, ban user, admin panel, setup)
	*/

	//create topics table
	mysql_query("
		CREATE TABLE topics (
		id INT(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		title varchar(255) NOT NULL,
		pagename varchar(30) NOT NULL,
		description varchar(255)
	)", $connection) or trigger_error(mysql_error());

	mysql_query("INSERT INTO topics (title, pagename, description) VALUES ('Default Topic', 'default', 	'This is an example topic')" , $connection);
	

	//create threads table
	mysql_query("CREATE TABLE threads (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		author varchar(20) NOT NULL,
		epoch int(11) NOT NULL,
		topic varchar(30) NOT NULL,
		subject varchar(70) NOT NULL,
		sticky int(1) NOT NULL,
		locked int(1) NOT NULL,
		lastpostepoch int(11) NOT NULL
	)", $connection) or trigger_error(mysql_error());

	//create posts table
	mysql_query("CREATE TABLE posts (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		author varchar(20) NOT NULL,
		epoch int(11) NOT NULL,
		threadid int(8) NOT NULL,
		content varchar(5000) NOT NULL
	)", $connection) or trigger_error(mysql_error());

	//create global table
	mysql_query("CREATE TABLE global (
		announce char(1) NOT NULL,
		lockdown char(1) NOT NULL,
		theme varchar(20) NOT NULL,  
		themeopts varchar(100) NOT NULL,
		site_title varchar(100) NOT NULL,
		site_name varchar(100) NOT NULL,
		site_quote varchar(100) NOT NULL,
		site_announce varchar(500) NOT NULL,
		version varchar(10) NOT NULL,
		page_limit_threads int(3) NOT NULL,
		page_limit_posts int(3) NOT NULL,
		date_format varchar(30) NOT NULL
	)", $connection) or trigger_error(mysql_error());
	
	
	//default values (this could be tidied up...)
	mysql_query("INSERT INTO global
		(
			announce, 
			lockdown, 
			theme, 
			themeopts, 
			site_title, 
			site_name, 
			site_announce, 
			site_quote, 
			version,
			page_limit_threads,
			page_limit_posts,
			date_format
			) 
		VALUES (
			'Y', 
			'N', 
			'default', 
			'?fixed', 
			'<span class=\"bannertext\">psy</span><span class=\"banneremphasis\">BBS</span>', 
			'psyBBS', 
			'Announcement: Congratulations on installing psyBBS!!', 
			'powered by psyBBS ".$version."', 
			'".$version."',
			10,
			10,
			'[m:h:s] M/d/Y'
		)" , $connection) or trigger_error(mysql_error());


	//user settings
	mysql_query("CREATE TABLE user_settings (
		level_admin varchar(20) NOT NULL,  
		level_moderator varchar(20) NOT NULL,  
		level_member varchar(20) NOT NULL,  
		level_banned varchar(20) NOT NULL,
		colour_admin varchar(7) NOT NULL,
		colour_moderator varchar(7) NOT NULL,
		colour_member varchar(7) NOT NULL,
		colour_banned varchar(7) NOT NULL
	)", $connection) or trigger_error(mysql_error());
	
	mysql_query("INSERT INTO user_settings
		(
			level_admin, 
			level_moderator,
			level_member,
			level_banned,
			colour_admin,
			colour_moderator,
			colour_member,
			colour_banned
		) 
		VALUES (
			'admin', 
			'moderator',
			'member',
			'banned',
			'red',
			'orange',
			'yellow',
			'grey'
		)" , $connection) or trigger_error(mysql_error());
		
		
	} else {
		echo 'Error creating database: ' . mysql_error() . "\n";
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="content-language" content="en-US" />

	<meta name="description" content="psyBBS" />
	<meta name="copyright" content="GPLv3, CC" />
	<meta name="robots" content="nofollow" />

	<link href="<?php echo $psybbs; ?>/theme/<?php echo getMysqlStr("theme", "global");?>/icon/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<link href="<?php echo $psybbs; ?>/theme/<?php echo getMysqlStr("theme", "global"); ?>/theme.css.php<?php echo getMysqlStr("themeopts", "global"); ?>" rel="stylesheet" type="text/css" />
	<title><?php if(isset($_GET["topic"])){ echo getCurrentTopicTitle($_GET["topic"]); } else { echo getMysqlStr("site_name", "global"); } ?></title>
	
	<script type="text/javascript">
	//limit form input
	function maxChars(msg, len, id) {
		chars = msg.value.length;
		document.getElementById(id).innerHTML = len - chars;
		if (chars > len) {
			msg.value = msg.value.substring(0, len);
			document.getElementById(id).innerHTML = 0;
		}
	}
	
	function appendText(code, id) {
        var input = document.getElementById(id);
        input.value = input.value + code;
    }
	</script>
	
</head>

<body>
<div id="wrap">
	<div id="page">
		<div id="banner">
			<a id="headerlink" href="<?php echo $psybbs ;?>"><?php echo getMysqlStr("site_title", "global") ?></a><span class="bannerquote"><?php echo getMysqlStr("site_quote", "global"); ?></span>
		</div>
