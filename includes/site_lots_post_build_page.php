<SCRIPT LANGUAGE='Javascript'>    $(document).ready(function() {
                  oTable = $("#lotListTable").dataTable({
									"bJQueryUI": true,
                                    "bPaginate": false,
								    /*"sScrollY": "600px",*/
                                    "bLengthChange": false,
                                    "bFilter": true,
                                    "bSort": true,
                                    "bInfo": true,
    								 "bProcessing": false
                         });
               } );    </SCRIPT>
<?php 

//require_once("lot_filters_form.php");
echo '<h3>Post Move-In Homes</h3><h4>This page shows all lots where the move-in date has passed, and there is still some activity outstanding.</h4>';

if (!isset($lotSortList)) {
//	$lotSortList = " if(isnull(moveInDate),1,0),  moveInDate, siteShortName, lotNumber  ";
	$lotSortList = " siteShortName, lotNumber ";
}

?>
<br />
<form method="post" action="" > Sort by:
  <select name="lotSortList" onChange="this.form.submit();">
  <option value= " siteShortName, lotNumber ">Lot Number Asc</option>
  <option value= " siteShortName, lotNumber desc " <?php if ($lotSortList == 
                 " siteShortName, lotNumber desc ") {echo " SELECTED ";} ?> >Lot Number Desc</option>
  <option value=" if(isnull(moveInDate),1,0),  moveInDate, siteShortName, lotNumber  " <?php if ($lotSortList == 
                " if(isnull(moveInDate),1,0),  moveInDate, siteShortName, lotNumber  ") {echo " SELECTED ";} ?> >Move In Date Asc</option>
  <option value=" if(isnull(moveInDate),1,0),  moveInDate desc, siteShortName, lotNumber " <?php if ($lotSortList == 
                " if(isnull(moveInDate),1,0),  moveInDate desc, siteShortName, lotNumber ") {echo " SELECTED ";} ?> >Move In Date Desc</option>
</select>  
</form>
<table width="100%" border="1" cellpadding="0" cellspacing="0" class="tableLotData" id="lotListTable">
  <thead>
  <tr>
<th >Lot</th>
    <th align="center">Model on Offer<br />
      (or Designated Model)</th>
    <th  align="center" width="0">Date on Offer</th>
    <th align="center" >Move In 
      Date      <br /></th>
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
    <th align="center" >Watch</th>
</tr>
</thead>
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
$query = 'select * from offerDetailViewSignedOnly';
if ($siteShortName > "") {
	$query = $query.'  where moveInDate < curdate() and siteShortName="'.$siteShortName.'" ';
}
else {
	$query = $query.'  where moveInDate < curdate() and 1=1 ';
}



$query = $query." order by ".$lotSortList;

if ($lotSortList > '') {
	$query = $query.",";
}

$query = $query."siteShortName, lotNumber ";
//echo '<br>'.$query;

if ($db2->Query($query)) { 

	while ($resultRow = $db2->Row() ) {

		$headOfficeExpected = getExpectedCountForLotLocation($dbSingleUse, "Head Office",$resultRow->lotNumber, $resultRow->siteShortName);
		$siteOfficeExpected = getExpectedCountForLotLocation($dbSingleUse, "Site Office",$resultRow->lotNumber, $resultRow->siteShortName);
		$headOfficeCompleted = getCompletedCountForLot($dbSingleUse,$resultRow->lotNumber, $resultRow->siteShortName, 'Head Office', null);
		$headOfficeOutstanding = $headOfficeExpected - $headOfficeCompleted;
		$siteOfficeCompleted = getCompletedCountForLot($dbSingleUse,$resultRow->lotNumber, $resultRow->siteShortName, 'Site Office', null);
		$siteOfficeOutstanding = $siteOfficeExpected - $siteOfficeCompleted;
		$mostRecentBuildAction = '-';
		$mostRecentBuildAction = mostRecentBuildAction ($dbSingleUse,$resultRow->lotNumber, $resultRow->siteShortName);
		
		$mostRecentBuildActionDate = '-';
		if ($mostRecentBuildAction != '-') {
			$mostRecentBuildActionDate = buildActionDate ($dbSingleUse,$resultRow->lotNumber, $resultRow->siteShortName, $mostRecentBuildAction);
		}
		else
		{
			$mostRecentBuildActionDate = '-';
		}

		//$filterClearingGroup;
		$showThisRow = true;

		
		if ($headOfficeOutstanding == 0  and $siteOfficeOutstanding == 0) {
				$showThisRow = false;
		}
		
		if ($showThisRow == true)  {
			echo '<tr>';
			echo '<td class="lotLinkCellInTable" ><a href="index.php?myAction=APSDetails&lotNumber='.$resultRow->lotNumber.'&siteShortName='.$resultRow->siteShortName.'">'.$resultRow->lotNumber;
			if ($siteShortName <= "") {
					echo '<small>('.$resultRow->siteShortName.')</small>';
			}
			echo '</a></td>';
			
			$modelName =  '-';
			if ($resultRow->modelName > '') {
				$modelName = $resultRow->modelName;
			}
			else {
				if ($resultRow->designatedModelName > '') {
					$modelName = $resultRow->designatedModelName.'(d)';
				}
			}
			echo '<td align="center"> '.$modelName.'</td>';
			echo '<td align="center"> '.nullToChar($resultRow->offerDate,'-').'</td>';
			echo '<td align="center"> '.nullToChar($resultRow->moveInDate,'-');
			if ($securityLevelOneCheck) {
				echo $resultRow->amendedMoveInText;
			}
			$bgColor = "";
			if (isset($resultRow->moveInDate)) {
				$bgColor = ' bgcolor = "'.getLotBuildStatusColor( $dbSingleUse,$resultRow->lotNumber,$resultRow->siteShortName, "Head Office").'" ' ;
			}
    		echo '<td '.$bgColor.'  class="tableLotDetailsNumbericData">'.$headOfficeCompleted.'</td>';
    		echo '<td class="tableLotDetailsNumbericData" >'.$headOfficeOutstanding.'</td>';
			$bgColor = "";
			if (isset($resultRow->moveInDate)) {
				$bgColor = ' bgcolor = "'.getLotBuildStatusColor( $dbSingleUse,$resultRow->lotNumber, $resultRow->siteShortName, "Site Office").'" ' ;
			}
			echo '<td '.$bgColor.' class="tableLotDetailsNumbericData">'.$siteOfficeCompleted.'</td>';
    		echo '<td class="tableLotDetailsNumbericData">'.$siteOfficeOutstanding.'</td>';
	    	echo '<td class="tableLotDetailsNumbericData">'.nullToChar($mostRecentBuildActionDate, '-').'</td>';
    		echo '<td class="tableLotDetailsNumbericData">'.nullToChar($mostRecentBuildAction,'-').'</td>';
			$formAction = 'index.php?myAction=Lots&siteShortName='.$resultRow->siteShortName;
			echo '<td align="center">';
			echo getlotWatchCheckbox($dbSingleUse,$resultRow->lotNumber, $resultRow->siteShortName, $userName, $formAction);
		
			echo '</td>';

			echo '</tr>';
		}
	}
}
		

?>
</table>
