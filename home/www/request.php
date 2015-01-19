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

require_once(dirname(__FILE__)."/configs/functions.inc.php");

if(isset($_GET["switchDevice"])){
	$deviceId = $_GET["switchDevice"];
	$action = $_GET["action"];
	
	if(endsWith($action, "off"))
		$action="off";
	else
		$action="on";
		
	$requestString = "switchDevice/".$deviceId."/".$action;
	
	callPiServer($requestString);
}
else if(isset($_GET["addJob"])) {
	$deviceId = $_GET["addJob"];
	$requestString = "addJob/".$deviceId;
	callPiServer($requestString);
}
else if(isset($_GET["removeJob"])) {
	$jobId = $_GET["removeJob"];
	$requestString = "removeJob/".$jobId;
	callPiServer($requestString);
}
else if(isset($_GET["saveJob"])) {
	$jsonString = $_GET["saveJob"];
	$requestString = "saveJob/".$jsonString;
	callPiServer($requestString);
}
else {
	http_response_code(500);
	echo $L_MSG_ERROR_INVALID_DATA.": ".$_SERVER['QUERY_STRING'];
}
?>
