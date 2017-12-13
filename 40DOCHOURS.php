<?php 
session_start();
require_once('../tryconnection.php');
mysql_select_db($database_tryconnection, $tryconnection);
// At the beginning....
if (!isset($_POST["save"])) {
$query_Doctor = "SELECT * FROM DOCTOR WHERE INSTR(DOCTOR,'DVM') <> 0 OR INSTR(DOCTOR,'Dr.') <> 0 OR INSTR(DOCTOR,'D.V.M.') <> 0 ORDER BY PRIORITY";
$Doctor = mysql_query($query_Doctor, $tryconnection) or die(mysql_error());
$row_Doctor = mysqli_fetch_assoc($Doctor);
$totalRows_Doctor = mysqli_num_rows($Doctor);

$_POST['dayof'] = 0 ;
/*
echo "<script type='text/javascript'>" ;
echo "alert('Loading now')" ;
echo "</script>"; 
*/
}

// on exiting the routine.....
if (isset($_POST["save"])) {
   	$_SESSION['doctor'] = $_POST['invdoc'];
   	$_SESSION['day'] = $_POST['dayof'] ;
   	$doctor = $_POST['invdoc'];
   	$day =  $_POST['dayof'] ;
   	if ($_POST['dayof'] == 0 ) {
   	echo   	"<script type='text/javascript'>";
   	echo "alert('Please select a day') ";
   //	history.back() ;
   	echo "</script>" ;
   	unset($_POST['save']) ;
    header("Location: ../APPOINTMENTS/DOCHOURS.php");
   	}
   	else {
   echo "<script type='text/javascript' >";
   echo "window.open('HRS_EACH_DOC.php?doctor=$doctor&day=$day, width=790 height=400')";
   echo"</script>" ;
  }
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width, maximum-scale=1.5" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>SET UP DOCTORS HOURS</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../ASSETS/navigation.js">

<!-- InstanceBeginEditable name="head" -->

<style type="text/css">
</style>

<script type="text/javascript">

function bodyonload(){
}

function doctorselect(){
document.dochours.submit();
}

function checkday()
{
valid = true;
var day=document.dochours.dayof;
if (document.dochours.dayof==0){
alert ('Please select a day.');
valid = false;
}
return valid;
}


</script>

<!-- InstanceEndEditable -->

</head>

<body onload="bodyonload();" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;" >
<tr><td id="ds_calclass"></td></tr>
</table>
<script type="text/javascript" src="../ASSETS/calendar.js"></script>


<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>" class="FormDisplay" name="dochours" id="dochours" style="position:absolute; top:0px; left:0px; background-color:#FFFFFF; z-index:10" onsubmit="return checkday();">
<table width="400"  height="100" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
        <td width="400" height="35" align="center" valign="middle" class="Verdana13BBlue">DOCTOR'S HOURS</td>
    </tr>
    <tr>
    <td class="Verdana12B" align="center">First pick a day, then a doctor</td>
     </tr>
     <tr>
     <td height="10">&nbsp;</td>
     </tr>
     <tr>
      <td height="20" align="center"><table width="80%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="20" class="Verdana12"><label for = "Monday">
            <input type="radio"  name="dayof" value="1" id="Monday" />Monday
            </label></td>
          <td class="Verdana12"><label for = "Tuesday">
             <input type="radio"  name="dayof" value="2" id="Tuesday"  />Tuesday
            </label></td>
          <td class="Verdana12"><label for = "Wednesday">
             <input type="radio"  name="dayof" value="3" id="Wednesday"  />Wednesday
            </label></td>
        </tr>
        <tr>
          <td height="20" class="Verdana12"><label for = "Thursday">
            <input type="radio"  name="dayof" value="4" id="Thursday" />Thursday
            </label></td>
          <td class="Verdana12"><label for = "Friday">
             <input type="radio"  name="dayof" value="5" id="Friday"  />Friday 
            </label></td>
          <td class="Verdana12"><label for = "Saturday">
             <input type="radio"  name="dayof" value="6" id="Saturday"  />Saturday 
            </label></td>
        </tr>
        <tr align = "centre"><td class="Verdana12">&nbsp;</td>
          <td height="20" class="Verdana12"><label for = "Sunday">
             <input type="radio"  name="dayof" value="7" id="Sunday"  />Sunday 
            </label></td>
           
          <td class="Verdana12">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <!--
      -->
      <table width="400"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    
      <tr><td>&nbsp;</td></tr>
   <tr>
    <td height="" colspan="3" align="center" valign="top">
    
    
    <table class="table" width="200" height="80" border="1" cellpadding="0" cellspacing="0" >
    <tr>
    <td>
    
    <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30" colspan="3" align="center" valign="middle" class="Verdana12Blue">
    <strong>Please select a Doctor:</strong>
    <br  />
    <span class="Verdana11Grey">Doubleclick or click &amp; save.</span>
    </td>
    </tr>
  <tr>
    <td width="80%">&nbsp;</td>
    <td colspan="3">
    <select name="invdoc" size="12" id="select" ondblclick="doctorselect();">
      <?php
do {  
?>
      <option value="<?php echo $row_Doctor['DOCTOR']?>" ><?php echo $row_Doctor['DOCTOR']?></option>
      <?php
} while ($row_Doctor = mysqli_fetch_assoc($Doctor));
?>
    </select></td>
    <td width="1%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="82%" align="center" valign="top" class="Verdana11Grey"></td>
    <td width="7%">&nbsp;</td>
  </tr>
</table>
    
    </td>
    </tr>
    </table>
    
    </td>
  </tr>    
      <tr><td  height="35" align="center" class="Verdana12">Today is:&nbsp;&nbsp;<span style="background-color:#FFFF00;"><?php echo date('l F j Y'); ?></span></td>
    </tr>
    <tr>
        <td align="center" valign="middle" class="ButtonsTable">
        <input name="save" type="submit" class="button" id="save" value="SAVE" />
        <input name="button" type="button" class="button" id="button" value="CANCEL" onclick="window.opener='x'; window.close();" />
        </td>
    </tr>
</table>
</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
