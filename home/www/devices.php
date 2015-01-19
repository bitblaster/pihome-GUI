<?
require_once(dirname(__FILE__)."/configs/functions.inc.php");

$groupId=$_GET["group"];
$lp=getActiveDevices($groupId);
?>
<table id="devices_<?=$groupId?>" class="devices">

<?
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
    <tr <?= $i>0 ? 'class="separator"' : ''?>>
        <td class="lamp"><img class="lampImg" src="images/<?=$lampimg;?>" id="lampImg_<?=$lp[$i]["id"];?>" ></td>
        <td class="deviceName">
            <div class="device"><?=utf8_encode($lp[$i]["device"]);?></div>
            <div class="group">
                <!--<div class="nowrap"><?=$L_GROUP.": <strong>".utf8_encode(getGroupById($lp[$i]["group_id"]))."</strong>&nbsp;";?></div>-->
                <div class="nowrap"><?=$L_CODE.": <strong>".$lp[$i]["flags"].$lp[$i]["code"]."</strong>&nbsp;";?></div>
                <div class="nowrap"><?=$L_LOCAL_SWITCH.": <strong>".($lp[$i]["status"] == "-1" ? $L_YES : $L_NO)."</strong>&nbsp;";?></div>
            </div>
        </td>
        <td class="btn">
            <? if($lp[$i]["type"]=="simpleSwitch") {?>
            <span class="singleButton"><a href="javascript:switchDevice('<?=$lp[$i]["id"];?>', 'toggle');"><button class="button-toggle pure-button" data-role="none"><?=$L_TOGGLE?></button></a></span>
            <? } else {?>
            <span><a href="#" onclick="javascript:switchDevice('<?=$lp[$i]["id"];?>', 'on');return false;"><button class="button-on pure-button" data-role="none"><?=$L_ON?></button></a></span>
            <span><a href="#" onclick="javascript:switchDevice('<?=$lp[$i]["id"];?>', 'off');return false;"><button class="button-off pure-button" data-role="none"><?=$L_OFF?></button></a></span>
            <? } ?>
            <? if(!$mobileClient) { ?>
            <span><a href="#" onclick="javascript:toggleSchedule('<?=$lp[$i]["id"];?>', '<?=$lp[$i]["type"];?>');return false;"><button class="button-img pure-button" data-role="none"><img class="buttonImg" src="images/timer.svg" /></button></a></span>
            <? } ?>
        </td>
    </tr>
    <? if(!$mobileClient) { ?>
    <tr style="display:none" class="deviceSchedule" id="sched_<?=$lp[$i]["id"];?>">
    <td colspan="3" id="sched_<?=$lp[$i]["id"];?>_content"></td>
    </tr>
    <? } ?>
<?
}

if($i==0) {
?>
    <tr>
        <td class="deviceName" colspan="3">
            <div class="device"><i><?=$L_NO_DEVICES?></i></div>
        </td>
    </tr>
<?
}
?>
</table>
