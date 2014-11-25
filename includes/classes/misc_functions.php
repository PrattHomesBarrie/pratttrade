<?php
function getSettingValue($dbSingleUse, $settingName) {
	$result = "";
	$query = "select `settings`.`SettingValue` from `settings` where `settings`.`settingName` = '".$settingName."'";
	//echo '<br>'.$query;
	if ($dbSingleUse->Query($query)) { 
		while ($resultRow = $dbSingleUse->Row() ) {	
			$result = $resultRow->SettingValue;
		}
	}
	return $result;

}

function excavationStartedMessage($dbSingleUse,$lotNumber, $siteShortName) {

	$result = "";
	$query = "select distinct `lbr`.`siteShortName` AS `siteShortName`,`lbr`.`lotNumber` AS `lotNumber` from `lotBuildResultsView` `lbr` where (`lbr`.`sequence` = (select `settings`.`SettingValue` from `settings` where (`settings`.`settingName` = 'Sequence for Excavation Message'))) ";
	$query .=  'and lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'"  ';
	//echo '<br>'.$query;
	if ($dbSingleUse->HasRecords($query)) {
		$query = "select SettingValue from settings where settingName = 'Excavation Started Message'";
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				$result = '<table border="0" width="100%"><tr><td bgcolor="green" align="center"><font color="#FFFFFF">';
				$result .= $resultRow->SettingValue;
				$result .= '</font></td></tr></table>';
			}
		}
	}
	return $result;
}


function getNumberOfUnsignedItems($dbSingleUse,$table, $lotNumber, $siteShortName) {
	$result = 0;
	$query =  'SELECT count(*) as theTotal from '.$table.' WHERE (dateDocumentSigned IS NULL OR dateDocumentSigned = "0000-00-00") and lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'"  ';
	//echo '<br>'.$query;
	if ($dbSingleUse->Query($query)) { 
		while ($resultRow = $dbSingleUse->Row() ) {	
			$result = $resultRow->theTotal;
		}
	}
	return $result;
}



function getFileNameFromId($dbSingleUse,$id) {
	$result = -1;
	
		$query = 'select fileName from fileLocations where id = '.$id;

		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				$result = $resultRow->fileName;
			}
		}

	
	return $result;
}

function formatDateForHTML($input, $defaultWhenNotValid = NULL, $outputDateFormat = NULL) {
	//echo "<br>".$input;
	if($input == "0000-00-00") {
		$formattedDate = $defaultWhenNotValid;
		//echo "<br>1-".$formattedDate;
	}
	else
	if ($input == NULL) {
		$formattedDate = $defaultWhenNotValid;
		//echo "<br>2-".$formattedDate;
	}
	else
	{
		$formattedDate = date('d-M-Y',strtotime($input));
		//echo "<br>3-".$formattedDate;
	}
	
	//echo "<br>".$formattedDate;
	return $formattedDate;
}

function checkRowIdExists($dbSingleUse,$table,$column,$idValue) {
	
	$rowCount = 0;
	$query = 'select count(*) as howMany from '.$table.' where '.$column.' = '.$idValue;
	//echo $query;
	if ($dbSingleUse->Query($query)) { 
		while ($resultRow = $dbSingleUse->Row() ) {	
			$rowCount = $resultRow->howMany;
		}
	}
	if ($rowCount > 0) {
		$result = true;
	}
	else
	{
		$result = false;
	}
	return $result;
	
}

function getNextId ($dbSingleUse, $userName, $description , $sessionId) {
	$result = -1;
	
	$query = 'insert into sequenceValues (userName, description, sessionValue) values ("'.$userName.'", "'.$description.'","'.$sessionId.'")';
//	echo $query;
	$numberOfRows = $dbSingleUse->Query($query);
	if ($numberOfRows > 0) {
		$query = 'select max(id) as myValue from sequenceValues where userName = "'.$userName.'" and description = "'.$description.'" and sessionValue = "'.$sessionId.'"';

		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				$result = $resultRow->myValue;
			}
		}

	}
	
	return $result;
}

