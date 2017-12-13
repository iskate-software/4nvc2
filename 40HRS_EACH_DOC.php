<?php 
session_start();
require_once('../tryconnection.php');
mysql_select_db($database_tryconnection, $tryconnection);

if (!isset($_POST['save'])) {
// On the way into the page 

// This array contain the from [0] and to [1] dates, the opening and closing hours for 
// am [2] [3], pm [4] [5], evening [6] [7], and the duty field [8] for the doctor in 
// question for this day.
 
  $weekday = array() ;
  $eslots = array() ;

  $doctor = $_GET['doctor'] ;
  $day = $_GET['day'] ;
 
 // get the latest record which covers this date. The HRSID will ensure that if there are multiple entries, only the latest is chosen.
 
  $ind_hours = "SELECT HRSID, DOCTOR, SHORTDOC, INITIALS,  DATE_FORMAT(STARTDT,'%m/%d/%Y') AS STARTDT,  DATE_FORMAT(ENDDT,'%m/%d/%Y') AS ENDDT,  SEQUENCE, DUTY, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3,
                ES1ST, ES1SP, ES1BST, ES1BSP, ES2ST, ES2SP, ES2BST, ES2BSP, ES3ST,ES3SP, ES3BST, ES3BSP FROM HRSDOC 
                WHERE DOCTOR = '$doctor' AND DAYINWEEK = '$day' AND STARTDT <= DATE(NOW()) AND ENDDT >= DATE(NOW()) ORDER BY  HRSID DESC LIMIT 1  ";
  $GET_hrs = mysql_query($ind_hours, $tryconnection) or die(mysql_error()) ;
  $row_hours = mysqli_fetch_assoc($GET_hrs) ;
 
// Did the query find any data?

  if (array_key_exists('DOCTOR',$row_hours) ) {
  
  // then fill out the internal array which contains the most recent record on file.
    $found = 1 ;
    $hrsid      = $row_hours['HRSID'] ;
    $shortdoc   = $row_hours['SHORTDOC'] ;
    $initials   = $row_hours['INITIALS'] ;
    $sequence   = $row_hours['SEQUENCE'] ;
    $weekday[0] = $row_hours['STARTDT'] ;
    $weekday[1] = $row_hours['ENDDT'] ;
    $weekday[2] = $row_hours['OPEN1'] ;
    $weekday[3] = $row_hours['CLOSE1'] ;
    $weekday[4] = $row_hours['OPEN2'] ;
    $weekday[5] = $row_hours['CLOSE2'] ;
    $weekday[6] = $row_hours['OPEN3'] ;
    $weekday[7] = $row_hours['CLOSE3'] ;
    $weekday[8] = $row_hours['DUTY'] ;
    
    $eslots[0] = $row_hours['ES1ST'] ;
    $eslots[1] = $row_hours['ES1SP'] ;
    $eslots[2] = $row_hours['ES1BST'] ;
    $eslots[3] = $row_hours['ES1BSP'] ;
    $eslots[4] = $row_hours['ES2ST'] ;
    $eslots[5] = $row_hours['ES2SP'] ;
    $eslots[6] = $row_hours['ES2BST'] ;
    $eslots[7] = $row_hours['ES2BSP'] ;
    $eslots[8] = $row_hours['ES3ST'] ;
    $eslots[9] = $row_hours['ES3SP'] ;
    $eslots[10] = $row_hours['ES3BST'] ;
    $eslots[11] = $row_hours['ES3BSP'] ;
    
// These two variables needed for date comparison purposes when the record is updated/changed.

    $begincf = $row_hours['STARTDT'] ;
    $endcf   = $row_hours['ENDDT'] ;

// Opening and closing times
    
    $_POST['newstart'] = $weekday[0] ;
    $_POST['newend'] = $weekday[1] ;
    
    $_POST['open1h'] = substr($weekday[2],0,2) ;
    $_POST['open1m'] = substr($weekday[2],3,2) ;
    $_POST['open2h'] = substr($weekday[4],0,2) ;
    $_POST['open2m'] = substr($weekday[4],3,2) ;
    $_POST['open3h'] = substr($weekday[6],0,2) ;
    $_POST['open3m'] = substr($weekday[6],3,2) ;
    
    $_POST['close1h'] = substr($weekday[3],0,2) ;
    $_POST['close1m'] = substr($weekday[3],3,2) ;
    $_POST['close2h'] = substr($weekday[5],0,2) ;
    $_POST['close2m'] = substr($weekday[5],3,2) ;
    $_POST['close3h'] = substr($weekday[7],0,2) ;
    $_POST['close3m'] = substr($weekday[7],3,2) ;
    
    // eslots
    
    $_POST['opene1h'] = substr($eslots[0],0,2) ;
    $_POST['opene1m'] = substr($eslots[0],3,2) ;
    $_POST['closee1h'] = substr($eslots[1],0,2) ;
    $_POST['closee1m'] = substr($eslots[1],3,2) ;
    $_POST['opene2h'] = substr($eslots[2],0,2) ;
    $_POST['opene2m'] = substr($eslots[2],3,2) ;
    $_POST['closee2h'] = substr($eslots[3],0,2) ;
    $_POST['closee2m'] = substr($eslots[3],3,2) ;
    
    $_POST['opene3h'] = substr($eslots[4],0,2) ;
    $_POST['opene3m'] = substr($eslots[4],3,2) ;
    $_POST['closee3h'] = substr($eslots[5],0,2) ;
    $_POST['closee3m'] = substr($eslots[5],3,2) ;
    $_POST['opene4h'] = substr($eslots[6],0,2) ;
    $_POST['opene4m'] = substr($eslots[6],3,2) ;
    $_POST['closee4h'] = substr($eslots[7],0,2) ;
    $_POST['closee4m'] = substr($eslots[7],3,2) ;
    
    $_POST['opene5h'] = substr($eslots[8],0,2) ;
    $_POST['opene5m'] = substr($eslots[8],3,2) ;
    $_POST['closee5h'] = substr($eslots[9],0,2) ;
    $_POST['closee5m'] = substr($eslots[9],3,2) ;
    $_POST['opene6h'] = substr($eslots[10],0,2) ;
    $_POST['opene6m'] = substr($eslots[10],3,2) ;
    $_POST['closee6h'] = substr($eslots[11],0,2) ;
    $_POST['closee6m'] = substr($eslots[11],3,2) ;
     
    // Decode the duty flags.
    
    $A1 = substr($weekday[8],0,1) ;
    $S1 = substr($weekday[8],1,1) ;
    $A2 = substr($weekday[8],2,1) ;
    $S2 = substr($weekday[8],3,1) ;
    $E1 = substr($weekday[8],4,1) ;
    $L1 = substr($weekday[8],5,1) ;
    $L2 = substr($weekday[8],6,1) ;
    $A3 = substr($weekday[8],7,1) ;
    $L3 = substr($weekday[8],8,1) ;
    $E2 = substr($weekday[8],9,1) ;
    $H1 = substr($weekday[8],10,1) ;
    $H2 = substr($weekday[8],11,1) ;
    $D1 = substr($weekday[8],12,1) ;
    $D2 = substr($weekday[8],13,1) ;
    // for New Hamburg only. If any emergency checkbox is filled, default the first to on and turn the others off.
    if ($E1 == 1 || $E2 == 1 || $E3 == 1)  {$E1 = 1; $E2 = 0 ; $E3 = 0 ;}
    // The next two will never be used. Default to zero.
    $D3 = 0 ;
    $H3 = 0 ;
    
// Finally, the room variables. The $Ax, $Lx and $Hx variables, if non zero, mean that 
// the doctor is either doing appts, large animals or is a Hospital doctor. The actual 
// value of these variables indicates the column in which they will appear in the appt
// scheduler. Initialize these to zero, then decode from the duty field. Room1 is in fact
// the morning column, Room2 is the Afternoon column, and Room3 is the evening Column.

   $_POST['room1'] = 0 ;
   $_POST['room2'] = 0 ;
   $_POST['room3'] = 0 ;

   if ($A1 != 0 ) {$_POST['room1'] = $A1 ;}
   if ($L1 != 0 ) {$_POST['room1'] = $L1 ;}
   if ($H1 != 0 ) {$_POST['room1'] = $H1 ;}
   if ($A2 != 0 ) {$_POST['room2'] = $A2 ;}
   if ($L2 != 0 ) {$_POST['room2'] = $L2 ;}
   if ($H2 != 0 ) {$_POST['room2'] = $H2 ;}
   if ($A3 != 0 ) {$_POST['room3'] = $A3 ;}
   if ($L3 != 0 ) {$_POST['room3'] = $L3 ;}
   if ($H3 != 0 ) {$_POST['room3'] = $H3 ;}
   
    }  // if (array_key_exists('HDOCTOR',$row_hours) in the HRSDOC file 
     
  else {
  
// Now we deal with the no existing scheduling data this doctor, this day situation. Look in the CRITDATA file
  
         $found = 0 ;
         $ind_dtls = "SELECT HDOCTOR, HSHORTDOC, HDOCINIT FROM CRITDATA WHERE HDOCTOR = '$doctor' AND SCHEDULE = 1 LIMIT 1 " ;
         $GET_dtls = mysql_query($ind_dtls, $tryconnection) or die(mysql_error()) ;
         $row_dtls = mysqli_fetch_assoc($GET_dtls) ;
         // Stage 3
         
         if (array_key_exists('HDOCTOR',$row_dtls)) {
          // The doctor does not have a schedule yet, but is at least in the Doctor file. Pick up the priority (sequence) from there.
            $get_query="SELECT PRIORITY FROM DOCTOR WHERE DOCTOR = '$doctor' LIMIT 1" ;
            $Query_sequence = mysql_query($get_query, $tryconnection) or die(mysql_error()) ;
            $row_priority = mysqli_fetch_assoc($Query_sequence) ;
            $priority = $row_priority['PRIORITY'] ; 
            
   //       $doctor = $row_dtls['DOCTOR'] ;  we already have this from the opening GETs
   
            $shortdoc = $row_dtls['HSHORTDOC'] ;
            $initials = $row_dtls['HDOCINIT'] ;
            $sequence = $priority ;
            $weekday[0] = '00/00/0000' ;
            $weekday[1] = '00/00/0000' ;
            $weekday[2] = '00:00' ;
            $weekday[3] = '00:00' ;
            $weekday[4] = '00:00' ;
            $weekday[5] = '00:00' ;
            $weekday[6] = '00:00' ;
            $weekday[7] = '00:00' ;
            $weekday[8] = '00000000000000' ;
                
            $eslots[0] = '00:00' ;
            $eslots[1] = '00:00' ;
            $eslots[2] = '00:00' ;
            $eslots[3] = '00:00' ;
            $eslots[4] = '00:00' ;
            $eslots[5] = '00:00' ;
            $eslots[6] = '00:00' ;
            $eslots[7] = '00:00' ;
            $eslots[8] = '00:00' ;
            $eslots[9] = '00:00' ;
            $eslots[10] = '00:00' ;
            $eslots[11] = '00:00' ;
            
            $A1 = 0 ;
            $S1 = 0 ;
            $D1 = 0 ;
            $L1 = 0 ;
            $E1 = 0 ;
            $H1 = 0 ;
            $A2 = 0 ;
            $S2 = 0 ;
            $D2 = 0 ;
            $L2 = 0 ;
            $E2 = 0 ;
            $H2 = 0 ;
            $A3 = 0 ;
            $S3 = 0 ;
            $D3 = 0 ;
            $L3 = 0 ;
            $E3 = 0 ;
            $H3 = 0 ;
            
            $_POST['room1'] = 0 ;
            $_POST['room2'] = 0 ;
            $_POST['room3'] = 0 ;
            
            $begincf = 0 ;
            $endcf = 0 ;
         }  // (array_key_exists('HDOCTOR',$row_dtls))  in Critdata 
         /*
// Hard to imagine how we could have come this far without any data, but still.....         
         else {
             echo "<script type='text/javascript'>" ;
             echo "alert('Doctor is not in the Doctor Hospital File. Please enter name, shortname and initials there, assign a sequence, flag as being in the scheduler, then try againN Thank you.')" ;
             echo "window.open('../UTILITIES/DOCTOR_HOSPITAL/DOCTOR_HOSPITAL_FILE.php',_self)" ;
             echo "</script>"; 
            
         } 
         */
    } // Whether found or not...
    
 // now turn these all into session variables in case they need to be passed on to the write routine.
 
 $_SESSION['found'] = $found ;
 $_SESSION['weekday'] = $weekday ;
 
 $_SESSION['day'] = $day ;
 $_SESSION['doctor'] = $doctor ;
 $_SESSION['shortdoc'] = $shortdoc ;
 $_SESSION['initials'] = $initials ;
 $_SESSION['sequence'] = $sequence ;
 $_SESSION['hrsid'] = $hrsid ;
 
 $_SESSION['room1'] = $_POST['room1'] ;
 $_SESSION['room2'] = $_POST['room2'] ;
 $_SESSION['room3'] = $_POST['room3'] ;
 
 $_SESSION['A1'] = $A1 ;
 $_SESSION['S1'] = $S1 ;
 $_SESSION['A2'] = $A2 ;
 $_SESSION['S2'] = $S2 ;
 $_SESSION['E1'] = $E1 ;
 $_SESSION['L1'] = $L1 ;
 $_SESSION['L2'] = $L2 ;
 $_SESSION['A3'] = $A3 ;
 $_SESSION['L3'] = $L3 ;
 $_SESSION['E2'] = $E2 ;
 $_SESSION['H1'] = $H1 ;
 $_SESSION['H2'] = $H2 ;
 $_SESSION['D1'] = $D1 ;
 $_SESSION['D2'] = $D2 ;
 
 $_SESSION['begincf'] = $begincf ;
 $_SESSION['endcf'] = $endcf ;


$closewin = '' ;

} // if (!isset($_POST['save']))  ie on first pass through.
/* else { // this is after getting the data. Update the Session variables which have been changed.



 $closewin='self.close();';

}
*/
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Hours each doctor</title>

