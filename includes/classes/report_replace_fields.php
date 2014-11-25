<?php

function getAmendmentPrintItem($tableName, $offerInfo, $dbSingleUse, $itemNumber, $columnName) {

	$returnValue = ''	;
	$query = 'select ';
	if ($itemNumber  == 99) {
		$offset = 0;
		$query = $query.' sum('.$columnName.') as '.$columnName;
	}
	else {
		$offset = $itemNumber - 1;
		$query = $query.' '.$columnName.' as '.$columnName;
	}
	$query = $query.' from '.$tableName.' where  siteShortName = "'.$offerInfo->siteShortName.'" and lotNumber ='.$offerInfo->lotNumber;
	if ($tableName <> 'offerFeatures') {
		$query = $query.' and printThisItem = true  ';
	}
	$query = $query.' order by id ';
	$query = $query.' limit '.$offset.', 1';
//	echo '<br>'.$tableName.'-'.$itemNumber.'-'.$query;
	if ($dbSingleUse->Query($query)) { 
	    $rowValue = $dbSingleUse->Row();
		$returnValue = $rowValue->$columnName;
	}
	return $returnValue;
}

function getOfferText($offerInfo, $textToReplace,$dbSingleUse = null){
	setlocale(LC_MONETARY, "en_US");
 
	$returnValue = "need to add logic - mike";

	$query = '';

	$textToReplaceOriginal = $textToReplace;
	$textToReplace = strtoupper($textToReplace);

	if (
	 	$textToReplaceOriginal == 'elevation'
	or	$textToReplacOriginale == 'lotNumber'
	or 	$textToReplaceOriginal == 'lotSize'
	or 	$textToReplaceOriginal == 'modelName'
	or 	$textToReplaceOriginal == 'munStreetAddress'
	or 	$textToReplaceOriginal == 'munStreetNumber'
	or 	$textToReplaceOriginal == 'numberOfBedrooms'
	or 	$textToReplaceOriginal == 'planNumber'
	or 	$textToReplaceOriginal == 'postalCode'
	or 	$textToReplaceOriginal == 'siteName'
	or 	$textToReplaceOriginal == 'siteShortName'
	or 	$textToReplaceOriginal == 'amendedClosingText'
	or 	$textToReplaceOriginal == 'amendedOccupancyText'
	or 	$textToReplaceOriginal == 'homePhone'
	or 	$textToReplaceOriginal == 'workPhone'
	or 	$textToReplaceOriginal == 'otherPhone'
	or 	$textToReplaceOriginal == 'email1'
	or 	$textToReplaceOriginal == 'clientAddress'
	or 	$textToReplaceOriginal == 'clientCity'
	or 	$textToReplaceOriginal == 'clientProvince'
	or 	$textToReplaceOriginal == 'clientPostalCode'

	) {
	$returnValue =  $offerInfo->$textToReplaceOriginal;
	}

	elseif (
	 	$textToReplaceOriginal == 'amendedClosingDate'
	or	$textToReplaceOriginal == 'calculatedClosingDate'
	or	$textToReplaceOriginal == 'calculatedOccupancyDate'
	or	$textToReplaceOriginal == 'moveInDate'
	or	$textToReplaceOriginal == 'calculatedClosingDate'
	or 	$textToReplaceOriginal == 'closingDate'
	or 	$textToReplaceOriginal == 'occupancyDate'
	or 	$textToReplaceOriginal == 'offerDate'
	) {
		if (isset($offerInfo->$textToReplaceOriginal)  &&  $offerInfo->$textToReplaceOriginal > '0000-00-00') {
			$returnValue =  date('F dS, Y',strtotime($offerInfo->$textToReplaceOriginal));
		}
		else {
			$returnValue =  '-';
		}
	}
	elseif ($textToReplace == strtoupper('BIRTHDATE1')) {
		$returnValue = '';
		if (isset($offerInfo->birthDate1)  &&  $offerInfo->birthDate1 > '0000-00-00') {
			$returnValue =  date('F dS, Y',strtotime($offerInfo->birthDate1));
		}
	}
	elseif ($textToReplace == strtoupper('BIRTHDATE2')) {
		$returnValue = '';
		if (isset($offerInfo->birthDate2)  &&  $offerInfo->birthDate2 > '0000-00-00') {
			$returnValue =  date('F dS, Y',strtotime($offerInfo->birthDate2));
		}
	}
	elseif ($textToReplace == strtoupper('BIRTHDATE3')) {
		$returnValue = '';
		if (isset($offerInfo->birthDate3)  &&  $offerInfo->birthDate3 > '0000-00-00') {
			$returnValue =  date('F dS, Y',strtotime($offerInfo->birthDate3));
		}
	}
	elseif ($textToReplace == strtoupper('allBirthDates')) {
		$returnValue = '';
		if (isset($offerInfo->birthDate1)  &&  $offerInfo->birthDate1 > '0000-00-00') {
			$returnValue =  date('F dS, Y',strtotime($offerInfo->birthDate1));
		}
		if ($returnValue > '' and isset($offerInfo->birthDate2) && $offerInfo->birthDate2 > '0000-00-00') { 
			$returnValue = $returnValue.' and ';
		}
		if (isset($offerInfo->birthDate2) && $offerInfo->birthDate2 > '0000-00-00') { 
			$returnValue =  $returnValue.date('F dS, Y',strtotime($offerInfo->birthDate2));
		}
		if ($returnValue > '' and isset($offerInfo->birthDate3) && $offerInfo->birthDate3 > '0000-00-00') { 
			$returnValue = $returnValue.' and ';
		}
		if (isset($offerInfo->birthDate3) && $offerInfo->birthDate3 > '0000-00-00') { 
			$returnValue =  $returnValue.date('F dS, Y',strtotime($offerInfo->birthDate3));
		}
	}
	elseif ($textToReplace == strtoupper('offerBaseAmount')) {
		$returnValue = '';
		$number = $offerInfo->offerPrice;
		$returnValue = money_format('%(#10n',$number);
	}
	elseif ($textToReplace == strtoupper('offerDollarsInText')) {
		$returnValue = '';
		$number = $offerInfo->offerPrice + calcSumOfFeatures($dbSingleUse,$offerInfo->lotNumber, $offerInfo->siteShortName) - $offerInfo->offerDiscountAmount;
		$returnValue = translateToWords(intval($number));
	}
	
	elseif ($textToReplace == strtoupper('offerAmount')) {
		$returnValue = '';
		$number = $offerInfo->offerPrice + calcSumOfFeatures($dbSingleUse,$offerInfo->lotNumber, $offerInfo->siteShortName) - $offerInfo->offerDiscountAmount;
		$returnValue = money_format('%(#10n',$number);
	}
	
	elseif ($textToReplace == strtoupper('discountActual')) {
		$returnValue = '';
		$number = $offerInfo->offerDiscountAmount;
		$returnValue = money_format('%(#10n',$number);
	}
	
	elseif ($textToReplace == strtoupper('discountAllowable')) {
		$returnValue = '';
		$number = 	getAvailableSiteDiscount($dbSingleUse,$offerInfo->siteShortName) * calcSumOfFeatures($dbSingleUse,$offerInfo->lotNumber, $offerInfo->siteShortName) / 100;
		$returnValue = money_format('%(#10n',$number);
	}
	elseif ($textToReplace == strtoupper('discountAllowableInteger')) {
		$returnValue = '';
		$number = 	getAvailableSiteDiscount($dbSingleUse,$offerInfo->siteShortName) * calcSumOfFeatures($dbSingleUse,$offerInfo->lotNumber, $offerInfo->siteShortName) / 100;
		$returnValue = number_format($number, 0,'','');
	}
	elseif ($textToReplace == strtoupper('extrasSum')) {
		$returnValue = '';
		$number = 	calcSumOfFeatures($dbSingleUse,$offerInfo->lotNumber, $offerInfo->siteShortName);
		$returnValue = money_format('%(#10n',$number);
	}
	
	elseif ($textToReplace == strtoupper('MSRP')) {
		$returnValue = '';
		$number = 	calcSumOfFeatures($dbSingleUse,$offerInfo->lotNumber, $offerInfo->siteShortName) + $offerInfo->offerPrice;
		$returnValue = money_format('%(#10n',$number);
	}
	elseif ($textToReplace == strtoupper('rTOChequeAmt1')) {
		$returnValue = '';
		$number = 	$offerInfo->rentToOwnInitialDeposit;
		if ($number > 0) {
			$returnValue = money_format('%(#10n',$number);
		}
	}
	elseif ($textToReplace == strtoupper('rTOChequeAmt2')) {
		$returnValue = '';
		if (isset($offerInfo->rentToOwnSubsequentDeposits)) {
			if ($offerInfo->numberOfPayments >= 2) {
				$number = 	700 + $offerInfo->rentToOwnSubsequentDeposits;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 700) {
			$returnValue = '';
		}
	}
	
	elseif ($textToReplace == strtoupper('rTOChequeAmt3')) {
		$returnValue = '';
		if (isset($offerInfo->rentToOwnSubsequentDeposits)) {
			if ($offerInfo->numberOfPayments >= 3) {
				$number = 	700 + $offerInfo->rentToOwnSubsequentDeposits;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 700) {
			$returnValue = '';
		}
	}
	
	elseif ($textToReplace == strtoupper('rTOChequeAmt4')) {
		$returnValue = '';
		if (isset($offerInfo->rentToOwnSubsequentDeposits)) {
			if ($offerInfo->numberOfPayments >= 4) {
				$number = 	700 + $offerInfo->rentToOwnSubsequentDeposits;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 700) {
			$returnValue = '';
		}
	}
	
	elseif ($textToReplace == strtoupper('rTOChequeAmt5')) {
		$returnValue = '';
		if (isset($offerInfo->rentToOwnSubsequentDeposits)) {
			if ($offerInfo->numberOfPayments >= 5) {
				$number = 	700 + $offerInfo->rentToOwnSubsequentDeposits;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 700) {
			$returnValue = '';
		}
	}
	
	elseif ($textToReplace == strtoupper('rTOChequeAmt6')) {
		$returnValue = '';
		if (isset($offerInfo->rentToOwnSubsequentDeposits)) {
			if ($offerInfo->numberOfPayments >= 6) {
				$number = 	700 + $offerInfo->rentToOwnSubsequentDeposits;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 700) {
			$returnValue = '';
		}
	}
	elseif ($textToReplace == strtoupper('rtoSubsequentDeposits')) {
		$returnValue = '';
		$number = 0;
		if ($offerInfo->rentToOwnSubsequentDeposits > 0 ) {
				$number = 	$offerInfo->rentToOwnSubsequentDeposits ;
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 0) {
			$returnValue = '';
		}
	}
	elseif ($textToReplace == strtoupper('sumOfRtoDeposits') || $textToReplace == strtoupper('RtoTotalDeposits')) {
		$returnValue = '';
		$number = 0;
		if (isset($offerInfo->numberOfPayments)) {
			if ($offerInfo->rentToOwnInitialDeposit > 0 ) {
				$number = 	$offerInfo->rentToOwnInitialDeposit + ($offerInfo->rentToOwnSubsequentDeposits * ($offerInfo->numberOfPayments - 1));
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 0) {
			$returnValue = '-';
		}
	}
	elseif ($textToReplace == strtoupper('RtoDeposit1')) {
		$returnValue = '';
		$number = 0;
		if (isset($offerInfo->numberOfPayments)) {
			if ($offerInfo->rentToOwnInitialDeposit > 0 ) {
				$number = 	$offerInfo->rentToOwnInitialDeposit;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 0) {
			$returnValue = '-';
		}
	}
	elseif ($textToReplace == strtoupper('RtoDeposit2')) {
		$returnValue = '';
		$number = 0;
		if ($offerInfo->numberOfPayments >= 2) {
			if ($offerInfo->rentToOwnSubsequentDeposits > 0 ) {
				$number = 	$offerInfo->rentToOwnSubsequentDeposits;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 0) {
			$returnValue = '-';
		}
	}
	elseif ($textToReplace == strtoupper('RtoDeposit3')) {
		$returnValue = '';
		$number = 0;
		if ($offerInfo->numberOfPayments >= 3) {
			if ($offerInfo->rentToOwnSubsequentDeposits > 0 ) {
				$number = 	$offerInfo->rentToOwnSubsequentDeposits;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 0) {
			$returnValue = '-';
		}
	}
	elseif ($textToReplace == strtoupper('RtoDeposit4')) {
		$returnValue = '';
		$number = 0;
		if ($offerInfo->numberOfPayments >= 4) {
			if ($offerInfo->rentToOwnSubsequentDeposits > 0 ) {
				$number = 	$offerInfo->rentToOwnSubsequentDeposits;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 0) {
			$returnValue = '-';
		}
	}
	elseif ($textToReplace == strtoupper('RtoDeposit5')) {
		$returnValue = '';
		$number = 0;
		if ($offerInfo->numberOfPayments >= 5) {
			if ($offerInfo->rentToOwnSubsequentDeposits > 0 ) {
				$number = 	$offerInfo->rentToOwnSubsequentDeposits;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 0) {
			$returnValue = '-';
		}
	}
	elseif ($textToReplace == strtoupper('RtoDeposit6')) {
		$returnValue = '';
		$number = 0;
		if ($offerInfo->numberOfPayments >= 6) {
			if ($offerInfo->rentToOwnSubsequentDeposits > 0 ) {
				$number = 	$offerInfo->rentToOwnSubsequentDeposits;
			}
		}
		$returnValue = money_format('%(#10n',$number);
		if ($number == 0) {
			$returnValue = '-';
		}
	}
	elseif (
	 	$textToReplace == strtoupper('pCity')) {
			
			$returnValue  = $offerInfo->clientCity;
	}
	elseif (
	 	$textToReplace == strtoupper('pProv')) {
			
			$returnValue  = $offerInfo->clientProvince;
	}
	elseif (
	 	$textToReplace == strtoupper('pPostal')) {
			
			$returnValue  = $offerInfo->clientPostalCode;
	}
	elseif (
	 	$textToReplace == strtoupper('pStreet')) {
			
			$returnValue  = $offerInfo->clientAddress;
	}
	elseif (
	 	substr($textToReplace,0,13) == strtoupper('AmendmentItem')) {
			$returnValue =  getAmendmentPrintItem("offerAmendments",$offerInfo, $dbSingleUse,substr($textToReplace, strlen($textToReplace) -1,1), "amendmentDescription" );
	}
	elseif (
	 	substr($textToReplace,0,7) == strtoupper('AmendPr')) {
			$returnValue =  getAmendmentPrintItem("offerAmendments",$offerInfo, $dbSingleUse,substr($textToReplace, strlen($textToReplace) -1,1), "amendmentResalePrice" );
			if ($returnValue > '') {
				$returnValue = money_format('%.2n',floatval($returnValue));
			}
	}
	elseif (
	 	substr($textToReplace,0,11) == strtoupper('AmendSigned')) {
			$returnValue =  getAmendmentPrintItem("offerAmendments",$offerInfo, $dbSingleUse,substr($textToReplace, strlen($textToReplace) -1,1), "dateDocumentSigned" );
    		if ($returnValue > '0000-00-00') {
				$returnValue =  date('F dS, Y',strtotime($returnValue));
			}
	}
	elseif (
	 	$textToReplace == strtoupper('AmendTot')) {
			$returnValue =   money_format('%.2n',getAmendmentPrintItem("offerAmendments",$offerInfo, $dbSingleUse, 99, "amendmentResalePrice" ));
	}
	elseif (
	 	$textToReplace == strtoupper('Bd')) {
			
			$returnValue  = $offerInfo->numberOfBedrooms;
	}
	elseif (
	 	$textToReplace == strtoupper('Bedroom')) {
			
			$returnValue  = $offerInfo->numberOfBedrooms;
	}
	elseif (
	 	substr($textToReplace,0,15) == strtoupper('ChangeOrderItem')) {
			$returnValue =  getAmendmentPrintItem("offerChangeOrders",$offerInfo, $dbSingleUse,substr($textToReplace, strlen($textToReplace) -1,1), "changeDescription" );
	}
	elseif (
	 	substr($textToReplace,0,8) == strtoupper('ChgOrdPr')) {
			$returnValue =  getAmendmentPrintItem("offerChangeOrders",$offerInfo, $dbSingleUse,substr($textToReplace, strlen($textToReplace) -1,1), "changePrice" );
			if ($returnValue > '') {
				$returnValue = money_format('%.2n',floatval($returnValue));
			}
	}
	elseif (
	 	substr($textToReplace,0,12) == strtoupper('ChgOrdSigned')) {
			$returnValue =  getAmendmentPrintItem("offerChangeOrders",$offerInfo, $dbSingleUse,substr($textToReplace, strlen($textToReplace) -1,1), "dateDocumentSigned" );
    		if ($returnValue > '0000-00-00') {
				$returnValue =  date('F dS, Y',strtotime($returnValue));
			}
	}
	elseif (
	 	$textToReplace == strtoupper('ChgOrdTot')) {
			$returnValue =   money_format('%.2n',getAmendmentPrintItem("offerChangeOrders",$offerInfo, $dbSingleUse, 99, "changePrice" ));
	}
	elseif (
	 	$textToReplace == strtoupper('CloseDate')) {
		$returnValue = '';
		if (isset($offerInfo->closingDate)  &&  $offerInfo->closingDate > '0000-00-00') {
			$returnValue =  date('F dS, Y',strtotime($offerInfo->closingDate));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('CloseDateAmended')) {
		$returnValue = '';
		if (isset($offerInfo->amendedClosingDate)  &&  $offerInfo->amendedClosingDate > '0000-00-00') {
			$returnValue =  date('F dS, Y',strtotime($offerInfo->amendedClosingDate));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('CloseDayTH')) {
		$returnValue = '';
		if (isset($offerInfo->closingDate)  &&  $offerInfo->closingDate > '0000-00-00') {
			$returnValue =  date('dS',strtotime($offerInfo->closingDate));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('CloseMonthText')) {
		$returnValue = '';
		if (isset($offerInfo->closingDate)  &&  $offerInfo->closingDate > '0000-00-00') {
			$returnValue =  date('F',strtotime($offerInfo->closingDate));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('CurrentDate')) {
			$returnValue =  date('F dS, Y');
	}
	elseif (
	 	$textToReplace == strtoupper('Cyr2')) {
		$returnValue = '';
		if (isset($offerInfo->closingDate)  &&  $offerInfo->closingDate > '0000-00-00') {
			$returnValue =  date('y',strtotime($offerInfo->closingDate));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('Elev')) {
			
			$returnValue  = $offerInfo->elevation;
	}
	elseif (
	 	$textToReplace == strtoupper('Elevation')) {
			
			$returnValue  = $offerInfo->elevation;
	}
	elseif (
	 	$textToReplace == strtoupper('email')) {
			
			$returnValue  = $offerInfo->email1;
	}
	elseif (
	 	substr($textToReplace,0,8) == strtoupper('ExtraNot')) {
			if (substr($textToReplace,10,5) == strtoupper('Price') || substr($textToReplace,9,5) == strtoupper('Price') ) {
				$ordinal = substr($textToReplace, 8,2);
				if (is_numeric($ordinal)) {
					// 0k - because the ordinal is two digits long
				}
				else {
					$ordinal = substr($ordinal,0,1);
				}
				$returnValue =  getAmendmentPrintItem("offerFeatures",$offerInfo, $dbSingleUse,$ordinal, "featureResalePrice" );
				if ($returnValue > '') {
				$returnValue = money_format('%.2n',floatval($returnValue));
				}
			}
			elseif (substr($textToReplace,10,7) == strtoupper('Subtext') || substr($textToReplace,9,7) == strtoupper('Subtext') ) {
				$ordinal = substr($textToReplace, 8,2);
				if (is_numeric($ordinal)) {
					// 0k - because the ordinal is two digits long
				}
				else {
					$ordinal = substr($ordinal,0,1);
				}
				$returnValue =  getAmendmentPrintItem("offerFeatures",$offerInfo, $dbSingleUse,$ordinal, "featureSubText" );
			}
			else {
				$ordinal = substr($textToReplace, 8,2);
				if (is_numeric($ordinal)) {
					// 0k - because the ordinal is two digits long
				}
				else {
					$ordinal = substr($ordinal,0,1);
				}
				$returnValue =  getAmendmentPrintItem("offerFeatures",$offerInfo, $dbSingleUse,$ordinal, "featureText" );
			}
	}
	elseif (
	 	$textToReplace == strtoupper('FrontDoor')) {
			
			$returnValue  = $offerInfo->frontDoor;
	}
	elseif (
	 	$textToReplace == strtoupper('GarageSize')) {
			
			$returnValue  = $offerInfo->garageSize;
	}
	elseif (
	 	$textToReplace == strtoupper('IrrevDayTH')) {
		$returnValue = '';
		if (isset($offerInfo->irrevocableDate)  &&  $offerInfo->irrevocableDate > '0000-00-00') {
			$returnValue =  date('dS',strtotime($offerInfo->irrevocableDate));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('IrrevMonthText')) {
		$returnValue = '';
		if (isset($offerInfo->irrevocableDate)  &&  $offerInfo->irrevocableDate > '0000-00-00') {
			$returnValue =  date('F',strtotime($offerInfo->irrevocableDate));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('irrevYr2')) {
		$returnValue = '';
		if (isset($offerInfo->irrevocableDate)  &&  $offerInfo->irrevocableDate > '0000-00-00') {
			$returnValue =  date('y',strtotime($offerInfo->irrevocableDate));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('Lawyer')) {
			
			$returnValue  = $offerInfo->lawyer;
	}
	elseif (
	 	$textToReplace == strtoupper('LotCity')) {
			
			$returnValue  = $offerInfo->city;
	}
	elseif (
	 	$textToReplace == strtoupper('LotNum')) {
			
			$returnValue  = $offerInfo->lotNumber;
	}
	elseif (
	 	$textToReplace == strtoupper('LotPostal')) {
			
			$returnValue  = $offerInfo->postalCode;
	}
	elseif (
	 	$textToReplace == strtoupper('LotSize')) {
			
			$returnValue  = $offerInfo->lotSize;
	}
	elseif (
	 	$textToReplace == strtoupper('Model')) {
			
			$returnValue  = $offerInfo->modelName;
	}
	elseif (
	 	$textToReplace == strtoupper('MunicipalAddress')) {
			
			$returnValue  = $offerInfo->munStreetAddress;
			$returnValue = trim($offerInfo->munStreetNumber.' '.$offerInfo->munStreetAddress);
	}
	elseif (
	 	$textToReplace == strtoupper('OccDate')) {
		$returnValue = '';
		if (isset($offerInfo->occupancyDate)  &&  $offerInfo->occupancyDate > '0000-00-00') {
			$returnValue =  date('F dS, Y',strtotime($offerInfo->occupancyDate));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('OccDatePlus1M')) {
		$returnValue = '';
		if (isset($offerInfo->occupancyDate)  &&  $offerInfo->occupancyDate > '0000-00-00') {
			$returnValue = date("F dS, Y", strtotime(date("F dS, Y", strtotime($offerInfo->occupancyDate)) . " +1 month"));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('OccDatePlus2M')) {
		$returnValue = '';
		if (isset($offerInfo->occupancyDate)  &&  $offerInfo->occupancyDate > '0000-00-00') {
			$returnValue = date("F dS, Y", strtotime(date("F dS, Y", strtotime($offerInfo->occupancyDate)) . " +2 month"));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('OccDatePlus3M')) {
		$returnValue = '';
		if (isset($offerInfo->occupancyDate)  &&  $offerInfo->occupancyDate > '0000-00-00') {
			$returnValue = date("F dS, Y", strtotime(date("F dS, Y", strtotime($offerInfo->occupancyDate)) . " +3 month"));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('OccDatePlus4M')) {
		$returnValue = '';
		if (isset($offerInfo->occupancyDate)  &&  $offerInfo->occupancyDate > '0000-00-00') {
			$returnValue = date("F dS, Y", strtotime(date("F dS, Y", strtotime($offerInfo->occupancyDate)) . " +4 month"));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('OccDatePlus5M')) {
		$returnValue = '';
		if (isset($offerInfo->occupancyDate)  &&  $offerInfo->occupancyDate > '0000-00-00') {
			$returnValue = date("F dS, Y", strtotime(date("F dS, Y", strtotime($offerInfo->occupancyDate)) . " +5 month"));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('OccDatePlus5M')) {
		$returnValue = '';
		if (isset($offerInfo->occupancyDate)  &&  $offerInfo->occupancyDate > '0000-00-00') {
			$returnValue = date("F dS, Y", strtotime(date("F dS, Y", strtotime($offerInfo->occupancyDate)) . " +5 month"));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('OfferDateFull')) {
		$returnValue = '';
		if (isset($offerInfo->offerDate)  &&  $offerInfo->offerDate > '0000-00-00') {
			$returnValue =  date('F dS, Y',strtotime($offerInfo->offerDate));
		}
	}
	elseif (
	 	$textToReplace == strtoupper('offerPlus1Mth')) {
		if (isset($offerInfo->offerDate)  &&  $offerInfo->offerDate > '0000-00-00') {
			$newDate = getOfferDatePlusMonths($dbSingleUse, $offerInfo->lotNumber, $offerInfo->siteShortName,1);
			$returnValue =  date('F dS, Y',strtotime($newDate));

		}
		else {
			$returnValue =  '-';
		}
	}
	elseif (
	 	$textToReplace == strtoupper('offerPlus2Mth')) {
		if (isset($offerInfo->offerDate)  &&  $offerInfo->offerDate > '0000-00-00') {
			$newDate = getOfferDatePlusMonths($dbSingleUse, $offerInfo->lotNumber, $offerInfo->siteShortName,2);
			$returnValue =  date('F dS, Y',strtotime($newDate));

		}
		else {
			$returnValue =  '-';
		}
	}
	elseif (
	 	$textToReplace == strtoupper('offerPlus30Days')) {
		if (isset($offerInfo->offerDate)  &&  $offerInfo->offerDate > '0000-00-00') {
			$newDate = getOfferDatePlusDays($dbSingleUse, $offerInfo->lotNumber, $offerInfo->siteShortName,30);
			$returnValue =  date('F dS, Y',strtotime($newDate));
		}
		else {
			$returnValue =  '-';
		}
	}
	elseif (
	 	$textToReplace == strtoupper('offerPlus60Days')) {
		if (isset($offerInfo->offerDate)  &&  $offerInfo->offerDate > '0000-00-00') {
			$newDate = getOfferDatePlusDays($dbSingleUse, $offerInfo->lotNumber, $offerInfo->siteShortName,60);
			$returnValue =  date('F dS, Y',strtotime($newDate));
		}
		else {
			$returnValue =  '-';
		}
	}
	elseif (
	 	$textToReplace == strtoupper('PaintClean')) {
		if ($offerInfo->paintAndClean) {
			$returnValue =  'Yes';
		}
		else {
			$returnValue =  'No';
		}
	}
	elseif (
	 	$textToReplace == strtoupper('PhoneHome')) {
			
			$returnValue  = $offerInfo->homePhone;
	}
	elseif (
	 	$textToReplace == strtoupper('PhoneOther')) {
			
			$returnValue  = $offerInfo->otherPhone;
	}
	elseif (
	 	$textToReplace == strtoupper('PhoneWork')) {
			
			$returnValue  = $offerInfo->workPhone;
	}
	elseif (
	 	$textToReplace == strtoupper('PlanNum')) {
			
			$returnValue  = $offerInfo->planNumber;
	}
	elseif ($textToReplace == strtoupper('LastNames')) {
		$returnValue = '';
		$returnValue = trim($offerInfo->lastName1);
		if ($returnValue > '' && $offerInfo->lastName2 > '') {
			$returnValue = $returnValue.' and ';
		}
		$returnValue = $returnValue.$offerInfo->lastName2;
		if ($returnValue > '' && $offerInfo->lastName3 > '') {
			$returnValue = $returnValue.' and ';
		}
		$returnValue = $returnValue.$offerInfo->lastName3;
	}
	elseif ($textToReplace == strtoupper('PURCHASERNAMEFULL')) {
		$returnValue = '';
		$returnValue = trim($offerInfo->personPrefix1.' '.$offerInfo->firstName1.' '.$offerInfo->lastName1);
		if ($returnValue > '' && trim($offerInfo->personPrefix2.' '.$offerInfo->firstName2.' '.$offerInfo->lastName2) > '') {
			$returnValue = $returnValue.' and ';
		}
		$returnValue = $returnValue.trim($offerInfo->personPrefix2.' '.$offerInfo->firstName2.' '.$offerInfo->lastName2);
		if ($returnValue > '' && trim($offerInfo->personPrefix3.' '.$offerInfo->firstName3.' '.$offerInfo->lastName3) > '') {
			$returnValue = $returnValue.' and ';
		}
		$returnValue = $returnValue.trim($offerInfo->personPrefix3.' '.$offerInfo->firstName3.' '.$offerInfo->lastName3);
	}
	elseif ($textToReplace == strtoupper('PurchaserName1stPersonFull')) {
		$returnValue = '';
		$returnValue = trim($offerInfo->personPrefix1.' '.$offerInfo->firstName1.' '.$offerInfo->lastName1);
	}
	elseif ($textToReplace == strtoupper('PurchaserName2ndPersonFull')) {
		$returnValue = '';
		$returnValue = trim($offerInfo->personPrefix2.' '.$offerInfo->firstName2.' '.$offerInfo->lastName2);
	}
	elseif (
	 	$textToReplace == strtoupper('SiteName')) {
			
			$returnValue  = $offerInfo->siteName;
	}
	elseif (
	 	$textToReplace == strtoupper('StreetName')) {
			
			$returnValue  = $offerInfo->munStreetAddress;
	}
	elseif (
	 	$textToReplace == strtoupper('StreetSide')) {
			
			$returnValue  = $offerInfo->streetSide;
	}
	elseif ($textToReplace == strtoupper('WorkCredTot')) {
			$returnValue =   money_format('%.2n',getAmendmentPrintItem("offerWorkCredits",$offerInfo, $dbSingleUse, 99, "workCreditPrice" ));
	}
	elseif (
	 	substr($textToReplace,0,8) == strtoupper('WorkCred')) {
			if (substr($textToReplace,8,2) == strtoupper('PR')) {
				$ordinal = substr($textToReplace, 10,2);
				if (is_numeric($ordinal)) {
					// 0k - because the ordinal is two digits long
				}
				else {
					$ordinal = substr($ordinal,0,1);
				}
				$returnValue =  getAmendmentPrintItem("offerWorkCredits",$offerInfo, $dbSingleUse,$ordinal, "workCreditPrice" );
				if ($returnValue > '') {
				$returnValue = money_format('%.2n',floatval($returnValue));
				}
			}
			else {
				$ordinal = substr($textToReplace, 12,2);
				if (is_numeric($ordinal)) {
					// 0k - because the ordinal is two digits long
				}
				else {
					$ordinal = substr($ordinal,0,1);
				}
				$returnValue =  getAmendmentPrintItem("offerWorkCredits",$offerInfo, $dbSingleUse,$ordinal, "workCreditDescription" );
			}
	}
	//echo '<br>'.$textToReplace.'-'.$returnValue;

	if ($returnValue == '') {
	//	$returnValue = '-';
	}

	return $returnValue;
}
?>