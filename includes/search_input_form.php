<?php
function getSearchPostValue($formField) {
	$postValue = '';
	if (isset($_POST[$formField])) {
		$postValue = $_POST[$formField];
	}
	else if (isset($_GET[$formField])) {
		$postValue = $_GET[$formField];
	}
	else if ($_POST["SearchScreenSubmitted"] == "Yes") {
		//will be blank if this is true
	}
	else {
		// get session value -- returning to search screen from another screen
		$postValue = $_SESSION[$formField];
	}

	//set session value
	$_SESSION[$formField] = $postValue;
	
	return $postValue;
}

$searchNameOnOffer = getSearchPostValue('searchNameOnOffer');
$searchModelOnOffer = getSearchPostValue('searchModelOnOffer');
$searchFeatureText = getSearchPostValue('searchFeatureText');
$searchAmendmentText = getSearchPostValue('searchAmendmentText');
$searchWorkCreditText = getSearchPostValue('searchWorkCreditText');
$searchChangeOrderText = getSearchPostValue('searchChangeOrderText');
//$ = getSearchPostValue('');
//$ = getSearchPostValue('');
//$ = getSearchPostValue('');



?>
<form id="form1" name="form1" method="post" action="">
<input name="SearchScreenSubmitted" type="hidden" value="Yes" />

  <table width="100%" border="1" cellpadding="0" cellspacing="0" class="tableLotFilters">
 <tr>
      <th align="right" valign="middle">At these sites</th>
      <td>
      <table class="tableLotFilters" width="100%">
<tr>
<?php 
$x=0;  
$query = ' SELECT * FROM sites';
if ($dbSingleUse->HasRecords($query)) { 
	$siteArray = $dbSingleUse->QueryArray($query);
	foreach ($siteArray as $j => $siteRow) {
		$x = $x + 1;
		echo '<td ';
		if ($siteRow["siteShortName"] == $siteShortName) {
			echo ' bgcolor="#FFFFCC" ';
		}
		echo '>';
		echo '<label>';
        echo '<input type="radio" name="siteShortName" value="';
		echo $siteRow["siteShortName"];
		echo '" id="siteShortName"';
		if ($siteRow["siteShortName"] == $siteShortName) {
			echo ' checked="checked" ';
		}
		echo ' "/>';
		echo $siteRow["siteShortName"];
		echo '</label></td>';
	}
}
echo '<td';
if ('' == $siteShortName ) {
	echo ' bgcolor="#FFFFCC" ';
}
echo '><label>';
echo '<input type="radio" name="siteShortName" value="All"';
if ('' == $siteShortName) {
	echo ' checked="checked" ';
}
echo ' "/>';
echo 'All';
echo '</label></td>';

?>
</tr>
</table>
</td>
</tr>
    <tr>
      <th align="right">With this offer status</th>
      <td>
      <table class="tableLotFilters">
      <tr>
 <?php 
	/*if ($securityLevelOneCheck) {
	
     echo '<td ';
		  if ($filterOfferStatusGroup == 'All') { echo ' bgcolor="#FFFFCC" ';} 
		  echo '><label  >
            <input  name="filterOfferStatusGroup" type="radio" id="filterOfferStatusGroup_0" value="All" ';
        if ($filterOfferStatusGroup == 'All' ) { echo ' checked="checked"  ';} 
	      echo '     />
            All Lots</label></td>';
	} */
        	echo '<td ';
		  if ($filterOfferStatusGroup == 'With Offers' or ($securityLevelOneCheck != true )or !isset($filterOfferStatusGroup)) { echo ' bgcolor="#FFFFCC" ';} 
//		  if ($filterOfferStatusGroup == 'With Offers'  or ($securityLevelOneCheck != true )) { echo ' bgcolor="#FFFFCC" ';} 
		  echo '><label>
            <input  type="radio" name="filterOfferStatusGroup" value="With Offers" id="filterOfferStatusGroup_1"  checked="checked" />
            With Signed Offers </label></td>';
		/*	
	if ($securityLevelOneCheck) {
        echo'<td ';
		  if ($filterOfferStatusGroup == 'Without Offers') { echo ' bgcolor="#FFFFCC" ';} 
		  echo '  ><label>
            <input  type="radio" name="filterOfferStatusGroup" value="Without Offers" id="filterOfferStatusGroup_2" ';
            if ($filterOfferStatusGroup == 'Without Offers') { echo ' checked="checked" ';} 
           echo '/>
            Without Offers (incl. unsigned)</label></td>
        ';
	} */
		?>
	</tr>
    </table>
    </td>
    </tr>
    

	<tr><th align="right">Customer Name</th><td><input name="searchNameOnOffer" type="<?php if ($securityLevelOneCheck ) {echo 'text'; } else {echo 'hidden';} ?>" class= "searchInputText" value="<?php echo $searchNameOnOffer; ?>" size="30" maxlength="20" /><?php if ($securityLevelOneCheck ) {echo ''; } else {echo ' n/a';} ?></td></tr>
		
    <tr><th align="right">Model</th><td><input name="searchModelOnOffer" type="text" class= "searchInputText" value="<?php echo $searchModelOnOffer; ?>" size="30" maxlength="20" /></td></tr>
    <tr><th align="right">Feature</th><td><input name="searchFeatureText" type="text" class= "searchInputText" value="<?php echo $searchFeatureText; ?>" size="30" maxlength="20" /></td></tr>
    <tr><th align="right">Amendments</th><td><input name="searchAmendmentText" type="text" class= "searchInputText" value="<?php echo $searchAmendmentText; ?>" size="30" maxlength="20" /></td></tr>
    <tr><th align="right">Work Credits</th><td><input name="searchWorkCreditText" type="text" class= "searchInputText" value="<?php echo $searchWorkCreditText; ?>" size="30" maxlength="20" /></td></tr>
    <tr><th align="right">Change Orders</th><td><input name="searchChangeOrderText" type="text" class= "searchInputText" value="<?php echo $searchChangeOrderText; ?>" size="30" maxlength="20" /></td></tr>
     </table>
     <input type="submit" value="Search" />
</form>
