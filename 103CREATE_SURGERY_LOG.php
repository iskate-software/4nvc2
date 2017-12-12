<?php 

session_start();
require_once('../../tryconnection.php');
include("../../ASSETS/age.php");

if (isset($_GET['patient'])){
$patient=$_GET['patient'];
$_SESSION['patient']=$_GET['patient'];
}
elseif (isset($_SESSION['patient'])){
$patient=$_SESSION['patient'];
}

$client=$_SESSION['client'];

mysql_select_db($database_tryconnection, $tryconnection);
$query_PATIENT_CLIENT = "SELECT *, DATE_FORMAT(PDOB,'%m/%d/%Y') AS PDOB FROM PETMAST JOIN ARCUSTO ON (ARCUSTO.CUSTNO=PETMAST.CUSTNO) WHERE PETID = '$patient' LIMIT 1";
$PATIENT_CLIENT = mysql_query($query_PATIENT_CLIENT, $tryconnection) or die(mysql_error());
$row_PATIENT_CLIENT = mysql_fetch_assoc($PATIENT_CLIENT);

$psex=$row_PATIENT_CLIENT['PSEX'] ;

$pdob=$row_PATIENT_CLIENT['PDOB'];

$pweight= $row_PATIENT_CLIENT['PWEIGHT'] ;

$petname=$row_PATIENT_CLIENT['PETNAME'] ;
$_SESSION['petname'] = $petname ;

$query_DOCTOR = sprintf("SELECT DOCTOR FROM DOCTOR WHERE SIGNEDIN='1' ORDER BY PRIORITY ASC");
$DOCTOR = mysql_query($query_DOCTOR, $tryconnection) or die(mysql_error());
$row_DOCTOR = mysql_fetch_assoc($DOCTOR);

$query_STAFF = sprintf("SELECT STAFF FROM STAFF WHERE SIGNEDIN='1' ORDER BY PRIORITY ASC");
$STAFF = mysql_query($query_STAFF, $tryconnection) or die(mysql_error());
$row_STAFF = mysql_fetch_assoc($STAFF);

mysql_select_db($database_tryconnection, $tryconnection);

