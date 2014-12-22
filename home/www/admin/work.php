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


$wid = $_GET["w"];
$opp =  $_GET['o'];


if($opp=="device"){
dbconnect();
$sql_work_data    = "SELECT * FROM pi_devices WHERE id = ".$wid;
$query_work_data  = mysql_query($sql_work_data);
while($workdata   = mysql_fetch_assoc($query_work_data)){
?>
<form id="formdevice" method="post">
	Nome dispositivo:<br>
	<input type="text" name="wdevice_name" value="<?=utf8_encode($workdata['device'])?>">
	<br><br>
	Stato:<br>
	<select name="wenabled">
		<? if($workdata['enabled']=="0"){ ?>
		<option value="0">Disabilitato</option>
		<option value="1">Abilitato</option>
		<? }else{ ?>
		<option value="1">Abilitato</option>
		<option value="0">Disabilitato</option>		
		<? } ?>

	</select>
	<br><br>
	Zona:<br>
	<select name="wroom_id">
		<? 
		$ro=getRooms();
		for($x=0;$x<count($ro);$x++){	
			if($ro[$x]["id"]==$workdata['room_id']){	
				echo '<option value="'.$ro[$x]["id"].'" selected>'.utf8_encode($ro[$x]["room"]).'</option>';
			}else{
				echo '<option value="'.$ro[$x]["id"].'">'.utf8_encode($ro[$x]["room"]).'</option>';
			}
		} 
		?>
	</select>
	<br><br>
	Flag comando:<br>
	<span class="wflags">
		<? for ($x = ord('A'); $x <= ord('C'); $x++) {
			 if (strpos($workdata['flags'], $x) !== false) { ?>
		<?=chr($x)?><input type="checkbox" name="wflags" value="<?=chr($x)?>" checked />&nbsp;
		<?   }else{ ?>
		<?=chr($x)?><input type="checkbox" name="wflags" value="<?=chr($x)?>" />&nbsp;
		<?   } ?>
		<? } ?>
	</span>
	<br><br>
	Tasto comando:<br>
	<select name="wcode">
		<? for ($x = 2; $x <= 12; $x+=2) {
			 if($workdata['code']==$x){ ?>
		<option value="<?=$x?>" selected><?=$x?></option>
		<?   }else{ ?>
		<option value="<?=$x?>"><?=$x?></option>
		<?   } ?>
		<? } ?>
	</select>
	<br><br>
	Tipo interruttore:<br>
	<select name="wtype">
		<option value="simpleSwitch" <?=$workdata['type']=='simpleSwitch' ? ' selected' : ''?>>Interruttore semplice (impulso inverte lo stato)</option>
		<option value="delaySwitch" <?=$workdata['type']=='delaySwitch' ? ' selected' : ''?>>Interruttore con delay (impulso breve spegne, impulso lungo accende)</option>
	</select>
	<br><br>
	Presenza interruttore locale:<br>
	<select name="wstatus">
		<option value="<?=$workdata['status']=='-1' ? '0' : $workdata['status'] ?>" <?=$workdata['status']=='-1' ? '' : 'selected' ?>>No</option>
		<option value="-1" <?=$workdata['status']=='-1' ? 'selected' : '' ?>>Sì</option>
	</select>
	<br><br>
	Posizione:<br>
	<input type="text" name="wsort" size="10" value="<?=$workdata['sort'];?>">				
	<br><br>				
	<span class="submit"><input type="button" onclick="update_device_send(<?=$workdata['id'];?>)" value="Salva modifiche"></span>	
</form>
<?
}
}elseif($opp=="room"){
dbconnect();
$sql_work_room    = "SELECT * FROM pi_rooms WHERE id = ".$wid;
$query_work_room  = mysql_query($sql_work_room);
while($workroom   = mysql_fetch_assoc($query_work_room)){
?>
<form method="post" id="formroom">
	Nome Zona:<br>
	<input type="text" name="wroom" value="<?=utf8_encode($workroom['room']);?>">
	<br><br>
	<span class="submit"><input type="button" onclick="update_room_send(<?=$workroom['id']?>);" value="Update room"></span>
</form>
<?
}
}
?>
