
var bPopup=null;

function enableDevice(id){
	var quest = "?w=device&o=enabled&id=" + id;
	request('request.php', 'GET', quest, function() { 
        
        var imgUrl = $('#deviceEnabledImg_'+id).attr("src");
        
        if(imgUrl.indexOf("enabled") > 0)
            $('#deviceEnabledImg_'+id).attr("src", imgUrl.replace("enabled", "disabled"));
        else
            $('#deviceEnabledImg_'+id).attr("src", imgUrl.replace("disabled", "enabled"));
    } );
}

function addDevice(groupId){
    bPopup = $('#popup').bPopup({
        contentContainer:'.content',
        loadUrl: 'editDevice.php?w=-1&g=' + groupId,
        onClose: function(){ bPopup=null }
    });
}

function editDevice(deviceId, groupId) {	
    bPopup = $('#popup').bPopup({
        contentContainer:'.content',
        loadUrl: 'editDevice.php?w=' + deviceId + '&g=' + groupId,
        onClose: function(){ bPopup=null }
    });
}

function editDeviceSend(deviceId, groupId) {
	var deviceName = $("#formDevice_"+deviceId+" > input[name='device_name']").val();
    var flagString = "";
    $("#formDevice_"+deviceId+" > .flags > input[name='flags']:checked").each(function(i){
        flagString += $(this).val();
    });
	var ioPort = $("#formDevice_"+deviceId+" > select[name='code']").val();
	var type = $("#formDevice_"+deviceId+" > select[name='type']").val();
	var status = $("#formDevice_"+deviceId+" > select[name='status']").val();
	//var device_sort = document.getElementsByName("sort")[0].value;
	var device_sort	 = "0";
	var params = "?id=" + deviceId + "&w=device&o=" + (deviceId < 0 ? "insert" : "update") /*&enabled=" + device_enabled*/ + "&device_name=" + deviceName + "&groupId=" + groupId + "&flags=" + flagString + "&code=" + ioPort + "&type=" + type/* + "&status=" + status + "&sort=" + device_sort*/;

	if(deviceName!=""){
		request('request.php', 'GET', params, function(){ 
            if(deviceId < 0) {
                $('#groupDevices_' + groupId + '_content').load('devices.php?group=' + groupId);
            }
            else {
                $("#deviceName_" + deviceId).text(deviceName);
                $("#deviceCode_" + deviceId).text(flagString+ioPort);
                $("#deviceLocalSwitch_" + deviceId).text(status=="-1" ? "Sì" : "No");
            }

            if(bPopup != null) {
                bPopup.close();
                bPopup=null;
            }
		});
	}
}

function deleteDevice(deviceId, groupId){
	if(confirm('Delete Device?')){
		var quest = "?w=device&o=delete&id=" + deviceId;
		request('request.php', 'GET', quest, function() { 
            $('#groupDevices_' + groupId + '_content').load('devices.php?group=' + groupId);
        });
	}
}

function addGroup(){
    bPopup = $('#popup').bPopup({
        contentContainer:'.content',
        loadUrl: 'editGroup.php?w=-1',
        onClose: function(){ bPopup=null }
    });
}

function editGroup(groupId) {	
    bPopup = $('#popup').bPopup({
        contentContainer:'.content',
        loadUrl: 'editGroup.php?w=' + groupId,
        onClose: function(){ bPopup=null }
    });
}

function editGroupSend(groupId) {
    var groupName = $("#formGroup_"+groupId+" > input[name='groupName']").val();
    
	var params = "?id=" + groupId + "&w=group&o=" + (groupId < 0 ? "insert" : "update") + "&group_name=" + groupName;
	if(groupName!=""){
		request('request.php', 'GET', params, function(){ 
            if(groupId < 0) {
                $('#groups').load('groups.php');
            }
            else {
                $("#groupName_" + groupId).text(groupName);
            }
            
            if(bPopup != null) {
                bPopup.close();
                bPopup=null;
            }
        });
	}
}

function deleteGroup(groupId){
	if(confirm('Delete group?')){
		var quest = "?w=group&o=delete&id=" + groupId;
		request('request.php', 'GET', quest, function(){ 
            $('#groups').load('groups.php');
        } );
	}
}

function setPlaceholder(event, ui) {
    //ui.placeholder.html("<td colspan='3' class='sortablePlaceholder'></td>");
    //ui.placeholder.html("<td colspan='3' style='height: 20px; width: 80px; border: 2px dashed #fcefa1; background-color: #fbfbf2'></td>");
}
    
function saveReorder(groupId, ui) {
    // if we dragged the item between a row and its (hidden) edit block, push up the edit block
    if(ui.item.next().attr("id") != null && ui.item.next().attr("id").startsWith("deviceEdit_"))
        ui.item.next().insertBefore(ui.item)

    // Reposition the item's edit block after it
    var deviceId = getEditBlockDeviceId($(".device", ui.item));
    $("#deviceEdit_"+deviceId, ui.item.parent()).insertAfter(ui.item);
    
    // Compose a string with the new order
    var deviceOrder=[];
    $(".device", ui.item.parent()).each(function (i) {
        deviceOrder.push(getEditBlockDeviceId($(this)));
    });
    
    // Call the server to update the items order
    var params = "?w=device&o=reorder&groupId=" + groupId + "&order=" + deviceOrder.join(",");
	request('request.php', 'GET', params, function(){ } );
};

function getEditBlockDeviceId(device) {
    var deviceId = device.attr("id");
    return deviceId.substring(deviceId.indexOf("_")+1);
}
