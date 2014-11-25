<?php
require_once ("classes/misc_functions.php");
require_once ("classes/login_functions.php");


function printChartSection($db2, $chartNumber, $sectionName, $siteShortName, $lotNumber) {

	$query = "select * from chartSections where chartId=".$chartNumber." and sectionName = '".$sectionName."' order by sortSequence, sectionDisplayTitle";
    //echo $query;
	echo '<table width="100%" cellpadding="0" cellspacing="0" >';
	$chartSectionsArray = $db2->QueryArray($query);
	//print_r($chartSectionsArray );
	foreach ($chartSectionsArray as $i => $sectionRow) 
	{ 
	
		$displayValue = "";
		if ($sectionRow["showSectionTitle"]) {
			$displayValue = $sectionRow["sectionDisplayTitle"];
		}
		echo '<tr><td><table  class="clsPrattChartTable" ><tr>';
		echo '<td  class="tableChartPrintSectionTitle" bgcolor="#CCCCCC" >'.$displayValue.'</td>';
		$query = "select * from chartSectionCategories where chartSectionId=".$sectionRow["id"].' order by sortSequence, categoryDisplayTitle';
		//echo '<br>'.$query;
		//print_r($chartCategoriesArray );
		$numberOfCategories = 0;
		if ($db2->HasRecords($query)) {
			$chartCategoriesArray = $db2->QueryArray($query);
			foreach ($chartCategoriesArray as $i => $categoryRow) 
			{
				$displayValue = "";
				if ($categoryRow["showCategoryTitle"]) {
					$displayValue = $categoryRow["categoryDisplayTitle"];
				}
				echo '<td width="150px" bgcolor="#CCCCCC" class="tableChartPrintSectionTitle"><b>'.$displayValue.'</b></td>';
				$numberOfCategories +=1;
			}
		}
		echo '</tr>';
		$query = "select * from chartSectionItems where chartSectionId=".$sectionRow["id"].' order by sortSequence, itemDisplayTitle';
		//echo '<br>'.$query;
		//print_r($chartItemsArray );
		$printItemTitle = false	;				
		if ($db2->HasRecords($query)) {
			$chartItemsArray = $db2->QueryArray($query);
			foreach ($chartItemsArray as $i => $itemRow) 
				
			{
				if ($itemRow["printColour"] > '') {
					$itemStartFont = '<font color = "'.$itemRow["printColour"].'">';
					$itemEndFont = '</font>';
				}
				else {
					$itemStartFont = '';
					$itemEndFont = '';
				}
				echo '<tr>';
				if ($itemRow["showItemTitle"]) {
					$printItemTitle = true	;				
					echo '<td width="200" class="tableChartPrintLabel">';
					echo $itemRow["itemDisplayTitle"];
					echo '</td>';
				}
				$query = "select * from chartSectionCategories where chartSectionId=".$sectionRow["id"].' order by sortSequence, categoryDisplayTitle';
				//print_r($chartCategoriesArray );
				if ($db2->HasRecords($query)) {
					$entryItemsArray = $db2->QueryArray($query);
					foreach ($entryItemsArray as $j => $entryItemRow) 
					{
						$query = "select * from offerChartData where lotNumber = ".$lotNumber." and siteShortName = '".$siteShortName."'";
						$query = $query." and itemId=".$itemRow["id"];
						$query = $query." and categoryId=".$entryItemRow["id"];
					//	echo $query;								
					//print_r($chartCategoriesArray );
						$textValue = "";
						$currencyValue = "";
						if ($db2->HasRecords($query)) {
							$itemDataArray = $db2->QueryArray($query);
							$textValue = $itemDataArray[0]["textValue"];
							$currencyValue = $itemDataArray[0]["currencyValue"];
						}
						$itemFormName = 'textValue_'.$itemRow["id"].'_'.$entryItemRow["id"];
						if ($sectionRow["entryTextBoxHeight"] == 1) {
						}
						if ($itemRow["showItemTitle"]) {
							$colspan = 'colspan="1"';
						}
						else {
							$colspan = 'colspan="2"';
						}
						$chartDataWidth = 290 /$numberOfCategories;
						echo '<td width="'.$chartDataWidth.'" '.$colspan.'class="tableChartPrintData" >';
						echo $itemStartFont;
						echo nl2br($textValue);
						echo $itemEndFont;
						//echo htmlspecialchars(nl2br($textValue));
						echo '</td>';
	
					}
				}
				echo '</tr>';
			}
		}
		$query = "select * from offerChartAdditionalItems where lotNumber = ".$lotNumber." and siteShortName = '".$siteShortName."'";
		$query = $query." and chartSectionId=".$sectionRow["id"];
		$query = $query."  order by rowNumber, columnNumber";
		//echo '<br>'.$query;
		//print_r($chartItemsArray );
        $prevRowNumber = -1;
		if ($db2->HasRecords($query)) {
			$extraItemsArray = $db2->QueryArray($query);
			foreach ($extraItemsArray as $i => $extraRow) 
			{
				if ($extraRow["rowNumber"] > $prevRowNumber) {
					$rowNumber += 1;
					if ($prevRowNumber != -1) {
						echo "</tr>";
					}
					echo "<tr>";
					$columnNumber=0;
				}
				$textValue = $extraRow["textValue"];
				$currencyValue = $extraRow["currencyValue"];
				$prevRowNumber = $extraRow["rowNumber"];
				if ($sectionRow["entryTextBoxHeight"] == 1) {
				}
				if ($itemRow["showItemTitle"]) {
					$colspan = 'colspan="1"';
				}
				else {
					$colspan = 'colspan="2"';
				}
				$chartDataWidth = 290 /$numberOfCategories;
//				if ($printItemTitle == true) {
				if ($extraRow["columnNumber"] == 0)  {
					echo '<td class="tableChartPrintLabel">';
				}
				else {
					echo '<td width="'.$chartDataWidth.'" '.$colspan.'class="tableChartPrintData" >';
				}
				echo nl2br($textValue);
				//echo htmlspecialchars(nl2br($textValue));
				echo '</td>';
			}
			if ($prevRowNumber != -1) {
				echo '</tr>';
			}
		}
		echo '</table></td></tr>';
	}
	echo "</table>";
}