<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>

<script type="text/javascript" src="../ASSETS/navigation.js"></script>

<script type="text/javascript">
function bodyonload(){
<?php echo $closewin; ?>

}

function bodyonunload() {

 }
 </script>
</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion4" -->
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;" >
<tr><td id="ds_calclass"></td></tr>
</table>
<script type="text/javascript" src="../ASSETS/calendar.js"></script>
<!-- InstanceEndEditable -->

<!-- InstanceBeginEditable name="HOME" -->
<div id="LogoHead" onclick="window.open('/'+localStorage.xdatabase+'/INDEX.php','_self');" onmouseover="CursorToPointer(this.id)" title="Home">DVM</div>
<!-- InstanceEndEditable -->

<div id="MenuBar">

	<ul id="navlist">
                
<!--FILE-->                
                
		<li><a href="#" id="current">File</a> 
			<ul id="subnavlist">
                <li><a href="#"><span class="">About DV Manager</span></a></li>
                <li><a onclick="utilities();">Utilities</a></li>
			</ul>
		</li>
                
<!--INVOICE-->                
                
		<li><a href="#" id="current">Invoice</a> 
			<ul id="subnavlist">
                <li><a href="#" onclick="window.open('','_self'/'+localStorage.xdatabase+'/INVOICE/CASUAL_SALE_INVOICING/STAFF.php?refID=SCI)"><span class="">Casual Sale Invoicing</span></a></li>
                <li><!-- InstanceBeginEditable name="reg_nav" --><a href="#" onclick="nav0();">Regular Invoicing</a><!-- InstanceEndEditable --></li>
                <li><a href="#" onclick="nav11();">Estimate</a></li>
                <li><a href="#" onclick=""><span class="">Barn/Group Invoicing</span></a></li>
                <li><a href="#" onclick="suminvoices()"><span class="">Summary Invoices</span></a></li>
                <li><a href="#" onclick="cashreceipts()"><span class="">Cash Receipts</span></a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Cancel Invoices</span></a></li>
                <li><a href="#" onclick="window.open('/'+localStorage.xdatabase+'/INVOICE/COMMENTS/COMMENTS_LIST.php?path=DIRECTORY','_blank','width=733,height=553,toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no')">Comments</a></li>
                <li><a href="#" onclick="tffdirectory()">Treatment and Fee File</a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Worksheet File</span></a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Procedure Invoicing File</span></a></li>
                <li><a href="#" onclick="invreports();"><span class="">Invoicing Reports</span></a></li>
			</ul>
		</li>
                
