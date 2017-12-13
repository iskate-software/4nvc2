<?php 
session_start();
require_once('../../tryconnection.php'); 
include("../../ASSETS/age.php");
if (!empty($_GET['id'])){
$_SESSION['id']=$_GET['id'];
}

if (isset($_GET['timeformat'])){
$timeformat=$_GET['timeformat'];
$_SESSION['timeformat']=$timeformat;
}
else{
$timeformat=$_SESSION['timeformat'];
}
$timeformat = '%H:%i' ;
///////////////// ------FILTER------ ////////////////////
$company;
$contact;
$petname;
$credate;
$tdate;
$enteredby;
$whotodo;


//////////////////------------- DISPLAY ONLY HOSPITAL OR ONLY PATIENTS -------------///////////////////
if ($_GET['hospital_patient']=="ALL"){
unset($_SESSION['petname']);
unset($_SESSION['contact']);
unset($_SESSION['company']);
unset($_SESSION['credate']);
unset($_SESSION['tdate']);
unset($_SESSION['enteredby']);
unset($_SESSION['whotodo']);
$_SESSION['hospital_patient'] = $_GET['hospital_patient'];
$hospital_patient='';
} 


elseif (isset($_SESSION['hospital_patient']) || !empty($_GET['hospital_patient'])){
	if(!empty($_GET['hospital_patient'])){
	$_SESSION['hospital_patient'] = $_GET['hospital_patient'];
	}
	
	if ($_SESSION['hospital_patient']=="HOSPITAL"){
			$hospital_patient = " AND TICKLER.DLPETID='0'";	
			}
	elseif($_SESSION['hospital_patient']=="CLIENT"){
			$hospital_patient = " AND TICKLER.DLPETID!='0'";
			}
	else{
			$hospital_patient = "";
			}
}



if (isset($_SESSION['petname'])){
$petname=$_SESSION['petname'];
$left=" ";
}
else {
$left = " LEFT ";
}

if (isset($_SESSION['company']) || isset($_SESSION['contact'])){
$left2=" ";
}
else {
$left2 = " LEFT ";
}

if (isset($_SESSION['company'])){
$company =$_SESSION['company'];
}

if (isset($_SESSION['contact'])){
$contact=$_SESSION['contact'];
}

if (isset($_SESSION['credate'])){
$credate=$_SESSION['credate'];
}

if (isset($_SESSION['tdate'])){
$tdate=$_SESSION['tdate'];
}

if (isset($_SESSION['enteredby'])){
$enteredby=$_SESSION['enteredby'];
}

if (isset($_SESSION['whotodo'])){
$whotodo=$_SESSION['whotodo'];
}

//////////////////---------- SORTING -------------///////////////////
if (!empty($_GET['sortingdlog']))
{
$_SESSION['sortingdlog'] = $_GET['sortingdlog'];
$sortby = $_SESSION['sortingdlog'] . ',DUTYLOGID' ;
}
elseif (!isset($_SESSION['sortingdlog'])){
$sortby='TICKLER.TDATE' . ',DUTYLOGID';
} 
else{
$sortby = $_SESSION['sortingdlog']  . ',DUTYLOGID';
}

/////////////////////////////////////

mysqli_select_db($tryconnection, $database_tryconnection);
$query_DLOG = "SELECT *, DATE_FORMAT(CREDATE, '%m/%d/%Y $timeformat') AS CREDATE, DATE_FORMAT(TDATE, '%m/%d/%Y') AS TDATE, DATE_FORMAT(TTIME, '$timeformat') AS TTIME FROM TICKLER ".$left." JOIN  PETMAST ON (PETMAST.PETID=TICKLER.DLPETID AND PETMAST.PETNAME LIKE '$petname%') ".$left2." JOIN ARCUSTO ON (ARCUSTO.CUSTNO=TICKLER.CUSTNO AND ARCUSTO.COMPANY LIKE '$company%'AND ARCUSTO.CONTACT LIKE '$contact%')  WHERE TICKLER.TDATE <= NOW() AND TICKLER.CREDATE LIKE '$credate%' AND TICKLER.TDATE LIKE '$tdate%' AND TICKLER.ENTEREDBY LIKE '$enteredby%' AND TICKLER.WHOTODO LIKE '$whotodo%' ".$hospital_patient." ORDER BY ".$sortby." ASC";
$DLOG = mysqli_query($tryconnection, $query_DLOG) or die(mysqli_error($mysqli_link));
$row_DLOG = mysqli_fetch_assoc($DLOG);
$totalRows_DLOG = mysqli_num_rows($DLOG);


