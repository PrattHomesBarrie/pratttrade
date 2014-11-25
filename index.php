<?php require_once('includes/check_session.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/PrattTradeTemplate.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<?php require_once('includes/head_text.php'); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<?php require_once("includes/initialize_logic.php"); ?>
<title>Pratt for Trades</title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<link href="PrattTradeTwoColFixLt.css" rel="stylesheet" type="text/css" />
<link href="js/css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery-1.6.2.js"></script>
<script src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="includes/javascripts/prattScripts.js"></script>

</head>

<body>

<div class="container">
  <div class="header">
  <?php 
  if ($printingFormat == "Yes") {
  }
  else
  {
echo '  <table width = "100%"><tr><td width="20%"><a href="index.php?myAction=Lots"><img src="./images/pratt_construction.jpg"  /></a> </td><td width="80%"><h2>Pratt for Trades</h2></td></tr></table>  ';
  }
  ?>
    <!-- end .header --></div>
    <?
     //include ("includes/leftSideBar.php"); 
	?>
 <div class="content">	
<!-- InstanceBeginEditable name="EditRegion3" --><h1></h1><?php include("includes/index_logic.php"); ?><!-- InstanceEndEditable --><!-- end .content --></div>
  <div class="footer">
  <?php //echo $_SERVER["PHP_SELF"];
  //print_r($_SESSION);
  ?>  
    
    <!-- end .footer --></div>
  <!-- end .container --></div>
</body>
<!-- InstanceEnd --></html>
