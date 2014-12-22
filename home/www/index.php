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
	<? if($mobileClient) { ?>
	<link rel="stylesheet" href="css/jquery.mobile-1.4.5.min.css" type="text/css" />
	<script type="text/javascript" src="js/jquery.mobile-1.4.5.min.js" ></script>
	<? } ?>
	<script type="text/javascript" src="js/jquery.serialize-object.min.js"></script>
	<script type="text/javascript" src="js/request.js" ></script>
	<title>PiHome</title>
	<script type="text/javascript">
	 /*$(document).ready(function() {	 	
	    $('#lights').load('lights.php');
	 });*/	 
	</script>
</head>
<body>

<div id="nav">
	<div><img src="images/pihome.svg" id="home" border="0"></div><a href="javascript:alloff()"><div class="separator"><img src="images/off.svg" border="0"></div><div>SPEGNI TUTTO</div></a><a href="javascript:refresh()"><div class="separator"><img src="images/refresh.svg" border="0" /></div><div>AGGIORNA</div></a>
</div>


<div id="page">
	<div id="lights">
		<? include("lights.php"); ?>
	</div>
</div>


<div id="settings">
	<a href="admin/"><div><img src="images/settings.svg" border="0" /></div><div>IMPOSTAZIONI</div></a>
</div>




<div class='toast' style='display:none'></div>

</body>
</html>
