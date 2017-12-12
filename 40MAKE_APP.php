<?php
session_start();
 
require_once('../tryconnection.php');
mysql_select_db($database_tryconnection, $tryconnection);
include("../ASSETS/age.php");
$timeformat=$_SESSION['timeformat'];
$recepid=$_GET['recepid'];

if (isset($_GET['patient'])) {
 $patient=$_GET['patient'];
 $client=$_GET['client'];
}

$docsurname = $_GET['doctor'] ;
$date=$_GET['date'] ;
$time=$_GET['time'] ;
mysql_select_db($database_tryconnection, $tryconnection);

$Get_now = "SELECT NOW() AS NOW ";
$Query_now = mysql_query($Get_now, $tryconnection) or die(mysql_error()) ;
$row_now = mysql_fetch_assoc($Query_now) ;
$vs = substr($row_now['NOW'],0,10) ;
$date1 = strftime('%Y-%m-%d',$date);

// prepare exit variables in case they just want to cancel and go back to where they were.

$exit_date = strftime('%m/%d/%Y',$date);
$eyear = substr($exit_date,5,4) ;
$emonth = substr($exit_date,0,2) ;
$eday = substr($exit_date,3,2) ;

$query_RECEP = "SELECT *, DATE_FORMAT(DATEIN, '%m/%d/%Y') AS DATEIN, DATE_FORMAT(TIME, '%H:%i') AS TIME FROM RECEP WHERE RFPETID='$patient' LIMIT 1";
$RECEP = mysql_query($query_RECEP, $tryconnection) or die(mysql_error());
$row_RECEP = mysql_fetch_assoc($RECEP);
 
$query_STAFF = sprintf("SELECT STAFF FROM STAFF WHERE SIGNEDIN=1 ORDER BY PRIORITY ");
$STAFF = mysql_query($query_STAFF, $tryconnection) or die(mysql_error());

$query_Doctor = "SELECT DOCTOR, SHORTDOC, SIGNEDIN, PRIORITY FROM DOCTOR WHERE SIGNEDIN = 1 AND SCHEDULE = 1 AND PRIORITY <> 99  ORDER BY PRIORITY ";
$Doctor = mysql_query($query_Doctor, $tryconnection) or die(mysql_error());

$docss = array() ;
$docsl = array() ;
$key = 0 ;
 while ($row_Doctor = mysql_fetch_assoc($Doctor)) {
  $docss[] = $row_Doctor['SHORTDOC'] ;
  $docsl[] = $row_Doctor['DOCTOR'] ;
  $key++ ;
 }
$numdocs = $key ;

$rpatient=$row_RECEP['RFPETID'];
$rclient=$row_RECEP['CUSTNO'];

include("../ASSETS/photo_directory.php");

