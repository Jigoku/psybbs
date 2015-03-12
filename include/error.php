<?php
/* psyBBS (error.php)
 * This file displays internal error messages
 * 
 * Set $errormsg, then simply include this file.
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
 

echo "
<div class=\"sub\">
	<span class=\"large2\">Error</span><hr />
	<div class=\"sub\">";
echo $errormsg;
echo "</div>
</div>";

include "bottom.php";
?>
