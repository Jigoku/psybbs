<?php
/* psyBBS (theme.css.php)
 * This file helps with dynamic theming
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
if (isset($psybbs)) {
	echo "<div class=\"info\">Theme Options<form class=\"settings\" method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "?setthemeopts\">\n";
	echo "<input type=\"text\" size=\"40\" maxlength=\"255\" name=\"themeopts\" value=\"".getMysqlStr("themeopts","global")."\">\n";	
	echo "<input type=\"submit\" value=\"Update\" name=\"submit\">\n";
	echo "</form></div>\n";
	return;
}

	//strip comments, strip newline/tab/carriage, compress to gzip
	header('Content-type: text/css');
	ob_start("compress");
	function compress($buffer) {
		// remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		// remove tabs, spaces, newlines, etc.
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
		return $buffer;
	}
?>


<?php
if (isset($_GET["colour1"])){
	$colour1 = "#" . $_GET["colour1"];

} else {
	$colour1 = "#991433";
}

if (isset($_GET["colour2"])){
	$colour2 = "#" . $_GET["colour2"];
} else {
	$colour2 = "#A25D45";
}
?>

html { 
	background: #333; /* fallback */
    background: -moz-linear-gradient(left,  #111,  #222, #333, #333,#333,#333,#333,#333,#333,#222, #111);
    background: -webkit-linear-gradient(left,  #111,  #222, #333, #333,#333,#333,#333,#333,#333,#222, #111);
	padding:0; 
}

body {
	color: #bbb;
	font-family: Helvetica, sans-serif;
	margin-top: 30px; 
	margin-bottom: 30px;
}

<?php
//check for user preference
if (isset($_GET["fixed"])){

//fixed layout
echo "
div#wrap {

    width: 1000px;
	margin: auto;
}

div#page {
    width: 800;
    border: 2px solid #555;
}
";

} else {

//stretch layout
echo "
div#wrap {
	margin-left: 30px;
	margin-right: 30px;
}

div#page {
	border: 2px solid #555;
}
";

}


echo "
div#page {
	background: transparent;
	position: relative;
	width: 100%;
}
div#wrap { 
	box-shadow: 0px 0px 6px 6px #111;
	background: #000;
 }

div.sub, div#reply { color: #ddd; padding:20px; background: url(tile.png) #000 }


.splashimg {
	padding: 50px 50px 0px 50px ;
	display:block;
	margin-left: auto;
	margin-right: auto;
}


p {
	color: #ddd;
	background: #222;
	padding:10px;
}