$empty = 2 ;
if (isset($_POST['check'])) {
 if (isset($_POST['save'])) {  

// Step 1  Pick the next 3 items out of HOSPHOURS.
 $query_param = "SELECT ENDHOUR,ENDMIN,APPTTIME FROM HOSPHOURS WHERE DAY = DAYOFWEEK(FROM_UNIXTIME('$date'))-1 LIMIT 1 " ;
 $Get_param   = mysql_query($query_param, $tryconnection) or die(mysql_error()) ;
 $row_param   = mysql_fetch_assoc($Get_param) ;
 $endahour    = $row_param['ENDHOUR'] ;
 $endamin     = $row_param['ENDMIN'] ;
 $show        = $row_param['APPTTIME'] ;
 
 $maxtime = $endahour*60 + $endamin ;

// Doublecheck the posted time

 $time_requested = $_POST['time'] ;
 $cycle = $_POST['duration'] ; 
 $duration = $cycle ;
 $newtime = $time_requested ;
 $empty = 0 ;

 $whatdate = "SELECT FROM_UNIXTIME('$date','%Y-%m-%d') as NEWDATE" ;
 $readit = mysql_query($whatdate, $tryconnection) or die(mysql_error()); 
 $row_date = mysql_fetch_assoc($readit) ;
 $english = $row_date['NEWDATE'] ;

 while ($cycle >= $show) {
  $CHK1 = "SELECT APPTNUM FROM APPTS WHERE DATEOF = '$english' AND TIMEOF = '$newtime' AND SHORTDOC = '$docsurname' AND CANCELLED <> 1 LIMIT 1" ;
  $query_CHK1 = mysql_query($CHK1, $tryconnection) or die(mysql_error()) ;
  $row_CHK1 = mysql_fetch_array($query_CHK1) ;

// Check for collisions

// set up a time variable for each of the possible appointment slots to ensure they can fit in without a conflict.
  if ($row_CHK1 === FALSE) {
    $newhr = substr($newtime,0,2) ;
    $newmin = substr($newtime,3,2) ;
  
  // work out the new time.
         $hr  = $newhr ;
         $min = $newmin + $show ;
     
         if ($min >= '60') {
            $hr  = substr($hr +101,1,2);
            $min = substr(($min + 40),1,2);
         }
         
         // Check to make sure they have not gone past the end of the day.
         if ($hr*60 + $min > $maxtime) {
             $_POST['duration'] = $_POST['duration'] - $cycle;
             $cycle = 0 ;
             }
         
         $newtime = $hr.':'.$min ;
         $cycle = $cycle - $show ;
 } // $row_CHK1 === FALSE
  else { // OK, there is a collision.
  $empty = 1 ;
  $cycle = 0 ;
  echo ' Smash!!! ' ;
  break ;
  } // else $row_CHK1 === FALSE
} //while ($cycle > $show)
if ($empty == 0 ) {


// Set up to add a note in the medical history.

    $query_PREFER="SELECT TRTMCOUNT FROM PREFER LIMIT 1";
    $PREFER= mysql_query($query_PREFER, $tryconnection) or die(mysql_error());
    $row_PREFER = mysql_fetch_assoc($PREFER);
    
    $client = $_SESSION['client'] ;

    $treatmxx= $client/$row_PREFER['TRTMCOUNT'];
    $treatmxx="TREATM".floor($treatmxx);

	$query_CHECKTABLE="SELECT * FROM $treatmxx LIMIT 1";
	$CHECKTABLE= mysql_query($query_CHECKTABLE, $tryconnection) or $none=1;
	
	if (isset($none)){
	 $create_TREATMXX="CREATE TABLE $treatmxx LIKE TREATM0";
	 $result=mysql_query($create_TREATMXX, $tryconnection) or die(mysql_error());
	}
	
	$today = 'Appointment made by '.
	mysql_real_escape_string($_POST['staff']).
	' for '. mysql_real_escape_string($_POST['problem'])  ;
  	
	$insertSQL1 = "INSERT INTO $treatmxx (CUSTNO, PETID, TREATDESC, HCAT, HSUBCAT, WHO, TREATDATE) VALUES ('$client','$patient','CLINICAL EXAMINATION',960,'91', '".mysql_real_escape_string($_POST['staff'])."', DATE_FORMAT(NOW(), '%Y/%m/%d'))";

    mysql_query($insertSQL1, $tryconnection) or die(mysql_error()) ;
    $insertSQL2 = "INSERT INTO $treatmxx (CUSTNO, PETID, TREATDESC, HCAT, HSUBCAT, WHO, TREATDATE) VALUES ('$client','$patient','$today', 960,'92', '".mysql_real_escape_string($_POST['staff'])."',  DATE_FORMAT(NOW(), '%Y/%m/%d'))";
    mysql_query($insertSQL2, $tryconnection)  or die(mysql_error()) ;
   
 $query_insertSQL="INSERT INTO APPTS (DATEOF, TIMEOF, SHORTDOC, DATEMADE, DOCREQ, DURATION, CUSTNO, NAME, CONTACT, PETID, PETNAME, RFPETTYPE, PSEX, LOCATION, PROBLEM, DESCRIP, NEWCL, NEWPET, CAREA, PHONE1, CAREA2, PHONE2, CAREA3, PHONE3, BUSEXT, STAFF)
                VALUES (STR_TO_DATE('$_POST[datein]','%m/%d/%Y'),'$time_requested','".mysql_real_escape_string($_GET['doctor'])."', NOW(), '$_POST[requested]','$duration',
                '$row_PATIENT_CLIENT[CUSTNO]', '".mysql_real_escape_string($row_PATIENT_CLIENT['COMPANY'])."', '".mysql_real_escape_string($row_PATIENT_CLIENT['CONTACT'])."','$row_PATIENT_CLIENT[PETID]', '".mysql_real_escape_string($row_PATIENT_CLIENT['PETNAME'])."', '$row_PATIENT_CLIENT[PETTYPE]', '$row_PATIENT_CLIENT[PSEX]', '1','".mysql_real_escape_string($_POST['problem'])."', 
                '".mysql_real_escape_string($row_PATIENT_CLIENT['PETBREED'])."','$_POST[newcl]','$_POST[newpnt]','".mysql_real_escape_string($row_PATIENT_CLIENT['CAREA'])."','".mysql_real_escape_string($row_PATIENT_CLIENT['PHONE'])."',
                '".mysql_real_escape_string($row_PATIENT_CLIENT['CAREA2'])."', '".mysql_real_escape_string($row_PATIENT_CLIENT['PHONE2'])."',
                '".mysql_real_escape_string($row_PATIENT_CLIENT['CAREA3'])."', '".mysql_real_escape_string($row_PATIENT_CLIENT['PHONE3'])."','".mysql_real_escape_string($row_PATIENT_CLIENT['CBEXT'])."','".mysql_real_escape_string($_POST['staff'])."')";
                
    $insertSQL=mysql_query($query_insertSQL,$tryconnection) or die(mysql_error()); 
    
    $year = substr($_POST['datein'],6,4) ;
    $month = substr($_POST['datein'],0,2) ;
    $day_of_month = substr($_POST['datein'],3,2) ;
    
    $today = "SELECT DATE(NOW()) AS DATE, TIME(NOW()) AS TIME ";
    $query_date = mysql_query($today, $tryconnection) or die(mysql_error()) ;
    $row_date = mysql_fetch_array($query_date) ;
    $datex = $row_date['DATE'] ;
    $timex = $row_date['TIME'] ;
             
    $Auto_roll = "INSERT INTO RECEP (CUSTNO,NAME,RFPETID,PETNAME,RFPETTYPE,LOCATION,DESCRIP,PSEX,FNAME,PROBLEM,AREA1,PH1,AREA2,PH2,AREA3,PH3,BUSEXT,DATEIN,TIME,
                CLINICIAN) SELECT CUSTNO,NAME,PETID,PETNAME,RFPETTYPE,'1',DESCRIP,PSEX,CONTACT,PROBLEM,CAREA,PHONE1,CAREA2,PHONE2,CAREA3,PHONE3,BUSEXT,DATEOF,TIMEOF,
                SHORTDOC FROM APPTS WHERE DATEOF = '$datex' AND TIMEOF >= '$timex' AND CANCELLED <> 1 AND NOT EXISTS (SELECT RFPETID FROM RECEP WHERE RECEP.RFPETID = APPTS.PETID  AND RECEP.DATEIN = '$datex' )" ;
                
    $update_it = mysql_query($Auto_roll, $tryconnection) or die(mysql_error()) ;
 //  
/*
// if it is for today, add to reception file if not already there.                ,
   
//   if  (empty($row_RECEP) && $_POST['datein'] == date("%m/%d/%Y") ){
 
   if  (empty($row_RECEP) && $_POST['datein'] == date("%m/%d/%Y") ){
    $query_insertSQL="INSERT INTO RECEP (DATEIN, TIME, DATETIME, CUSTNO, NAME, RFPETID, PETNAME, PSEX, RFPETTYPE, LOCATION, DESCRIP, FNAME, PROBLEM, AREA1, PH1, AREA2, PH2, AREA3, PH3, CLINICIAN) 
                VALUES (STR_TO_DATE('$_POST[datein]','%m/%d/%Y'), '$time_requested', NOW(),'$row_PATIENT_CLIENT[CUSTNO]', '".mysql_real_escape_string($row_PATIENT_CLIENT['COMPANY'])."', '$row_PATIENT_CLIENT[PETID]', 
                '".mysql_real_escape_string($row_PATIENT_CLIENT['PETNAME'])."', '$row_PATIENT_CLIENT[PSEX]', '$_POST[rfpettype]', '1', '".mysql_real_escape_string($row_PATIENT_CLIENT['PETBREED'])."',
                '".mysql_real_escape_string($row_PATIENT_CLIENT['CONTACT'])."','".mysql_real_escape_string($_POST['problem'])."','$row_PATIENT_CLIENT[CAREA]','$row_PATIENT_CLIENT[PHONE]','$row_PATIENT_CLIENT[CAREA2]',
                '$row_PATIENT_CLIENT[PHONE2]','$row_PATIENT_CLIENT[CAREA3]','$row_PATIENT_CLIENT[PHONE3]', '".mysql_real_escape_string($_POST['clinician2'])."')";
                
    $insertSQL=mysql_query($query_insertSQL,$tryconnection) or die(mysql_error());
    
   }
*/
  header("Location:DAY.php?year=".$year."&month=".$month."&day=".$day_of_month."&inc=0,_blank, width=1200, height=900, toolbar=no, status=no") ; 

   } //if ($empty == 0 
 } //if (isset($_POST['save']) )
 else {$winback="" ;
 } 
 if ($empty == 1) {
 
  header("Location:SMASH.html") ;
  
 } // if ($empty == 1)
} //if (isset($_POST['check']))
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/ClientPatientTemplate.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=2" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>MAKING AN APPOINTMENT</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>


