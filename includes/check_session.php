<?php
session_start();
require_once("classes/misc_functions.php");
require_once("vars.php");
if ($debug == "Yes")
	{	
 echo "<br>Your PHPSESSID is:".  session_id(); 
 echo "<br>session userName = ".$_SESSION["userName"];
 echo "<br>session validUser = ".$_SESSION["validUser"];
	}
//for myAction --> the POST takes precedence
if (isset($_POST['myAction'])) {
	$myAction = $_POST['myAction'];
	$_SESSION["myAction"] = $myAction;
}
else
{
	$myAction = $_GET["myAction"];
}

if ( $myAction == "ColourChart" or $myAction == "APSDetails") {
	$printingFormat = "Yes";
}
else
{
	$printingFormat = "No";
}

//for myEditAction --> the POST takes precedence
if (isset($_POST['myEditAction'])) {
	$myEditAction = $_POST['myEditAction'];
	$_SESSION["myEditAction"] = $myEditAction;
}
else
{
	$myEditAction = $_GET["myEditAction"];
}


if (isset($_GET['siteShortName'])) {
	$siteShortName = $_GET['siteShortName'];
	$_SESSION["siteShortName"] = $siteShortName;
}
elseif (isset($_POST['siteShortName'])) {
	$siteShortName = $_POST['siteShortName'];
	$_SESSION["siteShortName"] = $siteShortName;
}
else
{
	$siteShortName = $_SESSION['siteShortName'];
}
if ($siteShortName ==  'All') {
	$siteShortName = '';
}
if (isset($_GET['lotNumber'])) {
	$lotNumber = $_GET['lotNumber'];
	$_SESSION["lotNumber"] = $lotNumber;
}
elseif (isset($_SESSION['lotNumber']))
{
	$lotNumber = $_SESSION['lotNumber'];
}
else
{
	$lotNumber = $_POST['lotNumber'];
}

if (isset($_GET['updateLotWatch'])) {
	$updatelotNumber = $_GET['updateLotWatch'];
	$_SESSION["updateLotWatch"] = $updateLotWatch;
}
else
{
	$updateLotWatch = $_POST['updateLotWatch'];
}

if (isset($_GET['watchLot'])) {
	$watchLot = $_GET['watchLot'];
	$_SESSION["watchLot"] = $watchLot;
}
else
{
	$watchLot = $_POST['watchLot'];
}


if (isset($_GET['updateLotClearingStatus'])) {
	$updateLotClearingStatus = $_GET['updateLotClearingStatus'];
	$_SESSION["updateLotClearingStatus"] = $updateLotClearingStatus;
}
else
{
	$updateLotClearingStatus = $_POST['updateLotClearingStatus'];
}

if (isset($_GET['statusCheckBox'])) {
	$statusCheckBox = $_GET['statusCheckBox'];
	$_SESSION["statusCheckBox"] = $statusCheckBox;
}
else
{
	$statusCheckBox = $_POST['statusCheckBox'];
}

if (isset($_POST['filterOfferStatusGroup'])) {
	$filterOfferStatusGroup = $_POST['filterOfferStatusGroup'];
	$_SESSION["filterOfferStatusGroup"] = $filterOfferStatusGroup;
}
else
{
	$filterOfferStatusGroup = 'All';
}

if (!isset($filterOfferStatusGroup)) {
	$filterOfferStatusGroup = 'With Offers';
}

if (isset($_POST['filterClosingStatusGroup'])) {
	$filterClosingStatusGroup = $_POST['filterClosingStatusGroup'];
	$_SESSION["filterClosingStatusGroup"] = $filterClosingStatusGroup;
}
else
{
	$filterClosingStatusGroup = 'All';
}

if (isset($_POST['filterOccupancyStatusGroup'])) {
	$filterOccupancyStatusGroup = $_POST['filterOccupancyStatusGroup'];
	$_SESSION["filterOccupancyStatusGroup"] = $filterOccupancyStatusGroup;
}
else
{
	$filterOccupancyStatusGroup = $_SESSION['filterOccupancyStatusGroup'];
}

if (isset($_POST['filterClearingGroup'])) {
	$filterClearingGroup = $_POST['filterClearingGroup'];
	$_SESSION["filterClearingGroup"] = $filterClearingGroup;
}
else
{
	$filterClearingGroup = 'All';
}

if (isset($_POST['lotSortList'])) {
	$lotSortList = $_POST['lotSortList'];
	$_SESSION["lotSortList"] = $lotSortList;
}
else
{
	$lotSortList = $_SESSION['lotSortList'];
}

if (isset($_GET['buildSequence'])) {
	$buildSequence = $_GET['buildSequence'];
	$_SESSION["buildSequence"] = $buildSequence;
}
else
if (isset($_POST['buildSequence'])) {
	$buildSequence = $_POST['buildSequence'];
	$_SESSION["buildSequence"] = $buildSequence;
}
else
{
	$buildSequence = $_SESSION['buildSequence'];
}
//	alertBox($buildSequence);



//$siteShortName = getGETPOST('siteShortName');
//$lotNumber = getGETPOST('lotNumber');
$watch=$_POST["watch"];
$loginUserName = strtolower($_POST["loginUserNamePratt"]);
//$loginUserName = "tradeuser";

$loginPassword = strtolower($_POST["loginPasswordPratt"]);
$validUser = $_SESSION["validUser"];
if(isset($_GET['myAction']))
{
if($_GET['myAction'] != 'Logout')
$validUser = true;
else $validUser = false;
}
$validUserCheck = $_SESSION["validUserCheck"];

//this gets us past a bug with net firms
if ($validUserCheck == "Yes") {
	$validUser = true;
}
$userName = strtolower($_SESSION["userName"]);



if ($debug == "Yes")
	{	
		echo "<br>Checking Session data";
		echo "<br>'$validUser value:".$validUser;
		echo '<br>getting validUser value to return:'.$_SESSION["validUser"];
		echo '<br>$loginUserName value:'.$loginUserName;
		echo '<br>$userName value:'.$userName;
		echo '<br>$siteShortName value:'.$siteShortName;
		echo '<br>$lotNumber value:'.$lotNumber;
		echo '<br>$watchLot value:'.$watchLot;
		echo '<br>$updateLotWatch value:'.$updateLotWatch;
		echo '<br>$updateLotClearingStatus value:'.$updateLotClearingStatus;
		echo '<br>$statusCheckBox value:'.$statusCheckBox;
		echo '<br>$filterOfferStatusGroup value:'.$filterOfferStatusGroup;
		echo '<br>$filterClosingStatusGroup value:'.$filterClosingStatusGroup;
		echo '<br>$filterOccupancyStatusGroup value:'.$filterOccupancyStatusGroup;
		echo '<br>$filterClearingGroup value:'.$filterClearingGroup;
		echo '<br>$lotSortList value:'.$lotSortList;
		
	}

//alertBox($filterOfferStatusGroup);

?>