if (isset($_POST['check'])) {
 $query_PREFER="SELECT TRTMCOUNT FROM PREFER LIMIT 1";
 $PREFER= mysql_query($query_PREFER, $tryconnection) or die(mysql_error());
 $row_PREFER = mysql_fetch_assoc($PREFER);

 $treatmxx=$client/$row_PREFER['TRTMCOUNT'];
 $treatmxx="TREATM".floor($treatmxx);


 if (isset($_POST['finish']) || isset($_POST['save'])){

	$query_CHECKTABLE="SELECT * FROM $treatmxx LIMIT 1";
	$CHECKTABLE= mysql_query($query_CHECKTABLE, $tryconnection) or $none=1;
	
	if (isset($none)){
	$create_TREATMXX="CREATE TABLE $treatmxx LIKE TREATM0";
	$result=mysql_query($create_TREATMXX, $tryconnection) or die(mysql_error());
	} //if (isset($none))

////////////////////////////////////////////////////////////////////
////////////////////////// PROCEDURES /////////////////////////////
//////////////////////////////////////////////////////////////////

   //. (0101000000000000)
	
	$insertSQL = "INSERT INTO $treatmxx (CUSTNO, PETID, TREATDESC, HCAT, HSUBCAT, TREATDATE, WHO) VALUE ('$_SESSION[client]', '$_SESSION[patient]', 'PROCEDURES', b'0100000000000000', '41', STR_TO_DATE('$_POST[treatdate]', '%m/%d/%Y'), '".mysql_real_escape_string($_POST['who'])."')";
	mysql_query($insertSQL, $tryconnection) or die(mysql_error());
	
			$note=array();
	
			if (strlen($_POST['treatdesc']) > 500){
			$howmany=ceil(strlen($_POST['treatdesc'])/500);
				for ($i=0; $i<($howmany*500); $i=($i/500+1)*500){
				$note[]=substr($_POST['treatdesc'],$i,500);
				}
			}
			else {
				$note[]=$_POST['treatdesc'];
			}
			
			foreach ($note as $note2){
			$insertSQL = "INSERT INTO $treatmxx (CUSTNO, PETID, TREATDESC, HCAT, HSUBCAT, WHO, TREATDATE) VALUES ('$_SESSION[client]', '$_SESSION[patient]','".mysql_real_escape_string($note2)."', b'0100000000000000', '42', '".mysql_real_escape_string($_POST['who'])."', STR_TO_DATE('$_POST[treatdate]', '%m/%d/%Y'))";
			mysql_query($insertSQL, $tryconnection) or die(mysql_error());
			}//foreach (($note as $note2)
	
 ////////////////////////////////////////////////////////////////////
 //////////////////////////// SURGERY LOG ///////////////////////////
 ////////////////////////////////////////////////////////////////////
 
 // interim values.
 $get_Byear = "SELECT YEAR(PDOB) AS SYR, MONTH(PDOB) AS SMONTH FROM PETMAST WHERE PETID = '$patient' LIMIT 1" ;
 $query_Byear = mysql_query($get_Byear, $tryconnection) or die(mysql_error()) ;
 $row_Byear = mysql_fetch_assoc($query_Byear) ;
 $syr = $row_Byear['SYR'] ;
 $smon = $row_Byear['SMONTH'] ;
 
// $now_get = "SELECT YEAR(STR_TO_DATE('$_POST[treatdate]','%m/%d/%Y')) AS NOWYR, MONTH(STR_TO_DATE('$_POST[treatdate]','%m/%d/%Y')) AS NOWMTH" ;
 $now_get = "SELECT YEAR(NOW()) AS NOWYR, MONTH(NOW()) AS NOWMTH" ;
 $yea_mysql = mysql_query($now_get, $tryconnection) or die(mysql_error()) ;
 $row_convert = mysql_fetch_array($yea_mysql) ;
 
 $nowyr = $row_convert[0] ;
 $nowmth = $row_convert[1] ;
 
 $syr1 = $nowyr - $syr ;
 $smon1 = $nowmth - $smon ;
 if ($smon1 < 0 ) {
   $smon1 = $smon1 + 12 ;
   $syr1 = $syr1 - 1 ;
 }
 
    $set_up = "INSERT INTO SURGLOG (INVDTE,PETID,CUSTNO,WEIGHT,CONDPRE,CONDPOST,TIME,INVDOC,SYEAR,SMONTH,SWHODID,SWHOMON,SWHOREC,COMMENT)  
               VALUES (STR_TO_DATE('$_POST[treatdate]', '%m/%d/%Y'), '$patient', '$client', '$_POST[weight]', '$_POST[PreCC]', '$_POST[PostCC]', '$_POST[stime]',
               '".mysql_real_escape_string($_POST['who'])."', '$syr1', '$smon1', '".mysql_real_escape_string($_POST['whodid'])."', '".mysql_real_escape_string($_POST['whomon'])."',
               '".mysql_real_escape_string($_POST['whorec'])."', '".mysql_real_escape_string($_POST['treatdesc'])."')";
               
    $Logit = mysql_query($set_up, $tryconnection) or die(mysql_error()) ;
    
 } //if (isset($_POST['finish'])|| isset($_POST['save']))
 if (isset($_POST['save']) ) {
 
  header("Location:CREATE_LOGS_DIRECTORY.php") ; 
  
 }
 else {
 
 header("Location:CREATE_LOGS_DIRECTORY.php") ; 
  // header("Location:CREATE_NARCOTIC_LOG.php");
 }
} //if (isset($_POST['check'] ))
else {
 $closewin="" ;
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=1.2" />
<title>CREATE SURGERY LOG</title>


<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>

<script type="text/javascript">

function bodyonload(){
<?php 
echo $closewin; 
?>
}

function bodyonunload() {
}

function checknames() {
var who=document.add_s_log.who ;
var whodid = document.add_s_log.whodid ;
var whomon = document.add_s_log.whomon ;
var whorec = document.add_s_log.whorec ;
valid = true;
 if (document.add_s_log.who.options[0].selected==true){
		alert ("Please enter supervising doctor/'s name.");
		valid = false;
	}
 if (document.add_s_log.whodid.options[0].selected==true){
		alert ("Please enter executing doctor/'s name.");
		valid = false;
	}
 if (document.add_s_log.whomon.options[0].selected==true){
		alert ("Please enter monitor/'s name.");
		valid = false;
	}
 if (document.add_s_log.whorec.options[0].selected==true){
		alert ("Please enter scribe/'s name.");
		valid = false;
	}
return valid;
}

</script>

<style type="text/css">

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

</style>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>
</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion4" -->
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;" >
<tr><td id="ds_calclass"></td></tr>
</table>
<script type="text/javascript" src="../../ASSETS/calendar.js">
</script>
<!-- InstanceBeginEditable name="HOME" -->
<div id="LogoHead" onclick="window.open('/'+localStorage.xdatabase+'/INDEX.php','_self');" onmouseover="CursorToPointer(this.id)" title="Home">DVM
</div>
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
<form action="" name="add_s_log" method="post" onsubmit="return checknames();">
    
<table class="table" width="733" height="50" border="0" cellpadding="0" cellspacing="0">
 <tr>
      <td height="60" colspan="3" valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
        <td width="59%" height="15" align="left" class="Verdana12B">
        <span style="background-color:#FFFF00">
        <script type="text/javascript">document.write(sessionStorage.custname);</script>
        </span>
        </td>
        <td width="22%" rowspan="2" valign="middle" align="center">
        <span class="Verdana11">
        <script type="text/javascript">document.write(sessionStorage.custterm);</script>          
        </span>
                </td>
        <td width="19%" colspan="2" rowspan="4" align="center">
        <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table2">
            <tr>
              <td>
              <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="18" colspan="2" align="center"><span class="Verdana11B" style="background-color:#FFFF00"><?php echo date('m/d/Y'); ?></span></td>
                  </tr>
                  <tr>
                    <td width="41%" height="18" align="right" class="Labels2">        
					<script type="text/javascript">document.write(sessionStorage.custprevbal);</script></td>
                    <td width="59%" height="18" class="Labels2">&nbsp;Balance</td>
                  </tr>
                  <tr>
                    <td height="18" align="right" class="Labels2">
                    <script type="text/javascript">document.write(sessionStorage.custcurbal);</script></td>
                    <td height="18" class="Labels2">&nbsp;Deposit</td>
                  </tr>
              </table>
              </td>
            </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td height="15" align="left" class="Labels2">        
		<script type="text/javascript">document.write(sessionStorage.custphone);</script></td>
      </tr>
      <tr bgcolor="<?php if ($psex == 'M')  {echo '#DBEBF0';} else {echo '#F9DEE9';}; ?>">
        <td height="15" colspan="2" align="left"  class="Labels2"><span class="Verdana12B" style="background-color:#FFFF00">&nbsp;<script type="text/javascript">document.write(sessionStorage.petname);</script>
</span>        <script type="text/javascript">document.write(sessionStorage.desco);</script>         </td>
      </tr>
      <tr bgcolor="<?php if ($psex =='M') {echo '#DBEBF0';} else {echo '#F9DEE9';}; ?>" >
        <td height="15" colspan="2" align="left" class="Labels2">
        <script type="text/javascript">document.write(sessionStorage.desct);</script> (<?php agecalculation($tryconnection,$pdob); ?>)		
        </td>
      </tr>
    </table>    
    </td>
      </tr>
 <!--- -->
    
        <table class="table" width="733" height="2" border="1" cellpadding="0" cellspacing="0" >  
          <tr>
            <td width="28%" height="1" class="Verdana11"></td>
            <td width="12%"></td>
            <td width="20%"></td>
            <td width="40%"></td>
            </tr>
    <tr>
    <td align="left" class="Labels2">Doctor: <select name="who">
        <option>???</option>
            <?php do { ?>
		<option value="<?php echo $row_DOCTOR['DOCTOR']; ?>"><?php echo $row_DOCTOR['DOCTOR']; ?></option>
          <?php } while ($row_DOCTOR = mysql_fetch_assoc($DOCTOR)); ?>
          </select>        </td>
    <td class="Labels2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:</td>
    <td align="left" class="Labels2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="treatdate" type="text" class="Input" id="treatdate" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php echo date("m/d/Y"); ?>" onclick="ds_sh(this)" /></td>
    <td class="Labels">&nbsp;Surgery Time (mins) <input name = "stime" type="text" class="Input" id="stime" size="10" maxlength="3" value="0" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" />
                </td>
  </tr>
             <tr class="Verdana11" height="55">
 
<td>&nbsp;Patient Weight&nbsp;<input name="weight" type="text" class="Input" id="weight" size="10" maxlength="6" value="<?php echo $pweight ;?>" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" /></td>
    <td>&nbsp;Pre&nbsp;<select name="PreCC">
    <option value="C1">C1</option>
    <option value="C2">C2</option>
    <option value="C3">C3</option>
    <option value="C4">C4</option></select></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;and Post&nbsp;<select name="PostCC">
    <option value="P1">P1</option>
    <option value="P2">P2</option>
    <option value="P3">P3</option>
    <option value="P4">P4</option></select></td>
    <td>&nbsp;Condition Codes</td>
    </td>
  </tr>
  </table>
  <table class="table"width="733" height ="20" border="1" cellpadding="0" cellspacing="0" >
   <tr>
    <td width="20%" class="Verdana11">Procedure:</td>
    
    <td width="80%" class="Labels"><input type="button" name="view" id="view" value="VIEW" onclick="window.open('../../INVOICE/COMMENTS/LOG_COMMENTS_LIST.php?path=LABEL&display=INVOICE','_blank')" /></td>
    
    <!-- <td width="80%" class="Labels"><input name="procedure" type="text" class="Labels2" id="treatdesc" size="10" maxlength="25" value=" " onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" /></td> -->
    </tr>
    </table>
    <table>
    <tr>
    <td width="10%"></td>
    <td width="80%"></td>
    <td width="10%"></td>
    </tr>
    <td>&nbsp;</td>
    <td>
    <textarea name="treatdesc" cols="90" rows="16"  class="commentarea" ></textarea></td>
    <td>&nbsp;</td>
    </tr
    </table>
    <table>
    <tr class="Verdana11">
    <td width="9%"></td>
    <td width="30%"></td>
    <td width="29%"></td>
    <td width="29%"></td>
    <td width="3%"></td>
    </tr>
    <td>&nbsp;</td>
    <td align="left" class="Labels2">Who performed?<select name="whodid">
        <option>???</option>
            <?php 
               $DOCTOR = mysql_query($query_DOCTOR, $tryconnection) or die(mysql_error()); 
               while ($row_DOCTOR = mysql_fetch_assoc($DOCTOR)) { ?>
		      <option value="<?php echo $row_DOCTOR['DOCTOR']; ?>"><?php echo $row_DOCTOR['DOCTOR']; ?></option>
              <?php }  ?>
              </select>        
              </td>
    <td align="left" class="Labels2">Who monitored?<select name="whomon">
        <option>???</option>
            <?php $DOCTOR = mysql_query($query_DOCTOR, $tryconnection) or die(mysql_error()); 
               while ($row_DOCTOR = mysql_fetch_assoc($DOCTOR)) { ?>
		<option value="<?php echo $row_DOCTOR['DOCTOR']; ?>"><?php echo $row_DOCTOR['DOCTOR']; ?></option>
          <?php }  ?>
          
            <?php $STAFF = mysql_query($query_STAFF, $tryconnection) or die(mysql_error());
                  while($row_STAFF = mysql_fetch_assoc($STAFF)) { ?>
		<option value="<?php echo $row_STAFF['STAFF']; ?>"><?php echo $row_STAFF['STAFF']; ?></option>
          <?php }  ?>
          </select>        
          </td>
    <td align="left" class="Labels2">Who recorded?<select name="whorec">
        <option>???</option>
            <?php $DOCTOR = mysql_query($query_DOCTOR, $tryconnection) or die(mysql_error()); 
               while ($row_DOCTOR = mysql_fetch_assoc($DOCTOR)) { ?>
		<option value="<?php echo $row_DOCTOR['DOCTOR']; ?>"><?php echo $row_DOCTOR['DOCTOR']; ?></option>
          <?php }  ?>
          
        <?php $STAFF = mysql_query($query_STAFF, $tryconnection) or die(mysql_error());
              while ($row_STAFF = mysql_fetch_assoc($STAFF)) { ?>
		<option value="<?php echo $row_STAFF['STAFF']; ?>"><?php echo $row_STAFF['STAFF']; ?></option>
          <?php }  ?>
          </select>        
          </td>
          <td>&nbsp;</td>
          </tr>
          </table>
          <table class="table"width="733" border="1" cellpadding="0" cellspacing="0" >
          <tr>
          <td width="5%"></td>
          <td width="30%"></td>
          <td width="30%"></td>
          <td width="30%"></td>
          <td width="5%"></td>
          </tr>
        <tr height="35" colspan="5" align="center" valign="middle" bgcolor="#B1B4FF">
        <td>&nbsp;</td>
        <td><input name="save" class="button" type="submit" value="+ NARCOTIC"/></td>
        <td><input name="finish" class="button" type="submit" value="SAVE"/></td>
        <td><input name="cancel" class="button" type="button" value="CANCEL" onclick="history.back()";/></td>
        <td>&nbsp;</td>        
        <input type="hidden" name="petname" id="petname" />
        <input type="hidden" name="check" value="1"/>
        </tr>
        </table>
    </tr>
 <!--  -->
</form>
</div>
</body>
</html>
