<?php
/* psyBBS (config.php)
 * This file sets some default settings
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
 
/* admin account */
$default_admin_username = "psy";
$default_admin_password = "psy";

/* mysql settings */
$mysql_host 	= "localhost";
$mysql_user 	= "";
$mysql_pass 	= "";
$mysql_database = "psyBBS";
$mysql_salt 	= "";

$version = "0.3";


/* user level aliases */
$userLevel = [
	"banned" => "remnant",
	"member" => "shadow",
	"moderator" => "agent",
	"admin" => "operator",
];

?>
