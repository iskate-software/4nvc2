<?php 
session_start();
require_once('../../tryconnection.php');

mysqli_select_db($tryconnection, $database_tryconnection);

$olddate = new DateTime(date('m/d/Y'));
$olddate -> sub(new DateInterval('P11M'));

$oldday = date_format($olddate, 'j');

if ($oldday>=1 && $oldday <=7){
$oldday = 0;
}
else if ($oldday >= 8 && $oldday <= 14){
$oldday = 1;
}
else if ($oldday >= 15 && $oldday <= 21){
$oldday = 2;
}
else if ($oldday >= 22){
$oldday = 3;
}

$oldmonth = date_format($olddate, 'n');
$oldyear = date_format($olddate, 'Y');

if(isset($_POST["save"])) {
$_POST['startmonth'] = $_SESSION['startmonth'] ;
$_POST['startweek'] = $_SESSION['startweek'] ;
$_POST['endmonth'] = $_SESSION['endmonth'] ;
$_POST['endweek'] = $_SESSION['endweek'] ;
$_POST['startname'] = $_SESSION['startname'] ;
$_POST['endname'] = $_SESSION['endname'] ;
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/DVMBasicTemplate.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>HEARTWORM</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>

<!-- InstanceBeginEditable name="head" -->

<script type="text/javascript">

function bodyonload(){
document.getElementById('inuse').innerText=localStorage.xdatabase;

document.heartworm.startweek.options[<?php echo $oldday; ?>].selected = true;
document.heartworm.endweek.options[<?php echo $oldday; ?>].selected = true;
document.heartworm.startmonth.options[<?php echo $oldmonth-1; ?>].selected = true;
document.heartworm.endmonth.options[<?php echo $oldmonth-1; ?>].selected = true;
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

<form action="HW_SEARCH_CONFIRMATION.php" name="heartworm" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr align="center">
    <td height="240" bgcolor="#B1B4FF">
    <table width="660" border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" frame="box" rules="none">
      <tr>
        <td height="37" colspan="7" align="center" class="Verdana12B"><p>DEFINE THE SEARCH PERIOD</p>
          <p>Define the Period to exclude dogs from the search if they will get Annual Recalls anyway.</td>
        </tr>
        <tr>
        <td height="21" colspan="7" valign="top" align="center" class = "Verdana11Grey">(January to January omits this test)</td>
        </tr>
      <tr>
      </tr>
      <tr>
        <td  height="30" class="Verdana12B">&nbsp;Starting Week</td> 
        <td height="30" class="Verdana12"><span class="Verdana11">
        <select name="startweek" id="startweek"  class="SelectList" >
          <option value="1">First</option>
          <option value="2">Second</option>
          <option value="3">Third</option>
          <option value="4">Fourth</option>
                </select>
            </span></td>        
        <td class="Verdana12B">Starting Month</td>
        <td class="Verdana12"><span class="Verdana11">
          <select name="startmonth" id="startmonth"  class="SelectList">
          <option value="1">January</option>
          <option value="2">February</option>
          <option value="3">March</option>
          <option value="4">April</option>
          <option value="5">May</option>
          <option value="6">June</option>
          <option value="7">July</option>
          <option value="8">August</option>
          <option value="9">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
                </select>
                </span></td>
        <td class="Verdana12B">Ending Week</td>        
        <td class="Verdana12"><span class="Verdana11">
        <select name="endweek" id="endweek" class="SelectList">
          <option value="1">First</option>
          <option value="2">Second</option>
          <option value="3">Third</option>
          <option value="4">Fourth</option>
                </select>
                </span></td>        
        <td class="Verdana12"><span class="Verdana11">
        <select name="endmonth" id="endmonth" class="SelectList">
          <option value="1">January</option>
          <option value="2">February</option>
          <option value="3">March</option>
          <option value="4">April</option>
          <option value="5">May</option>
          <option value="6">June</option>
          <option value="7">July</option>
          <option value="8">August</option>
          <option value="9">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option>
        </select>
        </span></td>
      </tr>
      <tr>
       <td colspan="7" height="26" align="center" class="Verdana12B">What years do you wish to include if the dogs were previously tested?</td>
      </tr>
      <tr>
      <td colspan="7" height="31" valign="top" align="center" class="Verdana11Grey">(Blanks omits this test)</td>
      </tr>
      <tr>
        <td class="Verdana12">&nbsp;</td>
        <td class="Verdana12">&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td height="30" class="Verdana12">&nbsp;&nbsp;&nbsp;Starting Year</td>
        <td class="Verdana12"><input name="startyear" type="text" class="Input" id="startyear" size="4" maxlength="4" value="<?php echo $oldyear; ?>" onfocus="InputOnFocus(this.id);" onblur="InputOnBlur(this.id);"/></td>
        <td class="Verdana12">&nbsp;&nbsp;&nbsp;Ending Year</td>
        <td class="Verdana12"><input name="endyear" type="text" class="Input" id="endyear" size="4" maxlength="4" value="<?php echo $oldyear; ?>" onfocus="InputOnFocus(this.id);" onblur="InputOnBlur(this.id);"/></td>
      </tr>
    </table></td>
  </tr>
  <tr align="center">
    <td bgcolor="#B1B4FF">
    <table width="660" border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" frame="box" rules="none">
      <tr>
        <td width="324" height="40" align="center" class="Verdana12"><label>
          <input name="indivclient" type="checkbox" id="indivclient" value="1" disabled="disabled"/>
          Individual Client</label></td>
        <td width="324" height="40" align="center" class="Verdana12"><label><input name="skipref" type="checkbox" id="skipref" checked = "checked" value="1" /> 
        Skip Referral Clients</label></td>
      </tr>
      <tr>
      <td colspan="5" height="35" align="center" class="Verdana12B">Which search do you wish to perform?</td>
      </tr>
      <tr>
        <td height="45" colspan="4" align="center">
        <span  class="Verdana11">
        <label>
        <input type="radio" name="typeof" id="maturep" value="1" onclick="typeof('1')" checked="checked" />
        Mature previously tested</label>&nbsp;&nbsp;
        <label>
        <input type="radio" name="typeof" id="puppies" value="2" onclick="typeof('2')"  />
        Puppies</label>&nbsp;&nbsp;
        <label>
        <input type="radio" name="typeof" id="maturenew" value="3" onclick="typeof('3')" />
        Mature new to practice</label>&nbsp;&nbsp;
        <label>
         <input type="radio" name="typeof" id="maturenot" value="4" onclick="typeof('4')"/>
        Mature never tested</label>&nbsp;&nbsp;
         <br />
        </span>
       </td>
        </tr>
    </table></td>
  </tr>
  <tr align="center">
		<td height="160" bgcolor="#B1B4FF">
        <table width="660" border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" frame="box" rules="none">
          <tr>
            <td height="37" colspan="5" align="center" class="Verdana13B">DEFINE CLIENT SEARCH FOR ALL CLIENTS</td>
          </tr>
          <tr>
            <td width="50" class="Verdana12">&nbsp;</td>
            <td width="118" height="30" class="Verdana12">Starting Name</td>
            <td width="104" class="Verdana12"><input name="startname" type="text" class="Input" id="startname" size="10" maxlength="10" /></td>
            <td colspan="2" class="Verdana11Grey">Fill out both fields or leave blank for all clients.</td>
            </tr>
          <tr>
            <td class="Verdana12">&nbsp;</td>
            <td height="30" class="Verdana12">Ending Name</td>
            <td class="Verdana12"><input name="endname" type="text" class="Input" id="textfield4" size="10" maxlength="10" /></td>
            <td colspan="2" class="Verdana11Grey">&nbsp;</td>
            </tr>
          <tr>
            <td width="180" class="Verdana12">&nbsp;</td>
            <td class="Verdana12"><label><input name="media" type="radio" id="alltypes" value="1" checked="checked" />
        &nbsp;All Clients</label>&nbsp;&nbsp;</td>
            <td height="30" class="Verdana12"><input name="media" type="radio" id="email" value="2" />
        &nbsp;E-mail only</label>&nbsp;&nbsp;</td>
            <td class="Verdana12"><input name="media" type="radio" id="cards" value="3"  />
        &nbsp;Regular Mail only</label>&nbsp;&nbsp;</td>
            <td width="120" class="Verdana12">&nbsp;</td>
          </tr>
        </table></td>
  </tr>
  <tr>
    <td height="30" align="center" bgcolor="#B1B4FF"></td>
  </tr>
  <tr>
  <td align="center" class="ButtonsTable">
  	<input name="save" type="submit" class="button" id="save" value="SAVE" />
    <input name="cancel" type="button" class="button" id="cancel" value="CANCEL" onclick="document.location='RECALLS_DIRECTORY.php'" />
  </td>
</tr>
</table>

</form>	
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>

