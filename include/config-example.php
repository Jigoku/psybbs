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
$mysql_host 	= "localhost";	//hostname for the mysql server
$mysql_user 	= "";			//username .. 
$mysql_pass 	= "";			//password .. 
$mysql_database = "psyBBS";		//the name of the database to use/create
$mysql_salt 	= "";			//random string used to obfuscate password hashes

$version = "0.3"; //psyBBS version (don't change, this helps keep things compatbile)

?>
