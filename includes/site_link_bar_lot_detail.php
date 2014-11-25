<?php

	//echo '<div id="log_in_name" >Hello:'.$userName.'</div>';
    echo '<a id="logout_link" href="index.php?myAction=Logout">Logout</a>';
	echo  '<div id="main_nav">';
	echo  '<ul>';
	echo '<li><span onClick=”return true”><a>Actions</a></span>';
	
	if ($validUser) {
		
    	echo '<ul>';
	    //echo '<li><a href="index.php?myAction=MySummary">My Summary</a></li>';
    	echo '<li><a href="index.php?myAction=Search">Search</a></li>';
		if ($securityLevelOneCheck ) {
	    	//echo '<li><a href="index.php?myAction=Sites">Sites</a></li>';
		}
//		echo '<li>Lots';
  //  	echo '<ul>';
		echo '<li><a href="index.php?myAction=Activity">Activity</a></li>';
	    echo '<li><a href="index.php?myAction=Lots">Lots - All</a></li>';
		echo '<li>';
		echo '<a href="index.php?myAction=PostBuildLots">Lots - Moved-In</a>';
		echo '</li>';
	//	echo '</ul></li>';
		if ($securityLevelOneCheck ) {
	    	//echo '<li><a href="index.php?myAction=OfferDashboard">Offer Dashboard</a></li>';
		}
		if ($securityLevelOneCheck ) {
			$currentSettingCheck = 'Use Show Homes Page';
			$settingValue = getSettingValue($dbSingleUse, $currentSettingCheck) ;
			if ($settingValue == 1) {
		    	//echo '<li><a href="index.php?myAction=ShowHomes">Show Homes</a></li>';
			}
		}
		if ($securityLevelOneCheck ) {
			$currentSettingCheck = 'Use Spec Homes Page';
			$settingValue = getSettingValue($dbSingleUse, $currentSettingCheck) ;
			if ($settingValue == 1) {
		    	echo '<li><a href="index.php?myAction=SpecHomes">Spec Homes</a></li>';
			}
		}
		if ($securityLevelOneCheck ) {
	    	//echo '<li><a href="index.php?myAction=MoveInsGrid">Calendars</a></li>';
		}
		if ($securityLevelOneCheck ) {
	    	//echo '<li><a href="index.php?myAction=Activity">Activity</a></li>';
		}
		if ($securityLevelOneCheck ) {
	    	//echo '<li><a href="index.php?myAction=StatsWeekly">Stats Weekly</a></li>';
		}
		if ($securityLevelOneCheck ) {
	    	//echo '<li><a href="index.php?myAction=DepositTracking">Deposit Tracking</a></li>';
		}
    	//echo '<li><a href="index.php?myAction=NoteTracking">Note Tracking</a></li>';
		if ($securityCanDoServiceTracking) {
	    	//echo '<li><a href="index.php?myAction=ServiceTracking">Service Tracking</a></li>';
		}
		if ($securityTestUser) {
	    	//echo '<li><a href="index.php?myAction=Marketing">Marketing</a></li>';
		}
		if ($securityCanDoMaintenance) {
		   // echo '<li><a href="index.php?myAction=Maintenance">Maintenance</a></li>';
		}
		if ($securityCanDoSettings) {
		  //  echo '<li><a href="index.php?myAction=Settings">Settings</a></li>';
		}
    	
	    echo '</ul>';
   	    //echo '<p>&nbsp;</p>';
		echo '</li>';
		
	}
	elseif ($myAction!='login'){
    	//echo '<ul class="nav">';
      //echo '<li><a href="index.php">Login</a></li>';
      //echo '</ul>';
	}
    
  
echo  '<li';
	
	echo '><a href="index.php?myAction=';
	if ($myAction == 'PostBuildLots') {
		echo 'PostBuildLots';
	}
	else {
		echo 'Lots';
	}
	echo '&siteShortName=All">Sites</a>';
	echo  '<ul>';

	
	echo '<li';
	if (($siteShortName == 'All' or $siteShortName == '') and $myAction == 'Lots') {
		echo ' class="current"';
	}
	echo '><a href="index.php?myAction=Lots&siteShortName=All">All</a>
		  </li>';
$query = ' SELECT * FROM `sites` WHERE siteID >2';
if ($db2->Query($query)) { 
	while ($resultRow = $db2->Row() ) {
		echo '<li';
		if ($resultRow->siteShortName == $siteShortName and ($myAction == 'Lots' or $myAction == 'PostBuildLots')) {
			echo ' class="current"';
		}
		echo '><a href="index.php?myAction=Lots&siteShortName='.$resultRow->siteShortName.'&siteName='.$resultRow->siteName.'">'.$resultRow->siteName.'</a></li>';
	}
}

	echo '</ul></li>';
	if($myAction == 'Lots' or !isset($myAction) or $myAction == 'PostBuildLots' or $myAction == 'SpecHomes')
	{
		//echo '<li><a>Offer Status</a>';
		//echo '</li>';
		//echo '<li><a>Completion Date</a>';
		//echo '</li>';
		//echo '<li><a>Clearing Activity</a>';
		//echo '</li>';
	}
	else{
	echo  '</ul>';
	echo  '</div>';
	echo '<br><br>You are viewing: '.$myAction;
	}
  

?>