function calcSumOfFeatures($dbSingleUse,$lotNumber, $siteShortName) {
	$result = 0;
	$query =  'SELECT sum(featureResalePrice) as theTotal from offerFeatures where  lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'"  ';
	//echo '<br>'.$query;
	if ($dbSingleUse->Query($query)) { 
		while ($resultRow = $dbSingleUse->Row() ) {	
			$result = $resultRow->theTotal;
		}
	}
	return $result;
}

function getAvailableSiteDiscount($dbSingleUse, $siteShortName) {
	$result = 0;
	$query =  'SELECT availableSiteDiscount from sites where  siteShortName = "'.$siteShortName.'"  ';
	//echo '<br>'.$query;
	if ($dbSingleUse->Query($query)) { 
		while ($resultRow = $dbSingleUse->Row() ) {	
			$result = $resultRow->availableSiteDiscount;
			//alertbox($result);
		}
	}
	return $result;
}
function getAvailableSiteDiscountInteger($dbSingleUse, $siteShortName) {
	$result = 0;
	$query =  'SELECT availableSiteDiscount from sites where  siteShortName = "'.$siteShortName.'"  ';
	//echo '<br>'.$query;
	if ($dbSingleUse->Query($query)) { 
		while ($resultRow = $dbSingleUse->Row() ) {	
			$result = $resultRow->availableSiteDiscount;
			//alertbox($result);
		}
	}
	return number_format($result, 0,'','');
}


function getOfferDatePlusDays($dbSingleUse, $lotNumber, $siteShortName,$numberOfDays) {
	$result = 0;
	$query =  'SELECT DATE_ADD(offerDate , interval '.$numberOfDays.' day) as newOfferDate from offers where lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'"  ';
	//echo '<br>'.$query;
	
	
	if ($dbSingleUse->Query($query)) { 
		while ($resultRow = $dbSingleUse->Row() ) {	
			//alertBox($resultRow->newOfferDate);
			$result = $resultRow->newOfferDate;
			//alertbox($result);
		}
	}
	return $result;
}

function getOfferDatePlusMonths($dbSingleUse, $lotNumber, $siteShortName,$numberOfMonths) {
	$result = 0;
	$query =  'SELECT DATE_ADD(offerDate , interval '.$numberOfMonths.' month) as newOfferDate from offers where lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'"  ';
	//echo '<br>'.$query;
	
	
	if ($dbSingleUse->Query($query)) { 
		while ($resultRow = $dbSingleUse->Row() ) {	
			//alertBox($resultRow->newOfferDate);
			$result = $resultRow->newOfferDate;
			//alertbox($result);
		}
	}
	return $result;
}



function createBuildActionComboBox($dbSingleUse, $selectedSequence) {

	$result = '	<select name="noteBuildSequence" id="noteBuildSequence">';
	$query =  'SELECT * from buildTimeline order by sequence ';
		//echo '<br>'.$query;
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				$result = $result.'<option ';
				if ($resultRow->sequence == $selectedSequence) {
					$result = $result.' selected="selected" ';
				}
				$result = $result.' value="'.$resultRow->sequence.'">'.$resultRow->buildAction.'</option>';
			}
		}
        
      $result = $result.'</select>  ';
	
	return $result;
}


function findBuildResultNotesText($dbSingleUse,$lotNumber, $siteShortName, $buildAction) {

		$query =  'SELECT * from lotBuildResultsNotes where  buildAction = "'.$buildAction.'" and lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'" order by noteDate desc ';
		//echo '<br>'.$query;
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				$result = trim($result.'<br>'.$resultRow->noteText.'<br>('.substr($resultRow->noteDate,0,10).')' ,'<br>');
			}
		}
	return $result;
	}

function printCurrentDateLong() {
		echo  date('l, F dS, Y');

	}
function nullToChar($inputValue, $replaceChar) {
	if (isset($inputValue)) {
		return $inputValue;
	}
	else
	{
		return $replaceChar;
	}
}

function alertBox($inText) {

	echo "<script>alert('".$inText."')</script>";

}

