<?php 
session_start();
require_once('../../tryconnection.php'); 
mysql_select_db($database_tryconnection, $tryconnection);

$timeformat=$_GET['timeformat'];

$dutylogid=$_GET['dutylogid'];

$query_DLOG = "SELECT *, DATE_FORMAT(TDATE, '%m/%d/%Y') AS TDATE, DATE_FORMAT(TTIME, '$timeformat') AS TTIME FROM TICKLER WHERE DUTYLOGID='$dutylogid'";
$DLOG = mysql_query($query_DLOG, $tryconnection) or die(mysql_error());
$row_DLOG = mysql_fetch_assoc($DLOG);

$patient=$row_DLOG['PETID'];
$client=$row_DLOG['CUSTNO'];

if (isset($_POST['hospital'])){
$client="0";
}
elseif (isset($_GET['patient']) || isset($_GET['client'])){
$patient=$_GET['patient'];
$client=$_GET['client'];
}

$query_CLIENT_PATIENT="SELECT * FROM ARCUSTO JOIN PETMAST ON (PETMAST.PETID = '$patient') WHERE ARCUSTO.CUSTNO='$client' LIMIT 1";
$CLIENT_PATIENT=mysql_query($query_CLIENT_PATIENT, $tryconnection) or die(mysql_error());
$row_CLIENT_PATIENT = mysql_fetch_assoc($CLIENT_PATIENT);


$query_DOCTOR = sprintf("SELECT DOCTOR, DOCINIT FROM DOCTOR ORDER BY PRIORITY ASC");
$DOCTOR = mysql_query($query_DOCTOR, $tryconnection) or die(mysql_error());
$row_DOCTOR = mysql_fetch_assoc($DOCTOR);

$query_STAFF = sprintf("SELECT STAFF, STAFFINIT FROM STAFF ORDER BY STAFF ASC");
$STAFF = mysql_query($query_STAFF, $tryconnection) or die(mysql_error());
$row_STAFF = mysql_fetch_assoc($STAFF);

////////DAYS - TRY TO FIND OUT IF IT WORKS WITHOUT THIS SCRIPT - WAS GIVING TROUBLE ONLY IN WINDOWS.
if (empty($_POST['days'])){
$days="1";
}
else {
$days=$_POST['days'];
}
////////////////////
$ttime=$_POST['ttime']." ".$_POST['ampm'];

if (isset($_POST['save']) && $_GET['dutylogid']=="0"){
if ($_POST['recur'] == 0 ){
 $recurrit = 0 ;
}
else {
$recurrit = 1 ;
}
$insertSQL = "INSERT INTO TICKLER (CUSTNO, DLPETID, REASON, TDATE, TTIME, ENTEREDBY, WHOTODO, RECUR, DAYS, CREDATE) VALUES ('".$_GET['client']."', '".$_GET['patient']."', '".mysql_real_escape_string($_POST['veryimportant'].$_POST['reason'])."', STR_TO_DATE('".$_POST['tdate']."','%m/%d/%Y'), STR_TO_DATE('$ttime','$timeformat'), '".mysql_real_escape_string($_POST['enteredby'])."', '".mysql_real_escape_string($_POST['whotodo'])."', '$recurrit', '$days', NOW())";
$Result1 = mysql_query($insertSQL, $tryconnection) or die(mysql_error());
$closewindow='self.close();';
}

elseif (isset($_POST["save"]) && $_GET["dutylogid"]!="0") {
if ($_POST['recur'] == 0  ){
 $recurrit = 0 ;
}
else {
$recurrit = 1 ;
}
$updateSQL ="UPDATE TICKLER SET REASON='".mysql_real_escape_string($_POST['veryimportant'].$_POST['reason'])."', TDATE=STR_TO_DATE('".$_POST['tdate']."','%m/%d/%Y'), TTIME=STR_TO_DATE('".$_POST['ttime']."','$timeformat'), ENTEREDBY='".$_POST['enteredby']."', WHOTODO='".$_POST['whotodo']."', RECUR='$recurrit', DAYS='$days', CREDATE=NOW() WHERE DUTYLOGID='$dutylogid'";
$Result1 = mysql_query($updateSQL, $tryconnection) or die(mysql_error());
$closewindow='self.close();';
}


