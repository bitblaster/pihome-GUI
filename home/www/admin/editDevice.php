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

$wid = $_GET["w"];

$groupId = $_GET['g'];

$dataRow = array();
if($wid != "-1") {
    dbconnect();
    $sql_work_data    = "SELECT * FROM pi_devices WHERE id = ".$wid;
    $query_work_data  = mysql_query($sql_work_data);
    $dataRow   = mysql_fetch_assoc($query_work_data);
}
else {
    $dataRow['device']="";
    $dataRow['group_id']="";
    $dataRow['flags']="";
    $dataRow['code']="";
    $dataRow['type']="delaySwitch";
    $dataRow['status']="0";
}

?>
<form id="formDevice_<?=$wid?>" method="post">
    <?=$L_EDIT_DEVICE_NAME?>:<br>
    <input type="text" name="device_name" value="<?=utf8_encode($dataRow['device'])?>">
    <br><br>
    <?=$L_EDIT_DEVICE_FLAGS?>:<br>
    <span class="flags">
        <? for ($x = ord('A'); $x <= ord('C'); $x++) {
             if (strpos($dataRow['flags'], $x) !== false) { ?>
        <?=chr($x)?><input type="checkbox" name="flags" value="<?=chr($x)?>" checked />&nbsp;
        <?   }else{ ?>
        <?=chr($x)?><input type="checkbox" name="flags" value="<?=chr($x)?>" />&nbsp;
        <?   } ?>
        <? } ?>
    </span>
    <br><br>
    <?=$L_EDIT_DEVICE_COMMAND?>:<br>
    <select name="code">
        <? for ($x = 2; $x <= 12; $x+=2) {
             if($dataRow['code']==$x){ ?>
        <option value="<?=$x?>" selected><?=$x?></option>
        <?   }else{ ?>
        <option value="<?=$x?>"><?=$x?></option>
        <?   } ?>
        <? } ?>
    </select>
    <br><br>
    <?=$L_EDIT_DEVICE_SWITCH_TYPE?>:<br>
    <select name="type">
        <option value="simpleSwitch" <?=$dataRow['type']=='simpleSwitch' ? ' selected' : ''?>><?=$L_EDIT_DEVICE_SIMPLE_SWITCH?></option>
        <option value="delaySwitch" <?=$dataRow['type']=='delaySwitch' ? ' selected' : ''?>><?=$L_EDIT_DEVICE_DELAY_SWITCH?></option>
    </select>
    <br><br>
    <?=$L_EDIT_DEVICE_LOCAL_SWITCH?>:<br>
    <select name="status">
        <option value="<?=$dataRow['status']=='-1' ? '0' : $dataRow['status'] ?>" <?=$dataRow['status']=='-1' ? '' : 'selected' ?>><?=$L_NO?></option>
        <option value="-1" <?=$dataRow['status']=='-1' ? 'selected' : '' ?>><?=$L_YES?></option>
    </select>
    <br><br>
    <button class="submit button-on pure-button" data-role="none" onclick="editDeviceSend(<?=$wid;?>,<?=$groupId?>)"><?=$wid != '-1' ? $L_EDIT_DEVICE_UPDATE : $L_EDIT_DEVICE_ADD?></button>
</form>