$reportDocumentHeaderTitle1 = 'Colour Chart - For Trades'; 


?>
<div style="float: center; width: 950px;">
<table width="100%"><tr><td>

     
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
    <td  align="center"><img src="images/Pratt_Innisfil_Logo_Splash[1].png" width="210" height="73" alt="Pratt Contruction" /></td>
    		<td>
  			<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td>
            	<u><font size="+3"><b><?php echo($reportDocumentHeaderTitle1); ?></b></font></u>
                </td>
                <td >
                <?php
                	echo '<b>';
                    printCurrentDateLong();
					echo '</b>';
				?>
                </td>
                </tr>
<?php 

$query = 'select *
			from offerDetailView 	
				where siteShortName = "'.$siteShortName.'" and lotNumber ='.$lotNumber;

//printf( '<tr><td>'.$query.'</td></tr>');
$rowNumm = 0;
if ($db->Query($query)) { 

	while ($resultRow = $db->Row()) {

	$rowNumm = $rowNumm + 1;
	echo '<tr><td colspan="2">';
	echo '<font size="+1">Lot '.$resultRow->lotNumber.' - '.$resultRow->siteName.' - '.$resultRow->modelName.'</font>';
	
	$offerInfo = $resultRow;
    echo '</td>';
	echo '</tr>';
	}
}

?>
</table>
</td>
  </tr>
</table>
  <tr>
 
  </tr>
</table>

<table width="100%">

<?php 
	echo "<tr>";
	echo "<td width=45%>";	
	printChartSection($db2, $chartNumber, 'Exterior', $siteShortName, $lotNumber);
	printChartSection($db2, $chartNumber, 'Flooring Section', $siteShortName, $lotNumber);
	echo "</td>";	
	echo '<td width=55%>';
	printChartSection($db2, $chartNumber, 'Other Items Section', $siteShortName, $lotNumber);
	printChartSection($db2, $chartNumber, 'Comments', $siteShortName, $lotNumber);
    echo '</td>';
	echo "</tr>";
	echo "<tr>";
	echo '<td colspan="2">';	
	printChartSection($db2, $chartNumber, 'Storage Section', $siteShortName, $lotNumber);
    echo '</td>';
	echo "</tr>";
	echo "<tr>";
	echo '<td colspan="2">';	
	printChartSection($db2, $chartNumber, 'Wall Tile Section', $siteShortName, $lotNumber);
    echo '</td>';
	echo "</tr>";
	echo "<tr>";
	echo '<td colspan="2">';	
	printChartSection($db2, $chartNumber, 'Changes', $siteShortName, $lotNumber);
    echo '</td>';
	echo "</tr>";


?>
  </table>
</td>
<td>
</td>
</tr>
</table>
</div>