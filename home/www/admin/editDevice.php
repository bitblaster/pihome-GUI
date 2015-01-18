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
    Nome dispositivo:<br>
    <input type="text" name="device_name" value="<?=utf8_encode($dataRow['device'])?>">
    <br><br>
    Flag comando:<br>
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
    Tasto comando:<br>
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
    Tipo interruttore:<br>
    <select name="type">
        <option value="simpleSwitch" <?=$dataRow['type']=='simpleSwitch' ? ' selected' : ''?>>Interruttore semplice (impulso inverte lo stato)</option>
        <option value="delaySwitch" <?=$dataRow['type']=='delaySwitch' ? ' selected' : ''?>>Interruttore con delay (impulso breve spegne, impulso lungo accende)</option>
    </select>
    <br><br>
    Presenza interruttore locale:<br>
    <select name="status">
        <option value="<?=$dataRow['status']=='-1' ? '0' : $dataRow['status'] ?>" <?=$dataRow['status']=='-1' ? '' : 'selected' ?>>No</option>
        <option value="-1" <?=$dataRow['status']=='-1' ? 'selected' : '' ?>>Sì</option>
    </select>
    <br><br>				
    <span class="submit"><input type="button" onclick="editDeviceSend(<?=$wid;?>,<?=$groupId?>)" value="<?=$wid != '-1' ? 'Update device' : 'Add device'?>"></span>	
</form>