elseif (isset($_POST["delete"]) && !isset($_SESSION["dutylogid"])){
$query_archivedlog="INSERT INTO TICKLERARCHIVE (DUTYLOGID, CUSTNO, DLPETID, REASON, TDATE, TTIME, ENTEREDBY, WHOTODO, RECUR, DAYS, CREDATE) SELECT DUTYLOGID, CUSTNO, DLPETID, REASON, TDATE, TTIME, ENTEREDBY, WHOTODO, RECUR, DAYS, CREDATE FROM TICKLER WHERE DUTYLOGID='$dutylogid'";
$archivedlog = mysql_query($query_archivedlog, $tryconnection) or die(mysql_error());

//STATUS D = DELETED, STATUS C = COMPLETED
$query_status="UPDATE TICKLERARCHIVE SET STATUS='D', STATUSDATE=NOW() WHERE TDATE='$row_DLOG[TDATE]' AND  DUTYLOGID='$dutylogid'";
$status=mysql_query($query_status, $tryconnection) or die(mysql_error());

$deleteSQL = "DELETE FROM TICKLER WHERE DUTYLOGID='$dutylogid'";
$delete = mysql_query($deleteSQL, $tryconnection) or die(mysql_error());
$closewindow='self.close();';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php  if ($_GET['dutylogid'] =="0"){ echo "ADD NEW DUTY LOG FOR ";  	if ($_GET['patient']=="0"){ 	echo "HOSPITAL"; 	}  	else { 	echo strtoupper($row_CLIENT_PATIENT['PETNAME']); 	}  } else { echo "EDIT DUTY LOG FOR "; 	if ($_GET['patient']=="0"){ 	echo "HOSPITAL"; 	}  	else { 	echo strtoupper($row_CLIENT_PATIENT['PETNAME']); 	}  } ?></title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->

<script type="text/javascript">

function bodyonload()
{
<?php echo $closewindow; ?>
resizeTo(540,420);
var leftpos = opener.window.screenX;
var toppos = opener.window.screenY;
moveTo(leftpos+110,toppos+50);

//var browser=navigator.userAgent;
//if (browser.match(/Firefox/i)){
//document.getElementById('reason').cols=45;
//document.getElementById('reason').rows=3;
//}
//else {
//document.getElementById('reason').cols=40;
//document.getElementById('reason').rows=4;
//}

if(localStorage.timeformat=='%H:%i'){
document.duty.ampm.disabled=true;
document.duty.ampm.style.display="hidden";
}
document.getElementById('maxnum').innerText='0';
document.duty.reason.focus();
}

function oncancel()
{
var reason = document.getElementById('reason').value;
var dutylogid="<?php echo $dutylogid; ?>";
if ((reason!=0) && (dutylogid =="0")){
	if (confirm("There is unsaved data in your duty log details field, are you sure you want to close this window?"))
		{
		self.close();
		}
	}
else {self.close();}
}


function markhospital(){
document.getElementById('custno').value="0";
document.getElementById('petid').value="0";
document.getElementById('displayhospital').style.display="";
}


function insertdays(){
document.getElementById('days').value=document.getElementById('numdays').value;
}

function selectclient(){
document.getElementById('hospitalP').checked=true;
self.close();
}

function bodyonunload()
{
var tea='<?php echo $_GET['tea']; ?>';
if (document.getElementById('hospitalP').checked && tea=="5")  {
opener.document.location='/'+localStorage.xdatabase+'/CLIENT/CLIENT_SEARCH_SCREEN.php';
}
else if (tea=="6"){
opener.parent.document.location='/'+localStorage.xdatabase+'/CLIENT/CLIENT_PATIENT_FILE.php?client='+'<?php echo $client ;?>';
opener.parent.document.location='/'+localStorage.xdatabase+'/CLIENT/CLIENT_PATIENT_FILE.php?client='+'<?php echo $client ;?>';
}
else if (tea=="4") {
opener.document.location.reload();
opener.document.location.reload();
}
else if (tea=="7") {
}
else {
opener.document.location='DUTY_LOG.php';
opener.document.location='DUTY_LOG.php';
}
}

function countchar(){
var chars=document.forms[0].reason.value.length;
document.getElementById('maxnum').innerText=chars;
	if (chars>255){
	alert('I am sorry, but your duty log instructions are too long. It\'s not my fault.');
	document.forms[0].reason.value=document.forms[0].reason.value.substr(0,254);	
	}
}
</script>
<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
<tr><td id="ds_calclass">
</td></tr>
</table>
<script type="text/javascript" src="../../ASSETS/calendar.js"></script>
<form method="post" action="" name="duty" id="duty" style="position:absolute; top:0px; left:0px; width: 6px;">
<!-- WE MIGHT NOT NEED THESE AS ALL IS INDICATED IN THE PHP SCRIPT ABOVE
<input type="hidden" name="custno" id="custno" value="<?php echo $client;?>" />
<input type="hidden" name="petid" id="petid" value="<?php echo $patient; ?>" />
-->
<table width="540" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
        <td colspan="6" align="right" class="Verdana11"></td>
    </tr>
    <tr height="5">
        <td width="10" align="center"></td>
        <td width="108" align="center"></td>
        <td colspan="4" align="right" class="Labels2"><?php echo date("m/d/Y"); ?></td>
    </tr>
    <tr>
        <td height="30" colspan="6" align="center" class="Labels">
        <label><input name="hospital" type="radio" id="hospitalH" onchange="markhospital()" value="H" checked="checked" <?php if ($patient=="0"){echo "checked";} ?>/>
        Hospital</label>
        <label><input type="radio" name="hospital" id="hospitalP" onchange="selectclient();" <?php if (empty($client)){echo "";} elseif($client!="0"){echo "checked";} ?> value="P"/>Client</label></td>
    </tr>
    <tr>
        <td height="30" colspan="6" align="center">
            <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#446441" frame="hsides" rules="none">
          <tr class="Verdana12" align="center" height="15">
                    <td align="left">&nbsp;<strong><?php echo $row_CLIENT_PATIENT['TITLE']." ".$row_CLIENT_PATIENT['CONTACT']." ".$row_CLIENT_PATIENT['COMPANY'];?>
          <span id="displayhospital" style="display:none">HOSPITAL</span>
          </strong>          </td>
          <td align="right">
          <strong><?php echo $row_CLIENT_PATIENT['PETNAME']; ?></strong>&nbsp;          </td>
                </tr>
            </table>      </td>
    </tr>
    <tr height="20">
        <td colspan="6" align="center" valign="bottom" class="Labels2">Duty log details</td>
    </tr>
  <tr>
    <td height="35" colspan="6" align="center" valign="top"><textarea name="reason" cols="50" rows="5" class="commentarea" id="reason" onkeyup="countchar()"><?php if (substr($row_DLOG['REASON'],0,1)=='*') {echo substr($row_DLOG['REASON'],1);} else {echo $row_DLOG['REASON'];} ?></textarea></td>
  </tr>
  <tr>
    <td height="10" colspan="6" align="center" valign="top" class="Verdana11Grey"># of characters: <span id="maxnum"></span> (max 255)&nbsp;&nbsp;&nbsp;&nbsp;<label class="Verdana11Red">
      <input type="checkbox" name="veryimportant" id="veryimportant" value="*" <?php if (substr($row_DLOG['REASON'],0,1)=='*') {echo "checked";} ?>/>
      Very Important</label></td>
  </tr>
  <tr height="10">
    <td colspan="6" align="center" valign="top"></td>
  </tr>
  <tr height="30">
    <td colspan="2" align="right" class="Labels2">Entered by</td>
    <td class="Labels2">
        <select name="enteredby" id="enteredby">
			<?php do { ?>
            <option value="<?php echo $row_DOCTOR['DOCINIT']; ?>" <?php if($row_DLOG['ENTEREDBY']==$row_STAFF['DOCINIT']  && !empty($row_DLOG['ENTEREDBY'])){echo "selected='selected'";} ?>><?php echo $row_DOCTOR['DOCTOR']; ?></option>
			<?php } while ($row_DOCTOR = mysql_fetch_assoc($DOCTOR)); 
			
            do { ?>
            <option value="<?php echo $row_STAFF['STAFFINIT']; ?>" <?php if($row_DLOG['WHOTODO']==$row_STAFF['STAFFINIT']  && !empty($row_DLOG['WHOTODO'])){echo "selected='selected'";} ?>><?php echo $row_STAFF['STAFF']; ?></option>
            <?php } while ($row_STAFF = mysql_fetch_assoc($STAFF)); ?>
			</select>    </td>
    <td width="70" align="right" valign="middle" class="Labels2">Date Due</td>
    <td width="114" align="left" valign="middle" class="Labels2"><input onclick="ds_sh(this);" name="tdate" type="text" class="Input" id="tdate" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if (!empty($row_DLOG['TDATE'])) {echo $row_DLOG['TDATE']; } else {echo date("m/d/Y",mktime(0,0,0,date("m"),date("d")+7,date("Y"))) ;} ?>" title="MM/DD/YYYY"/></td>
    <td width="35" rowspan="2" class="Labels2">&nbsp;</td>
  </tr>
  <tr height="30">
    <td colspan="2" align="right" class="Labels2">Staff Member</td>
    <td width="152" class="Labels2">
        <select name="whotodo" id="whotodo">
			<?php 
			$DOCTOR = mysql_query($query_DOCTOR, $tryconnection) or die(mysql_error());
			$row_DOCTOR = mysql_fetch_assoc($DOCTOR);
			do { ?>
            <option value="<?php echo $row_DOCTOR['DOCINIT']; ?>" <?php if($row_DLOG['ENTEREDBY']==$row_STAFF['DOCINIT']  && !empty($row_DLOG['ENTEREDBY'])){echo "selected='selected'";} ?>><?php echo $row_DOCTOR['DOCTOR']; ?></option>
			
			<?php } while ($row_DOCTOR = mysql_fetch_assoc($DOCTOR)); ?>

			<option value="REC" <?php if($row_DLOG['WHOTODO']=="REC"){echo "selected='selected'";} ?>>REC</option>			
			<option value="TEC" <?php if($row_DLOG['WHOTODO']=="TEC"){echo "selected='selected'";} ?>>TEC</option>			

            <?php 
			$STAFF = mysql_query($query_STAFF, $tryconnection) or die(mysql_error());
            $row_STAFF = mysql_fetch_assoc($STAFF);
            do { ?>
            <option value="<?php echo $row_STAFF['STAFFINIT']; ?>" <?php if($row_DLOG['WHOTODO']==$row_STAFF['STAFFINIT'] && !empty($row_DLOG['ENTEREDBY'])){echo "selected='selected'";} ?>><?php echo $row_STAFF['STAFF']; ?></option>
            <?php } while ($row_STAFF = mysql_fetch_assoc($STAFF)); ?>
        </select>    </td>
    <td width="70" align="right" valign="middle" class="Labels2">Time Due</td>
    <td width="114" align="left" valign="middle" class="Labels2">
    	<input name="ttime" type="text" class="Input" id="ttime" size="5" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if (!empty($row_DLOG['TTIME'])) {echo substr($row_DLOG['TTIME'],0,5);} else {echo "10:00";} ?>" title="HH:MM"/> 
        <select name="ampm" id="ampm">
       		<option value="AM" <?php if(substr($row_DLOG['TTIME'],6,2)=="AM"){echo "selected='selected'";} ?>>AM</option>
         	<option value="PM" <?php if(substr($row_DLOG['TTIME'],6,2)=="PM"){echo "selected='selected'";} ?>>PM</option>
        </select>    </td>
  </tr>
  <tr height="3">
    <td align="right"></td>
    <td align="right"></td>
    <td colspan="3"></td>
    <td></td>
  </tr>
  <tr height="70">
    <td colspan="6" align="center">
    	<table width="85%" border="1" cellpadding="0" cellspacing="0" bordercolor="#446441" frame="box" rules="none">
          <tr height="25">
            <td width="250" class="Labels2">
            <label><input type="radio" name="recur" id="radio" value="0" <?php if($row_DLOG['RECUR']=="0" || empty($row_DLOG['RECUR'])){echo "checked='checked'";} ?>/> NON RECURRING DUTY</label></td>
            <td class="Labels2">&nbsp;</td>
            <td width="34" class="Labels2">&nbsp;</td>
            <td class="Labels2">&nbsp;</td>
          </tr>
       	  <tr height="25">
          	<td class="Labels2"><label><input type="radio" name="recur" id="radio" value="1" <?php if($row_DLOG['RECUR']=="1"){echo "checked='checked'";} ?> /> RECURRING DUTY FOR EVERY </label></td>
        	<td class="Labels2"><input name="days" type="text" class="Input" id="days" size="3" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if($row_DLOG['RECUR']=="1"){echo $row_DLOG['DAYS'];} else {echo "";} ?>"/></td>
        	<td class="Labels2">Days</td>
        	<td class="Labels2">
            <select name="numdays" id="numdays" onchange="insertdays();">
                <option value=""></option>
                <option value="14">2 weeks</option>
                <option value="21">3 weeks</option>
                <option value="28">4 weeks</option>
                <option value="30">1 month</option>
                <option value="60">2 months</option>
                <option value="90">3 months</option>
                <option value="120">4 months</option>
                <option value="180">6 months</option>
                <option value="365">1 year</option>
            </select>        	</td>
        	</tr>
    	</table>    </td>
  </tr>
  <tr>
    <td height="12" colspan="6" align="center" valign="top" class="Verdana11BRed"></td>
  </tr>
  <tr>
    <td colspan="6" align="center" class="ButtonsTable">
    <input name="save" type="submit" class="button" id="save" value="SAVE"/>
    <input name="delete" type="submit" class="button" id="delete" value="DELETE" />
    <input name="cancel" type="button" class="button" id="cancel" value="CANCEL" onclick="oncancel();" /></td>
  </tr>
</table>
</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
