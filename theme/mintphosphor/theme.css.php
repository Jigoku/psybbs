<?php
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
//theme colour vars here

/*$colour1 = "#71D096"; //phosphor
$colour2 = "#194D36";
$colour3 = "#0B221A";*/

$colour1 = "#3EBB9E"; //cyan
$colour2 = "#ff0000";
$colour3 = "#1D584D";

?>

html { 
	background: #444;
    background: -moz-linear-gradient(left,  #111,  #222, #333, #333,#333,#333,#333,#333,#333,#222, #111);
	margin-top: 40px; margin-bottom:30px; padding:0; 
}

body {
	color: #bbb;
	font-family: Helvetica, sans-serif;
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
}
";

} else {

//stretch layout
echo "
div#wrap {
	margin-left: 30px;
	margin-right: 30px;
}

";

}


echo "
div#page {
	background: transparent;
	border-bottom: 1px solid $colour1;

}
div#wrap { 
	box-shadow: 0px 0px 6px 6px #111;
	background: #000;
 }

div.sub { color: #ddd; padding:20px; background: url(tile.png) #000 }


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


div#banner {
	background: #000;
	padding: 10px; 
	background: -moz-linear-gradient(top,  #333,  #111);
	
	font-family: Monospace;
	font-size: 20px;
	font-weight: bold;
	border-bottom: 1px solid $colour1;
}

div#announce {
  padding: 10px;
  font-size: 12px;

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
	background: -moz-linear-gradient(bottom, #222, #111);
	border-left: 1px solid $colour1;
	margin-left: -1px;
}

a.threaditem,a.threaditem:link,a.threaditem:active,a.threaditem:hover { text-decoration: none; color:transparent;}
.topicdescription {color: #888; font-size: 14px;}
.topictitle { text-decoration: underline; color: $colour1; font-size: 16px;}

.topicimg {
	float:left;
	height: 30px;
	width: 30px;
	background: transparent;
	margin-right:10px;
	
}



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
	border-top: 1px solid $colour1;
	border-bottom: 1px solid $colour1;
}
div.threaditem, div.topicitem {
	padding: 10px;
	border-top: 1px solid #222;
	color: #bbb;
	background: #111;
}

div.topicitem {
	border-top: 1px solid #222;
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
	background: -moz-linear-gradient(top, #222, #111);

}

div.pagenav {
	padding: 5px;
	background: #222;
	height: 25px;
	background: url(tile2.png) #000;
	
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
	background: #111;
	overflow:hidden;
	border-bottom: 1px solid #222;
}

div.postinfo {
	float:left;
	width: 120px;
	padding: 10px 0px 0px 10px;
}
div.postbody {
	padding: 20px;
	color: #ddd;
	overflow:hidden;
	border-left: 1px solid #222;

	min-height: 170px;
}

div#threadtitle {
	font-size: 18px;
	font-weight: bold;
	color: $colour1;
	padding: 15px;
	border-top: 1px solid #222;;
	border-bottom: 1px solid #222;
	background: #000;
	background: url(tile.png) #000
}


.postdate { color: #555; font-size: 14px;font-style: italic; float:right;}
.postauthor { font-size: 16px; color: #555; font-weight: bold;}
/*div.postavatar { float:left; margin-right: 10px;}*/
img.avatar { float:left; margin-right: 10px; width: 100px; height: 100px; 
background: transparent; margin-top:5px;}

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
.small { font-size: 12px; }
.postcount { color: #555;  float:right;	padding: 5px;   font-size: 14px; }
.lastpost {  color: #555; font-style: italic; font-size: 14px; float:right;}
.large { font-size: 22px; color: #ddd; }
.large2 { font-size: 22px; color: $colour1; }
.large3 { font-size: 30px; color: $colour1; text-align: center;}
.center { text-align: center; }
.topiclabel { colour: $colour1; }
/* placement */
.right { float:right; }
.left  { float:left; }


/* uri */

a:link,a:visited,a:active    { color: #bbb; text-decoration: underline; }
a:hover   { color: $colour1; text-decoration: underline overline; }

/* global */
img { padding: 5px; }
img.settings { padding: 5px; margin-left: -35px; margin-top: -5px;  position: absolute;}
img.thread { width: 32px; height 32px; float:left; margin-right: 5px;  }

div#userbar {
	color: #000;
	padding:10px;
	background: -moz-linear-gradient(top, $colour3,  $colour1);
}

a.userbar:link,a.userbar:visited,a.userbar:active    { 
	text-decoration: none;
		  border-radius: 15px;
	font-size: 12px;
	background: #222;
	color: #888;
	border: 1px solid #111;
	padding: 5px;
		background: -moz-linear-gradient(top, #111,  #333);
}
a.userbar:hover   { color: #fff; text-decoration: none; }

/* forms */

label {
    display: block;
    width: 150px;
    float: left;
    margin: 2px 40px 6px 4px;
    text-align: right;
    color: #aaa;
}


hr, hr.thread { color: #444; background: #444; border: 0px;  }

form.create, form.login {
	margin-left:auto;
	margin-right:auto;
	background: transparent;
	width: 400px;
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
