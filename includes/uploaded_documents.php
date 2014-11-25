<?php

$query = 'select *
			from lotDocuments 	
				where availableToPublic = true
				and siteShortName = "'.$siteShortName.'" and lotNumber ='.$lotNumber;
$query = $query.' order by dateAdded';
$rowNumm = 0;
if ($db->Query($query)) { 

	while ($resultRow = $db->Row()) {

		$rowNumm = $rowNumm + 1;
		if ($rowNumm == 1) {
			echo '<table class="clsPrattTable">';
			echo '<tr>';
			echo '<th>Document</th>';
			echo '<th>Notes</th>';
			echo '<th width="10%" align="center">Date Added</th>';
			echo '<th width="15%">User</th>';
			echo '</tr>';
		}
		echo '<tr>';
		echo '<td class="tableDataSmall"><a target="_blank" href="http://d.prattbarrietrade.com/'.$resultRow->documentName.'">'.$resultRow->documentName.'</a></td>';
		echo '<td class="tableDataSmall">'.$resultRow->documentNotes.'</td>';
		echo '<td class="tableDataSmall" >'.substr($resultRow->dateAdded, 0,10).'</td>';
		echo '<td class="tableDataSmall">'.$resultRow->userName.'</td>';
		echo '</tr>';
	}
}

if ($rowNumm > 0) {
	echo '</table>';
}
?>