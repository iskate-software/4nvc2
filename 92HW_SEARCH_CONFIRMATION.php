<?php 
session_start();
require_once('../../tryconnection.php');

mysql_select_db($database_tryconnection, $tryconnection);

$get_Year = "SELECT MOD((YEAR(NOW())-1)/4,1) AS LEAP " ;
$query_Year = mysql_query($get_Year, $tryconnection) or die(mysql_error()) ;
$get_leap = mysqli_fetch_assoc($query_Year) ;
$year = $get_leap['LEAP'] ;

if ($_POST['startweek']==1){
$startday = 1;
}
else if ($_POST['startweek']==2){
$startday = 8;
}
else if ($_POST['startweek']==3){
$startday = 15;
}
else if ($_POST['startweek']==4){
$startday = 22;
}

if ($_POST['endweek']==1){
$endday = 7;
}
else if ($_POST['endweek']==2){
$endday = 14;
}

else if ($_POST['endweek']==3){
$endday = 21;
}

else if ($_POST['endweek']==4){
$month = $_POST['endmonth'] ;
if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12) {
$endday = 31;
}
else {
$endday = 30 ;
}
if ($month == 2) {
 $endday = 28 ;
  if ($year == 0.0000) {
   $endday = 29 ;
  }
}
}

 $Lastyr = "SELECT YEAR(NOW())- 1 AS Lyear" ;
 $getyr = mysql_query($Lastyr, $tryconnection) or die(mysql_error()) ;
 $getyr1 = mysqli_fetch_assoc($getyr) ;
 $rowlast = $getyr1['Lyear'] ;

$annual1 = $_POST['startmonth'].'/'.$startday.'/'.$rowlast;
$annual2 = $_POST['endmonth'].'/'.$endday.'/'.$rowlast;

if ($_POST['startmonth'] == 1) {
 unset($_POST['annual1']) ;
}
else {
list ($amonth, $aday,$ayear) = explode ('/',$annual1) ;
$avoidan1 = strftime('%Y-%m-%d',mktime(0,0,0,$amonth,$aday,$ayear)) ;
$_SESSION['annual1'] = $avoidan1 ;
list ($amonth, $aday,$ayear) = explode ('/',$annual2) ;
$avoidan2 = strftime('%Y-%m-%d',mktime(0,0,0,$amonth,$aday,$ayear)) ;
$_SESSION['annual2'] = $avoidan2 ;
}

$startyear = $_POST['startyear'];
$endyear = $_POST['endyear'];

if (empty($_POST['startname'])) {
$clrange = 'All Clients' ;
}
else {
$clrange = $_POST['startname'] . ' - ' . $_POST['endname'] ;
}
if (isset($_SESSION['newtable'])){
$xtable = $_SESSION['newtable'];
}
else {
$xtable = $_SESSION['oldtable'];
}

$skipref = $_POST['skipref'] ;

if ($xtable == 'HEARTWORM') {
	if ($_POST['typeof'] == 1) {
	  $xsearch = "Dogs previously tested between <span class='Verdana12BBlue'>$startyear - $endyear</span>";
	}
    else if ($_POST['typeof'] == 2) {
     $xsearch = "Puppies born after October 31 last year";
	}
    else if ($_POST['typeof'] == 3) {
     $xsearch = "Mature dogs new to the practice since Last October" ;
	}
    else {
     $xsearch = "Mature dogs never tested" ;
	}
    $typeof = $_POST['typeof'] ;
    $_SESSION['typeof'] = $typeof ;
    
	if ($_POST['startmonth'] != 1 ) {
	
	$ysearch = "For Annual Exams not between <span class='Verdana12BBlue'> $annual1 - $annual2</span>" ;
	}
	else {
	$ysearch = "(Regardless of when their Annual exam is due)" ;
	}
	if ($skipref == 1 ) {
	$refsearch = 'Skip Referral clients' ;
	}
	else {
	$refsearch = 'Include Referral clients' ;
	}
}


if ($_POST['media'] == 1) {$xsearch = $xsearch . ' All types of mail' ; $_SESSION['mail'] = 1 ;}
if ($_POST['media'] == 2) {$xsearch = $xsearch . ' E-mail only' ;  $_SESSION['mail'] = 2 ;}
if ($_POST['media'] == 3) {$xsearch = $xsearch . ' Regular mail only' ;  $_SESSION['mail'] = 3 ;}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/DVMBasicTemplate.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>CONFIRM PARAMETERS</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>

<!-- InstanceBeginEditable name="head" -->

<script type="text/javascript">

function bodyonload(){
document.getElementById('inuse').innerText=localStorage.xdatabase;
}

</script>
<!-- InstanceEndEditable -->
<script type="text/javascript" src="../ASSETS/navigation.js"></script>
</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion4" -->
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

<form action="HW_SEARCH_RESULTS.php" name="hwconfirmation" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr align="center">
    <td height="364" valign="bottom">
    <table width="500" border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" frame="box" rules="none">
      <tr>
        <td height="37" colspan="3" align="center" class="Verdana13B">HEARTWORM SEARCH DEFINITION</td>
        </tr>
      <tr>
        <td width="50" class="Verdana12">&nbsp;</td>
        <td height="30" colspan="2" class="Verdana12"><?php echo $xsearch; ?></td>
        </tr>
        <?php
        if ($_POST['typeof'] == 1) { 
      echo "<tr>
        <td width='50' class='Verdana12'>&nbsp;</td>
        <td height='30' colspan='2' class='Verdana12'>"; 
        echo "$ysearch; </td>
        </tr>" ;
        }
        ?>
      <tr>
        <td class="Verdana12">&nbsp;</td>
        <td height="30" class="Verdana12">Client Scan: <span class="Verdana12BBlue"><?php echo $clrange ;?></span></td>
        <td width="180" class="Verdana12">&nbsp;</td>
      </tr>
      <tr>
        <td class="Verdana12">&nbsp;</td>
        <td height="30" class="Verdana12">Referral Clients: <span class="Verdana12BBlue"><?php echo $refsearch ; ?></span></td>
        <td class="Verdana12">&nbsp;</td>
      </tr>
      <tr>
        <td class="Verdana12">&nbsp;</td>
        <td height="30" class="Verdana12">&nbsp;</td>
        <td class="Verdana12">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" colspan="3" align="center" class="ButtonsTable">
          <input name="ok" type="submit" class="button" id="ok" value="OK" />
          <input name="cancel" type="button" class="button" id="cancel" value="CANCEL" onclick="document.location='RECALLS_DIRECTORY.php'"/>
        </td>
      </tr>
    </table>    </td>
  </tr>
</table>

<input type="hidden" name="startyear" value="<?php echo $startyear; ?>" />
<input type="hidden" name="endyear" value="<?php echo $endyear; ?>" />
<input type="hidden" name="startday" value="<?php echo $startday; ?>" />
<input type="hidden" name="endday" value="<?php echo $endday; ?>" />
<input type="hidden" name="indivclient" value="<?php echo $_POST['indivclient']; ?>" />
<input type="hidden" name="skipref" value="<?php echo $_POST['skipref']; ?>" />
<input type="hidden" name="startname" value="<?php echo $_POST['startname']; ?>" />
<input type="hidden" name="endname" value="<?php echo $_POST['endname']; ?>" />

</form>	
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

