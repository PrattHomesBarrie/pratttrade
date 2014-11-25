<?php require_once("search_input_form.php");
if (!isset($lotSortList)) {
//	$lotSortList = " if(isnull(moveInDate),1,0),  moveInDate, siteShortName, lotNumber  ";
	$lotSortList = " siteShortName, lotNumber ";
}

?>
<br />
<form method="post" action="" > 
<table width = 100%">
<tr><td >Sort by:
  <select name="lotSortList" onChange="this.form.submit();">
  <option value= " siteShortName, lotNumber ">Lot Number Asc</option>
  <option value= " siteShortName, lotNumber desc " <?php if ($lotSortList == 
                 " siteShortName, lotNumber desc ") {echo " SELECTED ";} ?> >Lot Number Desc</option>
  <option value=" if(isnull(moveInDate),1,0),  moveInDate, siteShortName, lotNumber  " <?php if ($lotSortList == 
                " if(isnull(moveInDate),1,0),  moveInDate, siteShortName, lotNumber  ") {echo " SELECTED ";} ?> >Move In Date Asc</option>
  <option value=" if(isnull(moveInDate),1,0),  moveInDate desc, siteShortName, lotNumber " <?php if ($lotSortList == 
                " if(isnull(moveInDate),1,0),  moveInDate desc, siteShortName, lotNumber ") {echo " SELECTED ";} ?> >Move In Date Desc</option>
</select>  
</td>

<td >
<table align="right">
    <tr>
<?php
if ($securityLevelOneCheck) {

  echo '
      <td > Details:
        <label>
        <input type="radio" ';
		if ($radioViewOptions != 'stats') {
			echo ' checked = "checked"';
		}
		echo 'name="radioViewOptions" value="standard" id="radioViewOptions_0" onChange="this.form.submit();"/>
        Standard</label></td>
      <td ><label>
        <input type="radio"';
		if ($radioViewOptions == 'stats') {
			echo ' checked = "checked"';
		}
		echo ' name="radioViewOptions" value="stats" id="radioViewOptions_1" onChange="this.form.submit();"/>
        Statistics</label></td>
		';
}
else {
  echo'    <td ><input type="hidden" name="radioViewOptions" value="standard" id="radioViewOptions_0" onChange="this.form.submit();"/></td>';
}
  ?>
   </tr>
  </table></td>
</tr>
</table>
</form>

<table width="100%" border="1" cellpadding="0" cellspacing="0" class="tableLotData">
  <tr>
  
<th >Lot</th>
    <th align="center">Model on Offer<br />
      (or Designated Model)</th>
    <th  align="center" width="0">Date on Offer</th>
    <?php 
	if ( $securityLevelOneCheck == true ) {
	   echo ' <th align="center" >Move In<br />Date</th>';
	}
	?>
    <th align="center" >Completion<br />Date</th>
    <th align="center" >Head<br />
      Office<br />
      Cleared</th>
    <th  align="center">Head<br />
      Office<br />
      Outs.</th>
    <th  align="center">Site<br />
      Office<br />
      Cleared</th>
    <th align="center" >Site<br />
      Office<br />
      Outs.</th>
    <th colspan="2" align="center">Last Activity</th>
    <?php
	if ($radioViewOptions == 'stats' and $securityLevelOneCheck) {
	    echo '<th  align="center">%</th>';
	}
	?>
    <th align="center" >Watch</th>
</tr>
<?php

require_once ("classes/misc_functions.php");

if ($debug == "Yes") {
	echo 'Watch='.$watch;
	echo 'updatelotNumber='.$updatelotNumber;
}

 if (strlen($siteShortName) > 10) {
	 echo "<br><b>Error:something wrong with length of siteShortName";
	 exit;
 }
 
//$filterOfferStatusGroup;
/*
if ( $securityLevelOneCheck != true ) {
	   $query = 'select * from offerDetailViewSignedOnly';
}
else {
	if ($filterOfferStatusGroup == 'All') {
		$query = 'select * from offerDetailView o ';
	}
	if ($filterOfferStatusGroup == 'With Offers') {
	   $query = 'select * from offerDetailViewSignedOnly o ';
	}
	if ($filterOfferStatusGroup == 'Without Offers') {
		$query = 'select * from offerDetailView o ';
	}
} */

$query = 'select * from offerDetailViewSignedOnly o '; // With offer as default
if ($siteShortName > "") {
	$query = $query.'  where siteShortName="'.$siteShortName.'" ';
}
else {
	$query = $query.'  where 1=1 ';
}

//$filterOfferStatusGroup;
if ($filterOfferStatusGroup == 'All') {
	//do nothing
}
if ($filterOfferStatusGroup == 'With Offers') {
	$query = $query.' and offerDate is not null ';
}
if ($filterOfferStatusGroup == 'Without Offers') {
	$query = $query.' and (offerDate is null or dateDocumentSigned is null) ';
}

