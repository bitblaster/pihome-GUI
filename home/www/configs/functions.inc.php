<?
/*
 * PiHome v1.0
 * http://pihome.draw-design.com/
 *
 * PiHome Copyright (c) 2012, Sebastian Harke
 * Lizenz Informationen.
 * 
 *
*/

require_once(dirname(__FILE__)."/piconfig.inc.php");
require_once(dirname(__FILE__)."/lang_en.php");

session_start();

if (isset($_GET["lang"])) {
	$_SESSION["lang"] = $_GET["lang"];
	setcookie("lang", $_GET["lang"], time() + (86400 * 10000), "/"); 
}

if (!isset($_SESSION["lang"])) {
	if(isset($_COOKIE["lang"])) {
		$_SESSION["lang"] = $_COOKIE["lang"];
	}
	else {
		$_SESSION["lang"] = "en";
	}
}

require_once(dirname(__FILE__)."/lang_".$_SESSION["lang"].".php");

function get_db_table($data) {
	global $config;
	return "".$config['prefix']."".$data."";
}

function get_date() {
	$now=date("d.m.Y, H:i:s",time());
	return $now;
}

function getCutStrip($cs,$ml,$end) {
	$cutstrip = $cs;
	$maxlaenge = $ml;
	$cutstrip = (strlen($cutstrip) > $maxlaenge) ? substr($cutstrip,0,$maxlaenge).$end : $cutstrip;
	return $cutstrip;
}

function dbconnect() {
    global $config;
	if (!($link = mysql_connect($config['DB_HOST'], $config['DB_USER'], $config['DB_PWD'])))
	{
        print "<h3>could not connect to database</h3>\n";
		exit;
	}
	mysql_select_db($config['DB_NAME']);
    return $link;
}

function getcopy() {
	return '<a href="http://" target="_blank" title="PiHome">PiHome</a> &#169; '.date('Y');
}

function getActiveDevices($groupId) {
	dbconnect();
	$sql_getLights       = "SELECT * FROM  pi_devices WHERE group_id=".$groupId." AND enabled = '1' ORDER BY sort";
	$query_getLights     = mysql_query($sql_getLights);	
	$x=0;
	while($light = mysql_fetch_assoc($query_getLights)){
		$devices[$x]["id"] = $light['id'];
		$devices[$x]["group_id"] = $light['group_id'];
		$devices[$x]["device"] = $light['device'];
		$devices[$x]["flags"] = $light['flags'];
		$devices[$x]["code"] = $light['code'];
		$devices[$x]["type"] = $light['type'];
		$devices[$x]["status"] = $light['status'];
		$x=$x+1;
	}
	return $devices;
}

function getGroupById($id) {
	dbconnect();
	$sql_getgroup       = "SELECT * FROM  pi_groups  WHERE id = '".$id."' ";
	$query_getgroup      = mysql_query($sql_getgroup);
	while($getgroup = mysql_fetch_assoc($query_getgroup)){
		return $getgroup['group_name'];
	}
}

function getGroups() {
    dbconnect();
    $sql_getGroups       = "SELECT * FROM  pi_groups";
    $query_getGroups     = mysql_query($sql_getGroups);	
    $x=0;
    while($group = mysql_fetch_assoc($query_getGroups)) {
        $groups[$x]["id"] = $group['id'];
        $groups[$x]["group"] = $group['group_name'];
        $x=$x+1;
    }
    return $groups;
}

function allOff() {
	dbconnect();
	$sql_alloff = "SELECT * FROM pi_devices WHERE status = 1 ";
	$query_alloff = mysql_query($sql_alloff);
	while($getallon = mysql_fetch_assoc($query_alloff)){
		$stat="off";
		#echo $getallon["id"]."  ".$getallon['flags']."  ".$getallon['code']."<br>";
		setLightStatus($getallon["id"],$stat);
		file_get_contents("http://localhost:8550/request/".$getallon['flags']."/".$stat."/".$getallon['code']);
	}
}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function encrypt($text) {
	global $config;
	$iv = $config['encrypt_iv'];
	$passphrase = $config['encrypt_passphrase'];
	error_log("iv: ".$iv.", pwd: ".$passphrase);
	return base64_encode(mcrypt_encrypt(MCRYPT_BLOWFISH, $passphrase, $text, MCRYPT_MODE_CBC, $iv));
}

function callPiServer($requestString) {
	global $config;
	global $L_MSG_ERROR_PISERVER_UNREACHABLE;
	
    if(strpos($requestString, "?")>0)
        $requestString = $requestString."&client=webInterface&time=".strval(time());
    else
        $requestString = $requestString."?client=webInterface&time=".strval(time());
        
    $result = file_get_contents($config['pi_server_url']."/".encrypt($requestString));

	if($result == false) {		
		if(isset($http_response_header)) {
			list($version,$status_code,$msg) = explode(' ',$http_response_header[0], 3);
	
			//error_log("PiHome server response: ".$http_response_header[0]."---".$status_code.", ".$msg);
			http_response_code($status_code);
			echo $msg;
		}
		else {
			http_response_code(500);
			echo $L_MSG_ERROR_PISERVER_UNREACHABLE;
		}
	}
	
	return $result;
}

$useragent=$_SERVER['HTTP_USER_AGENT'];
$mobileClient = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)|| preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));

?>

