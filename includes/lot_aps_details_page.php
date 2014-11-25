<?php
require_once ("classes/misc_functions.php");
require_once ("classes/login_functions.php");
include("site_link_bar_lot_detail.php");
$currentSettingCheck = 'Show Lot Notes on Build Screen';
$settingValue = getSettingValue($dbSingleUse, $currentSettingCheck) ;
if ($settingValue == '1') {
	include("lot_notes_trade.php");
}


$reportDocumentHeaderTitle1 = 'A.P.S  DETAILS'; 
$reportDocumentHeaderTitle2 = 'AGREEMENT OF PURCHASE AND SALE DETAILS'; 
?>
<table class="clsPrattTable" border="0" cellspacing="0"><tr><td>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><u><h1><?php echo($reportDocumentHeaderTitle1); ?></h1></u>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><h2><?php echo($reportDocumentHeaderTitle2); ?></h2></td>
        </tr>
    </table></td>
    <td align="center"><img src="images/pratt_construction.jpg" width="240" height="83" alt="Pratt Contruction" /></td>
  </tr>
  <tr>
  <td align="right"><b>Date:</b></td>
  <td align="center"><b><?php printCurrentDateLong();?></b></td>
  </tr>
</table>


<!--<FORM  ><input name="myAction" type="hidden" value="Lots" /><INPUT TYPE="submit" VALUE="Back"></FORM> -->
<?php


$chartNumber = 2;
$query = "select * from offerChartHeaderData where availableToPublic = 1 and lotNumber = ".$lotNumber." and siteShortName = '".$siteShortName."'";
$query = $query." and chartId=".$chartNumber;
//echo $query;								
if ($db->HasRecords($query)) {
	//require_once ("lot_colour_chart_print.php");
	echo '</td></tr>';
	echo '<tr><td align="center" bgcolor="orange">';
	echo '<a target="_blank" href="index.php?myAction=ColourChart&chartNumber=2&lotNumber='.$lotNumber.'&siteShortName='.$siteShortName.'">View Colour Chart</a>';
}

echo '</td></tr>';
$query = 'select *
			from offerDetailViewSignedOnly 	
				where siteShortName = "'.$siteShortName.'" and lotNumber ='.$lotNumber;

//printf( '<tr><td>'.$query.'</td></tr>');
$rowNumm = 0;
if ($db->Query($query)) { 

	while ($resultRow = $db->Row()) {

	$rowNumm = $rowNumm + 1;
	echo excavationStartedMessage($dbSingleUse,$lotNumber, $siteShortName)	;
	echo '<tr><td>';
	echo '<h1>Lot '.$resultRow->lotNumber;
			if ($siteShortName <= "") {
					echo '<small>('.$resultRow->siteShortName.')</small>';
			}
			echo ' - '.$resultRow->siteName.'</h1>';
	
	$offerInfo = $resultRow;
    echo '</td></tr>';
	echo '<tr><td>';
	require_once ("table_aps_header.php");
    echo '</td></tr>';
	echo '<tr><td>&nbsp;';
    echo '</td></tr>';
	echo '<tr><td>';
	include("uploaded_documents.php");
    echo '</td></tr>';
	echo '<tr><td>&nbsp;';
    echo '</td></tr>';
	echo '<tr><td>';
	include ("table_offer_extras.php");
    echo '</td></tr>';
	echo '<tr><td>&nbsp;';
    echo '</td></tr>';
	echo '<tr><td>';
	include ("table_offer_change_orders.php");
    echo '</td></tr>';
	echo '<tr><td>';
	include ("table_offer_amendments.php");
    echo '</td></tr>';
	echo '<tr><td>';
	include ("table_offer_work_credits.php");
    echo '</td></tr>';
	echo '<tr><td>';
	}
}

/*
if ($rowNumm == 0) {
	echo '<br><br><h1>Error: Lot '.$lotNumber.' was not found in the database.</h1><br><br>';
}
*/
  echo '</td></tr>';
	echo '</table>';




?>

<form>
 <input type="button" value="Back" onClick="javascript:window.history.back();" />
</form>
<!--<FORM  ><input name="myAction" type="hidden" value="Lots" /><INPUT TYPE="submit" VALUE="Back"></FORM> -->
