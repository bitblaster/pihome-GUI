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

require('../configs/dbconfig.inc.php');
require('configs/functions.inc.php');
?>

<ul>
	<?
	$lp=getLights();
	for($i=0;$i<count($lp);$i++){
	
	if($lp[$i]["status"]=="0"){ $lampimg = "lamp_off.png"; $btn1 = "on_off.png"; $btn2 = "off_on.png"; }
	elseif($lp[$i]["status"]=="1"){ $lampimg = "lamp_on.png"; $btn1 = "on_on.png"; $btn2 = "off_off.png"; }
		
	if($lp[$i]["enabled"]=="0"){ $deimg="disabled.png"; }else{ $deimg="enabled.png"; }
	?>	
		<li>
			<div class="light">
				<div class="name">
					<div class="device"><?=utf8_encode($lp[$i]["device"]);?></div>
					<div class="room">
						<?="Stanza: <strong>".utf8_encode(getRoomById($lp[$i]["room_id"])).
						"</strong>&nbsp;&nbsp;Codice: <strong>".$lp[$i]["flags"].$lp[$i]["code"].
						"</strong>&nbsp;&nbsp;Interruttore locale: <strong>".
						($lp[$i]["status"] == "-1" ? "Sì" : "No")."</strong>";?>
					</div>
				</div>
				<!-- <div class="flags"><?=$lp[$i]["flags"] == '' ? '-' : $lp[$i]["flags"];?></div>
				<div class="code"><?=$lp[$i]["code"];?></div>
				<div class="code"><?=$lp[$i]["status"] == "-1" ? "Sì" : "No";?></div> -->
				<div class="btn">
					<span>
						<a href="javascript:enable_device('<?=$lp[$i]["id"];?>');">
							<img src="images/<?=$deimg;?>" border="0">
						</a>
					</span>
					<span>
						<a href="javascript:update_device('<?=$lp[$i]["id"];?>');">
							<img src="images/work.png" border="0">
						</a>
					</span>
					<span>
						<a href="javascript:del_device('<?=$lp[$i]["id"];?>');">
							<img id="btn2_<?=$lp[$i]["id"];?>" src="images/del.png" border="0">
						</a>
					</span>				
				</div>
			</div>
		</li>
	<?
	}
	?>	
	</ul>
