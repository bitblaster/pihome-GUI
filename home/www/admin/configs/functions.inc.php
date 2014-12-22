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

require('../configs/functions.inc.php');

function pihome_acp_login($pih_username,$pih_passwort) {
    dbconnect();
    $query = "SELECT `id`, `user`, `pass`  FROM pi_admin WHERE user = '".$pih_username."'";
    $result =  mysql_query($query);
    dbconnect();
    $result =  mysql_query("SELECT `id`, `user`, `pass`  FROM pi_admin WHERE user = '$pih_username'");
    $zeileholen =  mysql_fetch_array($result);
    if (!$zeileholen) { 
	die ("<meta http-equiv='refresh' content='0; URL=index.php'></SCRIPT><script language='JavaScript'>(window-alert('Utente non trovato!'))</script>");
    }
    if ($zeileholen["pass"] <> $pih_passwort) {
	die ("<meta http-equiv='refresh' content='0; URL=index.php'></SCRIPT><script language='JavaScript'>(window-alert('Password errata!'))</script>");
    } else {
	$_SESSION["pihome_username"]=$pih_username;
	$_SESSION["pihome_usid"]=$zeileholen["id"];
    }
}

function getLights() {
    dbconnect();
    $sql_getLights       = "SELECT * FROM  pi_devices ORDER BY enabled DESC ";
    $query_getLights     = mysql_query($sql_getLights);	
    $x=0;
    while($light = mysql_fetch_assoc($query_getLights)) {
	$devices[$x]["id"] = $light['id'];
	$devices[$x]["room_id"] = $light['room_id'];
	$devices[$x]["device"] = $light['device'];
	$devices[$x]["flags"] = $light['flags'];
	$devices[$x]["code"] = $light['code'];
	$devices[$x]["status"] = $light['status'];
	$devices[$x]["sort"] = $light['sort'];
	$devices[$x]["enabled"] = $light['enabled'];
	$x=$x+1;
    }
    return $devices;
}

function getRooms() {
    dbconnect();
    $sql_getRooms       = "SELECT * FROM  pi_rooms";
    $query_getRooms     = mysql_query($sql_getRooms);	
    $x=0;
    while($room = mysql_fetch_assoc($query_getRooms)) {
	$rooms[$x]["id"] = $room['id'];
	$rooms[$x]["room"] = $room['room'];
	$x=$x+1;
    }
    return $rooms;
}

function getDeviceIsEnabledById($id) {
    dbconnect();
    $sql_dabi       = "SELECT * FROM pi_devices WHERE id = '".$id."'";
    $result_dabi    = mysql_query($sql_dabi) OR die(mysql_error());
    while($get_dabi = mysql_fetch_assoc($result_dabi)) {
	$da = $get_dabi['enabled']; 
    }
    return $da;
}


?>
