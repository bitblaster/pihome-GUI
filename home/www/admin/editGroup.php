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

$dataRow = array();

if($wid != "-1") {
    dbconnect();
    $sql_work_group    = "SELECT * FROM pi_groups WHERE id = ".$wid;
    $query_work_group  = mysql_query($sql_work_group);
    $dataRow   = mysql_fetch_assoc($query_work_group);
}

?>
<form id="formGroup_<?=$wid?>" method="post">
	<?=$L_EDIT_GROUP_NAME?><br>
	<input type="text" name="groupName" value="<?=utf8_encode($dataRow['group_name']);?>">
	<br><br>
	<button class="submit button-on pure-button" data-role="none" onclick="editGroupSend(<?=$wid?>)"><?=$wid != '-1' ? $L_EDIT_GROUP_UPDATE : $L_EDIT_GROUP_ADD?></button>
</form>
