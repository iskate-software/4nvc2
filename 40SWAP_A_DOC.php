<?php
session_start();
require_once('../tryconnection.php');
mysql_select_db($database_tryconnection, $tryconnection);

$day = $_GET['day'];
$month = $_GET['month'];
$year = $_GET['year'];

$weekday = strftime('%u', mktime(0,0,0,$month,$day,$year)) ;
$full_day = strftime('%A %e %B %Y',mktime(0,0,0,$month,$day,$year)) ;
$effective_day = strftime('%Y-%m-%d',mktime(0,0,0,$month,$day,$year)) ;

$_SESSION['day'] = $day ;
$_SESSION['month'] = $month ;
$_SESSION['year'] = $year ;
$_SESSION['weekday'] = $weekday ;
$_SESSION['full_day'] = $full_day ;
$_SESSION['effective_day'] = $effective_day ;

$whoison = "SELECT DISTINCT UNIQUE1,APPTDOCS.SHORTDOC,APPTDOCS.DOCTOR,APPTDOCS.INITIALS, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY FROM APPTDOCS JOIN DOCTOR ON APPTDOCS.SHORTDOC = DOCTOR.SHORTDOC WHERE  DATEIS = '$effective_day' ORDER BY PRIORITY ASC, UNIQUE1 DESC" ;
$query_doc = mysql_query($whoison, $tryconnection) or die(mysql_error()) ;

$available = "SELECT DISTINCT DOCTOR,SHORTDOC,DOCINIT FROM DOCTOR WHERE SCHEDULE = 1 AND PRIORITY < 99 ORDER BY PRIORITY ASC" ;
$query_who = mysql_query($available, $tryconnection) or die(mysql_error()) ;

 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=2" />
<title>CHANGE APPOINTMENT DOCTORS</title>
<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>

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

<form action="SWAP_DAT_DOC" name="swap_a_doc" method="get" >

<div align="center" class="Verdana14" id="whichday" title="whichday">
  <h1 align="center">CHANGING DOCTORS' SCHEDULES</h1>
  <h2 align="center" class="Verdana14Blue">Effective Date</h2>
  <table width="310" border="1" cellspacing="0" cellpadding="0">
  
  <tr>
    <td align="center" class="Verdana14"><strong><?php echo $full_day ;?></strong></td>
  </tr>
 </table>
 </div>
 
