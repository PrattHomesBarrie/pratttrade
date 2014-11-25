    <?php
require_once('check_session.php');
require_once('vars.php');
//if (isset($userName) ) {
	
 if ($printingFormat == "Yes") {
  }
  else
  {	 
	echo '  <div class="sidebar1">';	
	if ($validUser) {
//		echo 'logged in as:'.$userName.'<br>';
    	echo '<ul class="nav">';
//		echo '<li>Lots';
  //  	echo '<ul>';
	    echo '<li><a href="index.php?myAction=Lots">Lots - All</a></li>';
	//	echo '</ul></li>';
    	echo '<li><a href="index.php?myAction=Logout">Logout</a></li>';
	    echo '</ul>';
   	    echo '<p>&nbsp;</p>';
	}
	elseif ($myAction!='login'){
    	echo '<ul class="nav">';
      echo '<li><a href="index.php">Login</a></li>';
      echo '</ul>';
	}
    echo '<!-- end .sidebar1 --></div>';
  }
	 ?>