<!--RECEPTION-->                
                
		<li><a href="#" id="current">Reception</a> 
			<ul id="subnavlist">
                <li><a href="#" onclick="window.open('','_self')"><span class="">Appointment Scheduling</span></a></li>
                <li><a href="#" onclick="reception();">Patient Registration</a></li>
                <li><a href="#" onclick="window.open('/'+localStorage.xdatabase+'/RECEPTION/USING_REG_FILE.php','_blank','width=550,height=535')">Using Reception File</a></li>
                <li><a href="#" onclick="nav2();"><span class="hidden"></span>Examination Sheets</a></li>
                <li><a href="#" onclick="gexamsheets()"><span class="">Generic Examination Sheets</span></a></li>
                <li><a href="#" onclick="nav3();">Duty Log</a></li>
                <li><a href="#" onclick="staffsiso()">Staff Sign In &amp; Out</a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">End of Day Accounting Reports</span></a></li>
                    </ul>
                </li>
                
<!--PATIENT-->                
                
                <li><a href="#" id="current">Patient</a> 
			<ul id="subnavlist">
                <li><a href="#" onclick="nav4();">Processing Menu</a> </li>
                <li><a href="#" onclick="nav5();">Review Patient Medical History</a></li>
                <li><a href="#" onclick="nav6();">Enter New Medical History</a></li>
                <li><a href="#" onclick="nav7();">Enter Patient Lab Results</a></li>
                <li><a href="#" onclick=""window.open('/'+localStorage.xdatabase+'/CLIENT/CLIENT_SEARCH_SCREEN.php?refID=ENTER SURG. TEMPLATES','_self')><span class="">Enter Surgical Templates</span></a></li>
                <li><a href="#" onclick="window.open('/'+localStorage.xdatabase+'/CLIENT/CLIENT_SEARCH_SCREEN.php?refID=CREATE NEW CLIENT','_self','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no');">Create New Client</a></li>
                <li><a href="#" onclick="movepatient();">Move Patient to a New Client</a></li>
                <li><a href="#" onclick="searchpatient()">Rabies Tags</a></li>
                <li><a href="#" onclick="searchpatient()">Tattoo Numbers</a></li>
                <li><a href="#" onclick="nav8();"><span class="">Certificates</span></a></li>
                <li><a href="#" onclick="nav9();"><span class="">Clinical Logs</span></a></li>
                <li><a href="#" onclick="nav10();"><span class="">Patient Categorization</span></a></li>
                <li><a href="#" onclick="">Laboratory Templates</a></li>
                <li><a href="#" onclick="nav1();"><span class="">Quick Weight</span></a></li>
