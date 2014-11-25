
<?php

$query = 'select *
			from lotNotes
				where availableToPublic = 1 and  siteShortName = "'.$siteShortName.'" and lotNumber ='.$lotNumber;
$query = $query.' order by noteDate desc';
$rowNumm = 0;
//echo $query;
if ($db->Query($query)) { 

	while ($resultRow = $db->Row()) {

		$rowNumm = $rowNumm + 1;
		if ($rowNumm == 1) {
			echo '<h3>Lot Related Notes</h3>';
			echo '<table class="clsPrattTable">';
			echo '<tr>';
			echo '<th width="60%">Note</th>';
			echo '<th width="14%" align="center">Date Added</th>';
			/*
			echo '<th width="18%">User</th>';
			echo '<th width="8%">Available To Public</th>';
			if ($allowLotNoteEditing ) {
				echo '<th colspan="2" align="center" width="15%">Action</th>';
			}
			*/
			echo '</tr>';
		}
		echo '<tr>';
		echo '<td class="tableDataNotes" >'.nl2br($resultRow->noteText).'</td>';
		echo '<td class="tableDataSmall" align="center">'.substr($resultRow->noteDate, 0,10).'</td>';
		/*
		echo '<td class="tableDataSmall" align="center">'.$resultRow->userName.'<p></p></td>';
		if ($resultRow->availableToPublic == 1) {
			$checked = 'checked="checked"';
			$tdbgcolor = 'bgcolor="green"';
		}
		else {
			$checked = '';
			$tdbgcolor = 'bgcolor="yellow"';
		}
		echo '<td  align="center" '.$tdbgcolor.'>'; 
			if ($resultRow->availableToPublic == 1) {
				echo 'Public';
			}
			else {
				echo 'Not Public';
			}
		
		echo '</td>';
		if ($allowLotNoteEditing ) {
			echo '<td align="center"><big><a href="index.php?myAction=EditSingleLotNote&lotNotesID='.$resultRow->lotNotesID.'&siteShortName='.$resultRow->siteShortName.'&lotNumber='.$resultRow->lotNumber.'">Edit</a></big></td>';
			echo '<td align="center"><big><a href="index.php?myAction=DeleteSingleLotNote&lotNotesID='.$resultRow->lotNotesID.'&siteShortName='.$resultRow->siteShortName.'&lotNumber='.$resultRow->lotNumber.'" onclick="javascript:return confirm('."'Delete entry?'".')">Delete</a></big></td>';
		}
		*/
		echo '</tr>';
	}
}

if ($rowNumm > 0) {
	echo '</table>';
}
?>
<br />