<div align="center" class="Verdana11Blue" id="day" title="day">
<h3 align="center">Current Assignments</h3>
<table width="95%" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td width="6%">&nbsp;</td>
      <td width=12%><div align="center">&nbsp;Currently</div></td>
      <td width=8%><div align="center">&nbsp;am Appts</div></td>
      <td width=8%><div align="center">&nbsp;pm Appts</div></td>
      <td width=8%><div align="center">&nbsp;Ev Appts</div></td>
      <td width=8% colspan="2"><div align="center">&nbsp;Surg.</div></td>
      <td width=8% colspan="2"><div align="center">&nbsp;Dent.</div></td>
      <td width=8% colspan="2"><div align="center">&nbsp;Hosp.</div></td>
      <td width=12% colspan="3"><div align="center">&nbsp;External</div></td>
      <td width=5%><div align="center">&nbsp;Emerg 1</div></td>
      <td width=5%><div align="center">&nbsp;Emerg 2</div></td>
    </tr>
    
  <?php 
   while ($row_doc = mysql_fetch_assoc($query_doc)) {
   $unique1 = $row_doc['UNIQUE1'] ; 
   $i = $unique1 ;
    echo
    '<tr>
  <td width="6%"><input type="radio" name="changedoc" value="'.$row_doc['DOCTOR'].'" />&nbsp;All</form></td>
      <td width="12%">&nbsp;'.$row_doc['SHORTDOC'] .'</td>
    <td width="8%">'; if (substr($row_doc['DUTY'],0,1) > '0'){echo '<input type="checkbox" name="amappt:'.$i.'"  value="'.$row_doc['SHORTDOC'].'" />
      Col ' .substr($row_doc['DUTY'],0,1);}else {echo '&nbsp;'; } echo '</td>
    <td width="8%">'; if (substr($row_doc['DUTY'],2,1) > '0'){echo '<input type="checkbox" name="pmappt:'.$i.'"  value="'.$row_doc['SHORTDOC'].'" />
      Col ' .substr($row_doc['DUTY'],2,1);} else {echo '&nbsp;'; } echo '</td>
    <td width="8%">'; if (substr($row_doc['DUTY'],7,1) > '0'){echo '<input type="checkbox" name="evappt:'.$i.'"  value="'.$row_doc['SHORTDOC'].'" />
      Col ' .substr($row_doc['DUTY'],7,1);} else {echo '&nbsp;'; } echo '</td>
    <td width="4%">'; if (substr($row_doc['DUTY'],1,1) > '0'){echo '<input type="checkbox" name="amsurg:'.$i.'"  value="'.$row_doc['SHORTDOC'].'" />&nbsp;am' ;} else {echo '&nbsp;'; } echo '</td>
    <td width="4%">'; if (substr($row_doc['DUTY'],3,1) > '0'){echo '<input type="checkbox" name="pmsurg:'.$i.'"  value="'.$row_doc['SHORTDOC'].'" />&nbsp;pm' ;} else {echo '&nbsp;'; } echo '</td>
    <td width="4%">'; if (substr($row_doc['DUTY'],12,1) > '0'){echo '<input type="checkbox" name="amdent:'.$i.'" value="'.$row_doc['SHORTDOC'].'" />&nbsp;am' ;} else {echo '&nbsp;'; } echo '</td>
    <td width="4%">'; if (substr($row_doc['DUTY'],13,1) > '0'){echo '<input type="checkbox" name="pmdent:'.$i.'" value="'.$row_doc['SHORTDOC'].'" />&nbsp;pm' ;} else {echo '&nbsp;'; } echo '</td>
    <td width="4%">'; if (substr($row_doc['DUTY'],10,1) > '0'){echo '<input type="checkbox" name="amhosp:'.$i.'" value="'.$row_doc['SHORTDOC'].'" />
     Col ' .substr($row_doc['DUTY'],10,1);} else {echo '&nbsp;'; } echo '</td>
    <td width="4%">'; if (substr($row_doc['DUTY'],11,1) > '0'){echo '<input type="checkbox" name="pmhosp:'.$i.'" value="'.$row_doc['SHORTDOC'].'" />
     Col ' .substr($row_doc['DUTY'],11,1);} else {echo '&nbsp;'; } echo '</td>
    <td width="4%">'; if (substr($row_doc['DUTY'],5,1) > '0'){echo '<input type="checkbox" name="amex:'.$i.'"  value="'.$row_doc['SHORTDOC'].'" />&nbsp;am' ;} else {echo '&nbsp;'; } echo '</td>
    <td width="4%">'; if (substr($row_doc['DUTY'],6,1) > '0'){echo '<input type="checkbox" name="pmex:'.$i.'"  value="'.$row_doc['SHORTDOC'].'" />&nbsp;pm' ;} else {echo '&nbsp;'; } echo '</td>
    <td width="4%">'; if (substr($row_doc['DUTY'],8,1) > '0'){echo '<input type="checkbox" name="evex:'.$i.'"  value="'.$row_doc['SHORTDOC'].'" />&nbsp;pm' ;} else {echo '&nbsp;'; } echo '</td>
    <td width="5%">'; if (substr($row_doc['DUTY'],4,1) > '0'){echo '<input type="checkbox" name="emerg1:'.$i.'" value="'.$row_doc['SHORTDOC'].'" />&nbsp;pm' ;} else {echo '&nbsp;'; } echo '</td>
    <td width="5%">'; if (substr($row_doc['DUTY'],9,1) > '0'){echo '<input type="checkbox" name="emerg2:'.$i.'" value="'.$row_doc['SHORTDOC'].'" />&nbsp;pm' ;} else {echo '&nbsp;'; } echo '</td>
  </tr>' ; }  //$row_doc = mysql_fetch_assoc($query_doc)

  ?>
  <tr></tr>
  </table>
<table>
<tr height="50"><td>Replacement doctor
  <select name="replacement_doctor" id="replacement_doctor"><option value=""></option>
  <option value=" ">No Doctor</option>
  <?php while ($row_who = mysql_fetch_assoc($query_who)) { ?>
            <option value="<?php echo $row_who['SHORTDOC']; ?>"><?php echo $row_who['DOCTOR']; ?></option>
            <?php }  ?>
  </select>
  </td>
</tr>
<tr>
<td class="Verdana10">You must do one doctor at a time.</br></br>
To change an assignment, click on its checkbox. Then select a replacement doctor from the pull down menu above this message. Click on SAVE</br></br>
The replacement doctor will appear in the schedule in the same columns as the original doctor. The appointments for the original doctor will</br>
be re-assigned to the replacement doctor.</br></br>
If you want to replace more than one assignment for a doctor (but not the entire day), you must do each one individually.</br></br>
To replace the entire day for a doctor, click on the "All" radio button to select all of the duties for which that doctor is scheduled. </br></br>
If you have more than one doctor to change, do the first one, save the changes. You will be brought back to this screen to do the next one.</td>
</tr>
</table>
<table width="95%" border:1px solid green; cellspacing="0" cellpadding="0">
<tr>
    <td colspan="2" align="center" class="ButtonsTable"><input name="save" type="submit" class="button" id="save" value="SAVE" "/>
      <input name="cancel" type="button" class="button" id="cancel" value="CANCEL"  onclick="document.location='DAY.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&day=<?php echo $day ;?>&inc=0'"/></td>
      <input type="hidden" name="check" value="1"  />
    </tr>
</table>
    </div>
</body>
</html>