/////////////////////////////////////////

if (isset($_POST['save'])){
$delete=$_POST['complete'];

	foreach ($delete as $value){
	$query_recur="SELECT RECUR, DAYS, TDATE FROM TICKLER WHERE DUTYLOGID=".$value;
	$recur = mysqli_query($tryconnection, $query_recur) or die(mysqli_error($mysqli_link));
	$row_recur = mysqli_fetch_assoc($recur);

	$query_archivedlog="INSERT INTO TICKLERARCHIVE (DUTYLOGID, CUSTNO, DLPETID, REASON, TDATE, TTIME, ENTEREDBY, WHOTODO, RECUR, DAYS, CREDATE) SELECT DUTYLOGID, CUSTNO, DLPETID, REASON, TDATE, TTIME, ENTEREDBY, WHOTODO, RECUR, DAYS, CREDATE FROM TICKLER WHERE DUTYLOGID=".$value;
	$archivedlog = mysqli_query($tryconnection, $query_archivedlog) or die(mysqli_error($mysqli_link));
	
	//STATUS D = DELETED, STATUS C = COMPLETED
	$query_status="UPDATE TICKLERARCHIVE SET STATUS='C', STATUSDATE=NOW() WHERE TDATE='$row_recur[TDATE]' AND  DUTYLOGID=".$value;
	$status=mysqli_query($tryconnection, $query_status) or die(mysqli_error($mysqli_link));
	
	
		if ($row_recur['RECUR']=="1"){
		$oldtdate=$row_recur['TDATE'];
		$days=$row_recur['DAYS'];
		$query_recuring="UPDATE TICKLER SET TDATE=DATE_ADD('$oldtdate', INTERVAL '$days' DAY) WHERE DUTYLOGID=".$value;
		$recuring = mysqli_query($tryconnection, $query_recuring) or die(mysqli_error($mysqli_link));
}
		
		else {
		$query_deletedlog = "DELETE FROM TICKLER WHERE DUTYLOGID=".$value;
		$deletedlog = mysqli_query($tryconnection, $query_deletedlog) or die(mysqli_error($mysqli_link));
	}
	}
header("Location: DUTY_LOG.php");
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/DVMBasicTemplate.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>DUTY LOG</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>

<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">

function bodyonload(){
document.getElementById('inuse').innerText=localStorage.xdatabase;
//highlight of the criteria for sorting
document.getElementById('<?php echo $_SESSION['id']; ?>').bgColor="#666666";
}

function setsortingdlog(x,y)
{
//reloads the window with sorting criteria
self.location='DUTY_LOG.php?sortingdlog=' + x + '&id=' + y;
}

function setfilter(a)
{
//reloads the window with a filer for hospital or patient Dlog display
self.location='DUTY_LOG.php?hospital_patient=' + a;
self.location='DUTY_LOG.php?hospital_patient=' + a;
}

//self refresh every 5 minutes
setInterval("self.location.reload()", 300000);

////////NEEDS REVISION NOV 13, 2009!!!!!!!!!//////
function updatedlog(dlid, petid)
{
if (petid=='0'){
window.open('ADD_EDIT_DUTY_LOG.php?timeformat='+localStorage.timeformat+'&dutylogid='+dlid+'&patient='+petid,'_blank','width=540,height=365,status=no,scrolling=no');
}
else {
window.open('../../PATIENT/PATIENT_DETAIL.php?dutylogid='+dlid+'&patient='+petid,'_self');
}

}
</script>

<style type="text/css">
#dlog{height:504px; overflow:auto;}
</style>
<link rel="stylesheet" type="text/css" href="../../ASSETS/print.css" media="print"/>

<!-- InstanceEndEditable -->
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>
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
<!-- InstanceBeginEditable name="DVMBasicTemplate" -->
<form name="duty_log" action="" method="post" >
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr height="10" class="Verdana11Bwhite" bgcolor="#000000">
        <td width="71" id="client" onclick="setsortingdlog('ARCUSTO.COMPANY',this.id);" onmouseover="CursorToPointer(this.id)">&nbsp;Client</td>
        <td width="71" id="patient" onclick="setsortingdlog('PETMAST.PETNAME',this.id);" onmouseover="CursorToPointer(this.id)" title="Click to sort by patient name">Patient</td>
        <td width="234">&nbsp;</td>
        <td width="88" id="entered" align="center" onclick="setsortingdlog('TICKLER.CREDATE',this.id);" onmouseover="CursorToPointer(this.id)" title="Click to sort by date entered">Entered</td>
        <td width="74" id="due" align="center" onclick="setsortingdlog('TICKLER.TDATE',this.id);" onmouseover="CursorToPointer(this.id)" title="Click to sort by date due">Due</td>
        <td width="73" id="entryby" align="center" onclick="setsortingdlog('TICKLER.ENTEREDBY',this.id);" onmouseover="CursorToPointer(this.id)" title="Click to sort by who entered the duty logs">Entered By</td>
        <td width="52" id="dueby" onclick="setsortingdlog('TICKLER.WHOTODO',this.id);" onmouseover="CursorToPointer(this.id)" align="center" title="Click to sort by who the duty logs are for">For</td>
        <td width="66" id="complete" onclick="setsortingdlog('TICKLER.WHOTODO',this.id);" onmouseover="CursorToPointer(this.id)">Complete</td>
      </tr>
      <tr>
        <td height="488" colspan="8" valign="top">
        <!--onclick="window.open('<?php if ($row_DLOG['CUSTNO']==0){echo "ADD_EDIT_DUTY_LOG.php";} else {echo "../../CLIENT/CLIENT_PATIENT_FILE.php";} ?>?refID=DUTY LOG&client=<?php if ($row_DLOG['CUSTNO']==0){echo "0";} else {echo $row_DLOG['CUSTNO'];} ?>','_blank','status=no,scrolling=no')"-->
            <div id="dlog">
                <table width="100%"border="0" cellspacing="0" cellpadding="0">
                  <?php do { ?>
                    <tr bgcolor="#E1E1E1" <?php if ($row_DLOG['DUTYLOGID']==0){echo "style='display:none'";}?>>
        <td height="10" colspan="6" class="Labels2">
        <span style="background-color:#FFFF00" class="Verdana12B"  id="<?php echo ($row_DLOG['DUTYLOGID']*1.1); ?>"><?php if ($row_DLOG['CUSTNO']==0){echo "HOSPITAL";} else { echo $row_DLOG['TITLE']." ".$row_DLOG['enterdate']." ".$row_DLOG['COMPANY']; ?></span> Tel: (<?php echo $row_DLOG['CAREA'].") ".$row_DLOG['PHONE'];} ?></td>
        <td colspan="4" align="right" class="Verdana11B">
						<?php 
						if($row_DLOG['RECUR']=="1"){
							if ($row_DLOG['DAYS'] <= 7){
							echo "Recurring every ".$row_DLOG['DAYS']." days";
							}
							elseif ($row_DLOG['DAYS']=="60" || $row_DLOG['DAYS']=="90" || $row_DLOG['DAYS']=="120" || $row_DLOG['DAYS']=="180"){
							$numdays = $row_DLOG['DAYS']/30;
							echo "Recurring every $numdays months";
							}
							elseif ($row_DLOG['DAYS'] =="30"){
							echo "Recurring every month";
							}
							elseif ($row_DLOG['DAYS']=="14" || $row_DLOG['DAYS']=="21" || $row_DLOG['DAYS']=="28"){
							$numdays = $row_DLOG['DAYS']/7;
							echo "Recurring every $numdays weeks";
							}
							elseif ($row_DLOG['DAYS']=="365") {
							echo "Recurring every year";
							}
						}
							
							 ?>                        </td>
      </tr>
      <tr height="20" bgcolor="<?php if ($row_DLOG['PSEX']=='M'){echo '#DBEBF0';} else {echo '#F9DEE9';}; ?>" <?php if ($row_DLOG['DLPETID']==0){echo "style='display:none'";}?>>
                        <td>&nbsp;</td>
                        <td colspan="8" class="Labels2" ><span class="Verdana12B" onclick="window.open('ADD_EDIT_DUTY_LOG.php?timeformat='+localStorage.timeformat+'&dutylogid=0&client=<?php echo $row_DLOG['CUSTNO']; ?>&patient=<?php echo $row_DLOG['PETID']; ?>','_blank','scrolling=no,status=no')" id="<?php echo ($row_DLOG['DUTYLOGID']/10); ?>" onmouseover="CursorToPointer(this.id)" title="Click to add new duty log for this patient."><?php echo $row_DLOG['PETNAME']; ?></span> 
                        <?php if ($row_DLOG['PETTYPE']=='1'){echo "Canine";} else if ($row_DLOG['PETTYPE']=='2'){echo "Feline";} else if ($row_DLOG['PETTYPE']=='3'){echo "Equine";}else if ($row_DLOG['PETTYPE']=='4'){echo "Bovine";}else if ($row_DLOG['PETTYPE']=='5'){echo "Caprine";}else if ($row_DLOG['PETTYPE']=='6'){echo "Porcine";}else if ($row_DLOG['PETTYPE']=='7'){echo "Avian";}else if ($row_DLOG['PETTYPE']=='8'){echo "Other";} 
                        echo ", ".$row_DLOG['PETBREED'].", ".$row_DLOG['PWEIGHT']."&nbsp;".$_SESSION['weightunit'].",&nbsp;"; 
                        if ($row_DLOG['PSEX']=='M'){echo "M";} else{echo "F";} 
                        if ($row_DLOG['PNEUTER']=='1' && $row_DLOG['PSEX']=='M'){echo "(N), ";} elseif ($row_DLOG['PNEUTER']=='1' && $row_DLOG['PSEX']=='F'){echo "(S), ";} else {echo ", ";}
                        echo "age: ";
                        agecalculation($tryconnection,$row_DLOG['PDOB']); 
                        ?>                    	</td>
                    	<td width="24"></td>
                  </tr>
                  <tr height="35">
                        <td width="10">&nbsp;</td>
                        <td width="18" class="Georgia14BRed" valign="top"><?php if (substr($row_DLOG['REASON'],0,1)=='*') {echo '!';} ?></td>
                        <td valign="top" class="Verdana11" id="<?php echo $row_DLOG['DUTYLOGID']; ?>"  onmouseover="CursorToPointer(this.id);" title="Click to update this duty log" onclick="window.open('ADD_EDIT_DUTY_LOG.php?timeformat='+localStorage.timeformat+'&dutylogid=<?php echo $row_DLOG['DUTYLOGID']; ?>','_blank','status=no,scrolling=no')" ><?php if (substr($row_DLOG['REASON'],0,1)=='*') {echo substr($row_DLOG['REASON'],1);} else {echo $row_DLOG['REASON'];} if ($row_DLOG['DUTYLOGID']==0){echo "You're lucky, the duty log is empty :)";} ?></td>
                        <td width="7">&nbsp;</td>
                        <td width="85" align="center" valign="middle">
						<span class="Verdana12"><?php echo substr($row_DLOG['CREDATE'],0,10); ?></span>
                        <br  />
                        <span class="Verdana10"><?php echo substr($row_DLOG['CREDATE'],11,8); ?></span>
                        </td>
                        <td width="85" align="center" valign="middle">
                        <span class="<?php if ($row_DLOG['TDATE'] < date("m/d/Y")){echo "alerttext12";} else {echo "Verdana12Blue";} ?>"><?php echo $row_DLOG['TDATE']; ?></span>
                        <br />
                        <span class="<?php $datetime=$row_DLOG['TDATE'].$row_DLOG['TTIME']; if ( $datetime < date("m/d/Y".$timeformat)){echo "alerttext10";} else {echo "Verdana10Blue";} ?>" >
						<?php  echo $row_DLOG['TTIME']; ?> </span>
                        </td>
                        <td width="60" align="center" valign="middle" class="Verdana12"><?php echo $row_DLOG['ENTEREDBY']; ?></td>
                        <td width="60" align="center" valign="middle" class="Verdana12"><?php echo $row_DLOG['WHOTODO']; ?></td>
                        <td width="29" align="center" valign="middle"><img src="../../IMAGES/h copy.jpg" alt="h" id="<?php echo $row_DLOG['DLPETID']; ?>" width="28" height="28" onmouseover="CursorToPointer(this.id)" title="Click to enter medical history" onclick="window.open('../../PATIENT/HISTORY/REVIEW_HISTORY.php?client=<?php echo $row_DLOG['CUSTNO']; ?>&patient=<?php echo $row_DLOG['DLPETID']; ?>&dutylog=&path=3close','_blank','width=785,height=670')" <?php if ($row_DLOG['DLPETID']==0){echo "style='display:none'";}?>/></td>
                    <td align="center" valign="middle"><input type="checkbox" name="complete[]" id="complete2" value="<?php echo $row_DLOG['DUTYLOGID']; ?>" title="Tick to mark completed duties and click SAVE" <?php if ($row_DLOG['DUTYLOGID']==0){echo "style='display:none'";}?>/></td>
                  </tr>
                  <?php } while ($row_DLOG = mysqli_fetch_assoc($DLOG)); ?>
				</table>     
          </div>
		</td>
      </tr>
      <tr>
        <td colspan="8" align="center" valign="middle" class="ButtonsTable">
        <input name="save" type="submit" class="button" id="save" value="SAVE" title="Click to save duties, that you have marked as completed"/><input name="button" type="button" class="button" id="button" value="ADD NEW" onclick="window.open('ADD_EDIT_DUTY_LOG.php?timeformat='+localStorage.timeformat+'&dutylogid=0&tea=5','_blank','status=no,scrolling=no,toolbar=no')" title="Click to add new duty log"/><input name="print" type="button" class="button" id="print" value="PRINT" onclick="window.print();"/><input name="all" type="button" class="button" id="all" value="ALL" onclick="setfilter('ALL')" title="Click to display all duty logs" /><input name="patients" type="button" class="button" id="patients" value="PATIENTS" onclick="setfilter('CLIENT')" <?php if ($_SESSION['hospital_patient']=="CLIENT"){echo "disabled='disabled'";} ?> title="Click to display only duty logs for patients"/><input name="hospital" type="button" class="button" id="hospital" value="HOSPITAL" onclick="setfilter('HOSPITAL')" <?php if ($_SESSION['hospital_patient']=="HOSPITAL"){echo "disabled='disabled'";} ?> title="Click to display only duty logs for hospital"/><input name="filter" type="button" class="button" id="filter" value="FILTER" onclick="window.open('FILTER_DLOG.php','_blank','status=no,scrolling=no,toolbar=no, width=455, height=329')" title="Click to open filter to search through duty logs" /><input name="cancel" type="button" class="button" id="cancel" value="CANCEL" onclick="<?php if ($_GET['path']=='2close') {echo "self.close();";}  else {echo "window.open('../../INDEX.php','_self');";}?>"/>
        </td>
      </tr>
</table>
</form>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
