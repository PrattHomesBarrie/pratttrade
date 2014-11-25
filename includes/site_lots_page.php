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
<?php require_once("lot_filters_form.php");
if (!isset($lotSortList)) {
	$lotSortList = "  ";
	if ($securityLevelOneCheck) {
		$lotSortList .= " siteShortName, lotNumber ";
	}
	else {
		$currentSettingCheck = 'Default Lot Sorting for Level 2 User';
		$settingValue = getSettingValue($dbSingleUse, $currentSettingCheck) ;
		if ($settingValue > 'a') {
			$lotSortList .= $settingValue.',';
		}
		$lotSortList .= " siteShortName, lotNumber ";
	}
}

?>
<table width="100%" border="1" cellpadding="0" cellspacing="0" class="tableLotData" id="lotListTable">
  <thead>
  <tr>
  
<th width="200px">Site</th>
<th width="75px">Lot</th>
    <th align="center">Model on Offer<br />
      (or Designated Model)</th>
	 <th  align="center" width="0">Date on Offer</th>
    <th align="center" >Completion 
      <br />Date</th>
</tr>
</thead>
<?php
 
require_once ("classes/misc_functions.php");


 if (strlen($siteShortName) > 10) {
	 echo "<br><b>Error:something wrong with length of siteShortName";
	 exit;
 }
 
//$filterOfferStatusGroup;
   if ( $securityLevelOneCheck != true ) {
	   $query = 'select * from offerDetailViewSignedOnly';
}
else {
	/* if ($filterOfferStatusGroup == 'All') {
		$query = 'select * from offerDetailView';
	}
	if ($filterOfferStatusGroup == 'With Offers') {
	   $query = 'select * from offerDetailViewSignedOnly';
	}
	 if ($filterOfferStatusGroup == 'Without Offers') {
		$query = 'select * from offerDetailView';
	} */
	$query = 'select * from offerDetailViewSignedOnly';
}

if ($siteShortName > "") {
	$query = $query.'  where siteShortName="'.$siteShortName.'" ';
}
else {
	$query = $query.'  where 1=1 ';
}

//$filterOfferStatusGroup;
	$query = $query.' and offerDate is not null ';
	//$filterClosingStatusGroup;
if ($filterClosingStatusGroup == 'All' or !isset($filterClosingStatusGroup)) {
	//do nothing
}
if ($filterClosingStatusGroup == 'Last 30 Plus' ) {
	$query = $query.' and (calculatedBuildCompletionDate >= curdate() - interval 30 day or calculatedClosingDate >= curdate() ) ';
}
if ($filterClosingStatusGroup == 'In the Future') {
	$query = $query.' and calculatedBuildCompletionDate >= curdate() ';
}
if ($filterClosingStatusGroup == 'In the Past') {
	$query = $query.' and calculatedBuildCompletionDate < curdate() ';
}
if ($filterClosingStatusGroup == 'Next 7 Days') {
	$query = $query.' and calculatedBuildCompletionDate >= curdate() ';
	$query = $query.' and calculatedBuildCompletionDate <= (curdate() + interval 7 day) ';
}
if ($filterClosingStatusGroup == 'Next 14 Days') {
	$query = $query.' and calculatedBuildCompletionDate >= curdate() ';
	$query = $query.' and calculatedBuildCompletionDate <= (curdate() + interval 14 day) ';
}
if ($filterClosingStatusGroup == 'This Fiscal Year') {
	$query = $query." and calculatedBuildCompletionDate >= '".$thisFiscalStart."'";
	$query = $query." and calculatedBuildCompletionDate <= '".$thisFiscalEnd."'";
}
if ($filterClosingStatusGroup == 'Next Fiscal Year') {
	$query = $query." and calculatedBuildCompletionDate >= '".$nextFiscalStart."'";
	$query = $query." and calculatedBuildCompletionDate <= '".$nextFiscalEnd."'";
}

	$query = $query.' and (moveInDate >= curdate() - interval 60 day or calculatedClosingDate >= curdate() ) ';

$query = $query." order by ".$lotSortList;

if ($lotSortList > '') {
	$query = $query.",";
}

$query = $query."siteShortName, lotNumber ";
// echo '<br>'.$query;

if ($db2->Query($query)) { 
	$headOfficeExpected = getExpectedCountForLocation($dbSingleUse, "Head Office");
	$siteOfficeExpected = getExpectedCountForLocation($dbSingleUse, "Site Office");

	while ($resultRow = $db2->Row() ) {



		//$filterClearingGroup;
		$showThisRow = true;

		
		if ($showThisRow == true)  {
			echo '<tr>';
			echo '<td align="left" > '.nullToChar($resultRow->siteName,'-').'</td>';
			echo '<td class="lotLinkCellInTable"  ><a href="index.php?myAction=APSDetails&lotNumber='.$resultRow->lotNumber.'&siteShortName='.$resultRow->siteShortName.'">'.$resultRow->lotNumber;
//			if ($siteShortName <= "") {
//					echo '<small> ('.$resultRow->siteShortName.')</small>';
//			}
			echo '</a></td>';
			echo '<td align="center"> '.nullToChar($resultRow->modelName,'-').'</td>';
			echo '<td align="center"> '.nullToChar($resultRow->offerDate,'-').'</td>';
			echo '<td align="center"> '.nullToChar($resultRow->calculatedBuildCompletionDate,'-');
			if ($securityLevelOneCheck) {
				echo $resultRow->calculatedBuildCompletionDateText;
			}
			echo '</td>';
//			echo '<td align="center"> '.nullToChar($resultRow->moveInDate,'-').$resultRow->amendedMoveInText.'</td>';

			echo '</tr>';
		}
	}
}
		

?>
</table>
