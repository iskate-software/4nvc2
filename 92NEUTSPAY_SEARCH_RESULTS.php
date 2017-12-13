<?php 
session_start();
require_once('../../tryconnection.php');

mysqli_select_db($tryconnection, $database_tryconnection);

$startdate=$_POST['startdate'];

$startdate="SELECT STR_TO_DATE('$startdate','%c/%e/%Y')";
$startdate=mysqli_query($tryconnection, $startdate) or die(mysqli_error($mysqli_link));
$startdate=mysqli_fetch_array($startdate);

$enddate=$_POST['enddate'];

$enddate="SELECT STR_TO_DATE('$enddate','%c/%e/%Y')";
$enddate=mysqli_query($tryconnection, $enddate) or die(mysqli_error($mysqli_link));
$enddate=mysqli_fetch_array($enddate);


if (!empty($_POST['species'])){
$species = "";
$_POST['species'] = explode(',',$_POST['species']);
	foreach ($_POST['species'] as $spec) {
	$species = " OR PETMAST.PETTYPE = $spec ".$species;
	}
$species = substr($species, 4);
}
else {
$species = " PETMAST.PETTYPE > 0 ";
}

if (($_POST['skipref']) == '1'){
$skipref = "AND ARCUSTO.REFCLIN = '' AND ARCUSTO.REFVET = ''";
}
else {
$skipref = "";
}

if (!empty($_POST['startname'])){
$startname=$_POST['startname'];
$endname=$_POST['endname'];
$namerange = " AND COMPANY BETWEEN '$startname%' AND '$endname%'";
}
else {
$namerange = "";
}



if (isset($_SESSION['newtable'])){

$xtable = $_SESSION['newtable'];

$query_RECALL = "TRUNCATE TABLE $xtable";
$RECALL = mysqli_query($tryconnection, $query_RECALL) or die(mysqli_error($mysqli_link));

$query_RECALL = "INSERT INTO $xtable (PETID, CUSTNO, PETNAME, PETTYPE, PETBREED, PCOLOUR, PSEX, PNEUTER, PDOB, PRABDAT, POTHDAT, POTHFOR, POTH8, TITLE, CONTACT, COMPANY, ADDRESS1, ADDRESS2, CITY, STATE, ZIP, 
CAREA, CAREA2, PHONE, PHONE2, CYCLE) 
SELECT PETMAST.PETID, PETMAST.CUSTNO, PETMAST.PETNAME, PETMAST.PETTYPE, PETMAST.PETBREED, PETMAST.PCOLOUR, PETMAST.PSEX, PETMAST.PNEUTER, PETMAST.PDOB, PETMAST.PRABDAT, PETMAST.POTHDAT, PETMAST.POTHFOR, 
PETMAST.POTH8, ARCUSTO.TITLE, ARCUSTO.CONTACT, ARCUSTO.COMPANY, ARCUSTO.ADDRESS1, ARCUSTO.ADDRESS2, ARCUSTO.CITY, ARCUSTO.STATE, ARCUSTO.ZIP, ARCUSTO.CAREA, ARCUSTO.CAREA2, ARCUSTO.PHONE, ARCUSTO.PHONE2, 
PETMAST.PVACOUNT FROM PETMAST JOIN ARCUSTO ON (ARCUSTO.CUSTNO = PETMAST.CUSTNO) WHERE ARCUSTO.INACTIVE <> 1 AND PDOB >= '$startdate[0]' AND PDOB < '$enddate[0]' AND PNEUTER <> 1 
AND (PETMAST.PDEAD + PETMAST.PMOVED = 0) AND INSTR(UPPER(PETMAST.PDATA),'NO REM') = 0  AND INSTR(UPPER(ARCUSTO.COMMENT),'NO REM') = 0 AND (".$species.") $skipref $namerange";
$RECALL = mysqli_query($tryconnection, $query_RECALL) or die(mysqli_error($mysqli_link));

$select_RECALL = "SELECT * FROM $xtable";
$select_RECALL = mysqli_query($tryconnection, $select_RECALL) or die(mysqli_error($mysqli_link));
$row_RECALL = mysqli_fetch_assoc($select_RECALL);
$totalRows_RECALL = mysqli_num_rows($select_RECALL);
}

else {
$xtable = $_SESSION['oldtable'];
$select_RECALL = "SELECT * FROM $xtable";
$select_RECALL = mysqli_query($tryconnection, $select_RECALL) or die(mysqli_error($mysqli_link));
$row_RECALL = mysqli_fetch_assoc($select_RECALL);
$totalRows_RECALL = mysqli_num_rows($select_RECALL);
}


$query_REPLOG = "SELECT * FROM REPLOG WHERE TYPE='$xtable' ORDER BY LOGDTE DESC LIMIT 1";
$REPLOG = mysqli_query($tryconnection, $query_REPLOG) or die(mysqli_error($mysqli_link));
$row_REPLOG = mysqli_fetch_assoc($REPLOG);

$query_POSTCARDS = "SELECT * FROM POSTCARDS WHERE TYPE='$xtable'";
$POSTCARDS = mysqli_query($tryconnection, $query_POSTCARDS) or die(mysqli_error($mysqli_link));
$row_POSTCARDS = mysqli_fetch_assoc($POSTCARDS);


