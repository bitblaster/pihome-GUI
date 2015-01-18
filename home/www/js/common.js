
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

function expandGroup(groupId) {
    if($('#groupDevices_' + groupId).is(':hidden')) {
        var visibleGroups = $('.groupDevices:visible');
		$('.groupDevicesContent:visible').slideUp("200", function() {
            visibleGroups.hide();
        });
        
        $('#groupDevices_' + groupId + '_content').load('devices.php?group=' + groupId,
            function() {
                $('#groupDevices_' + groupId).show();
                $('#groupDevices_' + groupId + '_content').hide().slideDown("200").css({ display:'block' });
            }
        );
	}
	else {
		$('#groupDevices_' + groupId + '_content').slideUp("200", function() {
			$('#groupDevices_' + groupId).hide();
        });
		
	}
}
