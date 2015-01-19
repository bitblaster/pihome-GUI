<?
session_start();

$username=isset($_POST["username"]) ? stripslashes($_POST["username"]) : null;
$passwort=isset($_POST["passwort"]) ? stripslashes($_POST["passwort"]) : null;
$page=isset($_GET["p"]) ? stripslashes($_GET["p"]) : null;
$query=isset($_GET["q"]) ? trim($_GET["q"]) : null;
$user_id = isset($_SESSION["pihome_usid"]) ? $_SESSION["pihome_usid"] : null;

######## System Includes ########
require_once(dirname(__FILE__).'/configs/functions.inc.php');
######### LOGIN ############
if($username) {
    pihome_acp_login($username,$passwort);
    if (isset($_SESSION['pihome_username'])) {
        header("Location: index.php");
        die();
    }
}
######### LOGOUT ############
if ($page == "logout") {
    header("Location: index.php");
    session_unset(); 
    session_destroy(); 
    ob_end_flush(); 
    die();
}
##### Page Auswahl nach Session #####
if (!isset($_SESSION['pihome_username'])) { ?>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?=$L_ADMIN_TITLE?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
        <meta name="viewport" content="width=device-width, user-scalable=no" />
        <meta name="format-detection" content="telephone=yes">
        <link rel="shortcut icon" href="images/favicon.png" />
        <link rel="stylesheet" href="../css/pure-min.css" type="text/css" />
        <link rel="stylesheet" href="../css/style.css" type="text/css" media="screen" />
    </head>
    <body class="login">
        <div id="navAdmin" class="nav">
            <div class="headerTitle">
                <img src="../images/pihome.svg" id="home" border="0">
                <span style="display: inline-block;">
                    <span style="font-size: 2.3em;color: #ABABAB">Pi</span>
                    <span style="font-size: 2.3em">Home</span>
                    <br/>
                    <span style="font-size: 0.8em">&nbsp;<?=$L_ADMIN_SUBTITLE?></span>
                </span>
            </div>
        </div>

        <div id="page_login">
            <div id="login">
                <form method="POST" id="loginForm" class="form">
                    <br/><br/> 
                    <strong><?=$L_LOGIN_USER?></strong><br/>
                    <input type="text" name="username" />  
                    <br/><br/>
                    <strong><?=$L_LOGIN_PASSWORD?></strong><br/>
                    <input type="password" name="passwort" />
                    <br/>
                    <br/>
                    <button class="submit button-img pure-button" data-role="none" onclick="document.getElementById('loginForm').submit();"><?=$L_LOGIN_SUBMIT?></button>
                    <br/><br/><br/>            
                </form>
            </div>
        </div>

        <a href="../">
            <div id="settings">
                <div><img src="images/return.svg" border="0" /></div>
                <div><?=$L_BACK_PIHOME?></div>
            </div>
        </a>

        <div id="copy"><?=getcopy();?></div>

    </body>
    </html>
<?
    exit();
}
?>