<!-- InstanceBeginEditable name="head" -->

<script type="text/javascript">
function bodyonload(){
var d1 = '<?php echo $date1 ; ?>' ;
var vs = '<?php echo $vs ; ?>' ;
//alert (' the required is ' + d1 + ' and comp is ' + vs) ;
if (d1 < vs) {
 alert ('You cannot book in the past!') ;
 self.close() ;
}
window.opener.close() ;
document.getElementById('inuse').innerText=localStorage.xdatabase;
document.make_appt.problem.focus();	
}

function smash() {

 var r = confirm("The appointment time and duration creates a conflict with another appointment. Please re-do.");

}

function bodyonunload(){
//sessionStorage.removeItem('refID');
sessionStorage.setItem('refID','PROCESSING MENU');
//"document.location=\'DAY.php?year='.$year.'&month='.$month.'&day='.$day_of_month.'&inc=0'.'\'"
}


function checknames()
{
valid = true;
var clinician=document.make_appt.clinician2;
var staff=document.make_appt.staff;
if (document.make_appt.clinician2.options[0].selected===true){
alert ('Please enter Doctor\'s name.');
valid = false;
}
if (document.make_appt.staff.options[0].selected===true){
alert ('Please enter your name as Staff.');
valid = false;
}
return valid;
}

