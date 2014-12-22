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

if(isset($_GET["switchDevice"])){
	$deviceId = $_GET["switchDevice"];
	$action = $_GET["action"];
	
	if(endsWith($action, "off"))
		$action="off";
	else
		$action="on";
		
	$requestString = "switchDevice/".$deviceId."/".$action;
	
	callServer($requestString);
}
else if(isset($_GET["addJob"])) {
	$deviceId = $_GET["addJob"];
	$requestString = "addJob/".$deviceId;
	callServer($requestString);
}
else if(isset($_GET["removeJob"])) {
	$jobId = $_GET["removeJob"];
	$requestString = "removeJob/".$jobId;
	callServer($requestString);
}
else if(isset($_GET["saveJob"])) {
	$jsonString = $_GET["saveJob"];
	$requestString = "saveJob/".$jsonString;
	callServer($requestString);
}
else {
	http_response_code(500);
	echo "request.php: parametri di chiamata non validi: ".$_SERVER['QUERY_STRING'];
}
function callServer($requestString) {
	$result = file_get_contents("http://localhost:8444/".encrypt($requestString));
	#exec("sudo python3.2 api.py A on 1 0 0 0 0 ");

	if($result == false) {		
		if(isset($http_response_header)) {
			list($version,$status_code,$msg) = explode(' ',$http_response_header[0], 3);
	
			//error_log("PiHome server response: ".$http_response_header[0]."---".$status_code.", ".$msg);
			http_response_code($status_code);
			echo $msg;
		}
		else {
			http_response_code(500);
			echo "Server PiHome non raggiungibile";
		}
	}
}
?>