$searchText = strtoupper($searchNameOnOffer);
if ($searchText > '' ){
	$query = $query.' and (upper(firstName1) like "%'.$searchText.'%"
						   or upper(lastName1) like "%'.$searchText.'%"
						   or upper(firstName2) like "%'.$searchText.'%"
						   or upper(lastName2) like "%'.$searchText.'%"
							) ';
}

$searchText = strtoupper($searchModelOnOffer);
if ($searchText > '' ){
	$query = $query.' and (upper(modelName) like "%'.$searchText.'%") ';
}


$query = $query." order by ".$lotSortList;

if ($lotSortList > '') {
	$query = $query.",";
}

$query = $query."siteShortName, lotNumber ";
// echo '<br>'.$query;


if ($dbSingleUse->HasRecords($query)) { 
	$searchArray = $dbSingleUse->QueryArray($query);
	foreach ($searchArray as $j => $searchRow) {
		$headOfficeExpected = getExpectedCountForLotLocation($dbSingleUse, "Head Office",$searchRow["lotNumber"], $searchRow["siteShortName"]);
		$siteOfficeExpected = getExpectedCountForLotLocation($dbSingleUse, "Site Office",$searchRow["lotNumber"], $searchRow["siteShortName"]);
		$showThisRow = true;

		$featureTextMatch = false;
		$searchText = strtoupper($searchFeatureText);
		if ($searchText > '' ){
			$featureQuery = 'SELECT * FROM offerFeatures of   WHERE of.lotNumber = '.$searchRow["lotNumber"].' and of.siteShortName = "'.$searchRow["siteShortName"].'" 
						and (upper(of.featureText) like "%'.$searchText.'%" or upper(of.featureSubText) like "%'.$searchText.'%" ) ';
			if ($dbSingleUse->HasRecords($featureQuery)) { 
				$featureTextMatch = true;
			}
			else {
				$showThisRow = false;
			}
		}
		
		$amendmentTextMatch = false;
		$searchText = strtoupper($searchAmendmentText);
		if ($searchText > '' ){
			$amendmentQuery = 'SELECT * FROM offerAmendments of   WHERE of.lotNumber = '.$searchRow["lotNumber"].' and of.siteShortName = "'.$searchRow["siteShortName"].'" 
						and (upper(of.amendmentDescription) like "%'.$searchText.'%" ) ';
			if ($dbSingleUse->HasRecords($amendmentQuery)) { 
				$amendmentTextMatch = true;
			}
			else {
				$showThisRow = false;
			}
		}
		
		$workCreditTextMatch = false;
		$searchText = strtoupper($searchWorkCreditText);
		if ($searchText > '' ){
			$workCreditQuery = 'SELECT * FROM offerWorkCredits of   WHERE of.lotNumber = '.$searchRow["lotNumber"].' and of.siteShortName = "'.$searchRow["siteShortName"].'" 
						and (upper(of.workCreditDescription) like "%'.$searchText.'%" ) ';
			if ($dbSingleUse->HasRecords($workCreditQuery)) { 
				$workCreditTextMatch = true;
			}
			else {
				$showThisRow = false;
			}
		}
		
		$changeOrderTextMatch = false;
		$searchText = strtoupper($searchChangeOrderText);
		if ($searchText > '' ){
			$changeOrderQuery = 'SELECT * FROM offerChangeOrders of   WHERE of.lotNumber = '.$searchRow["lotNumber"].' and of.siteShortName = "'.$searchRow["siteShortName"].'" 
						and (upper(of.changeDescription) like "%'.$searchText.'%" ) ';
			if ($dbSingleUse->HasRecords($changeOrderQuery)) { 
				$changeOrderTextMatch = true;
			}
			else {
				$showThisRow = false;
			}
		}

		if ($showThisRow == true)  {
			$headOfficeCompleted = getCompletedCountForLot($dbSingleUse,$searchRow["lotNumber"], $searchRow["siteShortName"], 'Head Office', null);
			$headOfficeOutstanding = $headOfficeExpected - $headOfficeCompleted;
			$siteOfficeCompleted = getCompletedCountForLot($dbSingleUse,$searchRow["lotNumber"], $searchRow["siteShortName"], 'Site Office', null);
			$siteOfficeOutstanding = $siteOfficeExpected - $siteOfficeCompleted;
			$mostRecentBuildAction = '-';
			$mostRecentBuildAction = mostRecentBuildAction ($dbSingleUse,$searchRow["lotNumber"], $searchRow["siteShortName"]);
			
			$mostRecentBuildActionDate = '-';
			if ($mostRecentBuildAction != '-') {
				$mostRecentBuildActionDate = buildActionDate ($dbSingleUse,$searchRow["lotNumber"], $searchRow["siteShortName"], $mostRecentBuildAction);
			}
			else
			{
				$mostRecentBuildActionDate = '-';
			}

		//$filterClearingGroup;
			echo '<tr>';
			echo '<td class="lotLinkCellInTable" ><a href="index.php?myAction=APSDetails&lotNumber='.$searchRow["lotNumber"].'&siteShortName='.$searchRow["siteShortName"].'">'.$searchRow["lotNumber"];
			if ($siteShortName <= "") {
					echo '<small>('.$searchRow["siteShortName"].')</small>';
			}
			echo '</a></td>';

			$modelName = "-";
			if (($searchRow["modelName"]) > '') {
				$modelName = $searchRow["modelName"];
			}
			else {
				if ($searchRow["designatedModelName"] > '') {
					$modelName = $searchRow["designatedModelName"].'(d)';
				}
			}
			echo '<td align="center"> '.$modelName.'</td>';
	
			echo '<td align="center"> '.nullToChar($searchRow["offerDate"],'-').'</td>';
			if ( $securityLevelOneCheck == true ) {
				echo '<td align="center"> '.nullToChar($searchRow["moveInDate"],'-').$searchRow["amendedMoveInText"].'</td>';
			}
			echo '<td align="center"> '.nullToChar($searchRow["calculatedBuildCompletionDate"],'-');
			if ($securityLevelOneCheck) {
				echo $searchRow["calculatedBuildCompletionDateText"];
			}
			echo '</td>';
			$bgColor = "";
			if (isset($searchRow["calculatedBuildCompletionDate"])) {
				$bgColor = ' bgcolor = "'.getLotBuildStatusColor( $dbSingleUse,$searchRow["lotNumber"],$searchRow["siteShortName"], "Head Office").'" ' ;
			}
    		echo '<td '.$bgColor.'  class="tableLotDetailsNumbericData">'.$headOfficeCompleted.'</td>';
    		echo '<td class="tableLotDetailsNumbericData" >'.$headOfficeOutstanding.'</td>';
			$bgColor = "";
			if (isset($searchRow["calculatedBuildCompletionDate"])) {
				$bgColor = ' bgcolor = "'.getLotBuildStatusColor( $dbSingleUse,$searchRow["lotNumber"], $searchRow["siteShortName"], "Site Office").'" ' ;
			}
			echo '<td '.$bgColor.' class="tableLotDetailsNumbericData">'.$siteOfficeCompleted.'</td>';
    		echo '<td class="tableLotDetailsNumbericData">'.$siteOfficeOutstanding.'</td>';
	    	echo '<td class="tableLotDetailsNumbericData">'.nullToChar($mostRecentBuildActionDate, '-').'</td>';
    		echo '<td class="tableLotDetailsNumbericData">'.nullToChar($mostRecentBuildAction,'-').'</td>';
			$formAction = 'index.php?myAction=Lots&siteShortName='.$searchRow["siteShortName"];
			if ($radioViewOptions == 'stats' and $securityLevelOneCheck) {
			    echo '<td>';
				echo getLotBuildPercent($dbSingleUse,$searchRow["lotNumber"], $searchRow["siteShortName"], $searchRow["timelineMasterID"]);
				echo '</td>';
			}

			echo '<td align="center">';
			echo getlotWatchCheckbox($dbSingleUse,$searchRow["lotNumber"], $searchRow["siteShortName"], $userName, $formAction);
		
			echo '</td>';

			echo '</tr>';
			if ($searchNameOnOffer > '') {
					echo '<tr><td></td>';
					echo '<td colspan="1" align="right">Name Match</td>';
					echo '<td colspan="9" bgcolor="#FFFFEE">'.$searchRow["firstName1"].' '.$searchRow["lastName1"].', '.$searchRow["firstName2"].' '.$searchRow["lastName2"];
					echo '</tr>';
			}
			if ($featureTextMatch == true) {
				$recArray = $dbSingleUse->QueryArray($featureQuery);
				foreach ($recArray as $k => $recRow) {
					echo '<tr><td></td>';
					echo '<td colspan="1" align="right">Feature Match</td>';
					echo '<td colspan="9" bgcolor="#FFFFEE">'.$recRow["featureText"].'<br />'.$recRow["featureSubText"];
					echo '</tr>';
				}
			}
			if ($amendmentTextMatch == true) {
				
				$recArray = $dbSingleUse->QueryArray($amendmentQuery);
				foreach ($recArray as $k => $recRow) {
					echo '<tr><td></td>';
					echo '<td colspan="1" align="right">Amendment Match</td>';
					echo '<td colspan="9" bgcolor="#FFFFEE">'.$recRow["amendmentDescription"];
					echo '</tr>';
				}
			}
			if ($workCreditTextMatch == true) {
				$recArray = $dbSingleUse->QueryArray($workCreditQuery);
				foreach ($recArray as $k => $recRow) {
					echo '<tr><td></td>';
					echo '<td colspan="1" align="right">Work Credit Match</td>';
					echo '<td colspan="9" bgcolor="#FFFFEE">'.$recRow["workCreditDescription"];
					echo '</tr>';
				}
			}
			if ($changeOrderTextMatch == true) {
				$recArray = $dbSingleUse->QueryArray($changeOrderQuery);
				foreach ($recArray as $k => $recRow) {
					echo '<tr><td></td>';
					echo '<td colspan="1" align="right">Change Order Match</td>';
					echo '<td colspan="9" bgcolor="#FFFFEE">'.$recRow["changeDescription"];
					echo '</tr>';
				}
			}
		}
	}
}
		

?>
</table>
