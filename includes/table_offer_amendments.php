<?php 

$query = 'select *
			from offerAmendments 	
				where  dateDocumentSigned is not null and dateDocumentSigned > "0000-00-00"  and siteShortName = "'.$siteShortName.'" and lotNumber ='.$lotNumber;

$query = $query.' order by id ';
//echo $query;
$x=0;
if ($dbSingleUse->Query($query)) { 
	while ($rowFeatures = $dbSingleUse->Row()) {
		$x = $x + 1;
		if ($x==1) {
			echo '<table class="clsPrattTable" width="100%" border="1" cellspacing="1" cellpadding="1" align="left">';
			echo '<tr><th width="6%" class="apsHeaderStandard" ><strong>ITEM</td>
			<td width="12%" class="apsHeaderStandard" >SIGNED DATE</td>
		    <td width="67%" class="apsHeaderLarge" >AMENDMENT DETAIL</td>';
			if ($securityLevelOneCheck) {
				echo '<td width="15%" class="apsHeaderStandard" >PRICE</td>';
			}
			echo '</tr>';
		}
		echo '<tr>';
	    echo '<td  align="center"><strong>'.$x.'</strong></td>';
    	echo '<td><strong>'.formatDateForHTML($rowFeatures->dateDocumentSigned,'-').'</strong></td>';
    	echo '<td><strong>'.nullToChar($rowFeatures->amendmentDescription,'-').'</strong></td>';
		if ($securityLevelOneCheck) {
	    echo '<td  align="right"><strong>$';
			echo $rowFeatures->amendmentResalePrice;
		echo '</strong></td>';
		}
		echo '</tr>';
	}
	} 
	if ($x > 0) {
		echo '</table>'; 
	}
	?>
