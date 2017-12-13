<?php
session_start();

require_once('../tryconnection.php');

mysqli_select_db($tryconnection, $database_tryconnection);

include("../ASSETS/age.php");
$apptnum = $_GET['apptnum'];
$winback = '' ;

$retrieve = "SELECT APPTNUM,DATEOF,TIMEOF,SHORTDOC,DOCREQ,CUSTNO,PETID,NAME,CONTACT,NEWCL,PETNAME,NEWPET,PROBLEM,DURATION,RFPETTYPE, STAFF,CAREA,PHONE1,CAREA2,PHONE2, 
             DATE_FORMAT(DATEMADE,'%e %M %Y') AS DATEMADE, CHSTAFF, DATE_FORMAT(CHDATE,'%e %M %Y') AS CHDATE FROM APPTS WHERE APPTNUM = '$apptnum' AND CANCELLED <> 1 LIMIT 1" ;
$Get_AP = mysqli_query($tryconnection, $retrieve) or die(mysqli_error($mysqli_link)) ;
$row_AP  = mysqli_fetch_assoc($Get_AP) ;

  $ak = $apptnum ;
  $dateof =   $row_AP['DATEOF'] ;
  $timeof =   $row_AP['TIMEOF'] ;
  $docsurname = $row_AP['SHORTDOC'] ;
  $req =      $row_AP['DOCREQ'] ;
  $client =   $row_AP['CUSTNO'] ;
  $patient =  $row_AP['PETID'] ;
  $name =     $row_AP['NAME'] ;
  $contact =  $row_AP['CONTACT'] ;
  $newcl =    $row_AP['NEWCL'] ;
  $petname =  $row_AP['PETNAME'] ;
  $newpet =   $row_AP['NEWPET'] ;
  $problem =  $row_AP['PROBLEM'] ;
  $duration = $row_AP['DURATION'] ;
  $pettype =  $row_AP['RFPETTYPE'] ;
  $staff =    $row_AP['STAFF'] ;
  $datemade = $row_AP['DATEMADE'] ;
  $chstaff =  $row_AP['CHSTAFF'] ;
  $chdate =   $row_AP['CHDATE'] ;
  
 $year =substr($dateof,0,4) ;
 $month=substr($dateof,5,2) ; 
 $day = substr($dateof,8,2) ;
 
$query_STAFF = "SELECT STAFF FROM STAFF WHERE SIGNEDIN=1 ORDER BY PRIORITY ";
$STAFF = mysqli_query($tryconnection, $query_STAFF) or die(mysqli_error($mysqli_link));
//$row_STAFF = mysql_fetch_assoc($STAFF);

$query_Doctor = "SELECT DOCTOR, SHORTDOC, SIGNEDIN, PRIORITY FROM DOCTOR WHERE SIGNEDIN = 1 AND PRIORITY <> 99  ORDER BY PRIORITY ";
$Doctor = mysqli_query($tryconnection, $query_Doctor) or die(mysqli_error($mysqli_link));

$docss = array() ;
$docsl = array() ;
$key = 0 ;
 while ($row_Doctor = mysqli_fetch_assoc($Doctor)) {
  $docss[] = $row_Doctor['SHORTDOC'] ;
  $docsl[] = $row_Doctor['DOCTOR'] ;
  $key++ ;
 }
 $docss[] = "Technician" ;
 $docsl[] = "Technician" ;
 $key++ ;
 
$numdocs = $key ;

$rpatient=$row_RECEP['RFPETID'];
$rclient=$row_RECEP['CUSTNO'];

include("../ASSETS/photo_directory.php");

$now = mktime(0,0,0,date('m/d/Y')) ;

