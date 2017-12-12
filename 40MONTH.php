<?php
require_once('../tryconnection.php');

mysql_select_db($database_tryconnection, $tryconnection);


if ($_GET['month'] > 12){
$year = $_GET['year'] + 1;
$month = 1;
}
else if ($_GET['month'] < 1){
$year = $_GET['year'] - 1;
$month = 12;
}
else {
$year = $_GET['year'];
$month = $_GET['month'];
}

$day = $_GET['day'];

$first_of_month = mktime(0,0,0,$month,1,$year);
$first_of_month = strftime('%u',$first_of_month);  // gives the day of the week the month begins.

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);


$holidays = array() ;
$longname = array() ;
$shortname = array() ;
$nyhol = array() ;
$Get_all="SELECT HOLIDATE,HOLIDAY,NYDATE,SHORTHOL FROM HOLIDAY WHERE OBSERVED = 1" ;
$get_holidays=mysql_query($Get_all, $tryconnection) or die(mysql_error()) ;

$howmany = "SELECT FOUND_ROWS() AS IMAX" ;
$getmax = mysql_query($howmany, $tryconnection) or die(mysql_error()) ;
$max = mysql_fetch_assoc($getmax) ;

$lines = $max['IMAX'] - 1 ;
 
for ($i = 0; $i<=$lines; $i++) {
 
 $get_det = mysql_fetch_assoc($get_holidays) ;
 $holidays[$i] = $get_det['HOLIDATE'];
 $longname[$i] =  $get_det['HOLIDAY'] ;
 $shortname[$i] =  $get_det['SHORTHOL'] ;
 $nyhol[$i] = $get_det['NYDATE'] ;
 

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=2" />
<title><?php echo strtoupper(strftime('%B %Y', mktime(0,0,0,$month,1,$year))); ?></title>
<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>
<style type="text/css">
body {
background-color:#FFFFFF;
overflow:auto;
}


</style>

<script type="text/javascript">
function bodyonload(){
resizeTo(910,820) ;
}
function bodyonunload(){
sessionStorage.setItem('cancel',document.location);
}
</script>

</head>
<body onload="bodyonload()" onunload="bodyonunload()">


<?php if (intval($month/2) == $month/2) {$bg = '#000000' ;} else {$bg = 'DF013A' ;} ?>
<table border="0" cellspacing="0" cellpadding="0" width="800" style="position:absolute; top:0px; left:0px; >
  <tr class="Verdana13B" bgcolor="#CCCCCC">
    <th bgcolor="<?php echo $bg ;?>" class="Verdana14Bwhite" scope="col">Mon</th>
    <th bgcolor="<?php echo $bg ;?>" class="Verdana14Bwhite" scope="col">Tue</th>
    <th bgcolor="<?php echo $bg ;?>" class="Verdana14Bwhite" scope="col">Wed</th>
    <th bgcolor="<?php echo $bg ;?>" class="Verdana14Bwhite" scope="col">Thu</th>
    <th bgcolor="<?php echo $bg ;?>" class="Verdana14Bwhite" scope="col">Fri</th>
    <th bgcolor="<?php echo $bg ;?>" class="Verdana14Bwhite" scope="col">Sat</th>
    <th bgcolor="<?php echo $bg ;?>" class="Verdana14Bwhite" scope="col">Sun</th>
  </tr>
  <tr>  
      <td width="250" height="0"></td>
      <td width="250" height="0"></td>
      <td width="250" height="0"></td>
      <td width="250" height="0"></td>
      <td width="250" height="0"></td>
      <td width="250" height="0"></td>
      <td width="250" height="0"></td>
	</tr>  
<tr>  
      <td colspan="7">
<table border="1" cellspacing="0" cellpadding="0"  width="900" bordercolor="#00FF00">
  
  <?php 

//  if ($first_of_month == 0) {$first_of_month = 7;}
  for ($i=1; $i <= 6; $i++){
  
  		echo "<tr>";
	
			for ($j=1; $j <= 7; $j++) {;
                 $day_of_cal = ($i-1)*7 + $j ;
                 $day_of_month = $day_of_cal - $first_of_month +1 ;
                 // This next block of code only executes when there are valid days of the month
//			     if ( $day_of_month > 0 && $day_of_month <= $days_in_month) {
			     
// Check to see if it is a holiday This is workng

                    $itisnox = mktime(0,0,0,$month,($day_of_cal-$first_of_month +1),$year) ;
                    $itisnow = strftime('%Y-%m-%d',$itisnox) ;
                    $enjoy1 =  array_search($itisnow,$holidays) ;
                    if ($enjoy1 === FALSE) { 
                      $enjoy = '' ;
                    }
                    else {$enjoy = $longname[$enjoy1] ;
                    }
                	if ($enjoy == '') {
                     $enjoy1 = array_search($itisnow,$nyhol) ;
                     if ($enjoy1 !== FALSE) { 
                	  $enjoy = $longname[$enjoy1]  ;
                	 }
                	}
				//DAYS
				   echo '<td width="250" height="100" class="Verdana12Blue" align="left" valign="bottom"'; 
				
				//SUNDAYS This is working
				   if ($j==7 && $day_of_month > 0 && $day_of_month <= $days_in_month ){
				    echo ' bgcolor="#FFCCCC"';
				
				   }
				//CIVIC HOLIDAYS 
				   else if ($enjoy1 !== FALSE ){
				     echo ' bgcolor="#F3F781"';   //BAE3FF
				     $day_of_month = $day_of_month." ".$enjoy;
				   }
				   else {
				   
				     echo ' bgcolor="#FFFFFF"';
				   }
				  echo '>';		
				
				//if today's date
				   if ($day_of_month == date('j') && $month == date('n') && $year == date('Y')) {
				   echo '<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#FF00FF" frame="box" rules="none"  style="border:solid 2px #FF00FF">
						<tr>  
						  <td height="98" align="center">';
				   echo '<table width="99%" border="1" cellspacing="0" cellpadding="0" bordercolor="#FF00FF" frame="box" rules="none">
						<tr>  
						  <td height="94" valign="bottom">';
				   }
				
				   if ($day_of_month > 0 && $day_of_month <= $days_in_month ) {
				
				     $date_to_retrieve = $year.'-'.$month.'-'.$day_of_month;
				
				     $query_APPTDOCS = "SELECT * FROM APPTDOCS WHERE `DATEIS`='$date_to_retrieve' AND DUTY <> '00000000000000' ORDER BY SEQ";
				     $APPTDOCS = mysql_query($query_APPTDOCS, $tryconnection) or die(mysql_error());
//				     $row_APPTDOCS = mysql_fetch_assoc($APPTDOCS);
				     $totalRows_APPTDOCS = mysql_num_rows($APPTDOCS);			

/* 
APPOINTMENT FLAGS

1 am appt A
2 am surg S
3 pm apt   A
4 pm surg S
5 Emerg 1 E1
6 am Gv G
7 pm Gv G
8 Evening A
9 Even. Gv G
10 Emerg 2 E2
11 am Hosp doctor H
12 pm Hosp doctor H
13 am Dentistry D
14 pm Dentistry D
*/
				
				   echo '<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#E1E1E1" frame="box" rules="all"   id="'.$day_of_month.'" onmouseover="CursorToPointer(this.id)" onclick="document.location=\'DAY.php?year='.$year.'&month='.$month.'&day='.$day_of_month.'&inc=0'.'\'">';
				   while ($row_APPTDOCS = mysql_fetch_assoc($APPTDOCS)) {echo 
						'<tr class="Verdana9">  
						  <td height="9" width="20" align="center" class="Verdana9Red">';
				
					      if (substr($row_APPTDOCS['DUTY'],4,1) == '1'){echo "<span style='background-color:#D8D8D8'>E1</span>";}
					
					      echo  
						   '</td>
						    <td width="20" align="center" class="Verdana9Red">';
				
					      if (substr($row_APPTDOCS['DUTY'],9,1) == '1'){echo "<span style='background-color:#D8D8D8'>E2</span>";}
					    
					      if ($enjoy1 === FALSE) {
					      echo  
						   '</td><td width="30">'.$row_APPTDOCS['INITIALS'].'</td>
						   <td width="20" align="center">';
						   }
						   else if (substr($row_APPTDOCS['DUTY'],4,1) == '1' || substr($row_APPTDOCS['DUTY'],9,1) == '1') {
					        echo  
						    '</td><td width="30">'.$row_APPTDOCS['INITIALS'].'</td>
						    <td width="20" align="center">';
						    }
						   else {echo  
						   '</td><td width="30">&nbsp;</td>
						   <td width="20" align="center">';
						   }
					//AM  
					      if ($enjoy1 === FALSE ) {
					      if (substr($row_APPTDOCS['DUTY'],0,1) > '0'){echo "<span class='Verdana9Blue'> A </span>";}
					      else if (substr($row_APPTDOCS['DUTY'],1,1) == '1'){echo "<span class='Verdana9'> S </span>";}
					      else if (substr($row_APPTDOCS['DUTY'],10,1) > '0'){echo "<span class='Verdana9Red'> H </span>";}
					      else if (substr($row_APPTDOCS['DUTY'],12,1) == '1'){echo "<span class='Verdana9Pink'> D </span>";}
					      else if (substr($row_APPTDOCS['DUTY'],5,1) > '0'){echo "<span class='Verdana9Pink'> L </span>";}
	                      }
					      echo
					       '</td>
						    <td width="20" align="center">';
					//PM
					    if ($enjoy1 === FALSE ) {
					     if (substr($row_APPTDOCS['DUTY'],2,1) > '0'){echo "<span class='Verdana9Blue'> A </span>";}
					     else if (substr($row_APPTDOCS['DUTY'],3,1) == '1'){echo "<span class='Verdana9'> S </span>";}
					     else if (substr($row_APPTDOCS['DUTY'],11,1) > '0'){echo "<span class='Verdana9Red'> H </span>";}
					     else if (substr($row_APPTDOCS['DUTY'],13,1) == '1'){echo "<span class='Verdana9Pink'> D </span>";}
					     else if (substr($row_APPTDOCS['DUTY'],6,1) > '0'){echo "<span class='Verdana9Pink'> L </span>";}
					     }
					     echo
						'</td>
						  <td width="20" align="center">';
					//EVENING
					    if ($enjoy1 === FALSE ) {
					     if (substr($row_APPTDOCS['DUTY'],7,1) > '0'){echo "<span class='Verdana9Blue'> A </span>";}
					     else if (substr($row_APPTDOCS['DUTY'],8,1) > '0'){echo "<span class='Verdana9Pink'> L </span>";}
					     }
					     echo
						  '</td>
						  </tr>';
					  } //while ($row_APPTDOCS = mysql_fetch_assoc($APPTDOCS));
								
				     
	// This fills out the individual day with the doctors for that day.	
				
				     for ($a=1; $a <= 10-$totalRows_APPTDOCS; $a++){
				     echo 
						'<tr class="Verdana10">  
						  <td height="9"></td>
						  <td height="9"></td>
						  <td width="40"></td>
						  <td></td>
						  <td></td>
						  <td></td>
						</tr>';
			         }
						
				     echo	   '</table>';
				
				     echo $day_of_month;
				// The end of this loop was here....			
				
			//if today's date
			       if ($day_of_month == date('j') && $month == date('n') && $year == date('Y')) {
			          echo '</td></tr></table>';
			          echo '</td></tr></table>';
			       }
			
				
				   echo '</td>';

//				}//if ($day_of_month > 0 && $day_of_month <= $days_in_month ) 	
              } // end of blank before month starts loop
              

			} // end of day loop


  		echo "</tr>";
  
  } // end of week loop
  ?>
</table>

	</td>
  </tr>  
    <tr class="ButtonsTable">  
        <td colspan="7" align="center">
        	<a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image6','','../IMAGES/left_arrow_dark.JPG',1)" onclick="document.location='MONTH.php?year=<?php echo $year-1; ?>&month=<?php echo $month; ?>'">
            <img src="../IMAGES/left_arrow_light.JPG" alt="PREVIOUS" name="Image6" width="28" height="28" border="0" id="Image6" />
            </a>

          <input type="button" class="button" name="button" id="button" value="BACK" onclick="document.location='MONTH.php?year=<?php echo $year; ?>&month=<?php echo $month-1; ?>'"/>
          <input type="button" class="button" name="button" id="button" value="NEXT"  onclick="document.location='MONTH.php?year=<?php echo $year; ?>&month=<?php echo $month+1; ?>'"/>
          <input type="button" class="button" name="button" id="button" value="DAY" />
          <input type="button" class="button" name="button" id="button" value="PRINT" />
          <input type="button" class="button" name="button" id="button" value="ADMIN" onclick="window.open('ADMIN_DIRECTORY.php','_blank','width=413, height=413')"/>
          <input type="button" class="button" name="button" id="button" value="CANCEL" onclick="self.close()" />
            <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image7','','../IMAGES/right_arrow_dark.JPG',1)" style="margin-left:2px;" onclick="document.location='MONTH.php?year=<?php echo $year+1; ?>&month=<?php echo $month; ?>'">
            <img src="../IMAGES/right_arrow_light.JPG" alt="NEXT" name="Image7" width="28" height="28" border="0" id="Image7" />
            </a>
       </td>
    </tr>  
</table>


</body>
</html>
