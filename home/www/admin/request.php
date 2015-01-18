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

$what = $_GET['w'];   # device or group
$opp =  $_GET['o'];   # insert, update, delete, ( enable only device )

error_log("Ci siamo: ".$what.", op:".$opp);

$ret=0;
if($what=="device"){ 
	
	if($opp=="insert" || $opp=="update") {
        $wid = $_GET['id'];  # which id
        $device_name = $_GET['device_name'];
        $device_flags = strtoupper($_GET['flags']);
        $device_code = $_GET['code'];
        $device_type = $_GET['type'];
        //$device_status = $_GET['status'];
        $device_enabled = isset($_GET['enabled']) ? $_GET['enabled'] : '1';
        $groupId = $_GET['groupId'];
        
        dbconnect();
        $result = mysql_query("SELECT count(*) FROM pi_devices WHERE LOWER(device)='".strtolower($device_name)."' AND id!='".$wid."'");
        if($result && mysql_result($result, 0, 0) > 0) {
            http_response_code(500);
            echo "Nome dispositivo già utilizzato: '".$device_name."'";
            die();
        }
        $result = mysql_query("SELECT count(*) FROM pi_devices WHERE flags='".$device_flags."' AND code='".$device_code."' AND id!='".$wid."'");
        if($result && mysql_result($result, 0, 0) > 0) {
            http_response_code(500);
            echo "Combinazione flags-tasto comando già utilizzata: '".$device_flags."-".$device_code."'";
            die();
        }
        
        if($opp=="insert") {
            $result = mysql_query("SELECT count(*) FROM pi_devices WHERE group_id='".$groupId."'");
            $device_sort = mysql_result($result, 0, 0);
            $sql_device_insert= "INSERT INTO pi_devices (id,group_id,device,flags,code,type,status,sort,enabled) values ('', '".$groupId."', '".utf8_decode($device_name)."', '".$device_flags."', '".$device_code."', '".$device_type."', '0', '".$device_sort."', '".$device_enabled."' )";
            $ret = mysql_query($sql_device_insert);
        }
        elseif($opp=="update") {
            $sql_device_update = "UPDATE `pi_devices` SET `group_id`='".$groupId."', `device`='".utf8_decode($device_name)."', `flags`='".$device_flags."', `code`='".$device_code."', `type`='".$device_type./*"', `status`='".$device_status.*/"', `enabled`='".$device_enabled."' ";
            $sql_device_update .= " WHERE `id`='".$wid."'";
            error_log("Query: ".$sql_device_update);
            $ret = mysql_query($sql_device_update);
        }
	}
	elseif($opp=="delete") {
        $wid = $_GET['id'];  # which id
		dbconnect();
		$sql_delete_device = "DELETE FROM pi_devices WHERE id = ".$wid;
		$ret = mysql_query($sql_delete_device);
	}
	elseif($opp=="enabled") {
        $wid = $_GET['id'];  # which id
		if(getDeviceIsEnabledById($wid)=="1")
            $set="0";
        else
            $set="1";
            
		dbconnect();
		$sql_da_update = "UPDATE `pi_devices` SET `enabled`='".$set."' ";
		$sql_da_update .= " WHERE `id`='".$wid."'";
		$ret = mysql_query($sql_da_update);
	}
	elseif($opp=="reorder") {
        $groupId = $_GET['groupId'];
        $order = $_GET['order'];

        $orderArray = explode(',', $order);
            
		dbconnect();
        $ret=TRUE;
        foreach($orderArray as $i => $deviceId) {
            $sql_da_update = "UPDATE `pi_devices` SET `sort`=".$i." WHERE `id`='".$deviceId."'";
            error_log("eseguiamo la query: " + $sql_da_update);
            $ret = $ret && mysql_query($sql_da_update);
            error_log("ret: " + $ret);
            if(!$ret)
                break;
        }
	}
	
	if($ret)
		callPiServer("reloadDevices"));
}
elseif($what=="group") {
	#insert & update group
    error_log("siamo in group");
    
	if($opp=="insert") {
        $group_name = $_GET['group_name'];
        dbconnect();
		$sql_group_insert= "INSERT INTO pi_groups (id, group_name) values ('', '".utf8_decode($group_name)."')";
		$ret = mysql_query($sql_group_insert);
	}
	elseif($opp=="update") {
        $group_name = $_GET['group_name'];
        $wid = $_GET['id'];  # which id
		dbconnect();
		$sql_group_update = "UPDATE `pi_groups` SET `group_name`='".utf8_decode($group_name)."' ";
		$sql_group_update .= " WHERE `id`='".$wid."'";
		$ret = mysql_query($sql_group_update);
	}
	elseif($opp=="delete") {
        $wid = $_GET['id'];  # which id
		dbconnect();
        $result = mysql_query("SELECT count(*) FROM pi_devices WHERE group_id='".$wid."'");
        if($result && mysql_result($result, 0, 0) > 0) {
            http_response_code(500);
            echo "Impossibile eliminare un gruppo che contiene dispositivi!";
            die();
        }
		$sql_delete_group = "DELETE FROM pi_groups WHERE id = ".$wid;
		$ret = mysql_query($sql_delete_group);
		echo "del group ".$wid;
	}	
}

if(!$ret) {
	http_response_code(500);
	echo "Dati non validi";
}
?>