if (isset($_POST['delete'])) {

// Set up to add a note in the medical history.

    $query_PREFER="SELECT TRTMCOUNT FROM PREFER LIMIT 1";
    $PREFER= mysqli_query($tryconnection, $query_PREFER) or die(mysqli_error($mysqli_link));
    $row_PREFER = mysqli_fetch_assoc($PREFER);
    
//    $client = $_SESSION['client'] ;

    $treatmxx= $client/$row_PREFER['TRTMCOUNT'];
    $treatmxx="TREATM".floor($treatmxx);
    
    unset($none) ;
	$query_CHECKTABLE="SELECT LINENUMBER FROM $treatmxx LIMIT 1";
	$CHECKTABLE= mysqli_query($tryconnection, $query_CHECKTABLE) or $none=1;
	
	if (isset($none)){
	 $create_TREATMXX="CREATE TABLE $treatmxx LIKE TREATM0";
	 $result=mysqli_query($tryconnection, $create_TREATMXX) or die(mysqli_error($mysqli_link));
	}
	
	$now = "SELECT DATE_FORMAT(NOW(),'%a, %b %D %Y at %H:%i') AS NOW" ;
	$Q_now = mysqli_query($tryconnection, $now) or die(mysqli_error($mysqli_link)) ;
	$row_now = mysqli_fetch_assoc($Q_now) ;
	$ti = $row_now['NOW'] ;
	
	$today = 'Appointment CANCELLED by '.
	mysqli_real_escape_string($mysqli_link, $_POST['staff']). ' on ' . $ti . ' because '. mysqli_real_escape_string($mysqli_link, $_POST['problem'])  ;
  	
	$insertSQL1 = "INSERT INTO $treatmxx (CUSTNO, PETID, TREATDESC, HCAT, HSUBCAT, WHO, TREATDATE) VALUES ('$client','$patient','CLINICAL EXAMINATION',960,'91', '".mysqli_real_escape_string($mysqli_link, $_POST['staff'])."', DATE_FORMAT(NOW(), '%Y/%m/%d'))";

    mysqli_query($tryconnection, $insertSQL1) or die(mysqli_error($mysqli_link)) ;
    $insertSQL2 = "INSERT INTO $treatmxx (CUSTNO, PETID, TREATDESC, HCAT, HSUBCAT, WHO, TREATDATE) VALUES ('$client','$patient','$today', 960,'92', '".mysqli_real_escape_string($mysqli_link, $_POST['staff'])."',  DATE_FORMAT(NOW(), '%Y/%m/%d'))";
    mysqli_query($tryconnection, $insertSQL2)  or die(mysqli_error($mysqli_link)) ;
    
    $dropit = "UPDATE APPTS SET CANCELLED = 1 WHERE APPTNUM = '$apptnum' LIMIT 1 " ;
    $do_it = mysqli_query($tryconnection, $dropit) or die(mysqli_error($mysqli_link)) ;  

    $year = substr($_POST['datein'],6,4) ;
    $month = substr($_POST['datein'],0,2) ;
    $day_of_month = substr($_POST['datein'],3,2) ;
    
    header("Location:DAY.php?year=".$year."&month=".$month."&day=".$day_of_month."&inc=0,_blank, width=1200, height=900, toolbar=no, status=no") ; 

 }
if (isset($_POST['save']) ) { 

// Step 1  Pick the next 3 items out of HOSPHOURS.
  $query_param = "SELECT ENDHOUR,ENDMIN,APPTTIME FROM HOSPHOURS WHERE DAY = DAYOFWEEK(FROM_UNIXTIME('$date'))-1 LIMIT 1 " ;
  $Get_param   = mysqli_query($tryconnection, $query_param) or die(mysqli_error($mysqli_link)) ;
  $row_param   = mysqli_fetch_assoc($Get_param) ;
  $endahour    = $row_param['ENDHOUR'] ;
  $endamin     = $row_param['ENDMIN'] ;
  $show        = $row_param['APPTTIME'] ;

  $maxtime = $endahour*60 + $endamin ;
// Step 2,  Doublecheck the posted time

  $time_requested = $_POST['time'] ;
  $cycle = $_POST['duration'] ;
  $newtime = $time_requested ;
  $empty = 0 ;
  
  // Step 3. double check the doctor
  
  $docsurname = $_POST['clinician2'] ;

//  $whatdate = "SELECT FROM_UNIXTIME('$dateof','%Y-%m-%d') as NEWDATE" ;
//  $readit = mysql_query($whatdate, $tryconnection) or die(mysql_error()); 
//  $row_date = mysql_fetch_assoc($readit) ;
  $english = $dateof ;


  while ($cycle >= $show) {
    $CHK1 = "SELECT APPTNUM FROM APPTS WHERE DATEOF = '$english' AND TIMEOF = '$newtime' AND SHORTDOC = '$docsurname' AND CANCELLED <> 1 AND APPTNUM <> '$ak' LIMIT 1" ;
    $query_CHK1 = mysqli_query($tryconnection, $CHK1) or die(mysqli_error($mysqli_link)) ;
    $row_CHK1 = mysqli_fetch_array($query_CHK1) ;

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
  } // else $row_CHK1 !== FALSE
} //while ($cycle > $show)
if ($empty == 0 ) {
 $query_insertSQL="UPDATE APPTS SET DATEOF = STR_TO_DATE('$_POST[datein]','%m/%d/%Y'), TIMEOF ='$_POST[time]', SHORTDOC = '".mysqli_real_escape_string($mysqli_link, $_POST['clinician2'])."', 
                  DOCREQ = '$_POST[requested]', DURATION ='$_POST[duration]' , PROBLEM = '".mysqli_real_escape_string($mysqli_link, $_POST['problem'])."', NEWCL= '$_POST[newcl]',
                   NEWPET = '$_POST[newpnt]', CHSTAFF = '".mysqli_real_escape_string($mysqli_link, $_POST['staff'])."', CHDATE = NOW()  WHERE APPTNUM = '$apptnum' LIMIT 1";

    $insertSQL=mysqli_query($tryconnection, $query_insertSQL) or die(mysqli_error($mysqli_link)); 
    
    $year = substr($_POST['datein'],6,4) ;
    $month = substr($_POST['datein'],0,2) ;
    $day_of_month = substr($_POST['datein'],3,2) ;
 
    header("Location:DAY.php?year=".$year."&month=".$month."&day=".$day_of_month."&inc=0,_blank, width=1200, height=900, toolbar=no, status=no") ; 

    } //($empty == 0 )
  }
   else {$winback="" ;
  }

 if ($empty == 1) {
 
  header("Location:SMASH.html") ;
  
 }
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/ClientPatientTemplate.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=2" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>EDITING AN APPOINTMENT</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>