function countchar(){
var chars=document.forms[0].problem.value.length;
document.getElementById('maxnum').innerText=chars;
	if (chars>500){
	alert('I am sorry, but your text is too long. It\'s not my fault.');
	document.forms[0].problem.value=document.forms[0].problem.value.substr(0,499);	
	}
}


</script>

<style type="text/css">
<!--
#table {
	border-color: #CCCCCC;
	border-collapse: separate;
	border-spacing: 1px;
}

#table2 {
	border-color: #CCCCCC;
	border-collapse: separate;
	border-spacing: 1px;
}


.SelectList {
	width: 100%;
	height: 100%;
	font-family: "Andale Mono";
	font-size: 13px;
	border-width: 1px;
	padding: 5 px;
	outline-width: 0px;
}
-->
</style>



<!-- InstanceEndEditable -->
<script type="text/javascript" src="../ASSETS/navigation.js"></script>
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
<div id="inuse" title="File in memory"><!-- InstanceBeginEditable name="fileinuse" -->
<!-- InstanceEndEditable --></div>



<div id="WindowBody">
<!-- InstanceBeginEditable name="DVMBasicTemplate" -->

<form action="" class="FormDisplay" name="make_appt" method="post" onsubmit="return checknames();">
<input type="hidden" name="rfpettype" value="<?php echo $row_PATIENT_CLIENT['PETTYPE']; ?>" />
<table width="100%" height="553" border="0" cellpadding="0" cellspacing="0">
	<tr>
    <td height="60" colspan="3" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="59%" height="15" align="left" class="Verdana12B">
        <span style="background-color:#FFFF00">
        <?php echo $row_PATIENT_CLIENT['TITLE'].' '.$row_PATIENT_CLIENT['CONTACT'].' '.$row_PATIENT_CLIENT['COMPANY']; ?>
        <!--        <script type="text/javascript">document.write(sessionStorage.custname);</script>-->        
		</span>
        </td>
        <td width="22%" rowspan="2" valign="middle" align="center"><span class="Verdana11">
        <?php echo $custterm; ?>
        <!--<script type="text/javascript">document.write(sessionStorage.custterm);</script>-->          
        </span>
        </td>
        <td width="19%" colspan="2" rowspan="4" align="center"><table width="100%" border="1" cellspacing="0" cellpadding="0" id="table2">
            <tr>
              <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="18" colspan="2" align="center"><span class="Verdana11B" style="background-color:#FFFF00"><?php echo date('m/d/Y'); ?></span></td>
                  </tr>
                  <tr>
                    <td width="41%" height="18" align="right" class="Labels2"> 
                    <?php echo $row_PATIENT_CLIENT['BALANCE']; ?>       
					<!--<script type="text/javascript">document.write(sessionStorage.custprevbal);</script>--></td>
                    <td width="59%" height="18" class="Labels2">&nbsp;Balance</td>
                  </tr>
                  <tr>
                    <td height="18" align="right" class="Labels2">
                    <?php echo $row_PATIENT_CLIENT['CREDIT']; ?>
                    <!--<script type="text/javascript">document.write(sessionStorage.custcurbal);</script>--></td>
                    <td height="18" class="Labels2">&nbsp;Deposit</td>
                  </tr>
              </table></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td height="15" align="left" class="Labels2">        
		<?php echo $row_PATIENT_CLIENT['CAREA'].'-'.$row_PATIENT_CLIENT['PHONE'].', '.$row_PATIENT_CLIENT['CAREA2'].'-'.$row_PATIENT_CLIENT['PHONE2'].', '.$row_PATIENT_CLIENT['CAREA3'].'-'.$row_PATIENT_CLIENT['PHONE3'].', '.$row_PATIENT_CLIENT['CAREA4'].'-'.$row_PATIENT_CLIENT['PHONE4']; ?>
		<!--<script type="text/javascript">document.write(sessionStorage.custphone);</script>--></td>
      </tr>
      <tr bgcolor="<?php if ($psex=='M'){echo '#DBEBF0';} else {echo '#F9DEE9';}; ?>">
        <td height="15" colspan="2" align="left"  class="Labels2"><span class="Verdana12B" style="background-color:#FFFF00">&nbsp;<script type="text/javascript">document.write(sessionStorage.petname);</script>
