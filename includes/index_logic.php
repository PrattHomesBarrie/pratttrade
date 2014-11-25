<?php
require_once('vars.php');
//echo $myAction;
require_once('check_session.php');
require_once ("classes/login_functions.php");
require_once ("classes/misc_functions.php");
require_once('initialize_logic.php');  // put here just in case the index doesn't have it
if ($debug == "Yes")
	{
	echo '<br>$myAction='.$myAction;
	echo '<br>$userName='.$userName;
	echo '<br>$validUser='.$validUser;
	echo '<br>sess myAction='. $_SESSION["myAction"];
	echo '<br>sess $userName='. $_SESSION["userName"];
	echo '<br>sess $validUser='. $_SESSION["validUser"];
	}
if ($myAction == 'login'){
	
	if ($validUser == true ) {
		$query = 'select * from settings where settingName = "Trade Site Post Login Message" ';
		//echo $query;
		if ($db2->Query($query)) { 
			$settings = $db2->Row();
			echo nl2br($settings->SettingValue);
		}
	}
	if (
		(strtolower($userName) == 'suegallant@pratthomes.ca')
		||
		(strtolower($userName) == 'lynn@pratthomes.ca')
		){
		$myAction = 'OfferDashboard';
	}
	else{
		$myAction = '';
	}
			
	//do nothing - done in initialize_logic.php
}
if ($validUser == true ) {
	
		include("site_link_bar.php");
	
	if ($updateLotWatch > '') {
		//echo "need to update lot:".$updateLotWatch;
		setLotWatch($dbSingleUse,$siteShortName,$updateLotWatch,$watchLot, $userName);
	}
	if ($updateLotClearingStatus > '') {
		//echo "need to update lot:".$updateLotWatch;
		setLotClearingStatus($dbSingleUse,$siteShortName,$lotNumber,$updateLotClearingStatus,$statusCheckBox, $userName);
	}
	if ($myAction == '' or $myAction == 'login') 
		{
			require_once ("activity_dashboard.php");
		}
	else if ($myAction == 'ColourChart' ) 
		{
			//make sure that it is available to public
			$chartNumber = 2;
			$query = "select * from offerChartHeaderData where availableToPublic = 1 and lotNumber = ".$lotNumber." and siteShortName = '".$siteShortName."'";
			$query = $query." and chartId=".$chartNumber;
			//echo $query;								
			if ($db->HasRecords($query)) {
				require_once ("lot_colour_chart_print.php");
			}
		}
		
	elseif ($myAction == 'Lots' ) 
		{
			require_once ("site_lots_page.php");
		}
	else if ($myAction == 'APSDetails' ) 
		{
			require_once ("lot_aps_details_page.php");
		}
	else if ($myAction == 'Search' ) 
		{
			require_once ("search.php");
		}
	else if ($myAction == 'SpecHomes' ) 
		{
			require_once ("spechomes_page.php");
		}
	else if ($myAction == 'PostBuildLots' ) 
		{
			require_once ("site_lots_post_build_page.php");
		}
	else if ($myAction == 'Sites' ) 
		{
			require_once ("site_list_page.php");
		}
	else if ($myAction == 'Activity' ) 
		{
			require_once ("activity_dashboard.php");
		}
	else
	{ 
	require_once ("activity_dashboard.php");
	}
}
else
{
$query = 'select * from settings where settingName = "Trade Site Before Login Message" ';
//echo $query;
if ($db2->Query($query)) { 
	$settings = $db2->Row();
	echo nl2br($settings->SettingValue);
}

	if ($myAction == 'login')
		{
		 echo "<br>Login Failed.  Please try again";
		}

	// need to show login screen
	if ($myAction != 'Logout')
		{
			require_once("login_screen.php");
		}
	else 
		{
			header( "Location: index.php" );
		}
}

if ($debug == "Yes")
	{
	echo '<br>$myAction='.$myAction;
	echo '<br>$userName='.$userName;
	echo '<br>$validUser='.$validUser;
	echo '<br>sess myAction='. $_SESSION["myAction"];
	echo '<br>sess $userName='. $_SESSION["userName"];
	echo '<br>sess $validUser='. $_SESSION["validUser"];
	}

?>