<?php 
session_start();
require_once('../tryconnection.php');
include("../ASSETS/tax.php");

mysql_select_db($database_tryconnection, $tryconnection);
$startday = $_GET['month'] . '/'.$_GET['day'].'/'.$_GET['year']  ;
$calday = $_GET['year'].'-'.$_GET['month'] . '-'.$_GET['day']  ;

// if the names are empty, force them to the beginning and end of the ASCII printable character table.

if (!empty($_GET['startname'])) {
$startname = $_GET['startname'] ;
}
else {
$startname = "0" ;
}
if (!empty($_GET['endname'])) {
$endname = $_GET['endname'] ;
}
else {
$endname ="Zz" ;
}


// set up species abbrev table
$species = array() ;
$q_spec = "SELECT ABBREV FROM ANIMTYPE ORDER BY ANIMALID" ;
$get_spec = mysql_query($q_spec, $tryconnection) or die(mysql_error()) ;
while ($row_spec = mysql_fetch_assoc($get_spec)) {
 $species[] = $row_spec['ABBREV'] ;
 }

$startdate1="SELECT STR_TO_DATE('$startday','%m/%d/%Y')";
$startdate2=mysql_query($startdate1, $tryconnection) or die(mysql_error());
$startdate3=mysql_fetch_array($startdate2);

$Round_about_midnight = "SELECT DATE_ADD('$startdate3[0]', INTERVAL '23:55' HOUR_MINUTE) AS LATER" ;
$Bump_it = mysql_query($Round_about_midnight, $tryconnection) or die(mysql_error()) ;
$Get_Bump = mysql_fetch_assoc($Bump_it) ;
$startdate3 = $Get_Bump['LATER'] ;

echo ' at midnight, ' . $startdate3 ;

$closemonth ="SELECT DATE_FORMAT('$startdate3', '%W %M %e %Y') " ;
$clm = mysql_query($closemonth, $tryconnection) or die(mysql_error()) ;
$clm1 = mysql_fetch_array($clm) ;
$clm2 = $clm1[0] ;

echo ' Calendar day is ' . $calday ;
echo  ' Formatted date is ' . $clm2 ;
// Gather all appt data for that date:

$Daysheet = "SELECT TIMEOF,DURATION,NAME,CONTACT,CAREA,PHONE1,PETNAME,RFPETTYPE,PSEX,PROBLEM,SHORTDOC FROM APPTS WHERE DATEOF = '$calday' AND CANCELLED <> 1 ORDER BY SHORTDOC, TIMEOF " ;
            
$Get_Data = mysql_query($Daysheet, $tryconnection) or die(mysql_error()) ;
$row_Day = mysql_fetch_assoc($Get_DATA) ;
$firstdoc = $row_Day['SHORTDOC'] ;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/DVMBasicTemplate.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>APPOINTMENT DAY SHEET<?php echo $startdate[0] ; ?></title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>

<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" type="text/css" href="../ASSETS/print.css" media="print"/>
<script type="text/javascript">

function bodyonload(){
document.getElementById('inuse').innerText=localStorage.xdatabase;

var irresults=document.getElementById('irresults');
irresults.scrollTop = irresults.scrollHeight;
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function highliteline(x,y){
document.getElementById(x).style.cursor='default';
document.getElementById(x).style.backgroundColor=y;
}

function whiteoutline(x){
document.getElementById(x).style.backgroundColor="#FFFFFF";
}


</script>

<!-- InstanceEndEditable -->
<script type="text/javascript" src="../ASSETS/navigation.js"></script>
</head>


<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion4" -->
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;" >
<tr><td id="ds_calclass"></td></tr>
</table>
<script type="text/javascript" src="../../ASSETS/calendar.js"></script>
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
<!-- InstanceBeginEditable name="DVMBasicTemplate" --><!-- InstanceBeginEditable name="DVMBasicTemplate" -->
<form name="day_sheet" method="get" action="" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr id="prthospname">
    <td colspan="7" height="30" align="center" class="Verdana13B"><script type="text/javascript">document.write(localStorage.hospname);</script>    </td>
  </td>
  <tr id="reporttitle">
    <td colspan="7" height="20" align ="center" class="Verdana13"><?php echo 'Appointments for '.$clm2.'&nbsp;&nbsp;&nbsp;&nbsp;'.' Printed on: ' .date('jS @ g:i a',time()); ; ?> </td>
    </tr>
  </tr>
  <tr height="10" bgcolor="#000000" class="Verdana11Bwhite">
    <td width="40" align="left" >Time&nbsp;</td>
    <td width="60" align="center" >Duration</td>
    <td width="160" align="center" >Client</td>
    <td width="110" align="left" >Phone</td>
    <td width="120" align="left" >Patient</td>
    <td width="180" align="left" >&nbsp;Problem</td>
    <td width="85" align="left" >Doctor&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6" class="Verdana12" align="center">
    
    <div id="irresults2">
      <table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" frame="below" rules="rows">
        <?php 
  while ($row_Day = mysql_fetch_assoc($Get_Data)) {
  if ($row_Day['SHORTDOC'] != $firstdoc) {$firstdoc = $row_Day['SHORTDOC'] ; 
     echo '<tr><td>----</td><td>------</td><td>---------------</td><td>------------</td><td>------------</td><td>-------------------</td><td>------------</td></tr>';}
   $native = $row_Day['TIMEOF'] ;
   if (substr($native,0,2) > 12 ) {
    $native = (substr($native,0,2) - 12) . substr($native,2,3) ;
   }
  echo ' 
 <tr>
    <td height="20" width="40" align="left" class="Verdana13">'.$native. '&nbsp;</td>
    <td height="20"  width="60" align="right" class="Verdana13">'.$row_Day['DURATION']. '&nbsp;[ ]&nbsp;</td>
    <td  height="20" width="170" align="left" class="Verdana13">'.$row_Day['NAME'].', '.$row_Day['CONTACT']. '</td>
    <td height="20"  width="110" align="left" class="Verdana13">'.'('.$row_Day['CAREA'].')'.$row_Day['PHONE1'] . '</td>
    <td height="20"  width="120" class="Verdana13">&nbsp;'.$row_Day['PETNAME'].' (' . $species[$row_Day['RFPETTYPE']-1].'-'.$row_Day['PSEX'].')'.'</td>
    <td height="20"  width="200" align="left" class="Verdana13">'.$row_Day['PROBLEM'].'</td>
    <td height="20"  width="95" align="right" class="Verdana13">'.$row_Day['SHORTDOC'].'</td>
      </tr>';
  }
  
  ?>
      </table>
    </div>
        
  </tr>
  <tr id="buttons">
    <td align="center" class="ButtonsTable" colspan="8  "><input name="button2" type="button" class="button" id="button2" value="FINISHED" onclick="history.back()" />
        <input name="button3" type="button" class="button" id="button3" value="PRINT" onclick="window.print();" /></td>
  </tr>
</table></td>
    </tr>
</table>
</form>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