<!-- InstanceBeginEditable name="head" -->

<script type="text/javascript">
function bodyonload(){
window.opener.close() ;
document.getElementById('inuse').innerText=localStorage.xdatabase;
document.edit_appt.problem = addtext() ;
document.edit_appt.problem.focus();	
}

function bodyonunload(){
//sessionStorage.removeItem('refID');

sessionStorage.setItem('refID','PROCESSING MENU');
opener.Refresh() ; 
self.close() ;
}

function doublecheck() {
valid = true ;
var who = <?php echo $name.'?' ;?> ;
 r=confirm("Reconsider deleting appointment for \n" + "who"); 
 if (r === false) { 
 valid = false ;
 }
return valid ;
}

function checknames()
{
valid = true;
var clinician=document.edit_appt.clinician2;
var staff=document.edit_appt.staff;
if (document.edit_appt.clinician2.options[0].selected===true){
alert ('Please enter Doctor\'s name.');
valid = false;
}
if (document.edit_appt.staff.options[0].selected===true){
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

function addtext() {
 var newtext = <?php echo $problem; ?> ; 
 document.edit_appt.problem.value = newtext ;
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

<form action="" class="FormDisplay" name="edit_appt" method="post" onsubmit="return checknames();">
<table width="100%" height="553" border="0" cellpadding="0" cellspacing="0">
	<tr>
    <td height="60" colspan="3" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="59%" height="15" align="left" class="Verdana12B">
        <span style="background-color:#FFFF00">
        <?php echo $name . ', ' . $contact; ?>
		</span>
        </td>
        <td width="22%" rowspan="2" valign="middle" align="center"><span class="Verdana11">&nbsp;
        </span>
        </td>
        <td width="19%" colspan="2" rowspan="4" align="center"><table width="100%" border="1" cellspacing="0" cellpadding="0" id="table2">
            <tr>
              <td><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="18" colspan="2" align="center"><span class="Verdana11B" style="background-color:#FFFF00"><?php echo date('m/d/Y'); ?></span></td>
                  </tr>
                  <tr>
                    <td width="41%" height="18" align="right" class="Labels2">&nbsp;
					<!--<script type="text/javascript">document.write(sessionStorage.custprevbal);</script>--></td>
                    <td width="59%" height="18" class="Labels2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="18" align="right" class="Labels2">&nbsp;
                    <!--<script type="text/javascript">document.write(sessionStorage.custcurbal);</script>--></td>
                    <td height="18" class="Labels2">&nbsp;</td>
                  </tr>
              </table></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td height="15" align="left" class="Labels2">&nbsp<?php echo '('.$row_AP['CAREA'].')'.$row_AP['PHONE1'].' (' . $row_AP['CAREA2'].')'.$row_AP['PHONE2'];?>        
		<!--<script type="text/javascript">document.write(sessionStorage.custphone);</script>--></td>
      </tr>
      <tr bgcolor="<?php if ($psex=='M'){echo '#DBEBF0';} else {echo '#F9DEE9';} ?>">
        <td height="15" colspan="2" align="left"  class="Labels2"><span class="Verdana12B" style="background-color:#FFFF00">&nbsp;<?php echo $row_AP['PETNAME'] ; ?>
        </span>&nbsp;        
<!--<script type="text/javascript">document.write(sessionStorage.desco);</script>-->
         </td>
      </tr>
      <tr bgcolor="<?php if ($psex=='M'){echo '#DBEBF0';} else {echo '#F9DEE9';} ?>" >
        <td height="15" colspan="2" align="left" class="Labels2">&nbsp;</td>
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
        <td width="243" height="40" align="left" valign="middle" class="Verdana11B">Please edit the presenting problem:</td>
        <td height="40" colspan="2" align="center" valign="middle"><label>Date:
          <input name="datein" type="text" class="Input" id="datein" size="9" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $month.'/'.$day.'/'.$year ; ?>" /></label></td>
        <td width="108" height="40" align="right" valign="middle"><label>Time:
          <input name="time" type="time" step="600" class="Input" id="time" size="7" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php echo $timeof ; ?>"/></label></td>
        <td width="142" height="40" align="center" valign="middle">
        <label>Duration: 
        <select name="duration" id="duration" >
          <option value="10" <?php if ($duration == 10 ){echo 'selected="selected"' ;}?>>10</option>
          <option value="20" <?php if ($duration == 20 ){echo 'selected="selected"' ;}?>>20</option>
          <option value="30" <?php if ($duration == 30 ){echo 'selected="selected"' ;}?>>30</option>
          <option value="40" <?php if ($duration == 40 ){echo 'selected="selected"' ;}?>>40</option>
          <option value="50" <?php if ($duration == 50 ){echo 'selected="selected"' ;}?>>50</option>
          <option value="60" <?php if ($duration == 60 ){echo 'selected="selected"' ;}?>>60</option>
          <option value="90" <?php if ($duration == 90 ){echo 'selected="selected"' ;}?>>90</option>
          <option value="120"<?php if ($duration == 120 ){echo 'selected="selected"' ;}?>>120</option>>2hr</option>
          <option value="180"<?php if ($duration == 180 ){echo 'selected="selected"' ;}?>>180</option>>3hr</option>
          <option value="240"<?php if ($duration == 240 ){echo 'selected="selected"' ;}?>>240</option>>4hr</option>
          <option value="300"<?php if ($duration == 300 ){echo 'selected="selected"' ;}?>>300</option>>5hr</option>
          <option value="360"<?php if ($duration == 360 ){echo 'selected="selected"' ;}?>>360</option>>6hr</option>
        </select></label>        </td>
      </tr>
      <tr>
        <td width="2" height="120" align="left">&nbsp;</td>
		<td height="120" colspan="5" align="center" class="Verdana11Grey"><textarea name="problem" cols="80" rows="5" class="commentarea" id="textarea" onkeyup="countchar()"><?php echo $problem; ?></textarea><br  /># of characters: <span id="maxnum"></span> (max 500)</td>
      </tr>
      <tr>
        <td width="2" align="center">&nbsp;</td>
		<td height="80" colspan="5" align="center">
        
        <table width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#333333" frame="box" rules="none">
          <tr>
            <td class="Verdana11B">&nbsp;</td>
            <td height="30" align="center" class="Verdana11"><label>
              <input type="checkbox" name="newcl" id="newcl" value="1" <?php if ($newcl == 1) {echo ' checked="checked" '; }?> />
              New Client</label></td>
            <td height="30" align="center" class="Verdana11"><label>
              <input type="checkbox" name="newpnt" id="newpnt" value = "1" />
              New Patient</label></td>
            <td height="30" align="right" class="Labels">Doctor/Tech
              <select name="clinician2" id="clinician2">
                <option value="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;???</option>
             <?php for ($key=0; $key<$numdocs; $key++) { 
                    echo '<option  value="'.$docss[$key].'"'; if ($docsurname == $docss[$key]) {echo ' selected="selected" ';} echo ' >'.$docsl[$key].'</option>';
                 }  ?>
              </select>
            </td>
            
            <td height="30"  class="Verdana11"><label>
            <input type="checkbox" name="requested" id="requested" value="1" <?php if ($req == 1){ echo ' checked="checked"' ;} ?>/>
            Requested</label></td>
            </tr>
        </table>   </td>
      </tr>
      <tr>
        <td width="2">&nbsp;</td>
        <td height="30" colspan="2" class="Verdana11">
        
            <?php echo 'Made by: ' . $staff . ' on ' . $datemade ; if ($chstaff != '       ') {echo ' Changed by ' . $chstaff . ' on ' . $chdate ;} ?></td>
        <td width="80" height="30" align="right">Staff&nbsp;&nbsp;</td>
        <td height="30" colspan="2">
<select name="staff" id="staff">
			<option value="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;???</option>
			<?php while ($row_STAFF = mysqli_fetch_assoc($STAFF)) { ?>
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
     <input name="save" class="button" type="submit" value="SAVE" onSubmit="checknames"/>
     <input name="delete" class="button" type="submit" value="DELETE" onSubmit="doublecheck"/>
     <input name="cancel" class="button" type="reset" value="CANCEL" onclick="self.close();"/>
    </td>
  </tr>
</table>
</form>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>