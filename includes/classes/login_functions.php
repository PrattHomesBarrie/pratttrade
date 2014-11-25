<?php
require_once("mysql_ultimate.php"); 
require_once("misc_functions.php"); 


function validateLogin($dbSingleUse,$userName, $password) {

	$returnValue = false;
	$query =  'SELECT userName, count(*) as howMany FROM users where userName =  "'.$userName.'" and password = "'.$password.'" group by userName';
		//echo '<br>'.$query;
		$resultCount = 0;
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				$resultCount = $resultRow->howMany;
				$realUserName = $resultRow->userName;
			}
		}
		
		if ($resultCount > 0 ) {
			$returnValue = true;
			
		}

	return $returnValue;

}

function userSecurityLevelCheck($dbSingleUse,$userName,$levelToCheck) {
	$returnValue = false;
	$query =  'SELECT * FROM userSecurity us join users u on u.userUniqueID = us.userUniqueID where userName =  "'.$userName.'" ';
		//echo '<br>'.$query;
		$resultCount = 0;
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				//	alertBox($levelToCheck.'-'.$resultRow->securityLevel);
				if ($levelToCheck == $resultRow->securityLevel) {
					$returnValue = true;
				}
			}
		}
		

	return $returnValue;
	

}
function userSecurityLevel($dbSingleUse,$userName) {
	$returnValue = false;
	$query =  'SELECT * FROM userSecurity us join users u on u.userUniqueID = us.userUniqueID where userName =  "'.$userName.'" ';
		//echo '<br>'.$query;
		$resultCount = 0;
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
					$returnValue = $resultRow->securityLevel;
			}
		}
		

	return $returnValue;
	

}

?>