<!--                <li><a href="#" onclick="window.open('','_self')"><span class="">All Treatments Due</span></a></li>
-->			</ul>
		</li>
        
<!--ACCOUNTING-->        
		
        <li><a href="#" id="current">Accounting</a> 
			<ul id="subnavlist">
                <li><a href="#" onclick=""accreports()>Accounting Reports</a></li>
                <li><a href="#" onclick="inventorydir();" id="inventory" name="inventory">Inventory</a></li>
                <li><a href="#" onclick="" id="busstatreport" name="busstatreport"><span class="">Business Status Report</span></a></li>
                <li><a href="#" onclick="" id="hospstatistics" name="hospstatistics"><span class="">Hospital Statistics</span></a></li>
                <li><a href="#" onclick="" id="monthend" name="monthend"><span class="">Month End Closing</span></a></li>
			</ul>
		</li>
        
<!--MAILING-->        
		
        <li><a href="#" id="current">Mailing</a> 
			<ul id="subnavlist">
                <li><a href="#" onclick="window.open('','_self')" ><span class="">Recalls and Searches</span></a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Handouts</span></a></li>
                <li><a href="#" onclick="window.open('','_self')MAILING/MAILING_LOG/MAILING_LOG.php?refID=">Mailing Log</a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Vaccine Efficiency Report</span></a></li>
                <li><a href="#" onclick="window.open('/'+localStorage.xdatabase+'/MAILING/REFERRALS/REFERRALS_SEARCH_SCREEN.php?refID=1','_blank','width=567,height=473')">Referring Clinics and Doctors</a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Referral Adjustments</span></a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Labels</span></a></li>
			</ul>
		</li>
	</ul>
</div>
<div id="inuse" title="File in memory"> <!-- InstanceBeginEditable name="fileinuse" --> 
     <?php  if (empty($_SESSION['fileused'])) {
    echo"&nbsp;"; } 
    else {
    echo substr($_SESSION['fileused'],0,25);
    }  ?>
<!-- InstanceEndEditable --></div>


<div id="WindowBody">
     <!-- InstanceBeginEditable name="DVMBasicTemplate" -->
  <form action="DOC_HOURS_WRITE.php" name="hours" id="hours" method="post" >
  <div align="center" class="Verdana14" id="whichdoctor" title="whichdoctor">
  <h1 align="center">DOCTOR SCHEDULING BY DAY</h1>
  <h2 align="center" class="Verdana14Blue">Doctor</h2>
  <table width="310" border="1" cellspacing="0" cellpadding="0">
  
  <tr>
    <td align="center" class="Verdana14"><strong><?php echo $doctor ;?></strong></td>
  </tr>
 </table>
</div>
<div align="center" class="Verdana11Blue" id="day" title="day">
<h3 align="center">Current Day</h3>
<table width="210" border="1" cellspacing="0" cellpadding="0">

  <tr>
    <td align="center" class="Verdana14"><strong>
    <?php $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday',);
     echo $days[$day-1] ;?>
     </strong></td>
  </tr>
