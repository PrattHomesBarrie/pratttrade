

<?php

require_once ("classes/misc_functions.php");

if (stristr($_SERVER["SCRIPT_NAME"], "/".basename(__FILE__))!==false) {
	print "Naughty, Naughty, Naughty!";
	exit;
}
$debug = "No";
$validUser = false;
//$userName = null;

$levelOne = 'Level One';
$levelTwo = 'Level Two';

if (date("m") == '01' or date("m") == '02' or date("m") == '03' ) {
		$thisFiscalStart = date("Y") - 1;
		$thisFiscalStart =  $thisFiscalStart.'-05-01';
		$thisFiscalEnd = date("Y").'-04-30';
		$nextFiscalStart = date("Y").'-05-01';
		$nextFiscalEnd = date("Y") + 1;
		$nextFiscalEnd = $nextFiscalEnd.'-04-30';
}
else {
		$thisFiscalStart = date("Y") ;
		$thisFiscalStart =  $thisFiscalStart.'-05-01';
		$thisFiscalEnd = date("Y") + 1;
		$thisFiscalEnd = $thisFiscalEnd.'-04-30';
		$nextFiscalStart = date("Y") + 1;
		$nextFiscalStart = $nextFiscalStart.'-05-01';
		$nextFiscalEnd = date("Y") + 2;
		$nextFiscalEnd = $nextFiscalEnd.'-04-30';
}

//alertBox($thisFiscalStart.' '.$thisFiscalEnd.' '.$nextFiscalStart.' '.$nextFiscalEnd);

?>