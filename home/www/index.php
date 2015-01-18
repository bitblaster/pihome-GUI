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
include("configs/dbconfig.inc.php");
include("configs/functions.inc.php");

$adminArea=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
	<meta name="viewport" content="width=device-width, user-scalable=no" /> 
	<meta name="format-detection" content="telephone=yes">
	<link rel="shortcut icon" href="images/favicon.png" />
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png"/>
	<link rel="stylesheet" href="css/pure-min.css" type="text/css" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
	<script type="text/javascript" src="js/jquery-1.11.1.min.js" ></script>
	<? if(0 && $mobileClient) { // JQuery mobile disabled at the moment ?>
	<link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css" type="text/css" />
	<script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js" ></script>
	<? } ?>
	<script type="text/javascript" src="js/jquery.serialize-object.min.js"></script>
	<script type="text/javascript" src="js/request.js" ></script>
    <script type="text/javascript" src="js/common.js"></script>
	<title>PiHome</title>
</head>
<body>

<div id="nav" class="nav">
	<div>
		<img src="images/pihome.svg" id="home" border="0">
	</div>
	<div class="separator"><span style="border-left: 1px solid #565656; height: 3em"></span></div>
    <a href="javascript:alloff()">
        <div><img src="images/off.svg" border="0"></div>
        <div><?=$L_ALL_OFF?></div>
    </a>
	<div class="separator"><span style="border-left: 1px solid #565656; height: 3em"></span></div>
    <a href="javascript:refresh()">
        <div><img src="images/refresh.svg" border="0" /></div>
        <div><?=$L_REFRESH?></div>
    </a>
</div>


<!--<div id="devices">
	<? include(dirname(__FILE__)."/lights.php"); ?>
</div>-->

<div id="groups">
    <? include(dirname(__FILE__)."/groups.php"); ?>
</div>

<a href="admin/">
    <div id="settings">
        <div><img src="images/settings.svg" border="0" /></div>
        <div><?=$L_SETTINGS?></div>
    </div>
</a>

<div class='toast' style='display:none'></div>

</body>
</html>
