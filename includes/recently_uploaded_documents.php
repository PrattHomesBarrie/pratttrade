<h2>Recent Uploaded Documents</h2>
<?php

$query = 'select l.*, u.lastName as lastName from lotDocuments l left join users u on l.userName= u.userName ';
$query = $query.' order by dateAdded desc';
$query = $query.' limit 20';
$rowNumm = 0;
if ($db->Query($query)) { 

	while ($resultRow = $db->Row()) {

		$rowNumm = $rowNumm + 1;
		if ($rowNumm == 1) {
			echo '<table class="clsPrattTable">';
			echo '<tr>';
			echo '<th width="5%"></th>';
			echo '<th width="5%"></th>';
			echo '<th width="35%">Document</th>';
			echo '<th width="35%">Notes</th>';
			echo '<th width="10%" align="center">Date Added</th>';
			echo '<th width="15%">User</th>';
			echo '<th width="3%">Public?</th>';
			echo '</tr>';
		}
		echo '<tr>';
	    echo '<td class="tableDataSmall" >'.$resultRow->siteShortName.'</td>';
	    echo '<td class="tableDataSmall" align="center"><a href="index.php?myAction=APSDetails&lotNumber='.$resultRow->lotNumber.'&siteShortName='.$resultRow->siteShortName.'">'.$resultRow->lotNumber.'</a></td>';
		echo '<td class="tableDataSmall"><a target="_blank" href="http://prattbarriebuild.com/uploadedItems/'.$resultRow->documentName.'">'.$resultRow->documentName.'</a></td>';
		echo '<td class="tableDataSmall">'.$resultRow->documentNotes.'</td>';
		echo '<td class="tableDataSmall" >'.substr($resultRow->dateAdded, 0,10).'</td>';
		echo '<td class="tableDataSmall">'.$resultRow->lastName.'</td>';
		if ($resultRow->availableToPublic == 1) {
			$checked = 'checked="checked"';
			$tdbgcolor = 'bgcolor="green"';
		}
		else {
			$checked = '';
			$tdbgcolor = 'bgcolor="yellow"';
		}
		
		echo '<td  align="center" '.$tdbgcolor.'>'; 
		echo ' ';
		echo '</td>';
		echo '</tr>';
	}
}

if ($rowNumm > 0) {
	echo '</table>';
}
?>