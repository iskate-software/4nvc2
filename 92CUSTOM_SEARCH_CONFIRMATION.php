<?php 
session_start();
require_once('../../tryconnection.php');

mysqli_select_db($tryconnection, $database_tryconnection);

$allclients = 1 ;
if ($_GET['startname'] == 'A       '){
 $startname = " ";
}
else {
 $allclients = 2 ;
 $startname = $_GET['startname'];
}

$endname = $_GET['endname'] ;
if (!($endname == 'Zzzzzzzzzz' && $allclients == 1 )) {$allclients = 2 ;}

// flip them if they don't know the alphabet...
if ($startname > $endname) {$dummy = $startname ; $startname = $endname; $endname = $dummy ;}

$skipref = $_GET['skipref'] ;

$search = $_GET['searchterms'];

$_SESSION['startname'] = $startname ;
$_SESSION['endname'] = $endname ;
$_SESSION['skipref'] = $skipref ;
$_SESSION['search'] = $search ;
$_SESSION['typeof'] = 'CUSTOM' ;

$_SESSION['species'] = implode(',',$_GET['species']) ;

$write_species = "" ;
if (strpos($_SESSION['species'],'1') !== FALSE) {$write_species = $write_species . 'Canine,' ;}
if (strpos($_SESSION['species'],'2') !== FALSE) {$write_species = $write_species . ' Feline,' ;}
if (strpos($_SESSION['species'],'3') !== FALSE) {$write_species = $write_species . ' Equine,' ;}
if (strpos($_SESSION['species'],'4') !== FALSE) {$write_species = $write_species . ' Bovine,' ;}
if (strpos($_SESSION['species'],'5') !== FALSE) {$write_species = $write_species . ' Caprine,' ;}
if (strpos($_SESSION['species'],'6') !== FALSE) {$write_species = $write_species . ' Porcine,' ;}
if (strpos($_SESSION['species'],'7') !== FALSE) {$write_species = $write_species . ' Avian,' ;}
if (strpos($_SESSION['species'],'8') !== FALSE) {$write_species = $write_species . ' Other,' ;}
// and get rid of the last comma.
$write_species = substr($write_species,0,sizeof($write_species) -2) ;

if (isset($_SESSION['newtable'])){
$xtable = $_SESSION['newtable'];
}
else {
$xtable = $_SESSION['oldtable'];
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/DVMBasicTemplate.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>CONFIRM CUSTOM SEARCH PARAMETERS</title>
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
                <li><a href="#" onclick="searchpatient()">Tatoo Numbers</a></li>
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

<form action="CUSTOM_SEARCH_RESULTS.php" name="confirmation" method="get">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr align="center">
    <td height="364" valign="bottom">
    <table width="500" border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" frame="box" rules="none">
      <tr>
        <td height="37" colspan="3" align="center" class="Verdana13B">SEARCH DEFINITION</td>
        </tr>
      <tr>
        <td width="50" class="Verdana12">&nbsp;</td>
        <td height="30" colspan="2" class="Verdana12">Custom Search: <span class="Verdana12BBlue"><?php echo $search; ?></span></td>
        </tr>
      <tr>
        <td class="Verdana12">&nbsp;</td>
        <td height="30" class="Verdana12">Client Scan: <span class="Verdana12BBlue"><?php if ($allclients == 1) { echo 'All Clients';} else {echo 'From ' . $startname . ' to ' . $endname ;} ?></span></td>
        <td width="70" class="Verdana12">&nbsp;</td>
      </tr>
      <tr>
        <td class="Verdana12">&nbsp;</td>
        <td height="30" class="Verdana12">Include Referral Clients: <span class="Verdana12BBlue"><?php if ($skipref == 1) {echo 'No';} else {echo 'Yes';} ?></span></td>
        <td class="Verdana12">&nbsp;</td>
      </tr>
      <tr>
        <td class="Verdana12">&nbsp;</td>
        <td height="30" class="Verdana12">Include Species: <span class="Verdana12BBlue"><?php echo $write_species ; ?></span></td>
        <td class="Verdana12">&nbsp;</td>
      </tr>
      <tr>
        <td class="Verdana12">&nbsp;</td>
        <td height="30" class="Verdana12">&nbsp;</td>
        <td class="Verdana12">&nbsp;</td>
      </tr>
      <tr>
        <td class="Verdana12">&nbsp;</td>
        <td height="30" class="Verdana12">Today's Date: <span class="Verdana12BBlue"><?php echo date('l j F Y'); ?></span></td>
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

<input type="hidden" name="skipref" value="<?php echo $_POST['skipref']; ?>" />
<input type="hidden" name="species" value="<?php echo implode(',',$_POST['species']); ?>" />
<input type="hidden" name="startname" value="<?php echo $_POST['startname']; ?>" />
<input type="hidden" name="endname" value="<?php echo $_POST['endname']; ?>" />

</form>	
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

