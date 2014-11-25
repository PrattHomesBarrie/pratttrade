<h2>Recently Signed Items</h2>
<?php

require_once ("classes/misc_functions.php");

function recentOffersHeader() {
	echo '  <tr>';
    echo '<th align="center" >Item</th>';
    echo '<th align="center" >Signed Date</th>';
    echo '<th align="center" width="30%" >Additional Details</th>';
    echo '<th align="center" >Site</th>';
    echo '<th align="center" >Lot</th>';
    echo '<th align="center" >Model</th>';
    echo '<th align="center" width="0">Offer<br>Date</th>';
    echo '<th align="center" >Completion<br>Date</th>';
    echo '<th align="center" >Watch</th>';
    echo '</tr>';
}

echo '<table width="100%" border="1" cellpadding="0" cellspacing="0" class="tableLotData">';

if ($debug == "Yes") {
	echo 'Watch='.$watch;
	echo 'updatelotNumber='.$updatelotNumber;
}

$query = "SELECT 'Offer Signed' as itemName, 'offers' as tableName, dateDocumentSigned, offerDate as itemDate, null as id,  siteShortName, lotNumber, null as additionalInfo FROM offers
union SELECT 'Amendment Signed' as itemName, 'offerAmendments' as tableName, dateDocumentSigned, amendmentAddedDate as itemDate, id, siteShortName, lotNumber, amendmentDescription as additionalInfo FROM offerAmendments
union SELECT 'Change Order Signed' as itemName, 'offerChangeOrders' as tableName, dateDocumentSigned, changeAddedDate as itmeDate, id, siteShortName, lotNumber, changeDescription as additionalInfo FROM offerChangeOrders
union SELECT 'Word Credit Signed' as itemName, 'offerWorkCredits' as tableName, dateDocumentSigned, workCreditAddedDate as ItemDate, id, siteShortName, lotNumber, workCreditDescription as additionalInfo FROM offerWorkCredits
order by dateDocumentSigned Desc, id desc, itemDate desc limit 30";

if ($db->Query($query)) { 
	while ($signedRow = $db->Row() ) {
		if ($rowNum == 0) {
			recentOffersHeader();
		}
		$rowNum = $rowNum + 1;
		$query = "select * from offerDetailView  where lotNumber = ".$signedRow->lotNumber." and siteShortName = '".$signedRow->siteShortName."'";
		//echo '<br>'.$query;
		if ($db2->Query($query)) { 
			while ($resultRow = $db2->Row() ) {
				echo '<tr>';
			    echo '<td>'.$signedRow->itemName.'</td>';
				echo '<td align="center"> '.nullToChar($signedRow->dateDocumentSigned,'-').'</td>';
			    echo '<td>'.$signedRow->additionalInfo.'</td>';
			    echo '<td>'.$resultRow->siteShortName.'</td>';
	    		echo '<td align="center"><a href="index.php?myAction=APSDetails&lotNumber='.$resultRow->lotNumber.'&siteShortName='.$resultRow->siteShortName.'">'.$resultRow->lotNumber.'</a></td>';
				echo '<td align="center"> '.nullToChar($resultRow->modelName,'-').'</td>';
				echo '<td align="center">'.nullToChar($resultRow->offerDate,'-').'</td>';

				echo '<td align="center"> '.nullToChar($resultRow->calculatedBuildCompletionDate,'-');
				if ($securityLevelOneCheck) {
					echo $resultRow->calculatedBuildCompletionDateText;
				}
				echo '</td>';
		    	echo '<td class="tableLotDetailsNumbericData">';
				$formAction = 'index.php?myAction=Activity&siteShortName='.$resultRow->siteShortName;
				getlotWatchCheckbox($dbSingleUse,$resultRow->lotNumber, $resultRow->siteShortName, $userName, $formAction);
				echo '</td>';
				echo '</tr>';
			}
		}
	}
}
echo '</table>';

?>

  