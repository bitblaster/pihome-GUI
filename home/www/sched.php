<?
include("configs/functions.inc.php");
		
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
<!--<?=$result?>-->
	<table>
<? foreach ($jobs as $job) { 
      $jobMap = json_decode($job, true);
      $jobId = $jobMap["jobID"];
      if(!$jobId)
	continue;
	
      $cronFields = $jobMap["cronFields"];
?>
	    <tr class="jobSchedule">
		<td>
		    <form id="scheduleForm_<?=$deviceId;?>_<?=$jobId;?>" method="get" action="#">
		      <fieldset class="ui-field-contain">
			<input type="hidden" name="jobID" value="<?=$jobId;?>">
			<input type="hidden" name="deviceId" value="<?=$deviceId;?>">
			<label><?=$L_SCHED_SECOND?></label>
			<select name="cronFields[second][]">
			    <? for ($i = 0; $i <= 59; $i++) { ?>
				<option value="<?=$i?>" <?=($cronFields["second"] === strval($i) ? ' selected="selected"' : '')?>><?=$i?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label><?=$L_SCHED_MINUTE?></label>
			<select name="cronFields[minute][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["minute"])) ? ' selected="selected"' : '')?>><?=$L_SCHED_EVERY_MINUTE?></option>
			    <? for ($i = 0; $i <= 59; $i++) { ?>
				<option value="<?=$i?>" <?=(in_array(strval($i), explode(",", $cronFields["minute"])) ? ' selected="selected"' : '')?>><?=$i?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label><?=$L_SCHED_HOUR?></label>
			<select name="cronFields[hour][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["hour"])) ? ' selected="selected"' : '')?>><?=$L_SCHED_EVERY_HOUR?></option>
			    <? for ($i = 0; $i <= 23; $i++) { ?>
				<option value="<?=$i?>" <?=(in_array(strval($i), explode(",", $cronFields["hour"])) ? ' selected="selected"' : '')?>><?=$i?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label><?=$L_SCHED_DAY?></label>
			<select name="cronFields[day][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["day"])) ? ' selected="selected"' : '')?>><?=$L_SCHED_EVERY_DAY?></option>
			    <? for ($i = 1; $i <= 31; $i++) { ?>
				<option value="<?=$i?>" <?=(in_array(strval($i), explode(",", $cronFields["day"])) ? ' selected="selected"' : '')?>><?=$i?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label><?=$L_SCHED_DAY_OF_WEEK?></label>
			<select name="cronFields[day_of_week][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["day_of_week"])) ? ' selected="selected"' : '')?>><?=$L_SCHED_EVERY_DAY?></option>
			    <? for ($i = 0; $i < 7; $i++) { ?>
				<option value="<?=$i?>" <?=(in_array(strval($i), explode(",", $cronFields["day_of_week"])) ? ' selected="selected"' : '')?>><?=$L_WEEK_DAYS[$i]?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label><?=$L_SCHED_MONTH?></label>
			<select name="cronFields[month][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["month"])) ? ' selected="selected"' : '')?>><?=$L_SCHED_EVERY_MONTH?></option>
			    <? for ($i = 0; $i < 12; $i++) { ?>
				<option value="<?=$i?>" <?=(in_array(strval($i), explode(",", $cronFields["month"])) ? ' selected="selected"' : '')?>><?=$L_MONTHS[$i]?></option>
			    <? } ?>
			</select>
			&nbsp;&nbsp;
			<label><?=$L_SCHED_YEAR?></label>
			<select name="cronFields[year][]" multiple="true">
			    <option value="*" <?=(in_array("*", explode(",", $cronFields["year"])) ? ' selected="selected"' : '')?>><?=$L_SCHED_EVERY_YEAR?></option>
			    <? for ($i = 0; $i < 3; $i++) { 
				$year = date('Y', strtotime('+'.$i.' years'));
				?>
				<option value="<?=$year?>" <?=(in_array(strval($year), explode(",", $cronFields["year"])) ? ' selected="selected"' : '')?>><?=$year?></option>
			    <? } ?>
			</select>
			<br/><br/>
			<label style="display=inherit"><?=$L_SCHED_ACTION?></label>
			<select name="action">
			    <? if($type=="delaySwitch") {?>
			    <option value="on" <?=($jobMap["action"] == "on" ? ' selected="selected"' : '')?>><?=$L_SCHED_ACTION_TURN_ON?></option>
			    <option value="off" <?=($jobMap["action"] == "off" ? ' selected="selected"' : '')?>><?=$L_SCHED_ACTION_TURN_OFF?></option>
			    <? } ?>
			    <option value="toggle" <?=($jobMap["action"] == "toggle" ? ' selected="selected"' : '')?>><?=$L_SCHED_ACTION_TOGGLE?></option>
			    <option value="disabled" <?=($jobMap["action"] == "disabled" ? ' selected="selected"' : '')?>><?=$L_SCHED_ACTION_TIMER_DISABLED?></option>
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