if ($xtable == 'NEUTSPAY') {
$xsearch = mysqli_real_escape_string($mysqli_link, "Eligible for neuter/spay");
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/DVMBasicTemplate.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>NEUTER/SPAY SEARCH RESULTS</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>

<!-- InstanceBeginEditable name="head" -->

<script type="text/javascript">

function bodyonload(){
document.getElementById('inuse').innerText=localStorage.xdatabase;
<?php echo $openwin; ?>
	if (document.neutspay_search_results.report[3].checked == true){
	document.getElementById('pcds').style.display="";
	}
	else {
	document.getElementById('pcds').style.display="none";
	}
document.neutspay_search_results.xsubtype[0].checked = true;
}

function showtemplate(){
var report=document.search_results.report;
	if (document.neutspay_search_results.report[3].checked == true || document.neutspay_search_results.report[4].checked == true){
	document.getElementById('pcds').style.display="";
	}
	else {
	document.getElementById('pcds').style.display="none";
	}

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

<form action="NEUTSPAY_SEARCH_RESULTS_PRINT.php" name="search_results" method="get" target="_blank">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr align="center">
    <td height="364" valign="bottom">
    <table width="600" border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" frame="box" rules="cols">
      <tr>
        <td height="37" align="center" class="Verdana13B">SEARCH RESULTS</td>
        <td height="37" align="center" class="Verdana13B">MAILING</td>
        <td align="center" class="Verdana13B">TEMPLATE</td>
      </tr>
      <tr>
        <td height="30" class="Verdana12">&nbsp;&nbsp;&nbsp;<?php echo $row_REPLOG['SEARCH']; ?></td>
        <td height="30" class="Verdana12">&nbsp;&nbsp;&nbsp;<label>
          <input type="radio" name="report" id="radio" value="Display List" <?php //sif ($row_REPLOG['REPORT']=='') {echo "checked";} ?>/>
          Display List</label></td>
        <td width="158" rowspan="7" class="Verdana12">
        <div id="pcds" style="height:190px; overflow:auto;">
			<table width="100" border="0" cellspacing="0" cellpadding="0">
              <?php do { ?>
              <tr>
                <td width="10" height="30">&nbsp;</td>
                <td><label title="<?php echo $row_POSTCARDS['MESSAGE']; ?>"><input type="radio" name="xsubtype" value="<?php echo $row_POSTCARDS['SUBTYPE']; ?>"/> <?php echo $row_POSTCARDS['SUBTYPE']; ?></label></td>
              </tr>
              <?php } while ($row_POSTCARDS = mysqli_fetch_assoc($POSTCARDS)); ?>
            </table>
        </div>
        </td>
      </tr>
      <tr>
        <td height="30" class="Verdana12">&nbsp;&nbsp;&nbsp;Client Scan: <span class="Verdana12BBlue">All Clients</span></td>
        <td width="158" class="Verdana12">&nbsp;&nbsp;&nbsp;<label>
          <input type="radio" name="report" id="radio2" value="Print List" <?php //if ($row_REPLOG['REPORT']=='') {echo "checked";} ?> onchange="showtemplate();"/>
          Print List</label></td>
        </tr>
      <tr>
        <td height="30" class="Verdana12">&nbsp;&nbsp;&nbsp;Number of Patients: <span class="Verdana12BBlue"><?php echo $totalRows_RECALL; ?></span></td>
        <td class="Verdana12">&nbsp;&nbsp;&nbsp;<label>
          <input type="radio" name="report" id="radio3" value="Labels" <?php if ($row_REPLOG['REPORT']=='Labels') {echo "checked";} ?> onchange="showtemplate();"/>
          Labels</label></td>
        </tr>
      <tr>
        <td height="30" class="Verdana12">&nbsp;</td>
        <td class="Verdana12">&nbsp;&nbsp;&nbsp;<label>
          <input type="radio" name="report" id="radio4" value="Post Cards" <?php if ($row_REPLOG['REPORT']=='Post Cards') {echo "checked";} ?> onchange="showtemplate();" />
          Post Cards</label></td>
        </tr>
      <tr>
        <td height="30" class="Verdana12"></td>
        <td class="Verdana12">&nbsp;&nbsp;&nbsp;<label>
          <input type="radio" name="report" id="radio5" value="Custom Letters" <?php if ($row_REPLOG['REPORT']=='Custom Letters') {echo "checked";} ?> onchange="showtemplate();"/>
          Custom Letters</label></td>
        </tr>
      <tr>
        <td height="30" class="Verdana12"></td>
        <td class="Verdana12">&nbsp;&nbsp;&nbsp;<label>
          <input type="radio" name="report" id="radio6" value="Merging" <?php if ($row_REPLOG['REPORT']=='Merging') {echo "checked";} ?> onchange="showtemplate();"/>
          Create Merge File</label></td>
        </tr>
      <tr>
        <td height="10" class="Verdana12">&nbsp;</td>
        <td class="Verdana12">&nbsp;</td>
        </tr>
      <tr>
        <td height="30" colspan="3" align="center" class="ButtonsTable">
        	<input name="submit" type="submit" class="button" id="submit" value="SUBMIT" />
          	<input name="cancel" type="button" class="button" id="cancel" value="CANCEL" onclick="document.location='LAST_SEARCH.php?type=<?php echo $xtable; ?>'"/>        </td>
      </tr>
    </table>    </td>
  </tr>
</table>
<input type="hidden" name="xsearch" value="<?php echo $xsearch; ?>"/>
<input type="hidden" name="xtable" value="<?php echo $xtable; ?>"  />
</form>	
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