</span>        
<?php  echo $pettype.', '.$row_PATIENT_CLIENT['PETBREED'];?>
<!--<script type="text/javascript">document.write(sessionStorage.desco);</script>-->
         </td>
      </tr>
      <tr bgcolor="<?php if ($psex=='M'){echo '#DBEBF0';} else {echo '#F9DEE9';}; ?>" >
        <td height="15" colspan="2" align="left" class="Labels2">
        <?php echo  $desct; ?>
        <!--<script type="text/javascript">document.write(sessionStorage.desct);</script>--> (<?php agecalculation($tryconnection,$pdob); ?>)
		</td>
      </tr>
    </table>    </td>
    </tr>
  <tr>
    <td height="" colspan="3" align="center" valign="top">
    
    
    <table class="table" width="733" height="457" border="1" cellpadding="0" cellspacing="0" >
    <tr>
    <td align="center"><table width="85%" border="1" cellpadding="0" cellspacing="0" bordercolor="#333333" bgcolor="#FFFFFF" frame="box" rules="none">
      <tr>
        <td width="2" align="left" class="Verdana11B">&nbsp;</td>
        <td width="243" height="40" align="left" valign="middle" class="Verdana11B">Please enter admitting information:</td>
        <td height="40" colspan="2" align="center" valign="middle"><label>Date:
          <input name="datein" type="text" class="Input" id="datein" size="9" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo strftime('%m/%d/%Y',$date); ?>" /></label></td>
        <td width="108" height="40" align="right" valign="middle"><label>Time:
          <input name="time" type="time" step="600" class="Input" id="time" size="7" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php echo $time ; ?>"/></label></td>
        <td width="142" height="40" align="center" valign="middle">
        <label>Duration: 
        <select name="duration" id="duration" >
          <option value="10">10</option>
          <option value="15">15</option>
          <option value="20" <?php if ($row_PATIENT_CLIENT['PETTYPE'] < 3 || $row_PATIENT_CLIENT['PETTYPE'] > 6 ){echo 'selected="selected"' ;}?>>20</option>
          <option value="30">30</option>
          <option value="40">40</option>
          <option value="45">45</option>
          <option value="50">50</option>
          <option value="60">60</option>
          <option value="90" <?php if ($row_PATIENT_CLIENT['PETTYPE'] > 2 && $row_PATIENT_CLIENT['PETTYPE'] < 7 ){echo 'selected="selected"' ;}?>>90</option>
          <option value="120">2hr</option>
          <option value="180">3hr</option>
          <option value="240">4hr</option>
          <option value="300">5hr</option>
          <option value="360">6hr</option>
        </select></label>        </td>
      </tr>
      <tr>
        <td width="2" height="120" align="left">&nbsp;</td>
		<td height="120" colspan="5" align="center" class="Verdana11Grey"><textarea name="problem" cols="80" rows="5" class="commentarea" id="textarea" onkeyup="countchar()"><?php echo $row_RECEP['PROBLEM']; ?></textarea><br  /># of characters: <span id="maxnum"></span> (max 500)</td>
      </tr>
      <tr>
        <td width="2" align="center">&nbsp;</td>
		<td height="80" colspan="5" align="center">
        
        <table width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#333333" frame="box" rules="none">
          <tr <?php // if (!empty($row_RECEP)) {echo "class='hidden'";} ?>>
            <td class="Verdana11B">&nbsp;</td>
            <td height="30" align="center" class="Verdana11"><label>
              <input type="checkbox" value="1" name="newcl" id="newcl" />
              New Client</label></td>
            <td height="30" align="center" class="Verdana11"><label>
              <input type="checkbox" value="1" name="newpnt" id="newpnt" />
              New Patient</label></td>
            <td height="30" align="right" class="Labels">Doctor/Tech
              <select name="clinician2" id="clinician2">
                <option value="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;???</option>
             <?php for ($key=0; $key<$numdocs; $key++) { 
                    echo '<option  value="'.$docss[$key].'"'; if ($docsurname == $docss[$key]) {echo ' selected="selected" ';} echo ' >'.$docsl[$key].'</option>';
                 }  ?>
              </select>
            </td>
            
            <td height="30" align="center" class="Verdana11"><label > <!--
            <input type="radio" name="location" value="" class="hidden"/>Treatment</label> <label class="hidden">
            <input type="radio" name="location" value="" />Surgery</label> <label class="hidden">
            <input type="radio" name="location" value="" />Recovery</label> --><label>
            <input type="checkbox" value="1" name="requested" id="requested" />
            Requested</label></td>
            </tr>
        </table>        </td>
      </tr>
      <tr>
        <td width="2">&nbsp;</td>
        <td height="30" colspan="2" align="right"><label class="hidden">
        <input type="checkbox" name="checkbox2" id="checkbox2" /> 
        Add another patient for this client
</label>
          &nbsp;</td>
        <td width="100" height="30" align="right">Staff&nbsp;&nbsp;</td>
        <td height="30" colspan="2">
<select name="staff" id="staff">
			<option value="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;???</option>
			<?php while ($row_STAFF = mysql_fetch_assoc($STAFF)) { ?>
            <option value="<?php echo $row_STAFF['STAFF']; ?>"><?php echo $row_STAFF['STAFF']; ?></option>
            <?php }  ?>
        </select>        </td>
        </tr>
    </table></td>
    </tr>
    </table>    </td>
  </tr>
  <tr>
    <td height="35" colspan="5" align="center" valign="middle" bgcolor="#B1B4FF">
     <input name="save" class="button" type="submit" value="SAVE" />
     <input name="cancel" class="button" type="reset" value="CANCEL" onclick="self.close();"/>
     <input type="hidden" name="check" value="1"/>
    </td>
  </tr>
</table>
</form>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>