function getBuildActionName($dbSingleUse, $sequence) {

	$query =  'SELECT buildAction FROM buildTimeline bt  where sequence = '.$sequence;
		//echo '<br>'.$query;
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				$result = $resultRow->buildAction;
			}
		}
	return $result;
}

function getBuildActionColor($inDate){

	$value =  "white";
	
	if (isset($inDate)) {
		$inDateTimestamp = strtotime($inDate);
		$todayPlusSeven = strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +"."7"." day");
		$todays_date = date("Y-m-d");
		$todayTimestamp = strtotime($todays_date);
		if ($todayTimestamp > $inDateTimestamp ) {
		
			$value =  "red";
		}
		else if  ($todayPlusSeven > $inDateTimestamp ){
			$value =  "yellow";
		}
		else {
			$value =  "white";
		}
		
	}
	return $value;
}

function getExpectedDateForBuildAction($dbSingleUse,$lotNumber, $siteShortName, $sequenceToCheck){
		$query =  'SELECT * from buildTimeline where sequence = '.$sequenceToCheck;
		//echo '<br>'.$query;
		if ($dbSingleUse->Query($query)) { 
			$resultRow = $dbSingleUse->Row(); 	
			$isPostBuildItem =  $resultRow->isPostBuildItem;
		}

		$query =  'SELECT moveInDate from offerDetailView where  lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'" ';
		//echo '<br>'.$query;
		$resultCount = 0;
		$buildActionDate = '-';
		if ($dbSingleUse->Query($query)) { 
			$resultRow = $dbSingleUse->Row(); 	
			$closingDate =  $resultRow->moveInDate;
		}

		if ($isPostBuildItem == 1) {
			$query =  'select 0 - sum(numberOfDays) as numberOfDays from buildTimeline bt2 where isPostBuildItem = 1 and bt2.sequence >= '.$sequenceToCheck;
		}
		else {
		$query =  'select sum(numberOfDays) as numberOfDays from buildTimeline bt2 where isPostBuildItem = 0 and bt2.sequence >= '.$sequenceToCheck;
		}
		//echo '<br>'.$query;
		$resultCount = 0;
		$buildActionDate = '-';
		if ($dbSingleUse->Query($query)) { 
			$resultRow = $dbSingleUse->Row(); 	
			$numberOfDays =  $resultRow->numberOfDays;
			$foundRow = true;
		}


		if ($closingDate > '2001-01-01') {			
			if ($isPostBuildItem == 1) {
				$tempDate = strtotime(date("Y-m-d", strtotime($closingDate)) . " +".$numberOfDays." day");
			}
			else {
				$tempDate = strtotime(date("Y-m-d", strtotime($closingDate)) . " -".$numberOfDays." day");
			}
			$expectedDate = date('Y-m-d', $tempDate);
			//$expiration_date = strtotime($expectedDate);
		}
		else
		{
			if ($isPostBuildItem == 1) {
				$tempDate = strtotime(date("Y-m-d", strtotime('2001-01-01')) . " +".$numberOfDays." day");
			}
			else {
				$tempDate = strtotime(date("Y-m-d", strtotime('2001-01-01')) . " -".$numberOfDays." day");
			}
			$expectedDate = date('Y-m-d', $tempDate);
		}
		
	return $expectedDate;
}

function getLotNextActivity($dbSingleUse,$lotNumber, $siteShortName, $location, $startSequence){
	
		$query =  'SELECT min( `sequence` ) as nextSequence FROM buildTimeline WHERE buildAction NOT IN ( select buildAction from lotBuildResults where  lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'") ';
		if (isset($location)) {
			$query = $query.' and location = "'.$location.'" ';
		}
		//echo '<br>'.$query;
				//alertBox($query);
		$resultCount = 0;
		$buildActionDate = '-';
		if ($dbSingleUse->Query($query)) { 
			$resultRow = $dbSingleUse->Row(); 	
			$answer =  $resultRow->nextSequence;
		}
	return $answer;
}

