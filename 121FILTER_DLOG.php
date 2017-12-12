<?php
session_start();
require_once('../../tryconnection.php');

mysql_select_db($database_tryconnection, $tryconnection);

$query_DOCTOR = sprintf("SELECT * FROM DOCTOR ORDER BY DOCTOR ASC");
$DOCTOR = mysql_query($query_DOCTOR, $tryconnection) or die(mysql_error());
$row_DOCTOR = mysql_fetch_assoc($DOCTOR);

$query_STAFF = sprintf("SELECT * FROM STAFF ORDER BY STAFF ASC");
$STAFF = mysql_query($query_STAFF, $tryconnection) or die(mysql_error());
$row_STAFF = mysql_fetch_assoc($STAFF);

if (isset($_POST['save'])){

	if (!empty($_POST['company'])){
	$_SESSION['company']=$_POST['company'];
	}
	else {
	unset($_SESSION['company']);
	}

	if (!empty($_POST['contact'])){
	$_SESSION['contact']=$_POST['contact'];
	}
	else {
	unset($_SESSION['contact']);
	}

	if (!empty($_POST['petname'])){
	$_SESSION['petname']=$_POST['petname'];
	}
	else {
	unset($_SESSION['petname']);
	}

	if ( $_POST['enteredby'] != 'Anybody' ){
	$_SESSION['enteredby']=$_POST['enteredby'];
	}
	else {
	unset($_SESSION['enteredby']);
	}

	if (!empty($_POST['whotodo'])){
	$_SESSION['whotodo']=$_POST['whotodo'];
	}
	else {
	unset($_SESSION['whotodo']);
	}


	if (!empty($_POST['credate'])){
	$credateconv="SELECT STR_TO_DATE('".$_POST['credate']."','%m/%d/%Y')";
	$credaterun=mysql_query($credateconv, $tryconnection) or die(mysql_error());
	$credate=mysql_fetch_array($credaterun);
	$_SESSION['credate']=$credate[0];
	}
	else {unset($_SESSION['credate']);}
	if (!empty($_POST['tdate'])){
	$tdateconv="SELECT STR_TO_DATE('".$_POST['tdate']."','%m/%d/%Y')";
	$tdaterun=mysql_query($tdateconv, $tryconnection) or die(mysql_error());
	$tdate=mysql_fetch_array($tdaterun);
	$_SESSION['tdate']=$tdate[0];
	}
	else {unset($_SESSION['tdate']);}
	
$closewindow='opener.document.location="DUTY_LOG.php";self.close();';
}

if (isset($_SESSION['credate'])){
	$credateconvback="SELECT DATE_FORMAT('".$_SESSION['credate']."', '%m/%d/%Y')";
	$credaterunback=mysql_query($credateconvback, $tryconnection) or die(mysql_error());
	$credateback=mysql_fetch_array($credaterunback);
}

if (isset($_SESSION['tdate'])){
	$tdateconvback="SELECT DATE_FORMAT('".$_SESSION['tdate']."', '%m/%d/%Y')";
	$tdaterunback=mysql_query($tdateconvback, $tryconnection) or die(mysql_error());
	$tdateback=mysql_fetch_array($tdaterunback);
}

