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

require('../configs/dbconfig.inc.php');
require('configs/functions.inc.php');

$what = $_GET['w'];   # device or room
$opp =  $_GET['o'];   # insert, update, delete, ( enable only device )
$wid = $_GET['wid'];  # which id

$ret=0;
if($what=="device"){ 
	#insert & update device
	$device_name = $_GET['device_name'];
	$device_flags = $_GET['flags'];
	$device_code = $_GET['code'];
	$device_type = $_GET['type'];
	$device_status = $_GET['status'];
	$device_enabled = $_GET['enabled'];
	$device_sort = $_GET['sort'];
	$room_id = $_GET['room'];

	if($opp=="insert") {
		dbconnect();
		$sql_device_insert= "INSERT INTO pi_devices ( id,room_id,device,flags,code,type,status,sort,enabled ) values (  '', '".$room_id."', '".utf8_decode($device_name)."', '".$device_flags."', '".$device_code."', '".$device_type."', '0', '".$device_sort."', '".$device_enabled."' )";
		$ret = mysql_query($sql_device_insert);
	}
	elseif($opp=="update") {
		dbconnect();
		$sql_device_update = "UPDATE `pi_devices` SET `room_id`='".$room_id."', `device`='".utf8_decode($device_name)."', `flags`='".$device_flags."', `code`='".$device_code."', `type`='".$device_type."', `status`='".$device_status."', `sort`='".$device_sort."', `enabled`='".$device_enabled."' ";
		$sql_device_update .= " WHERE `id`='".$wid."'";
		$ret = mysql_query($sql_device_update);
	}
	elseif($opp=="delete") {
		dbconnect();
		$sql_delete_device = "DELETE FROM pi_devices WHERE id = ".$wid;
		$ret = mysql_query($sql_delete_device);
	}
	elseif($opp=="enabled") {
		if(getDeviceIsEnabledById($wid)=="1"){ $set="0"; }else{ $set="1"; }
		dbconnect();
		$sql_da_update = "UPDATE `pi_devices` SET `enabled`='".$set."' ";
		$sql_da_update .= " WHERE `id`='".$wid."'";
		$ret = mysql_query($sql_da_update);
	}
	
	if($ret)
		file_get_contents("http://localhost:8444/".encrypt("reloadDevices"));
}
elseif($what=="room") {
	#insert & update room
	$room_name = $_GET['room_name'];
	if($opp=="insert") {
		dbconnect();
		$sql_room_insert= "INSERT INTO pi_rooms ( id,room ) values (  '', '".utf8_decode($room_name)."' )";
		$ret = mysql_query($sql_room_insert);
	}
	elseif($opp=="update") {
		dbconnect();
		$sql_room_update = "UPDATE `pi_rooms` SET `room`='".utf8_decode($room_name)."' ";
		$sql_room_update .= " WHERE `id`='".$wid."'";
		$ret = mysql_query($sql_room_update);
	}
	elseif($opp=="delete") {
		dbconnect();
		$sql_delete_room = "DELETE FROM pi_rooms WHERE id = ".$wid;
		$ret = mysql_query($sql_delete_room);
		echo "del room ".$wid;
	}	
}

if(!$ret)
	http_response_code(500);
	echo "Dati non validi";
?>
