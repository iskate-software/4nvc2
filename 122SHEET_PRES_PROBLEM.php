<?php 
session_start();
require_once('../../tryconnection.php');

mysql_select_db($database_tryconnection, $tryconnection);

$query_PATIENT_CLIENT = "SELECT *, DATE_FORMAT(PDOB,'%m/%d/%Y') AS PDOB FROM PETMAST JOIN ARCUSTO ON (ARCUSTO.CUSTNO=PETMAST.CUSTNO) WHERE PETID = '$_SESSION[espatient]' LIMIT 1";
$PATIENT_CLIENT = mysql_query($query_PATIENT_CLIENT, $tryconnection) or die(mysql_error());
$row_PATIENT_CLIENT = mysqli_fetch_assoc($PATIENT_CLIENT);

$query_RECEP = "SELECT PROBLEM FROM RECEP WHERE RFPETID='$_SESSION[espatient]' LIMIT 1";
$RECEP = mysql_query($query_RECEP, $tryconnection) or die(mysql_error());
$row_RECEP = mysqli_fetch_assoc($RECEP);



if (isset($_POST['save']) && empty($row_RECEP)){
$query_insertSQL="INSERT INTO RECEP (CUSTNO, NAME, RFPETID, PETNAME, PSEX, RFPETTYPE, LOCATION, DESCRIP, FNAME, PROBLEM, AREA1, PH1, AREA2, PH2, AREA3, PH3, DATEIN, TIME, DATETIME) 
VALUES ('$row_PATIENT_CLIENT[CUSTNO]', '".mysql_real_escape_string($row_PATIENT_CLIENT['COMPANY'])."', '$row_PATIENT_CLIENT[PETID]', '".mysql_real_escape_string($row_PATIENT_CLIENT['PETNAME'])."', '$row_PATIENT_CLIENT[PSEX]', '$row_PATIENT_CLIENT[PETTYPE]', '1', 
      '".mysql_real_escape_string($row_PATIENT_CLIENT['PETBREED'])."','".mysql_real_escape_string($row_PATIENT_CLIENT[CONTACT])."','".mysql_real_escape_string($_POST['problem'])."','$row_PATIENT_CLIENT[CAREA]','$row_PATIENT_CLIENT[PHONE]','$row_PATIENT_CLIENT[CAREA2]','$row_PATIENT_CLIENT[PHONE2]','$row_PATIENT_CLIENT[CAREA3]','$row_PATIENT_CLIENT[PHONE3]',NOW(), NOW(), NOW())";
$insertSQL=mysql_query($query_insertSQL,$tryconnection) or die(mysql_error());
$closewin="self.close();";
}

else if (isset($_POST['save']) && !empty($row_RECEP)){
$query_insertSQL="UPDATE RECEP SET PROBLEM='".mysql_real_escape_string($_POST['problem'])."' WHERE RFPETID='$_SESSION[espatient]' LIMIT 1";
$insertSQL=mysql_query($query_insertSQL,$tryconnection) or die(mysql_error());
$closewin="self.close();";
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>ADMITTING INFORMATION</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->

<script type="text/javascript">

function bodyonload()
{
<?php echo $closewin; ?>
var leftpos = opener.window.screenX;
var toppos = opener.window.screenY;
moveTo(leftpos+140,toppos+140);
document.sheet_pres_problem.problem.focus();
}

function bodyonunload()
{

}

</script>


<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->
<form method="post" action="" name="sheet_pres_problem" id="sheet_pres_problem" class="FormDisplay" style="position:absolute; top:0px; left:0px;">

<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="66" align="center" valign="bottom" bgcolor="#FFFFFF" class="Verdana13B">Please enter the admitting information for<br />
      <?php echo $row_PATIENT_CLIENT['PETNAME']; ?></td>
  </tr>
  <tr>
    <td height="130" align="center" bgcolor="#FFFFFF"><textarea name="problem" cols="50" rows="7" class="commentarea" id="problem"><?php echo $row_RECEP['PROBLEM']; ?></textarea></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#FFFFFF" class="ButtonsTable">
    <input name="save" type="submit" class="button" id="button" value="SAVE" />
    <input name="close" type="button" class="button" id="button2" value="CLOSE" onclick="self.close();" /></td>
  </tr>
</table>

</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
