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
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>PiHome - Pannello di controllo</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, user-scalable=no" />
<meta name="format-detection" content="telephone=yes">
<link rel="shortcut icon" href="images/favicon.png" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
<script src="../js/jquery-1.11.1.min.js"></script>
<script src="js/tabcontent.js"></script>
<script src="js/request.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#lights').load('lights.php');
		$('#homerooms').load('rooms.php');
	});	
</script>
</head>
<body>


<div id="nav">
	<span><img src="images/pihome_admin.png" id="home" border="0"></span><span><img src="images/spacer.png" border="0"></span><span><a href="index.php?p=logout"><img src="images/logout.png" border="0"></a></span>
</div>


<div id="tabcontainer">

	<ul id="tabs" class="shadetabs">
	<li><a href="#" rel="devices" class="selected">Dispositivi</a></li>
	<li><a href="#" rel="rooms">Zone</a></li>
	</ul>
	
	<br><br>

	<div id="devices" class="tabcontent">
		<div id="add_device_btn"><br><a href="javascript:toggle_add_device()"><img src="images/add.png" name="0" id="add_image_device" border="0" /></a><br></div>
		
		<div id="work_device_btn"><a href="javascript:close_work_device()"><br><img src="images/close.png" id="work_image_device" border="0" /></a><br></div>
		
		<div id="work_device"></div>
		
		<div id="add_device">
		
		<form id="formdevice" method="post">
			Nome Dispositivo:<br>
			<input type="text" name="device_name">
			<br><br>
			Stato:<br>
			<select name="enabled">
				<option value="1">Abilitato</option>
				<option value="0">Disabilitato</option>		
			</select>
			<br><br>
			Zona:<br>
			<select name="room_id">
				<? 
				$ro=getRooms();
				for($x=0;$x<count($ro);$x++){					
					echo '<option value="'.$ro[$x]["id"].'">'.utf8_encode($ro[$x]["room"]).'</option>';
				} 
				?>
			</select>
			<br><br>
			Flag comando:<br>
			<span class="flag">
				A<input type="checkbox" name="flags" value="A" />&nbsp;
				B<input type="checkbox" name="flags" value="B" />&nbsp;
				C<input type="checkbox" name="flags" value="C" />&nbsp;
				<!--D<input type="checkbox" name="flags" value="D"> -->
			</span>
			<br><br>
			Tasto comando:<br>
			<select name="code">
				<? for ($x = 2; $x <= 10; $x+=2) {?>
				<option value="<?=$x?>"><?=$x?></option>
				<? } ?>
			</select>
			<br><br>
			Tipo interruttore:<br>
			<select name="type">
				<option value="simpleSwitch">Interruttore semplice (impulso inverte lo stato)</option>
				<option value="delaySwitch">Interruttore con delay (impulso breve spegne, impulso lungo accende)</option>
			</select>
			<br><br>
			Presenza interruttore locale:<br>
			<select name="status">
				<option value="0">No</option>
				<option value="-1">Sì</option>
			</select>
			<br><br>
			Posizione:<br>
			<input type="text" name="sort" size="10" value="0">				
			<br><br>				
			<span class="submit"><input type="button" onclick="add_device()" value="Crea Dispositivo"></span>	
		</form>

		</div>
		
		<br>
		<div id="lights">
		</div>
	</div>
	
	
	<div id="rooms" class="tabcontent">
		<div id="add_room_btn"><br><a href="javascript:toggle_add_rooms()"><img src="images/add.png" name="0" id="add_image_room" border="0" /></a><br></div>
		
		<div id="work_room_btn"><a href="javascript:close_work_room()"><br><img src="images/close.png" id="work_image_room" border="0" /></a><br></div>
		
		<div id="work_room"></div>
		
		<div id="add_room">
		
			<form method="post" id="formroom">
				Nome Zona:<br>
				<input type="text" name="room">
				<br><br>
				<span class="submit"><input type="button" onclick="add_room();" value="Crea Zona"></span>
			</form>
		
		</div>
		
		<br>
		<div id="homerooms"></div>	
	</div>

</div>


<script type="text/javascript">
var countries=new ddtabcontent("tabs")
countries.setpersist(true)
countries.setselectedClassTarget("link")
countries.init()
</script>


<div id="settings">
	<a href="../"><img src="images/back-pihome.png" border="0" /></a>
</div>


<div id="copy"><?=getcopy();?></div>

<div class='toast' style='display:none'></div>

</body>
</html>
