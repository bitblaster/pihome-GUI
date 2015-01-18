<?
/*
 * PiHome v1.0
 * http://pihome.harkemedia.de/
 *
 * PiHome Copyright (c) 2012, Sebastian Harke
 * Lizenz Informationen.
 * 
 * This work is licensed under the Creative Commons Namensnennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen 3.0 Unported License. To view a copy of this license,
 * visit: http://creativecommons.org/licenses/by-nc-sa/3.0/.
 *
*/

require_once("access.php");

$adminArea=1;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PiHome - Pannello di controllo</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, user-scalable=no" />
<meta name="format-detection" content="telephone=yes">
<link rel="shortcut icon" href="images/favicon.png" />
<link rel="stylesheet" href="../css/pure-min.css" type="text/css" />
<link rel="stylesheet" href="../css/style.css" type="text/css" />
<script type="text/javascript" src="../js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.bpopup.min.js"></script>
<!--<script type="text/javascript" src="js/tabcontent.js"></script>-->
<script type="text/javascript" src="js/request.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
</head>
<body>

<div id="navAdmin" class="nav">
	<div class="headerTitle">
		<img src="../images/pihome.svg" id="home" border="0">
		<span style="display: inline-block;">
			<span style="font-size: 2.3em;color: #ABABAB">Pi</span>
			<span style="font-size: 2.3em">Home</span>
			<br/>
			<span style="font-size: 0.8em">&nbsp;administration panel</span>
		</span>
	</div>
	<div class="separator"><span style="border-left: 1px solid #565656; height: 3em"></span></div>
	
	<a href="access.php?p=logout">
		<div><img src="images/logout.svg" border="0" /></div>
		<div><?=$L_LOGOUT?></div>
	</a>
</div>

<div id="groups">
    <? include(dirname(__FILE__)."/../groups.php"); ?>
</div>

<a href="../">
    <div id="settings">
        <div><img src="images/return.svg" border="0" /></div>
		<div><?=$L_BACK_PIHOME?></div>
    </div>
</a>

<div id="copy"><?=getcopy();?></div>

<div class='toast' style='display:none'></div>

<div id="popup">
    <span class="b-close"><span>X</span></span>
    <div class="content"></div>
</div>


</body>
</html>