if (isset($_POST['clear'])){
unset($_SESSION['petname']);
unset($_SESSION['contact']);
unset($_SESSION['company']);
unset($_SESSION['credate']);
unset($_SESSION['tdate']);
unset($_SESSION['enteredby']);
unset($_SESSION['whotodo']);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>FILTER DUTY LOG</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->

<script langauge="javascript">

function bodyonload()
{
<?php echo $closewindow; ?>
var leftpos = opener.window.screenX;
var toppos = opener.window.screenY;
moveTo(leftpos+165,toppos+50);

}

function bodyonunload()
{
opener.document.location="DUTY_LOG.php";
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

<form method="post" name="searching" id="searching" style="position:absolute; top:0px; left:0px;">

<?php //print_r($_SESSION); ?>

<table width="440" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
    <tr height="20" >
  <td width="39" height="34" class="Labels">&nbsp;</td>
  <td align="center" valign="bottom" class="Labels">First name</td>
  <td align="center" valign="bottom" class="Labels">Last name</td>
  <td width="40" class="Labels">&nbsp;</td>
  </tr>
<tr height="20" >
  <td class="Labels"></td>
  <td align="right" valign="top" class="Labels"><input name="contact" id="contact" value="<?php echo $_SESSION['contact']; ?>" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" size="20" class="Input" />    &nbsp;&nbsp;</td>
      <td width="181" align="left" valign="top" class="Labels">&nbsp;&nbsp;
      <input name="company" id="company" value="<?php echo $_SESSION['company']; ?>" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" size="20" class="Input" /></td>
      <td width="40" class="Labels">&nbsp;</td>
  </tr><tr height="20" valign="bottom" >
      <td width="39" align="center" class="Labels">&nbsp;</td>
      <td colspan="2" align="center" valign="bottom" class="Labels">&nbsp;Patient name</td>
      <td width="40" class="Labels">&nbsp;</td>
    </tr>
    <tr height="40" valign="top" >
      <td width="39" align="center" class="Labels">&nbsp;</td>
      <td colspan="2" align="center" class="Labels">&nbsp;&nbsp;<input name="petname" id="petname" value="<?php echo $_SESSION['petname']; ?>" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" size="20" class="Input"  /></td>
      <td width="40" align="left" class="Labels">&nbsp;</td>
    </tr>
    <tr height="20" >
      <td width="39" align="center" class="Labels">&nbsp;</td>
      <td width="180" align="center" class="Labels">Entered</td>
      <td align="center" class="Labels">Due</td>
      <td width="40" align="center" class="Labels">&nbsp;</td>
    </tr>
    <tr height="20" >
      <td width="39" align="center" class="Labels">&nbsp;</td>
      <td width="180" align="center" class="Labels"><input name="credate" id="credate" value="<?php echo $credateback[0]; ?>" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" size="10" class="Input" onclick="ds_sh(this);" title="MM/DD/YYYY"/></td>
      <td align="center" class="Labels"><input name="tdate" id="tdate" value="<?php echo $tdateback[0]; ?>" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" size="10" class="Input" onclick="ds_sh(this);" title="MM/DD/YYYY"/></td>
      <td width="40" align="center" class="Labels">&nbsp;</td>
    </tr>
    <tr height="40" valign="bottom" >
      <td width="39" align="center" class="Labels">&nbsp;</td>
      <td width="180" align="center" class="Labels">Entered by</td>
      <td align="center" class="Labels">For</td>
      <td width="40" align="center" class="Labels">&nbsp;</td>
    </tr>
    <tr height="60" valign="top" >
      <td width="39" align="center" class="Labels">&nbsp;</td>
      <td width="180" align="center" class="Labels">
        <select name="enteredby" id="enteredby">
        <option value="Anybody" selected='selected'>&nbsp;&nbsp;Anybody</option>
            <?php //if($row_DLOG['ENTEREDBY']==$row_STAFF['DOCINIT']){echo "selected='selected'";} ?>
			<?php do { ?>
            <option value="<?php echo $row_DOCTOR['DOCINIT']; ?>" ><?php echo $row_DOCTOR['DOCTOR']; ?></option>
			<?php } while ($row_DOCTOR = mysql_fetch_assoc($DOCTOR)); 
			
            do { ?>
            <option value="<?php echo $row_STAFF['STAFFINIT']; ?>" <?php if($row_DLOG['WHOTODO']==$row_STAFF['STAFFINIT']){echo "selected='selected'";} ?>><?php echo $row_STAFF['STAFF']; ?></option>
            <?php } while ($row_STAFF = mysql_fetch_assoc($STAFF)); ?>
			</select>    </td>
      
      
            <td align="center" class="Labels">
          <select name="whotodo" id="whotodo">
			<?php 
			$DOCTOR = mysql_query($query_DOCTOR, $tryconnection) or die(mysql_error());
			$row_DOCTOR = mysql_fetch_assoc($DOCTOR);
			do { ?>
            <option value="<?php echo $row_DOCTOR['DOCINIT']; ?>" <?php if($_SESSION['whotodo']==$row_DOCTOR['DOCINIT']){echo "selected='selected'";} ?>><?php echo $row_DOCTOR['DOCTOR']; ?></option>
			
			<?php } while ($row_DOCTOR = mysql_fetch_assoc($DOCTOR)); ?>

			<option value="REC" <?php if($_SESSION['whotodo']=="REC"){echo "selected='selected'";} ?>>REC</option>			
			<option value="TEC" <?php if($_SESSION['whotodo']=="TEC"){echo "selected='selected'";} ?>>TEC</option>			
			
            <?php 
            $STAFF = mysql_query($query_STAFF, $tryconnection) or die(mysql_error());
            $row_STAFF = mysql_fetch_assoc($STAFF);
            do { ?>
            <option value="<?php echo $row_STAFF['STAFFINIT']; ?>" <?php if($_SESSION['whotodo']==$row_STAFF['STAFFINIT']){echo "selected='selected'";} ?>><?php echo $row_STAFF['STAFF']; ?></option>
            <?php } while ($row_STAFF = mysql_fetch_assoc($STAFF)); ?>
        </select>    </td>
      <td width="40" align="center" class="Labels">&nbsp;</td>
    </tr>
    <tr height="20" >
      <td colspan="4" align="center" class="ButtonsTable">
      <input type="submit" name="save" id="save" value="SUBMIT" class="button" onclick="self.close();"/>
      <input type="submit" name="clear" id="clear" value="CLEAR" class="button"/>
      <input type="button" name="cancel" id="cancel" value="CANCEL" class="button" onclick="self.close();"/>      </td>
    </tr>
</table>


</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php 
mysql_free_result($STAFF);
?>
