
function switchDevice(deviceId, action){
	var lampImgId = "lampImg_" + deviceId;
	
	if(action == "off") {  
		request('request.php', 'GET', {"switchDevice": deviceId, "action": action}, function() { 
			$("#"+lampImgId).attr("src", "images/lamp_off.svg");
		});
	}
	else if(action == "on") {  
		request('request.php', 'GET', {"switchDevice": deviceId, "action": action}, function() { 
			$("#"+lampImgId).attr("src", "images/lamp_on.svg");
		});
	}
	else {
		request('request.php', 'GET', {"switchDevice": deviceId, "action": action}, function() { 
			$("#"+lampImgId).attr("src", "images/lamp_unk.svg"); 
		});
	}
}
	
function refresh(){
 	$('#lights').load('lights.php');
}	  
 
function allOff(){
 	if(confirm('All Devices off?')){
		request('request.php', 'GET', {"allOff": "all"}, function() {
			$(".lampImg").attr("src", "images/lamp_off.svg");
		});
	}
}

function allOffGroup(groupId){
 	if(confirm('All Devices off?')){
		request('request.php', 'GET', {"allOff": groupId}, function() {
			$("groupDevices_" + groupId + "->.lampImg").attr("src", "images/lamp_off.svg");
		});
	}
}

function allOnGroup(groupId){
 	if(confirm('All Devices on?')){
		request('request.php', 'GET', {"allOn": groupId}, function() {
			$("groupDevices_" + groupId + "->.lampImg").attr("src", "images/lamp_on.svg");
		});
	}
}

function toggleSchedule(deviceId, type) {
	if($('#sched_' + deviceId).is(':hidden')) {
		$('.deviceSchedule').hide();
		$('#sched_' + deviceId + '_content').load('sched.php?deviceId=' + deviceId + '&type=' + type,
			function() {
				$('#sched_' + deviceId).toggle();
			}
		);
	}
	else
		$('#sched_' + deviceId).toggle();
}	

function addJob(deviceId, type) {
	request('request.php', 'GET', {addJob: deviceId}, function() {
		$('#sched_' + deviceId + '_content').load('sched.php?deviceId=' + deviceId + '&type=' + type);
	});
}

function removeJob(jobId, deviceId, type) {
	request('request.php', 'GET', {removeJob: jobId}, function() {
		$('#sched_' + deviceId + '_content').load('sched.php?deviceId=' + deviceId + '&type=' + type);
	});
}

function saveJob(jobId, deviceId) {
	
	var cronFields = $("#scheduleForm_" + deviceId + "_" + jobId).serialize() + "&";

	jsonString = $("#scheduleForm_" + deviceId + "_" + jobId).serializeJSON();
	if(jsonString.indexOf("*\",") > 0 || jsonString.indexOf(",\"*") > 0) {
		alert("Selezionati valori incompatibili!");
	}
	else {
		//alert(jsonString);
		request('request.php', 'GET', {saveJob: jsonString}, function() {});
	}
}
