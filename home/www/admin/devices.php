<?
require_once("access.php");

$groupId=$_GET["group"];
$lp=getDevices($groupId);
?>
<table id="devices_<?=$groupId?>" class="devices">

<?
for($i=0;$i<count($lp);$i++) {
?>
    <tr <?= $i>0 ? 'class="separator"' : ''?>>
        <td class="deviceName">
            <div class="device" id="deviceName_<?=$lp[$i]["id"];?>"><?=utf8_encode($lp[$i]["device"]);?></div>
            <div class="group">
            <!--<div class="nowrap"><? //$L_GROUP.': <strong>'.utf8_encode(getGroupById($lp[$i]["group_id"])).'</strong>&nbsp;';?></div>-->
            <div class="nowrap"><?=$L_CODE.': <strong id="deviceCode_'.$lp[$i]["id"].'">'.$lp[$i]["flags"].$lp[$i]["code"]."</strong>&nbsp;";?></div>
            <div class="nowrap"><?=$L_LOCAL_SWITCH.': <strong id="deviceLocalSwitch_'.$lp[$i]["id"].'">'.($lp[$i]["status"] == "-1" ? $L_YES : $L_NO)."</strong>&nbsp;";?></div>
            </div>
        </td>
        <td class="btn">
            <button id="btnEnable_<?=$lp[$i]["id"];?>" class="button-enable pure-button" data-role="none" onclick="enableDevice('<?=$lp[$i]["id"];?>');"><img class="buttonImg" style="height:1.2em" id="deviceEnabledImg_<?=$lp[$i]["id"]?>" src="images/<?=$lp[$i]["enabled"]=="0" ? "disabled.svg" : "enabled.svg" ?>" /></button>
            <button id="btnEdit_<?=$lp[$i]["id"];?>" class="button-on pure-button" data-role="none" onclick="editDevice('<?=$lp[$i]["id"];?>', '<?=$groupId?>');"><img class="buttonImg" src="images/edit.svg" /></button>
            <button id="btnDelete_<?=$lp[$i]["id"];?>" class="button-off pure-button" data-role="none" onclick="deleteDevice('<?=$lp[$i]["id"];?>', '<?=$groupId?>');"><img class="buttonImg" src="images/delete.svg" /></button>
            <span id="dragHandle_<?=$lp[$i]["id"];?>" class="reorderHandle"><img src="images/reorder.svg" /></span>
            
        </td>
    </tr>
<?
}
?>    
</table>

<button class="button-on pure-button" style="margin: 10px" data-role="none" onclick="addDevice('<?=$groupId?>');"><?=$L_EDIT_DEVICE_ADD?></button>

<script type="text/javascript">

var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width())
    });
    return $helper;
};

$("#devices_<?=$groupId?> tbody").sortable({
    helper: fixHelperModified,
    axis: "y",
    handle: ".reorderHandle",
    //opacity: 0.75,
    revert: 150,
    scrollSpeed: 200,
    scroll: true,
    placeholder: "sortablePlaceholder",
    cursor: "move",
    start: setPlaceholder,
    stop: function(event, ui) {
        saveReorder('<?=$groupId?>', ui);
    }
}).disableSelection();

</script>
