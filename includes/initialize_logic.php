
<?php
//define('TIMEZONE', 'America/Toronto');  
//date_default_timezone_set(TIMEZONE);
//ini_set("date.timezone", "America/Toronto"); 
//date_default_timezone_set("America/Toronto"); 

require_once('check_session.php');
require_once('vars.php');
require_once("classes/mysql_ultimate.php");  
require_once ("classes/login_functions.php");
$db = new MySQL(); 
$db2 = new MySQL(); 
$dbSingleUse = new MySQL(); 
$db->Query("SET time_zone = '-4:00';");
$db2->Query("SET time_zone = '-4:00';");
$dbSingleUse->Query("SET time_zone = '-4:00';");
//echo $myAction;
if ($myAction == 'login')
{
	$validUser = false;  //initialize it
	require_once("classes/login_functions.php"); 
	
	if (validateLogin($dbSingleUse, $loginUserName, $loginPassword))
		{
			$userName = $loginUserName;
			$validUser = true;
    	     $_SESSION["validUser"] = $validUser;
    	     $_SESSION["userName"] = $userName;
			//echo 'Testing'.$sess->getVariable('userName');
			if (isset($_GET["myAction"])) {
				$myAction = $_GET["myAction"];
			}
	}
	else
	{
			$validUser = false;
    	     $_SESSION["validUser"] = $validUser;
    	     $_SESSION["userName"] = "none";
		}

//	echo 'checking login';
}
else if ($myAction == 'Logout')
{
			$validUser = false;
    	     $_SESSION["validUser"] = $validUser;
    	     $_SESSION["userName"] = "none";
	
}

if ($validUser == true ) {
	$securityLevelOneCheck = userSecurityLevelCheck($dbSingleUse,$userName,$levelOne);
	$securityLevelTwoCheck = userSecurityLevelCheck($dbSingleUse,$userName,$levelTwo);
}
?>