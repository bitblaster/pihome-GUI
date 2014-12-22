<?
include("configs/functions.inc.php");

$mesi = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile',
                'Maggio', 'Giugno', 'Luglio', 'Agosto',
                'Settembre', 'Ottobre', 'Novembre','Dicembre');

$giorni = array('Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato','Domenica');
		
if(isset($_GET["deviceId"])) {
    $deviceId = $_GET["deviceId"];
    $type = $_GET["type"];
    
    $result = file_get_contents("http://localhost:8444/".encrypt("readJobs/".$deviceId));
    $jobs = array();
    if($result) {
	$jobs = explode("|", $result);
    }
    
    // jobId/devId/action/year;month;day;weekday;hour;minute;second
?>
    <div data-role="main" class="ui-content">
	<table>
<? foreach ($jobs as $job) { 
      $jobMap = json_decode($job, true);
      $jobId = $jobMap["jobID"];
      $cronFields = $jobMap["cronFields"];
?>
	    <tr class="jobSchedule">
		<td>
		    <form id="scheduleForm_<?=$deviceId;?>_<?=$jobId;?>" method="get" action="#">
		      <fieldset class="ui-field-contain">
			<input type="hidden" name="jobID" value="<?=$jobId;?>">
			<input type="hidden" name="deviceId" value="<?=$deviceId;?>">
			<label>Secondo</label>
			<select name="cronFields[second][]">
			    <? for ($i = 0; $i <= 59; $i++) { ?>
				<option value="<?=$i?>" <?=($cronFields["second"] === strval($i) ? ' selected="selected"' : '')?>><?=$i?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label>Minuto</label>
			<select name="cronFields[minute][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["minute"])) ? ' selected="selected"' : '')?>>Ogni minuto</option>
			    <? for ($i = 0; $i <= 59; $i++) { ?>
				<option value="<?=$i?>" <?=(in_array(strval($i), explode(",", $cronFields["minute"])) ? ' selected="selected"' : '')?>><?=$i?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label>Ora</label>
			<select name="cronFields[hour][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["hour"])) ? ' selected="selected"' : '')?>>Ogni ora</option>
			    <? for ($i = 0; $i <= 23; $i++) { ?>
				<option value="<?=$i?>" <?=(in_array(strval($i), explode(",", $cronFields["hour"])) ? ' selected="selected"' : '')?>><?=$i?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label>Giorno</label>
			<select name="cronFields[day][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["day"])) ? ' selected="selected"' : '')?>>Ogni giorno</option>
			    <? for ($i = 1; $i <= 31; $i++) { ?>
				<option value="<?=$i?>" <?=(in_array(strval($i), explode(",", $cronFields["day"])) ? ' selected="selected"' : '')?>><?=$i?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label>Giorno Sett</label>
			<select name="cronFields[day_of_week][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["day_of_week"])) ? ' selected="selected"' : '')?>>Ogni giorno</option>
			    <? for ($i = 0; $i < 7; $i++) { ?>
				<option value="<?=$i?>" <?=(in_array(strval($i), explode(",", $cronFields["day_of_week"])) ? ' selected="selected"' : '')?>><?=$giorni[$i]?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label>Mese</label>
			<select name="cronFields[month][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["month"])) ? ' selected="selected"' : '')?>>Ogni mese</option>
			    <? for ($i = 0; $i < 12; $i++) { ?>
				<option value="<?=$i?>" <?=(in_array(strval($i), explode(",", $cronFields["month"])) ? ' selected="selected"' : '')?>><?=$mesi[$i]?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label>Anno</label>
			<select name="cronFields[year][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["year"])) ? ' selected="selected"' : '')?>>Ogni anno</option>
			    <? for ($i = 0; $i < 3; $i++) { 
				$year = date('Y', strtotime('+'.$i.' years'));
				?>
				<option value="<?=$year?>" <?=(in_array(strval($year), explode(",", $cronFields["year"])) ? ' selected="selected"' : '')?>><?=$year?></option>
			    <? } ?>
			</select>
			<br/><br/>
			<label style="display=inherit">Azione</label>
			<select name="action">
			    <? if($type=="delaySwitch") {?>
			    <option value="on" <?=($jobMap["action"] == "on" ? ' selected="selected"' : '')?>>Accendi</option>
			    <option value="off" <?=($jobMap["action"] == "off" ? ' selected="selected"' : '')?>>Spegni</option>
			    <? } ?>
			    <option value="toggle" <?=($jobMap["action"] == "toggle" ? ' selected="selected"' : '')?>>Inverti</option>
			    <option value="disabled" <?=($jobMap["action"] == "disabled" ? ' selected="selected"' : '')?>>Timer Disattivato</option>
			</select>
		      </fieldset>
		    </form>
		</td>
		<td>
		    <span style="display:block; text-align: center; "><a href="#" onclick="removeJob('<?=$jobId?>', '<?=$deviceId;?>', '<?=$type;?>');"><button class="button-off pure-button" data-role="none">&nbsp;-&nbsp;</button></a></span>
		    <span style="display:block; text-align: center; padding:5px"><a href="#" onclick="saveJob('<?=$jobId?>', '<?=$deviceId;?>', '<?=$type;?>');"><button class="button-img pure-button" data-role="none"><img class="buttonImg" src="images/save.svg" /></button></a></span>
		</td>
	    </tr>
<? } ?>	    
	    <tr>
		<td colspan="2" style="padding:10px">
		    <span ><a href="#" onclick="addJob('<?=$deviceId;?>', '<?=$type;?>');"><button class="button-on pure-button" data-role="none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></a></span>
		</td>
	    </tr>
	</table>
    </div>
<?
}
?>