function getLotBuildStatusColor ($dbSingleUse,$lotNumber, $siteShortName, $location) {
	
		//first get the lots next expected sequence
		$nextSequence = getLotNextActivity($dbSingleUse,$lotNumber, $siteShortName, $location, $startSequence);
		if (isset($nextSequence)) {
			$expectedDateForActivity = getExpectedDateForBuildAction($dbSingleUse,$lotNumber, $siteShortName, $nextSequence);
			$statusColor = getBuildActionColor($expectedDateForActivity);
		}
		else {
			$statusColor = "green";
		}
		return $statusColor;
}


function buildActionDate ($dbSingleUse,$lotNumber, $siteShortName, $buildAction) {

	$query =  'SELECT date(buildDate) as dateFormatted FROM lotBuildResults where buildAction = "'.$buildAction.'" and lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'" ';
		//echo '<br>'.$query;
		$resultCount = 0;
		$buildActionDate = '-';
		if ($dbSingleUse->Query($query)) { 
			$resultRow = $dbSingleUse->Row(); 	
			$buildActionDate =  $resultRow->dateFormatted;
///			$buildActionDate =  date('Y-m-d',$resultRow->buildDate);
		}
	return $buildActionDate;
}
function mostRecentBuildAction ($dbSingleUse,$lotNumber, $siteShortName) {

	$query =  'SELECT * FROM lotBuildResults where lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'"  order by buildDate desc';
		//echo '<br>'.$query;
		$resultCount = 0;
		$buildAction = '-';
		if ($dbSingleUse->Query($query)) { 
			$resultRow = $dbSingleUse->Row(); 	
			$buildAction = $resultRow->buildAction;
		}
	return $buildAction;
}

function getExpectedCountForLocation($dbSingleUse, $location) {

	$query =  'SELECT count(*) as howMany FROM buildTimeline bt  where location = "'.$location.'"';
		//echo '<br>'.$query;
		$resultCount = 0;
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				$resultCount = $resultRow->howMany;
			}
		}
	return $resultCount;
}


function getCompletedCountForLot($dbSingleUse,$lotNumber, $siteShortName, $location, $buildAction) {

	$query =  'SELECT count(*) as howMany FROM lotBuildResults lbr join buildTimeline bt on  bt.buildAction = lbr.buildAction where lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'" and location = "'.$location.'"';
		//echo '<br>'.$query;
		$resultCount = 0;
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				$resultCount = $resultRow->howMany;
			}
		}

	return $resultCount;

}

function getlotWatchCheckBox($db, $lotNumber, $siteShortName, $userName, $formAction) {
	$query = "select * from lotWatchList where userName = '".$userName."' and siteShortName = '".$siteShortName."' and lotnumber = ".$lotNumber;

	if ($db->Query($query)) { 
		while ($resultRow = $db->Row()) {
			$checked = 'checked="checked"';
		}
	}	

	echo '<form action="'.$formAction.'" method="post" name="form1" target="_self" id="form1">
			  	<input '.$checked.'  name="watchLot" type="checkbox" id="watchLot" value="watchIt" onclick="this.form.submit();"/>
  				<label for="watch"></label>
  				<input name="updateLotWatch" type="hidden" id="updateLotWatch" value="'.$lotNumber.'" />
  				<input name="siteShortName" type="hidden" id="siteShortName" value="'.$siteShortName.'" /></form>';

}


