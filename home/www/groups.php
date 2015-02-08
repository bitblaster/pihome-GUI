<?
require_once(dirname(__FILE__)."/configs/functions.inc.php");

?>
<table>
<?
$rp=getGroups();

for($x=0;$x<count($rp);$x++) {
?>    
    <tr <?= $x>0 ? 'class="separator"' : ''?>>
        <td style="cursor: pointer" onclick="expandGroup('<?=$rp[$x]["id"];?>', '<?=$adminArea?>')">
            <div class="groupName" id="groupName_<?=$rp[$x]["id"];?>"><?=utf8_encode($rp[$x]["group"]);?></div><span style="display: inline-block">&nbsp;&gt;</span>
        </td>
        <? if($adminArea==1) { ?>
        <td class="btn">
            <button class="button-on pure-button" data-role="none" onclick="editGroup('<?=$rp[$x]["id"];?>');"><img class="buttonImg" src="images/edit.svg" /></button>
            <button class="button-off pure-button" data-role="none" onclick="deleteGroup('<?=$rp[$x]["id"];?>');return false;"><img class="buttonImg" src="images/delete.svg" /></button>
        </td>
        <? } else { ?>
        <td class="btn">
            <button class="button-on pure-button" data-role="none" onclick="allOnGroup('<?=$rp[$x]["id"];?>');"><?=$L_ON?></button>
            <button class="button-off pure-button" data-role="none" onclick="allOffGroup('<?=$rp[$x]["id"];?>');"><?=$L_OFF?></button>
        </td>
        <? } ?>
    </tr>
    <tr style="display:none" class="groupDevices separator" id="groupDevices_<?=$rp[$x]["id"];?>">
        <td colspan="2"><span class="groupDevicesContent" id="groupDevices_<?=$rp[$x]["id"];?>_content"></span></td>
    </tr>
<?
}
?>
</table>

<? if($adminArea==1) { ?>
<button class="button-on pure-button" style="margin: 10px" data-role="none" onclick="addGroup();"><?=$L_EDIT_GROUP_ADD?></button>
<? } ?>
