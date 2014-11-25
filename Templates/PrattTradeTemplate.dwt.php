<?php require_once('includes/check_session.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once('includes/head_text.php'); ?>
<!-- TemplateBeginEditable name="doctitle" -->
<title>Untitled Document</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
<link href="PrattTradeTwoColFixLt.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="container">
  <div class="header">
  <?php 
  if ($printingFormat == "Yes") {
  }
  else
  {
echo '  <table width = "100%"><tr><td width="20%"><a href="index.php"><img src="./images/pratt_construction.jpg"  /></a> </td><td width="80%"><h2>Pratt for Trades</h2></td></tr></table>  ';
  }
  ?>
    <!-- end .header --></div>
    <?
     include ("includes/leftSideBar.php"); 
	?>
 <div class="content">	
<!-- TemplateBeginEditable name="EditRegion3" -->EditRegion3<!-- TemplateEndEditable --><!-- end .content --></div>
  <div class="footer">
  <?php //echo $_SERVER["PHP_SELF"];
  ?>  
    
    <!-- end .footer --></div>
  <!-- end .container --></div>
</body>
</html>