/* banner + headers */
.bannertext { color: #888;}
.banneremphasis { color: #ccc; }
.bannerquote {
	 color: #444; font-size: 12px; float:right; padding: 0px;
}

/* misc font */
.topicdescription,.bannerquote {
	font-family: Fixed;

}

div#account {
	padding: 15px;
}

div#banner {
	background: #222; /* fallback */
	padding: 10px; 
	background: -moz-linear-gradient(top,  #333,  #111);
	background: -webkit-linear-gradient(top,  #333,  #111);
	font-family: Monospace;
	font-size: 20px;
	font-weight: bold;
	border-bottom: 1px solid $colour1;
	height: 25px;
}

div#announce {
  padding: 10px;
  font-size: 12px;
	border-bottom: 2px solid #555;
  background: url(tile2.png) #000;
}


/* thread specific */
a.topic {
	text-decoration: none;
	display:block;
	border-top: 2px solid #222;
	background: #111;

}
a.topic:link,a.topic:active {
	color: #bbb;
	text-decoration: none;
}
a.topic:hover {
	background: #222; /* fallback */
	background: -moz-linear-gradient(bottom, #222, #111);
	background: -webkit-linear-gradient(bottom, #222, #111);
}

a.threaditem,a.threaditem:link,a.threaditem:active,a.threaditem:hover { text-decoration: none; color:transparent;}

a#headerlink { text-decoration: none; color:transparent; }

.topicdescription {color: #bbb; font-size: 14px;}
.topictitle { text-decoration: underline; color: $colour1; font-size: 16px;}

.topicimg {
	float:left;
	height: 30px;
	width: 30px;
	background: transparent;
	margin-right:10px;
	border-radius: 15px;
}

div.frame {padding: 20px; }

div.thread {
	color: #ddd;
	background: #222;
	padding:10px;
	
}

div.threadopts {
	padding: 5px;
	color: $colour1;
	font-size: 12px;
	background: url(tile2.png) #000;
	border-bottom: 2px solid #555;
	min-height:15px;
}
div.threaditem, div.topicitem {
	padding: 10px;
	border-top: 2px solid #222;
	color: #bbb;
	background: #111;
}

div.topicitem {
	border-bottom: 2px solid #555;
	color: $colour1;
	font-weight: bold;
	padding:15px;
	background: url(tile.png) #000;
}

/*div.topic {
	color: $colour1;
	background: #061113;
	padding:5px;
	border-bottom: 1px dashed #1C4a54;
}*/

div.topic:hover, div.threaditem:hover {
	background: #222; /* fallback */
	background: -moz-linear-gradient(top, #222, #111);
	background: -webkit-linear-gradient(top, #222, #111);

}

div.pagenav {
	padding: 5px;
	background: #222;
	height: 25px;
	background: url(tile2.png) #000;

	border-bottom: 2px solid #555;
}

a.pagebutton:link,a.pagebutton:visited,a.pagebutton:active,a.pagebutton:hover {
	padding: 4px;
	font-size: 12px;
}

a.pagebutton:link    { color: #bbb; text-decoration: none; background: #000; border-radius: 15px;}
a.pagebutton:visited { color: #bbb; text-decoration: none; }
a.pagebutton:active  { color: #bbb; text-decoration: none; }
a.pagebutton:hover   { color: #000; text-decoration: none; background: #444; }



div.post {
	background: #222;
	overflow:hidden;

	border-bottom: 2px solid #555;
}

div.postinfo {
	float:left;
	width: 120px;
	padding: 10px 0px 10px 10px;
	background: #222;
	min-height: 160px;

}
div.postbody {
	padding: 20px;
	color: #ddd;
	overflow:hidden;
	background: #111;
	min-height: 180px;
		border-left: 2px solid #555;
}

div#threadtitle {
	font-size: 18px;
	font-weight: bold;
	color: $colour1;
	padding: 15px;
	border-bottom: 2px solid #555;

	background: #000;
	background: url(tile.png) #000
}


.postdate { color: #bbb; font-size: 12px;font-style: italic; float:right;}
.postauthor { font-size: 16px; color: #888; font-weight: bold; }
div.postavatar { float:left; margin-right: 10px; }
img.avatar { float:left; width: 100px; height: 100px; border-radius: 15px;
background: transparent; margin-top:5px; }

/* text items */
.subject { color: #bbb; }
.title { font-style: italic; color: #bbb; }
.author { color: #555; font-style: italic; }
.date { color: $colour1; }
.replies { color: #555; }
.hl { color: $colour1; }
.info { font-size: 14px; padding: 2px;}
.topic { color: #ff2277; font-size:20px; padding: 10px; }
.newthread { float: right; color:#dddddd;}
.small { font-size: 12px;   }
.postcount { color: #555;  float:right;	padding: 5px;   font-size: 14px; }
.lastpost {  color: #555; font-style: italic; font-size: 14px; float:right;}
.large { font-size: 22px; color: #ddd; }
.large2 { font-size: 22px; color: $colour1; }
.large3 { font-size: 30px; color: $colour1; text-align: center;}
.large4 { font-size: 22px; color: $colour1; margin-top: 8px; position: absolute; }
.center { text-align: center; }
.topiclabel { colour: $colour1; }
/* placement */
.right { float:right; }
.left  { float:left; }
.shadow {
 text-shadow:
    -1px -1px 0 #000,
    1px -1px 0 #000,
    -1px 1px 0 #000,
    1px 1px 0 #000;
}

.user-admin 	{}
.user-moderator {}
.user-member 	{}
.user-banned 	{}


/* uri */

a:link,a:visited,a:active    { color: #bbb; text-decoration: underline; }
a:hover   { color: $colour1; text-decoration: underline overline; }

/* global */
img { padding: 5px; }


img.thread { width: 32px; height 32px; float:left; margin-right: 5px;  border-radius: 15px; }

div#userbar {
	border-bottom: 2px solid #555;
	padding:10px;
	background: $colour1; /* fallback */
	background: -moz-linear-gradient(top, $colour2,  $colour1);
	background: -webkit-linear-gradient(top, $colour2,  $colour1);
}

a.userbar:link,a.userbar:visited,a.userbar:active    { 
	text-decoration: none;
	border-radius: 5px;
	font-size: 12px;
	color: #888;
	border: 1px solid #111;
	padding: 5px;
	background: #222; /* fallback */
	background: -moz-linear-gradient(top, #111,  #333);
	background: -webkit-linear-gradient(top, #111,  #333);
}
a.userbar:hover { 
	color: #fff; 
	text-decoration: none; 
	background: #333; /* fallback */
	background: -moz-linear-gradient(top, #222,  #444);
	background: -webkit-linear-gradient(top, #222,  #444);
}

/* forms */

label.account, label.createtopic{
    display: block;
    width: 150px;
    float: left;
    margin: 2px 40px 6px 4px;
    text-align: right;
    color: #aaa;
}



label {
    color: #aaa;
}

img.bbcode {
	background: white;
	border: 1px solid #000;

}

hr { color: #444; background: #444; border: 1px #444 solid;  }
hr.thread  { color: $colour1; background: $colour1; border: 0px;  }
form.settings {
	margin: 0px auto;
}

form.create, form.login, form.search {
	margin-left:auto;
	margin-right:auto;
	background: transparent;
	width: 400px;
	padding: 20px;
	color: #ddd;
}


form.post {
	margin-left:auto;
	margin-right:auto;
	background: transparent;

	padding: 20px;
	color: #ddd;
}

.captcha {
	border: 2px solid #555;	  border-radius: 15px;
}
input {
	border: 2px solid #555;
	padding: 5px;
	margin: 0 0 10px 0;
	color: #ddd;
	background: #111;
}

input.button {
	color: #ddd;
	border: 2px solid #333;
	background: #111;

	width: 200px;
	display: block;
	margin-top: 15px;
	margin-left: auto;
	margin-right: auto;

}
input.button:hover {
	color:#fff;
	border: 2px solid #555;
	background:#222;
}
textarea {
	border: 2px solid #555;
	padding: 5px;
	color: #ddd;
	background: #111;

}
pre {
	padding: 10px;
}

p.quote {
	padding: 10px;
	border: 2px solid #000;
	font-size: 13px;
	font-style: italic;
	color: #bbb;
	background: #222;
}

";
