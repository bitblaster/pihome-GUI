
<table id="lightsTable">
<?
$lp=getActiveLights();

for($i=0;$i<count($lp);$i++){

    if($lp[$i]["status"]=="0") {
	$lampimg = "lamp_off.svg";
    }
    elseif($lp[$i]["status"]=="1") {
	$lampimg = "lamp_on.svg";
    }
    elseif($lp[$i]["status"]=="-1") {
	$lampimg = "lamp_unk.svg";
    }
	
?>	
    <tr <?= $i==0 ? '' : 'class="separator"' ?>>
	<td class="lamp"><img class="lampImg" src="images/<?=$lampimg;?>" id="lampImg_<?=$lp[$i]["id"];?>" ></td>
	<td class="name">
		<div class="device"><?=utf8_encode($lp[$i]["device"]);?></div>
		<div class="room">
			<div class="nowrap"><?="Stanza: <strong>".utf8_encode(getRoomById($lp[$i]["room_id"]))."</strong>&nbsp;";?></div>
			<div class="nowrap"><?="Codice: <strong>".$lp[$i]["flags"].$lp[$i]["code"]."</strong>&nbsp;";?></div>
			<div class="nowrap"><?="Interruttore locale: <strong>".($lp[$i]["status"] == "-1" ? "Sì" : "No")."</strong>&nbsp;";?></div>
		</div>
	</td>
	<td class="btn">
	    <? if($lp[$i]["type"]=="simpleSwitch") {?>
	    <span class="singleButton"><a href="javascript:switchDevice('<?=$lp[$i]["id"];?>', 'toggle');"><button class="button-toggle pure-button" data-role="none">Inverti</button></a></span>
	    <? } else {?>
<!--	    <span><a href="#" onclick="javascript:switchDevice('<?=$lp[$i]["id"];?>', '<?=$lp[$i]["status"]=="-1" ? "toggleon" : "on";?>');"><button class="button-on pure-button" data-role="none">On</button></a></span>
	    <span><a href="#" onclick="javascript:switchDevice('<?=$lp[$i]["id"];?>', '<?=$lp[$i]["status"]=="-1" ? "toggleoff" : "off";?>');"><button class="button-off pure-button" data-role="none">Off</button></a></span>
-->	    <span><a href="#" onclick="javascript:switchDevice('<?=$lp[$i]["id"];?>', 'on');"><button class="button-on pure-button" data-role="none">On</button></a></span>
	    <span><a href="#" onclick="javascript:switchDevice('<?=$lp[$i]["id"];?>', 'off');"><button class="button-off pure-button" data-role="none">Off</button></a></span>
	    <? } ?>
	    <? if(!$mobileClient) { ?>
	    <span><a href="#" onclick="javascript:toggleSchedule('<?=$lp[$i]["id"];?>', '<?=$lp[$i]["type"];?>');"><button class="button-img pure-button" data-role="none"><img class="buttonImg" src="images/timer.svg" /></button></a></span>
	    <? } ?>
	</td>
    </tr>
    <? if(!$mobileClient) { ?>
    <tr style="display:none" class="deviceSchedule" id="sched_<?=$lp[$i]["id"];?>">
	<td colspan="3" id="sched_<?=$lp[$i]["id"];?>_content">
	</td>
    </tr>
    <? } ?>
<?
}
?>	
</table>
