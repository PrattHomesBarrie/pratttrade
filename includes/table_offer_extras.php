<?php 

$query = 'select *
			from offerFeatures 	
				where   siteShortName = "'.$siteShortName.'" and lotNumber ='.$lotNumber;

$query = $query.' order by id ';
//echo $query;
$x=0;
if ($dbSingleUse->Query($query)) { 
	while ($rowFeatures = $dbSingleUse->Row()) {
		$x = $x + 1;
		if ($x==1) {
			echo '<table class="clsPrattTable" width="100%" border="1" cellspacing="1" cellpadding="1" align="left">';
			echo '<tr><td width="6%" class="apsHeaderStandard" >ITEM</td>
			    <td class="apsHeaderLarge" width="79%">EXTRA DETAIL</td>';
			if ($securityLevelOneCheck) {
					echo '<td width="15%" class="apsHeaderStandard" >PRICE</td>';
			}
			echo '</tr>';
		}
		echo '<tr>';
	    echo '<td rowspan="2" align="center"><strong>'.$x.'</strong></td>';
    	echo '<td><strong>'.nullToChar($rowFeatures->featureText,'-').'</strong></td>';
		if ($securityLevelOneCheck) {
	    echo '<td rowspan="2" align="right"><strong>$';
			echo $rowFeatures->featureResalePrice;
		echo '</strong></td>';
		}
		echo '</tr>';
	    echo '<tr>';
    		echo '<td><small>'.nullToChar($rowFeatures->featureSubText,'&nbsp;').'</small></td>';
	 	echo '</tr>';
	}
	} 
	if ($x > 0) {
		echo '</table>'; 
	}
	?>