function setLotWatch($dbSingleUse,$siteShortName, $lotNumber,$watchLot, $userName) {
	echo "setting Watch on Lot Number:".$lotNumber." for user ".$userName;
	if ($watchLot == 'watchIt') {
		$query = 'insert into lotWatchList (userName, siteShortName, lotNumber) values ("'.$userName.'", "'.$siteShortName.'", '.$lotNumber.')';
	}
	else
	{
		$query = 'delete from lotWatchList where userName = "'.$userName.'" and lotNumber = '.$lotNumber.' and siteShortName = "'.$siteShortName.'"';
	}
	$numberOfRows = $dbSingleUse->Query($query);
	
	//echo "<br>".$query;
	//echo "<br>Number of Rows Affected:".$numberOfRows;
	return $numberOfRows;
}
function setLotClearingStatus($dbSingleUse,$siteShortName, $lotNumber,$buildAction, $statusCheckBox, $userName) {
	if ($statusCheckBox == 'on') {
		echo "<br><b>Setting to COMPLETE</b> -- build action:".$buildAction.", on Lot Number:".$lotNumber.", for user:".$userName;
		$query =  'SELECT count(*) as howMany FROM lotBuildResults where lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'" and buildAction = "'.$buildAction.'"';
		//echo '<br>'.$query;
		$okToSet = false;
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				if ($resultRow->howMany > 0){
					echo "<br><b>ERROR -- Already Complete: Setting to COMPLETE</b> -- Clearing Status for build action ".$buildAction." on Lot Number:".$lotNumber." for user ".$userName;
					$okToSet = false;
				}
				else {
					$okToSet = true;
				}
			}
		}
		if ($okToSet){
			$query = 'insert into lotBuildResults (userName, siteShortName, lotNumber, buildAction) values ("'.$userName.'", "'.$siteShortName.'", '.$lotNumber.',"'.$buildAction.'")';
			//echo '<br>'.$query;
			$numberOfRows = $dbSingleUse->Query($query);
			if ($numberOfRows < 1) {
				echo "<br><b>ERROR - Try Again - : Setting to COMPLETE</b> -- Clearing Status for build action ".$buildAction." on Lot Number:".$lotNumber." for user ".		$userName;
			}
		}
	}
	else
	{
		echo "<br><b>Setting to INCOMPLETE</b> -- Clearing Status for build action ".$buildAction." on Lot Number:".$lotNumber." for user ".$userName;
		$query =  'delete  FROM lotBuildResults where lotNumber =  '.$lotNumber.' and siteShortName = "'.$siteShortName.'" and buildAction = "'.$buildAction.'"';
		//echo "<br>".$query;
		$numberOfRows = $dbSingleUse->Query($query);
	}
	//echo "<br>Number of Rows Affected:".$numberOfRows;
	return $numberOfRows;
}