</table>
<table width="210" border:1px solid green; cellspacing="0" cellpadding="0">
<tr>
<td>&nbsp;</td>
</tr>
</table>
</div>
</table>
<div align="center" class="Arial10" id="working" title="workinghours">
<table width="90%" border:1px solid green; cellspacing="0" cellpadding="0">
  <tr>
  <td colspan="10">&nbsp;</td>
  </tr>
  <caption align="top" height="40" class="Verdana14Blue">
    Working hours and Duties
  </caption>
  <tr>
  <td colspan="12">&nbsp;</td>
  </tr>
  <tr>
    <td width="7%">&nbsp;Time</td>
    <td width="10%" align="Right">Start</td>
    <td width="9%" align="center">&nbsp;</td>
    <td width="9%" align="right">&nbsp;</td>
    <td width="9%" align="left">End</td>
    <td width="8%" align="center">&nbsp;Col.</td>
    <td width="8%" align="center" class="Verdana11Purple">&nbsp;Apt.</td>
    <td width="8%" align="center" class="Verdana11Purple">&nbsp;Surg</td>
    <td width="8%" align="center" class="Verdana11Purple">&nbsp;Dent</td>
    <td width="8%" align="center" class="Verdana11Purple">&nbsp;L.A.</td>
    <td width="8%" align="center" class="Verdana11BRed">&nbsp;Emerg</td>
    <td width="8%" align="center" class="Verdana11Green">&nbsp;Hosp.</td>
  </tr>
  <tr class="rowhighlightP" >
    <td class="Verdana11Purple">&nbsp;AM</td>
    <td class="Labels" align="right"><select name="open1h">
              <option value="00">00:</option>
              <option value="08" <?php if ($_POST['open1h'] == '08') {echo 'selected="selected"';}?>>08:</option>
              <option value="09" <?php if ($_POST['open1h'] == '09') {echo 'selected="selected"';}?>>09:</option>
              <option value="10" <?php if ($_POST['open1h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['open1h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['open1h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['open1h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['open1h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
            </select></td><td class="Labels" align="left"><select name="open1m">
              <option value="00" <?php if ($_POST['open1m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['open1m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['open1m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['open1m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['open1m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['open1m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['open1m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['open1m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td> 
    <td class="Labels" align="right"><select name="close1h">
              <option value="00">00:</option>
              <option value="10" <?php if ($_POST['close1h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['close1h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['close1h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['close1h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['close1h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['close1h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['close1h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['close1h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
            </select></td><td class="Labels" align="left"><select name="close1m">
              <option value="00" <?php if ($_POST['close1m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['close1m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['close1m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['close1m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['close1m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['close1m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['close1m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['close1m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td> 
            <td class="Labels" align="center"><select name="room1">
              <option value="0"<?php if ($_POST['room1'] == '0') {echo 'selected="selected"';}?>>0</option>
              <option value="1"<?php if ($_POST['room1'] == '1') {echo 'selected="selected"';}?>>1</option>
              <option value="2"<?php if ($_POST['room1'] == '2') {echo 'selected="selected"';}?>>2</option>
              <option value="3"<?php if ($_POST['room1'] == '3') {echo 'selected="selected"';}?>>3</option>
              <option value="4"<?php if ($_POST['room1'] == '4') {echo 'selected="selected"';}?>>4</option>
              <option value="5"<?php if ($_POST['room1'] == '5') {echo 'selected="selected"';}?>>5</option>
            </select></td>
    <td align="center" class="Verdana11Purple"><input type="checkbox" name="A1" id="A1" <?php if ($A1 != 0) {echo "checked" ;}?> value="1"/></td>
    <td align="center" class="Verdana11Purple"><input type="checkbox" name="S1" id="S1" <?php if ($S1 != 0) {echo "checked" ;}?> value="1"/></td>
    <td align="center" class="Verdana11Purple"><input type="checkbox" name="D1" id="D1" <?php if ($D1 != 0) {echo "checked" ;}?> value="1"/></td>
    <td align="center" class="Verdana11Purple"><input type="checkbox" name="L1" id="L1" <?php if ($L1 != 0) {echo "checked" ;}?> value="1"/</td>
    <td align="center" class="Verdana11BRed"><input type="checkbox" name="E1" id="E1" <?php if ($E1 != 0) {echo "checked" ;}?>  value="1"/></td>
    <td align="center" class="Verdana11Green"><input type="checkbox" name="H1" id="H1" <?php if ($H1 != 0) {echo "checked" ;}?>  value="1"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="rowhighlightB">
    <td class="Verdana11Blue">&nbsp;PM</td>
    <td class="Labels" align="right"><select name="open2h">
              <option value="00">00:</option>
              <option value="10" <?php if ($_POST['open2h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['open2h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['open2h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['open2h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['open2h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['open2h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['open2h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['open2h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
            </select></td>
            <td class="Labels" align="left"><select name="open2m">
              <option value="00" <?php if ($_POST['open2m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['open2m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['open2m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['open2m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['open2m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['open2m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['open2m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['open2m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
    <td class="Labels" align="right"><select name="close2h">
              <option value="00" <?php if ($_POST['close2h'] == '00') {echo 'selected="selected"';}?>>00:</option>
              <option value="12" <?php if ($_POST['close2h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['close2h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['close2h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['close2h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['close2h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['close2h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['close2h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['close2h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
            </select></td>
    <td class="Labels" align="left"><select name="close2m">
              <option value="00" <?php if ($_POST['close2m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['close2m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['close2m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['close2m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['close2m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['close2m'] == '40') {echo 'selected="selected"';}?>>40</option>>
              <option value="45" <?php if ($_POST['close2m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['close2m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td> 
    <td class="Labels" align="center"><select name="room2">
              <option value="0"<?php if ($_POST['room2'] == '0') {echo 'selected="selected"';}?>>0</option>
              <option value="1"<?php if ($_POST['room2'] == '1') {echo 'selected="selected"';}?>>1</option>
              <option value="2"<?php if ($_POST['room2'] == '2') {echo 'selected="selected"';}?>>2</option>
              <option value="3"<?php if ($_POST['room2'] == '3') {echo 'selected="selected"';}?>>3</option>
              <option value="4"<?php if ($_POST['room2'] == '4') {echo 'selected="selected"';}?>>4</option>
              <option value="5"<?php if ($_POST['room2'] == '5') {echo 'selected="selected"';}?>>5</option>
            </select></td>
    <td align="center" class="Verdana11Blue"><input type="checkbox" name="A2" id="A2" <?php if ($A2 != 0) {echo "checked" ;}?>  value="1" /></td>
    <td align="center" class="Verdana11Blue"><input type="checkbox" name="S2" id="S2" <?php if ($S2 != 0) {echo "checked" ;}?>  value="1" /></td>
    <td align="center" class="Verdana11Blue"><input type="checkbox" name="D2" id="D2" <?php if ($D2 != 0) {echo "checked" ;}?>  value="1" /></td>
    <td align="center" class="Verdana11Blue"><input type="checkbox" name="L2" id="L2" <?php if ($L2 != 0) {echo "checked" ;}?>  value="1" /></td>
    <td align="center" class="Verdana11BRed"><input type="checkbox" name="E2" id="E2" <?php if ($E2 != 0) {echo "checked" ;}?>  value="1" /></td>
    <td align="center" class="Verdana11Green"><input type="checkbox" name="H2" id="H2"<?php if ($H2 != 0) {echo "checked" ;}?>  value="1" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="rowhighlightG">
    <td class="Verdana11">&nbsp;Eve</td>
    <td class="Labels" align="right"><select name="open3h">
              <option value="00">00:</option>
              <option value="14" <?php if ($_POST['open3h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['open3h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['open3h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['open3h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['open3h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['open3h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
            </select></td>
    <td class="Labels" align="left"><select name="open3m">
              <option value="00" <?php if ($_POST['open3m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['open3m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['open3m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['open3m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['open3m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['open3m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['open3m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['open3m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
    <td class="Labels" align="right"><select name="close3h"> 
              <option value="00" <?php if ($_POST['close3h'] == '00') {echo 'selected="selected"';}?>>00:</option>
              <option value="14" <?php if ($_POST['close3h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['close3h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['close3h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['close3h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['close3h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['close3h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
            </select></td><td class="Labels" align="left"><select name="close3m">
              <option value="00" <?php if ($_POST['close3m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['close3m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['close3m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['close3m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['close3m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['close3m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['close3m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['close3m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td> 
    <td class="Labels" align="center"><select name="room3">
              <option value="0"<?php if ($_POST['room3'] == '0') {echo 'selected="selected"';}?>>0</option>
              <option value="1"<?php if ($_POST['room3'] == '1') {echo 'selected="selected"';}?>>1</option>
              <option value="2"<?php if ($_POST['room3'] == '2') {echo 'selected="selected"';}?>>2</option>
              <option value="3"<?php if ($_POST['room3'] == '3') {echo 'selected="selected"';}?>>3</option>
              <option value="4"<?php if ($_POST['room3'] == '4') {echo 'selected="selected"';}?>>4</option>
              <option value="5"<?php if ($_POST['room3'] == '5') {echo 'selected="selected"';}?>>5</option>
            </select></td>
    <td align="center" class="Verdana11"><input type="checkbox" name="A3" id="A3" <?php if ($A3 != 0) {echo "checked" ;}?>  value="1" /></td>
    <td align="center" class="Verdana11"><input type="checkbox" name="S3" id="S3" <?php if ($S3 != 0) {echo "checked" ;}?>  value="1" /></td>
    <td align="center" class="Verdana11"><input type="checkbox" name="D3" id="D3" <?php if ($D3 != 0) {echo "checked" ;}?>  value="1" /></td>
    <td align="center" class="Verdana11"><input type="checkbox" name="L3" id="L3" <?php if ($L3 != 0) {echo "checked" ;}?>  value="1" /></td>
    <td align="center" class="Verdana11BRed"><input type="checkbox" name="E3" id="E3" <?php if ($E3 != 0) {echo "checked" ;}?> value="1" /></td>
    <td align="center" class="Verdana11Green"><input type="checkbox" name="H3" id="H3"<?php if ($H3 != 0) {echo "checked" ;}?> value="1" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
<div align="center" class="Verdana11" id="daterange">
<table width="90%" border:1px solid green; cellspacing="0" cellpadding="0">
  <caption class="Verdana14Blue">E-slots for the above duties</caption> 
  <tr> 
  <td>&nbsp;</td>
    <td colspan="2" class="Verdana11" align="center" >From</td>
    <td colspan="2" class="Verdana11" align="center" >To</td>
    <td colspan="2" class="Verdana11" align="center" >From</td>
    <td colspan="2" class="Verdana11" align="center" >To</td>
  <td>&nbsp;</td>
  </tr>
   <tr class="rowhighlightP" >
    <td class="Verdana11Purple">&nbsp;AM</td>
    <td class="Labels" align="right"><select name="opene1h">
              <option value="00">00:</option>
              <option value="08" <?php if ($_POST['opene1h'] == '08') {echo 'selected="selected"';}?>>08:</option>
              <option value="09" <?php if ($_POST['opene1h'] == '09') {echo 'selected="selected"';}?>>09:</option>
              <option value="10" <?php if ($_POST['opene1h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['opene1h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['opene1h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['opene1h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['opene1h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
            </select></td>
            <td class="Labels" align="left"><select name="opene1m">
              <option value="00" <?php if ($_POST['opene1m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['opene1m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['opene1m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['opene1m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['opene1m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['opene1m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['opene1m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['opene1m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
            <td class="Labels" align="right"><select name="closee1h">
              <option value="00">00:</option>
              <option value="08" <?php if ($_POST['closee1h'] == '08') {echo 'selected="selected"';}?>> 8:</option>
              <option value="09" <?php if ($_POST['closee1h'] == '09') {echo 'selected="selected"';}?>> 9:</option>
              <option value="10" <?php if ($_POST['closee1h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['closee1h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['closee1h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['closee1h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['closee1h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['closee1h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
            </select></td>
            <td class="Labels" align="left"><select name="closee1m">
              <option value="00" <?php if ($_POST['closee1m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['closee1m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['closee1m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['closee1m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['closee1m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['closee1m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['closee1m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['closee1m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td> 
<td class="Labels" align="right"><select name="opene2h">
              <option value="00">00:</option>
              <option value="08" <?php if ($_POST['opene2h'] == '08') {echo 'selected="selected"';}?>>08:</option>
              <option value="09" <?php if ($_POST['opene2h'] == '09') {echo 'selected="selected"';}?>>09:</option>
              <option value="10" <?php if ($_POST['opene2h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['opene2h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['opene2h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['opene2h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['opene2h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
            </select></td>
            <td class="Labels" align="left"><select name="opene2m">
              <option value="00" <?php if ($_POST['opene2m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['opene2m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['opene2m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['opene2m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['opene2m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['opene2m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['opene2m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['opene2m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
            <td class="Labels" align="right"><select name="closee2h">
              <option value="00">00:</option>
              <option value="08" <?php if ($_POST['closee2h'] == '08') {echo 'selected="selected"';}?>> 8:</option>
              <option value="09" <?php if ($_POST['closee2h'] == '09') {echo 'selected="selected"';}?>> 9:</option>
              <option value="10" <?php if ($_POST['closee2h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['closee2h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['closee2h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['closee2h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['closee2h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['closee2h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
            </select></td>
            <td class="Labels" align="left"><select name="closee2m">
              <option value="00" <?php if ($_POST['closee2m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['closee2m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['closee2m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['closee2m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['closee2m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['closee2m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['closee2m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['closee2m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
  </tr>
  <tr class="rowhighlightB">
    <td class="Verdana11Blue">&nbsp;PM</td>
    <td class="Labels" align="right"><select name="opene3h">
              <option value="00">00:</option>
              <option value="10" <?php if ($_POST['opene3h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['opene3h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['opene3h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['opene3h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['opene3h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['opene3h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['opene3h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['opene3h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['opene3h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['opene3h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
            </select></td>
            <td class="Labels" align="left"><select name="opene3m">
              <option value="00" <?php if ($_POST['opene3m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['opene3m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['opene3m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['opene3m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['opene3m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['opene3m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['opene3m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['opene3m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
            <td class="Labels" align="right"><select name="closee3h">
              <option value="00">00:</option>
              <option value="10" <?php if ($_POST['closee3h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['closee3h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['closee3h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['closee3h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['closee3h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['closee3h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['closee3h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['closee3h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['closee3h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['closee3h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
            </select></td>
            <td class="Labels" align="left"><select name="closee3m">
              <option value="00" <?php if ($_POST['closee3m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['closee3m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['closee3m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['closee3m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['closee3m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['closee3m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['closee3m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['closee3m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td> 
<td class="Labels" align="right"><select name="opene4h">
              <option value="00">00:</option>
              <option value="10" <?php if ($_POST['opene4h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['opene4h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['opene4h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['opene4h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['opene4h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['opene4h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['opene4h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['opene4h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['opene4h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['opene4h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
            </select></td>
            <td class="Labels" align="left"><select name="opene4m">
              <option value="00" <?php if ($_POST['opene4m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['opene4m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['opene4m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['opene4m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['opene4m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['opene4m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['opene4m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['opene4m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
            <td class="Labels" align="right"><select name="closee4h">
              <option value="00">00:</option>
              <option value="10" <?php if ($_POST['closee4h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['closee4h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['closee4h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['closee4h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['closee4h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['closee4h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['closee4h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['closee4h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['closee4h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['closee4h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
            </select></td>
            <td class="Labels" align="left"><select name="closee4m">
              <option value="00" <?php if ($_POST['closee4m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['closee4m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['closee4m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['closee4m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['closee4m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['closee4m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['closee4m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['closee4m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
            </tr>
  <tr class="rowhighlightG">
    <td class="Verdana11">&nbsp;Eve</td>
    <td class="Labels" align="right"><select name="opene5h">
              <option value="00">00:</option>
              <option value="12" <?php if ($_POST['opene5h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['opene5h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['opene5h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['opene5h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['opene5h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['opene5h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['opene5h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['opene5h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
              <option value="20" <?php if ($_POST['opene5h'] == '20') {echo 'selected="selected"';}?>> 8:</option>
              <option value="21" <?php if ($_POST['opene5h'] == '21') {echo 'selected="selected"';}?>> 9:</option>
            </select></td>
            <td class="Labels" align="left"><select name="opene5m">
              <option value="00" <?php if ($_POST['opene5m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['opene5m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['opene5m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['opene5m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['opene5m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['opene5m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['opene5m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['opene5m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
            <td class="Labels" align="right"><select name="closee5h">
              <option value="00">00:</option>
              <option value="12" <?php if ($_POST['closee5h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['closee5h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['closee5h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['closee5h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['closee5h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['closee5h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['closee5h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['closee5h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
              <option value="20" <?php if ($_POST['closee5h'] == '20') {echo 'selected="selected"';}?>> 8:</option>
              <option value="21" <?php if ($_POST['closee5h'] == '21') {echo 'selected="selected"';}?>> 9:</option>
            </select></td>
            <td class="Labels" align="left"><select name="closee5m">
              <option value="00" <?php if ($_POST['closee5m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['closee5m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['closee5m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['closee5m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['closee5m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['closee5m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['closee5m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['closee5m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td> 
<td class="Labels" align="right"><select name="opene6h">
              <option value="00">00:</option>
              <option value="10" <?php if ($_POST['opene6h'] == '10') {echo 'selected="selected"';}?>>10:</option>
              <option value="11" <?php if ($_POST['opene6h'] == '11') {echo 'selected="selected"';}?>>11:</option>
              <option value="12" <?php if ($_POST['opene6h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['opene6h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['opene6h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['opene6h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['opene6h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['opene6h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['opene6h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['opene6h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
              <option value="20" <?php if ($_POST['opene6h'] == '20') {echo 'selected="selected"';}?>> 8:</option>
              <option value="21" <?php if ($_POST['opene6h'] == '21') {echo 'selected="selected"';}?>> 9:</option>
            </select></td>
            <td class="Labels" align="left"><select name="opene6m">
              <option value="00" <?php if ($_POST['opene6m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['opene6m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['opene6m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['opene6m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['opene6m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['opene6m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['opene6m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['opene6m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
            <td class="Labels" align="right"><select name="closee6h">
              <option value="00">00:</option>
              <option value="12" <?php if ($_POST['closee6h'] == '12') {echo 'selected="selected"';}?>>12:</option>
              <option value="13" <?php if ($_POST['closee6h'] == '13') {echo 'selected="selected"';}?>> 1:</option>
              <option value="14" <?php if ($_POST['closee4h'] == '14') {echo 'selected="selected"';}?>> 2:</option>
              <option value="15" <?php if ($_POST['closee6h'] == '15') {echo 'selected="selected"';}?>> 3:</option>
              <option value="16" <?php if ($_POST['closee6h'] == '16') {echo 'selected="selected"';}?>> 4:</option>
              <option value="17" <?php if ($_POST['closee6h'] == '17') {echo 'selected="selected"';}?>> 5:</option>
              <option value="18" <?php if ($_POST['closee6h'] == '18') {echo 'selected="selected"';}?>> 6:</option>
              <option value="19" <?php if ($_POST['closee6h'] == '19') {echo 'selected="selected"';}?>> 7:</option>
              <option value="20" <?php if ($_POST['closee6h'] == '20') {echo 'selected="selected"';}?>> 8:</option>
              <option value="21" <?php if ($_POST['closee6h'] == '21') {echo 'selected="selected"';}?>> 9:</option>
            </select></td>
            <td class="Labels" align="left"><select name="closee6m">
              <option value="00" <?php if ($_POST['closee6m'] == '00') {echo 'selected="selected"';}?>>00</option>
              <option value="10" <?php if ($_POST['closee6m'] == '10') {echo 'selected="selected"';}?>>10</option>
              <option value="15" <?php if ($_POST['closee6m'] == '15') {echo 'selected="selected"';}?>>15</option>
              <option value="20" <?php if ($_POST['closee6m'] == '20') {echo 'selected="selected"';}?>>20</option>
              <option value="30" <?php if ($_POST['closee6m'] == '30') {echo 'selected="selected"';}?>>30</option>
              <option value="40" <?php if ($_POST['closee6m'] == '40') {echo 'selected="selected"';}?>>40</option>
              <option value="45" <?php if ($_POST['closee6m'] == '45') {echo 'selected="selected"';}?>>45</option>
              <option value="50" <?php if ($_POST['closee6m'] == '50') {echo 'selected="selected"';}?>>50</option>
            </select></td>
            </tr>
  </table>
  </div>


<div align="center" class="Verdana11" id="daterange">
<table width="60%" border:1px solid green; cellspacing="0" cellpadding="0">
  <caption class="Verdana14Blue">Date Range for the above duties</caption>
  <tr> 
    <td width="280" class="Verdana11" align="center" >From</td>
    <td width="280" class="Verdana11" align="center" >To</td>
  </tr>
  <tr>
    <td align="center"><input name="newstart" id="newstart" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this<?php if ($begincf != "00/00/0000" && !empty($begincf))  {echo ", '".substr($begincf,0,2)."','".substr($begincf,3,2)."','".substr($begincf,6,4)."'";} ?>)" value="<?php if ($begincf == "00/00/0000"){echo "";} else {echo $begincf; }?>"/></td>
   
    <td align="center"><input name="newend" id="newend" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this<?php if ($endcf !="00/00/0000" && !empty($endcf)) {echo ", '".substr($endcf,0,2)."','".substr($endcf,3,2)."','".substr($endcf,6,4)."'";} ?>)" value="<?php if ($endcf =="00/00/0000"){echo "";} else {echo $endcf; }?>"/></td>
    <input type="hidden" name="check" value="1"  />
  </tr> 
   <tr>
  <td>&nbsp;</td>
    </tr>
</table>
<table width="90%" border:1px solid green; cellspacing="0" cellpadding="0">
<tr>
    <td colspan="2" align="center" class="ButtonsTable"><input name="save" type="submit" class="button" id="save" value="SAVE" "/>
      <input name="cancel" type="button" class="button" id="cancel" value="CANCEL"  onclick="self.close(); history.back();"/></td>
    </tr>
</table>
</div>
</form>
<!-- InstanceEndEditable --></div>
</body>
</html>
