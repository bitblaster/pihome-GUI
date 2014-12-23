
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
	//alert(url);
	if (method == "POST") {
		xmlHttp.open(method, url, true);
		xmlHttp.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	} else {
		if (_data)
			url += (url.indexOf("?") == -1 ? (_data.charAt(0) == "?" ? "" : "?") : "&") + _data;
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
	
function toggle_add_device(){
	var img_device = document.getElementById("add_image_device");
	if(img_device.name=="0"){
		$("#add_device").slideToggle("fast"); 
		img_device.name = "1";
		img_device.src = "images/add_off.png";				
	}else if(img_device.name=="1"){
		$("#add_device").slideToggle("fast"); 
		img_device.name = "0";
		img_device.src = "images/add.png";				
	}		
}


function toggle_add_rooms(){
	var img_device = document.getElementById("add_image_room");
	if(img_device.name=="0"){
		$("#add_room").slideToggle("fast"); 		
		img_device.name = "1";
		img_device.src = "images/add_off.png";				
	}else if(img_device.name=="1"){
		$("#add_room").slideToggle("fast"); 
		img_device.name = "0";
		img_device.src = "images/add.png";				
	}
}


function del_device(wid){
	if(confirm('Delete Device?')){
		var quest = "?w=device&o=delete&wid=" + wid;
		request('request.php', 'GET', quest, function(){ $('#lights').load('lights.php'); } );
	}
}


function del_room(wid){
	if(confirm('Delete Room?')){
		var quest = "?w=room&o=delete&wid=" + wid;
		request('request.php', 'GET', quest, function(){ $('#homerooms').load('rooms.php'); } );
	}
}


function enable_device(wid){
	var quest = "?w=device&o=enabled&wid=" + wid;
	request('request.php', 'GET', quest, function(){ $('#lights').load('lights.php'); } );
}

function add_device(){
	var device_enabled = document.getElementsByName("enabled")[0].value;
	var device_name  = document.getElementsByName("device_name")[0].value;
	var room_id      = document.getElementsByName("room_id")[0].value;
	var flags        = document.getElementsByName("flags");
	var flagString   = "";
	for(var i=0; i < flags.length; i++) {
		if(flags[i].checked)
			flagString += flags[i].value;
	}
	var ioPort  	 = document.getElementsByName("code")[0].value;
	var type 		 = document.getElementsByName("type")[0].value;
	var status 		 = document.getElementsByName("status")[0].value;
	var device_sort	 = document.getElementsByName("sort")[0].value;				
	var params = "?w=device&o=insert&enabled=" + device_enabled + "&device_name=" + device_name + "&room=" + room_id + "&flags=" + flagString + "&code=" + ioPort + "&type=" + type + "&status=" + status + "&sort=" + device_sort;
	if(device_name!=""){
		request('request.php', 'GET', params, function(){ window.location.reload(); } );
	}
}

function add_room(){
	var room_name   = document.getElementsByName("room")[0].value;
	var params 		= "?w=room&o=insert&room_name=" + room_name;
	if(room_name!=""){
		request('request.php', 'GET', params, function(){ window.location.reload(); } );
	}
}

function update_device(id){		
	document.getElementById('work_device').style.display = "block";			
	document.getElementById('add_device_btn').style.display = "none";
	document.getElementById('work_device_btn').style.display = "block";
	document.getElementById('add_device').style.display = "none";
	$('#work_device').load('work.php?w=' + id + '&o=device');				
}

function update_device_send(id){
	document.getElementById('work_device').style.display = "none";
	document.getElementById('work_device_btn').style.display = "none";
	document.getElementById('add_device_btn').style.display = "block";
	document.getElementById('add_device').style.display = "none";
	var img_device = document.getElementById("add_image_device");
	img_device.name = "0";
	img_device.src = "images/add.png";		
	var device_enabled = document.getElementsByName("wenabled")[0].value;
	var device_name  = document.getElementsByName("wdevice_name")[0].value;
	var room_id      = document.getElementsByName("wroom_id")[0].value;
	var flags        = document.getElementsByName("wflags");
	var flagString   = "";
	for(var i=0; i < flags.length; i++) {
		if(flags[i].checked)
			flagString += flags[i].value;
	}
	var ioPort 		 = document.getElementsByName("wcode")[0].value;
	var type 		 = document.getElementsByName("wtype")[0].value;
	var status 		 = document.getElementsByName("wstatus")[0].value;
	var device_sort	 = document.getElementsByName("wsort")[0].value;				
	var params = "?wid=" + id + "&w=device&o=update&enabled=" + device_enabled + "&device_name=" + device_name + "&room=" + room_id + "&flags=" + flagString + "&code=" + ioPort + "&type=" + type + "&status=" + status + "&sort=" + device_sort;

	if(device_name!=""){
		request('request.php', 'GET', params, function(){ window.location.reload(); } );
	}
}

function close_work_device(){
	document.getElementById('work_device').style.display = "none";
	document.getElementById('work_device_btn').style.display = "none";
	document.getElementById('add_device_btn').style.display = "block";		
	document.getElementById('add_device').style.display = "none";
	var img_device = document.getElementById("add_image_device");
	img_device.name = "0";
	img_device.src = "images/add.png";
}







function update_room(id){
	document.getElementById('work_room').style.display = "block";			
	document.getElementById('add_room_btn').style.display = "none";
	document.getElementById('work_room_btn').style.display = "block";
	document.getElementById('add_room').style.display = "none";
	$('#work_room').load('work.php?w=' + id + '&o=room');	
}

function update_room_send(id){
	document.getElementById('work_room').style.display = "none";
	document.getElementById('work_room_btn').style.display = "none";
	document.getElementById('add_room_btn').style.display = "block";
	document.getElementById('add_room').style.display = "none";
	var img_room = document.getElementById("add_image_room");
	img_room.name = "0";
	img_room.src = "images/add.png";
	var room_name = document.getElementsByName("wroom")[0].value;
	var params = "?wid=" + id + "&w=room&o=update&room_name=" + room_name;
	if(room_name!=""){
		request('request.php', 'GET', params, function(){ window.location.reload(); } );
	}
}

function close_work_room(){
	document.getElementById('work_room').style.display = "none";
	document.getElementById('work_room_btn').style.display = "none";
	document.getElementById('add_room_btn').style.display = "block";		
	document.getElementById('add_room').style.display = "none";
	var img_room = document.getElementById("add_image_room");
	img_room.name = "0";
	img_room.src = "images/add.png";
}
	
	
