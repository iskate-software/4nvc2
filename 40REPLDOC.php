<?php 
session_start();
require_once('../tryconnection.php');

mysqli_select_db($tryconnection, $database_tryconnection);
$query_CLIENT = sprintf("SELECT * FROM ARCUSTO WHERE CUSTNO = '%s'", $_GET['client']);
$CLIENT = mysqli_query($tryconnection, $query_CLIENT) or die(mysqli_error($mysqli_link));
$row_CLIENT = mysqli_fetch_assoc($CLIENT);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/DVMBasicTemplate.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=2" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>REPLACEMENT DOCTOR</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>

<!-- InstanceBeginEditable name="head" -->

<script type="text/javascript">
function bodyonload(){
document.getElementById('inuse').innerText=localStorage.xdatabase;
}

</script>

<style type="text/css">
<!--
.table {
	border-color: #FFFFFF;
	border-style: ridge;
	border-width: 3px;
	border-collapse: separate;
	border-spacing: 1px;
	background-color:#FFFFFF;
}
.style1 {color: #FF00FF}
.style2 {color: #00FF00}



-->
</style>

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

<form action="" name="repldoc" method="POST">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" bgcolor="#B1B4FF">&nbsp;</td>
    </tr>
    <tr>
      <td height="139" align="center" bgcolor="#B1B4FF">
      <table width="720" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="15" bgcolor="#OOOOOO" class="Verdana11Bwhite" align="center">Current Schedule</td>
        </tr>
      </table>
      <table class="table" width="720" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="40" align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12">&nbsp;</td>
          <td align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12BPink style1">AM</td>
          <td align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12BBlue">PM</td>
          <td align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12B">Ev.</td>
          <td align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12BRed">Emerg.</td>
          <td align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12B style2">Hosp.</td>
        </tr>
        <tr>
          <td height="64" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="Verdana11">MD</td>
              <td class="Verdana11"><input type="text" name="textfield" id="textfield" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
            </tr>
            <tr>
              <td class="Verdana11">CD</td>
              <td class="Verdana11"><input type="text" name="textfield2" id="textfield2" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
            </tr>
            <tr>
              <td class="Verdana11">SL</td>
              <td class="Verdana11"><input type="text" name="textfield3" id="textfield3" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
            </tr>
            <tr>
              <td class="Verdana11">MD</td>
              <td class="Verdana11"><input type="text" name="textfield" id="textfield" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
            </tr>
            <tr>
              <td class="Verdana11">CD</td>
              <td class="Verdana11"><input type="text" name="textfield2" id="textfield2" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
            </tr>
            <tr>
              <td class="Verdana11">SL</td>
              <td class="Verdana11"><input type="text" name="textfield3" id="textfield3" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
            </tr>
            <tr>
              <td class="Verdana11">MD</td>
              <td class="Verdana11"><input type="text" name="textfield" id="textfield" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
            </tr>
            <tr>
              <td class="Verdana11">CD</td>
              <td class="Verdana11"><input type="text" name="textfield2" id="textfield2" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
            </tr>
            <tr>
              <td class="Verdana11">SL</td>
              <td class="Verdana11"><input type="text" name="textfield3" id="textfield3" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
            </tr>
          </table></td>
          <td align="center" valign="top" bgcolor="#FFFFFF" class="Verdana12">
          <table width="90%" border="1" cellspacing="0" cellpadding="0" bordercolor="#FF00FF" frame="box" rules="none">
            <tr>
              <td width="50" height="21"><label>
                <input type="checkbox" name="checkbox" id="checkbox" />
                apt</label></td>
              <td width="50"><input type="checkbox" name="checkbox2" id="checkbox2" /> 
                sur</td>
              <td width="50"><input type="checkbox" name="checkbox3" id="checkbox3" /> 
                D</td>
              <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" /> 
                Gv</td>
            </tr>
            <tr>
              <td height="21"><label>
                <input type="checkbox" name="checkbox" id="checkbox" />
                apt</label></td>
              <td><input type="checkbox" name="checkbox2" id="checkbox2" /> 
                sur</td>
              <td><input type="checkbox" name="checkbox3" id="checkbox3" /> 
                D</td>
              <td><input type="checkbox" name="checkbox4" id="checkbox4" /> 
                Gv</td>
            </tr>
<tr>
              <td height="21"><label>
                <input type="checkbox" name="checkbox" id="checkbox" />
                apt</label></td>
              <td><input type="checkbox" name="checkbox2" id="checkbox2" /> 
                sur</td>
              <td><input type="checkbox" name="checkbox3" id="checkbox3" /> 
                D</td>
              <td><input type="checkbox" name="checkbox4" id="checkbox4" /> 
                Gv</td>
            </tr>
            <tr>
              <td width="50" height="21"><label>
                <input type="checkbox" name="checkbox" id="checkbox" />
                apt</label></td>
              <td width="50"><input type="checkbox" name="checkbox2" id="checkbox2" /> 
                sur</td>
              <td width="50"><input type="checkbox" name="checkbox3" id="checkbox3" /> 
                D</td>
              <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" /> 
                Gv</td>
            </tr>
            <tr>
              <td height="21"><label>
                <input type="checkbox" name="checkbox" id="checkbox" />
                apt</label></td>
              <td><input type="checkbox" name="checkbox2" id="checkbox2" /> 
                sur</td>
              <td><input type="checkbox" name="checkbox3" id="checkbox3" /> 
                D</td>
              <td><input type="checkbox" name="checkbox4" id="checkbox4" /> 
                Gv</td>
            </tr>
<tr>
              <td height="21"><label>
                <input type="checkbox" name="checkbox" id="checkbox" />
                apt</label></td>
              <td><input type="checkbox" name="checkbox2" id="checkbox2" /> 
                sur</td>
              <td><input type="checkbox" name="checkbox3" id="checkbox3" /> 
                D</td>
              <td><input type="checkbox" name="checkbox4" id="checkbox4" /> 
                Gv</td>
            </tr>
            <tr>
              <td width="50" height="21"><label>
                <input type="checkbox" name="checkbox" id="checkbox" />
                apt</label></td>
              <td width="50"><input type="checkbox" name="checkbox2" id="checkbox2" /> 
                sur</td>
              <td width="50"><input type="checkbox" name="checkbox3" id="checkbox3" /> 
                D</td>
              <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" /> 
                Gv</td>
            </tr>
            <tr>
              <td height="21"><label>
                <input type="checkbox" name="checkbox" id="checkbox" />
                apt</label></td>
              <td><input type="checkbox" name="checkbox2" id="checkbox2" /> 
                sur</td>
              <td><input type="checkbox" name="checkbox3" id="checkbox3" /> 
                D</td>
              <td><input type="checkbox" name="checkbox4" id="checkbox4" /> 
                Gv</td>
            </tr>
<tr>
              <td height="21"><label>
                <input type="checkbox" name="checkbox" id="checkbox" />
                apt</label></td>
              <td><input type="checkbox" name="checkbox2" id="checkbox2" /> 
                sur</td>
              <td><input type="checkbox" name="checkbox3" id="checkbox3" /> 
                D</td>
              <td><input type="checkbox" name="checkbox4" id="checkbox4" /> 
                Gv</td>
            </tr>
          </table></td>
          <td align="center" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="90%" border="1" cellspacing="0" cellpadding="0" bordercolor="#0000FF" frame="box" rules="none">
              <tr>
                <td width="50" height="21"><label>
<input type="checkbox" name="checkbox5" id="checkbox5" />                  
apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox2" id="checkbox2" />
                  sur</td>
                <td width="50"><input type="checkbox" name="checkbox3" id="checkbox3" />
                  D</td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox2" id="checkbox2" />
                  sur</td>
                <td><input type="checkbox" name="checkbox3" id="checkbox3" />
                  D</td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox2" id="checkbox2" />
                  sur</td>
                <td><input type="checkbox" name="checkbox3" id="checkbox3" />
                  D</td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td width="50" height="21"><label>
<input type="checkbox" name="checkbox5" id="checkbox5" />                  
apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox2" id="checkbox2" />
                  sur</td>
                <td width="50"><input type="checkbox" name="checkbox3" id="checkbox3" />
                  D</td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox2" id="checkbox2" />
                  sur</td>
                <td><input type="checkbox" name="checkbox3" id="checkbox3" />
                  D</td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox2" id="checkbox2" />
                  sur</td>
                <td><input type="checkbox" name="checkbox3" id="checkbox3" />
                  D</td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td width="50" height="21"><label>
<input type="checkbox" name="checkbox5" id="checkbox5" />                  
apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox2" id="checkbox2" />
                  sur</td>
                <td width="50"><input type="checkbox" name="checkbox3" id="checkbox3" />
                  D</td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox2" id="checkbox2" />
                  sur</td>
                <td><input type="checkbox" name="checkbox3" id="checkbox3" />
                  D</td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox2" id="checkbox2" />
                  sur</td>
                <td><input type="checkbox" name="checkbox3" id="checkbox3" />
                  D</td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
          </table></td>
          <td align="center" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="90%" border="1" cellspacing="0" cellpadding="0" bordercolor="#000000" frame="box" rules="none">
              <tr>
                <td width="50" height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td width="50" height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td width="50" height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
          </table></td>
          <td align="center" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="90%" border="1" cellspacing="0" cellpadding="0" bordercolor="#CC0033" frame="box" rules="none">
              <tr>
                <td width="50" height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td width="50" height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td width="50" height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
          </table></td>
          <td align="center" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="90%" border="1" cellspacing="0" cellpadding="0" bordercolor="#00FF00" frame="box" rules="none">
              <tr>
                <td width="50" height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td width="50" height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td width="50" height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td width="50"><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
              <tr>
                <td height="21"><label>
                  <input type="checkbox" name="checkbox" id="checkbox" />
                  apt</label></td>
                <td><input type="checkbox" name="checkbox4" id="checkbox4" />
                  Gv</td>
              </tr>
          </table></td>
        </tr>
      </table>      </td>
    </tr>
    <tr>
      <td align="center" bgcolor="#B1B4FF">&nbsp;</td>
    </tr>
    <tr>
      <td height="139" align="center" bgcolor="#B1B4FF"><table width="720" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="15" bgcolor="#OOOOOO" class="Verdana11Bwhite" align="center">Change To</td>
          </tr>
        </table>
          <table class="table" width="720" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="40" align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12">&nbsp;</td>
              <td align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12BPink style1">AM</td>
              <td align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12BBlue">PM</td>
              <td align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12B">Ev.</td>
              <td align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12BRed">Emerg.</td>
              <td align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana12B style2">Hosp.</td>
            </tr>
            <tr>
              <td height="64" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="Verdana11">MD</td>
                    <td class="Verdana11"><input type="text" name="textfield3" id="textfield6" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
                  </tr>
                  <tr>
                    <td class="Verdana11">CD</td>
                    <td class="Verdana11"><input type="text" name="textfield3" id="textfield7" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
                  </tr>
                  <tr>
                    <td class="Verdana11">SL</td>
                    <td class="Verdana11"><input type="text" name="textfield3" id="textfield8" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
                  </tr>
                  <tr>
                    <td class="Verdana11">MD</td>
                    <td class="Verdana11"><input type="text" name="textfield3" id="textfield6" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
                  </tr>
                  <tr>
                    <td class="Verdana11">CD</td>
                    <td class="Verdana11"><input type="text" name="textfield3" id="textfield7" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
                  </tr>
                  <tr>
                    <td class="Verdana11">SL</td>
                    <td class="Verdana11"><input type="text" name="textfield3" id="textfield8" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
                  </tr>
                  <tr>
                    <td class="Verdana11">MD</td>
                    <td class="Verdana11"><input type="text" name="textfield3" id="textfield6" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
                  </tr>
                  <tr>
                    <td class="Verdana11">CD</td>
                    <td class="Verdana11"><input type="text" name="textfield3" id="textfield7" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
                  </tr>
                  <tr>
                    <td class="Verdana11">SL</td>
                    <td class="Verdana11"><input type="text" name="textfield3" id="textfield8" class="Input" size="1" style="width:10px;" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
                  </tr>
              </table></td>
              <td align="center" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="90%" border="1" cellspacing="0" cellpadding="0" bordercolor="#FF00FF" frame="box" rules="none">
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td width="50" class="Verdana12"><input type="checkbox" name="checkbox6" id="checkbox14" />
                    Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td class="Verdana12"><input type="checkbox" name="checkbox6" id="checkbox14" />
                    Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td class="Verdana12"><input type="checkbox" name="checkbox6" id="checkbox14" />
                    Gv</td>
                  </tr>
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td width="50" class="Verdana12"><input type="checkbox" name="checkbox6" id="checkbox14" />
                    Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td class="Verdana12"><input type="checkbox" name="checkbox6" id="checkbox14" />
                    Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td class="Verdana12"><input type="checkbox" name="checkbox6" id="checkbox14" />
                    Gv</td>
                  </tr>
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td width="50" class="Verdana12"><input type="checkbox" name="checkbox6" id="checkbox14" />
                    Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td class="Verdana12"><input type="checkbox" name="checkbox6" id="checkbox14" />
                    Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td class="Verdana12"><input type="checkbox" name="checkbox6" id="checkbox14" />
                    Gv</td>
                  </tr>
              </table></td>
              <td align="center" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="90%" border="1" cellspacing="0" cellpadding="0" bordercolor="#0000FF" frame="box" rules="none">
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox15" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox15" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox15" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox12" />
                      sur</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox13" />
                      D</td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
              </table></td>
              <td align="center" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="90%" border="1" cellspacing="0" cellpadding="0" bordercolor="#000000" frame="box" rules="none">
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
              </table></td>
              <td align="center" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="90%" border="1" cellspacing="0" cellpadding="0" bordercolor="#CC0033" frame="box" rules="none">
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
              </table></td>
              <td align="center" valign="top" bgcolor="#FFFFFF" class="Verdana12"><table width="90%" border="1" cellspacing="0" cellpadding="0" bordercolor="#00FF00" frame="box" rules="none">
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td width="50" height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td width="50"><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
                  <tr>
                    <td height="21"><label>
                      <input type="checkbox" name="checkbox6" id="checkbox11" />
                      apt</label></td>
                    <td><input type="checkbox" name="checkbox6" id="checkbox14" />
                      Gv</td>
                  </tr>
              </table></td>
            </tr>
        </table></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#B1B4FF">&nbsp;</td>
    </tr>
    <tr>
      <td align="center" bgcolor="#B1B4FF" class="ButtonsTable">
      <input name="save" type="submit" class="button" id="button" value="SAVE" />
      <input name="cancel" type="reset" class="button" id="button2" value="CANCEL" /></td>
    </tr>
  </table>
    
</form>	
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