function translateToWords($number) 
    {
    /*****
         * A recursive function to turn digits into words
         * Numbers must be integers from -999,999,999,999 to 999,999,999,999 inclussive.    
         *
         *  (C) 2010 Peter Ajtai
         *    This program is free software: you can redistribute it and/or modify
         *    it under the terms of the GNU General Public License as published by
         *    the Free Software Foundation, either version 3 of the License, or
         *    (at your option) any later version.
         *
         *    This program is distributed in the hope that it will be useful,
         *    but WITHOUT ANY WARRANTY; without even the implied warranty of
         *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         *    GNU General Public License for more details.
         *
         *    See the GNU General Public License: <http://www.gnu.org/licenses/>.
         *
         */
        // zero is a special case, it cause problems even with typecasting if we don't deal with it here
        $max_size = pow(10,18);
        if (!$number) return "zero";
        if (is_int($number) && $number < abs($max_size)) 
        {            
            switch ($number) 
            {
                // set up some rules for converting digits to words
                case $number < 0:
                    $prefix = "negative";
                    $suffix = translateToWords(-1*$number);
                    $string = $prefix . " " . $suffix;
                    break;
                case 1:
                    $string = "One";
                    break;
                case 2:
                    $string = "Two";
                    break;
                case 3:
                    $string = "Three";
                    break;
                case 4: 
                    $string = "Four";
                    break;
                case 5:
                    $string = "Five";
                    break;
                case 6:
                    $string = "Six";
                    break;
                case 7:
                    $string = "Seven";
                    break;
                case 8:
                    $string = "Eight";
                    break;
                case 9:
                    $string = "Nine";
                    break;                
                case 10:
                    $string = "Ten";
                    break;            
                case 11:
                    $string = "Eleven";
                    break;            
                case 12:
                    $string = "Twelve";
                    break;            
                case 13:
                    $string = "Thirteen";
                    break;            
                // fourteen handled later
                case 15:
                    $string = "Fifteen";
                    break;            
                case $number < 20:
                    $string = translateToWords($number%10);
                    // eighteen only has one "t"
                    if ($number == 18)
                    {
                    $suffix = "een";
                    } else 
                    {
                    $suffix = "teen";
                    }
                    $string .= $suffix;
                    break;            
                case 20:
                    $string = "Twenty";
                    break;            
                case 30:
                    $string = "Thirty";
                    break;            
                case 40:
                    $string = "Forty";
                    break;            
                case 50:
                    $string = "Fifty";
                    break;            
                case 60:
                    $string = "Sixty";
                    break;            
                case 70:
                    $string = "Seventy";
                    break;            
                case 80:
                    $string = "Eighty";
                    break;            
                case 90:
                    $string = "Ninety";
                    break;                
                case $number < 100:
                    $prefix = translateToWords($number-$number%10);
                    $suffix = translateToWords($number%10);
                    $string = $prefix . "-" . $suffix;
                    break;
                // handles all number 100 to 999
                case $number < pow(10,3):                    
                    // floor return a float not an integer
                    $prefix = translateToWords(intval(floor($number/pow(10,2)))) . " Hundred";
                    if ($number%pow(10,2)) $suffix = " and " . translateToWords($number%pow(10,2));
                    $string = $prefix . $suffix;
                    break;
                case $number < pow(10,6):
                    // floor return a float not an integer
                    $prefix = translateToWords(intval(floor($number/pow(10,3)))) . " Thousand";
                    if ($number%pow(10,3)) $suffix = translateToWords($number%pow(10,3));
                    $string = $prefix . " " . $suffix;
                    break;
                case $number < pow(10,9):
                    // floor return a float not an integer
                    $prefix = translateToWords(intval(floor($number/pow(10,6)))) . " Million";
                    if ($number%pow(10,6)) $suffix = translateToWords($number%pow(10,6));
                    $string = $prefix . " " . $suffix;
                    break;                    
                case $number < pow(10,12):
                    // floor return a float not an integer
                    $prefix = translateToWords(intval(floor($number/pow(10,9)))) . " billion";
                    if ($number%pow(10,9)) $suffix = translateToWords($number%pow(10,9));
                    $string = $prefix . " " . $suffix;    
                    break;
                case $number < pow(10,15):
                    // floor return a float not an integer
                    $prefix = translateToWords(intval(floor($number/pow(10,12)))) . " trillion";
                    if ($number%pow(10,12)) $suffix = translateToWords($number%pow(10,12));
                    $string = $prefix . " " . $suffix;    
                    break;        
                // Be careful not to pass default formatted numbers in the quadrillions+ into this function
                // Default formatting is float and causes errors
                case $number < pow(10,18):
                    // floor return a float not an integer
                    $prefix = translateToWords(intval(floor($number/pow(10,15)))) . " quadrillion";
                    if ($number%pow(10,15)) $suffix = translateToWords($number%pow(10,15));
                    $string = $prefix . " " . $suffix;    
                    break;                    
            }
        } else
        {
            echo "ERROR with - $number<br/> Number must be an integer between -" . number_format($max_size, 0, ".", ",") . " and " . number_format($max_size, 0, ".", ",") . " exclussive.";
        }
        return $string;    
    }

	function getExpectedCountForLotLocation($dbSingleUse, $location,$lotNumber, $siteShortName) {

	$query =  'SELECT count(*) as howMany FROM buildTimeline bt  where sequenceIsActive = 1 and location = "'.$location.'" and timeLineMasterID =
			(select timelineMasterID from lots where lotNumber = '.$lotNumber.' and siteShortName = "'.$siteShortName.'")';
		//echo '<br>'.$query;
		$resultCount = 0;
		if ($dbSingleUse->Query($query)) { 
			while ($resultRow = $dbSingleUse->Row() ) {	
				$resultCount = $resultRow->howMany;
			}
		}
	return $resultCount;
	}
function prattDebug($value)
	{
		if ($_GET['debug']) 
		{
			print_r($value);
			echo '<br><br>';
			print_r($_SESSION);
		}
	}
?>