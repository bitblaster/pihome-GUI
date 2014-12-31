

function request(url, method, data, callback) {

	var xmlHttp = new XMLHttpRequest;
	if (!xmlHttp)
		return false;
	var _data;
	if (data != null && typeof data == "object") {
		_data = [];
		for (var i in data)
			_data.push(i + "=" + data[i]);
		_data = _data.join("&");
	} else {
		_data = data;
	}

	method = method.toUpperCase();
	if (method == "POST") {
		xmlHttp.open(method, url, true);
		xmlHttp.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	} else {
		if (_data)
			url += (url.indexOf("?") == -1 ? "?" : "&") + _data;
		_data = "";
		//alert(url);
		xmlHttp.open(method, url, true);
	}
	if (callback) {
		xmlHttp.onreadystatechange = function() {
			if (xmlHttp.readyState == 4) {
				xmlHttp.onreadystatechange = function(){};
				
				if(xmlHttp.status!=200) {
					$('.toast').text("Errore durante la chiamata al server: " + xmlHttp.responseText).fadeIn(400).delay(3000).fadeOut(400); 
				}
				else {
					$('.toast').text("Comando eseguito correttamente").fadeIn(400).delay(3000).fadeOut(400); 
					callback(xmlHttp, data);
				}
			}
		};
	}
	xmlHttp.send(_data);
	return xmlHttp;
}

function switchDevice(deviceId, action){
	var lampImgId = "lampImg_" + deviceId;
	
	// TODO: jquerizzare le function
	if(action == "off") {  
		request('request.php', 'GET', {"switchDevice": deviceId, "action": action}, function(){ document.getElementById(lampImgId).src="images/lamp_off.svg"; } );
	}
	else if(action == "on") {  
		request('request.php', 'GET', {"switchDevice": deviceId, "action": action}, function(){ document.getElementById(lampImgId).src="images/lamp_on.svg"; } );
	}
	else {
		request('request.php', 'GET', {"switchDevice": deviceId, "action": action}, function(){ document.getElementById(lampImgId).src="images/lamp_unk.svg"; } );
	}
}
	
function refresh(){
 	$('#lights').load('lights.php');
}	  
 
function alloff(){
 	if(confirm('All Devices off?')){
		request('alloff.php', 'GET', {s: ""}, function(){ $('#lights').load('lights.php'); } );
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
	request('request.php', 'GET', {addJob: deviceId}, function(){ $('#sched_' + deviceId + '_content').load('sched.php?deviceId=' + deviceId + '&type=' + type);} );
}

function removeJob(jobId, deviceId, type) {
	request('request.php', 'GET', {removeJob: jobId}, function(){ $('#sched_' + deviceId + '_content').load('sched.php?deviceId=' + deviceId + '&type=' + type); } );
}

function saveJob(jobId, deviceId) {
	
	var cronFields = $("#scheduleForm_" + deviceId + "_" + jobId).serialize() + "&";

	jsonString = $("#scheduleForm_" + deviceId + "_" + jobId).serializeJSON();
	if(jsonString.indexOf("*\",") > 0 || jsonString.indexOf(",\"*") > 0) {
		alert("Selezionati valori incompatibili!");
	}
	else {
		//alert(jsonString);
		request('request.php', 'GET', {saveJob: jsonString}, function(){} );
	}
}
