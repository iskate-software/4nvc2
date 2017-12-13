<?php

require_once('../tryconnection.php');
mysql_select_db($database_tryconnection, $tryconnection);

if (!isset($_POST["save"])) {
$weekarray[0] = 'Monday' ;
$weekarray[1] = 'Tuesday' ;
$weekarray[2] = 'Wednesday' ;
$weekarray[3] = 'Thursday' ;
$weekarray[4] = 'Friday' ;
$weekarray[5] = 'Saturday' ;
$weekarray[6] = 'Sunday' ;

$day = $_GET['day'] ;

$dayx = $_GET['day'] +1 ;
$alphday = $weekarray[$day] ;

    $workdays = "SELECT STARTHOUR,STARTMIN,ENDHOUR,ENDMIN,APPTTIME FROM HOSPHOURS WHERE DAY = '$dayx' LIMIT 1" ;
    $get_hours= mysql_query($workdays, $tryconnection) or die(mysql_error()) ;
    
    $open = array() ;
    $get_rrow = mysqli_fetch_assoc($get_hours) ;
    $open[0] = $get_rrow['STARTHOUR'] ;
    $open[1] = $get_rrow['STARTMIN'] ;
    $open[2] = $get_rrow['ENDHOUR'] ;
    $open[3] = $get_rrow['ENDMIN'] ;
    $open[4] = $get_rrow['APPTTIME'] ;
}

if (isset($_POST["save"])) {
$day = $_GET['day'] ;
$dayx = $day +1 ;
$starth = $_POST['shours'] ;
$startm = $_POST['smins'] ;
$endh   = $_POST['ehours'] ;
$endm   = $_POST['emins'] ;
$dur    = $_POST['duration'] ;
$UpdHours = "UPDATE HOSPHOURS SET STARTHOUR = '$starth', STARTMIN = '$startm', ENDHOUR = '$endh', ENDMIN = '$endm', APPTTIME = '$dur' WHERE DAY = '$dayx' LIMIT 1" ;
$doSQL    = mysql_query($UpdHours, $tryconnection) or die(mysql_error()) ;
header("Location: ../APPOINTMENTS/HOURS.php");
}
   
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-UTF8" />
<meta name="viewport" content="width=device-width, maximum-scale=1.5" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>SET UP WORKING HOURS</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->

<style type="text/css">
</style>

<script type="text/javascript">

function bodyonload(){
}

</script>

<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload();" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->
<form method="post" action="" name="admin_dir" id="admin_dir" style="position:absolute; top:0px; left:0px; background-color:#FFFFFF; z-index:10">
<table width="400" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
        <td width="400" height="40" align="center" valign="middle" class="Verdana13BBlue">WORKING HOURS FOR</td>
    </tr>
    <tr>
        <td width="400" height="40" align="center" valign="middle" class="Verdana13BBlue"><?php echo $alphday; ?></td>
    </tr>
              <tr>
                <td height="1" bgcolor="#556453"></td>
              </tr>
    <tr>
        <td width="400" height="20" align="center" valign="middle" class="Verdana13BBlue">&nbsp;</td>
    </tr>
</table>
<table width="400" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
   <td height="45" class="Verdana12B">&nbsp;Opening hour:</td>
     <td height="45" class="Verdana12"><span class="Verdana11">
      <select name="shours" id="shours" class="SelectList"  >
        <option value="06"<?php if ($open[0] == 6) {echo 'selected="selected"';}?>>&nbsp;&nbsp;06</option>
        <option value="07"<?php if ($open[0] == 7) {echo 'selected="selected"';}?>>&nbsp;&nbsp;07</option>
        <option value="08"<?php if ($open[0] == 8) {echo 'selected="selected"';}?>>&nbsp;&nbsp;08</option>
        <option value="09"<?php if ($open[0] == 9) {echo 'selected="selected"';}?>>&nbsp;&nbsp;09</option>
        <option value="10"<?php if ($open[0] == 10) {echo 'selected="selected"';}?>>&nbsp;&nbsp;10</option>
        <option value="11"<?php if ($open[0] == 11) {echo 'selected="selected"';}?>>&nbsp;&nbsp;11</option>
        <option value="12"<?php if ($open[0] == 12) {echo 'selected="selected"';}?>>&nbsp;&nbsp;12</option>
        <option value="13"<?php if ($open[0] == 13) {echo 'selected="selected"';}?>>&nbsp;&nbsp;13</option>
        <option value="14"<?php if ($open[0] == 14) {echo 'selected="selected"';}?>>&nbsp;&nbsp;14</option>
      </select>
    </span></td>
    <td><span class="Verdana11"
      <select name="ampm" id="ampm">
        <option value="am">&nbsp;&nbsp;am</option>
        <option value="pm">&nbsp;&nbsp;pm</option>
      </select>
      </span></td>
    <td height="45" class="Verdana12B"> Minute</td>
    <td height="45" class="Verdana12"><span class="Verdana11">
      <select name="smins" id="smins" >
        <option value="00"<?php if ($open[1] == 0) {echo 'selected="selected"';}?>>&nbsp;&nbsp;00</option>
        <option value="05"<?php if ($open[1] == 5) {echo 'selected="selected"';}?>>&nbsp;&nbsp;05</option>
        <option value="10"<?php if ($open[1] == 10) {echo 'selected="selected"';}?>>&nbsp;&nbsp;10</option>
        <option value="15"<?php if ($open[1] == 15) {echo 'selected="selected"';}?>>&nbsp;&nbsp;15</option>
        <option value="20"<?php if ($open[1] == 20) {echo 'selected="selected"';}?>>&nbsp;&nbsp;20</option>
        <option value="25"<?php if ($open[1] == 25) {echo 'selected="selected"';}?>>&nbsp;&nbsp;25</option>
        <option value="30"<?php if ($open[1] == 30) {echo 'selected="selected"';}?>>&nbsp;&nbsp;30</option>
        <option value="35"<?php if ($open[1] == 35) {echo 'selected="selected"';}?>>&nbsp;&nbsp;35</option>
        <option value="40"<?php if ($open[1] == 40) {echo 'selected="selected"';}?>>&nbsp;&nbsp;40</option>
        <option value="45"<?php if ($open[1] == 45) {echo 'selected="selected"';}?>>&nbsp;&nbsp;45</option>
        <option value="50"<?php if ($open[1] == 50) {echo 'selected="selected"';}?>>&nbsp;&nbsp;50</option>
        <option value="55"<?php if ($open[1] == 55) {echo 'selected="selected"';}?>>&nbsp;&nbsp;55</option>
      </select></td>
     </tr>
<tr>
   <td height="45" class="Verdana12B">&nbsp;Last Appointment:</td>
     <td height="45" class="Verdana12"><span class="Verdana11">
      <select name="ehours" id="ehours" >
        <option value="11"<?php if ($open[2] == 11) {echo 'selected="selected"';}?>>&nbsp;&nbsp;11</option>
        <option value="12"<?php if ($open[2] == 12) {echo 'selected="selected"';}?>>&nbsp;&nbsp;12</option>
        <option value="13"<?php if ($open[2] == 13) {echo 'selected="selected"';}?>>&nbsp;&nbsp;1</option>
        <option value="14"<?php if ($open[2] == 14) {echo 'selected="selected"';}?>>&nbsp;&nbsp;2</option>
        <option value="15"<?php if ($open[2] == 15) {echo 'selected="selected"';}?>>&nbsp;&nbsp;3</option>
        <option value="16"<?php if ($open[2] == 16) {echo 'selected="selected"';}?>>&nbsp;&nbsp;4</option>
        <option value="17"<?php if ($open[2] == 17) {echo 'selected="selected"';}?>>&nbsp;&nbsp;5</option>
        <option value="18"<?php if ($open[2] == 18) {echo 'selected="selected"';}?>>&nbsp;&nbsp;6</option>
        <option value="19"<?php if ($open[2] == 19) {echo 'selected="selected"';}?>>&nbsp;&nbsp;7</option>
        <option value="20"<?php if ($open[2] == 20) {echo 'selected="selected"';}?>>&nbsp;&nbsp;8</option>
      </select>
    </span></td>
    <td><span class="Verdana11"
      <select name="pmam" id="pmam">
        <option value="pm">&nbsp;&nbsp;pm</option>
        <option value="am">&nbsp;&nbsp;am</option>
      </select>
      </span></td>
    <td height="45" class="Verdana12B"> Minute</td>
    <td height="45" class="Verdana12"><span class="Verdana11">
      <select name="emins" id="emins" >
        <option value="00"<?php if ($open[3] == 0) {echo 'selected="selected"';}?>>&nbsp;&nbsp;00</option>
        <option value="05"<?php if ($open[3] == 5) {echo 'selected="selected"';}?>>&nbsp;&nbsp;05</option>
        <option value="10"<?php if ($open[3] == 10) {echo 'selected="selected"';}?>>&nbsp;&nbsp;10</option>
        <option value="15"<?php if ($open[3] == 15) {echo 'selected="selected"';}?>>&nbsp;&nbsp;15</option>
        <option value="20"<?php if ($open[3] == 20) {echo 'selected="selected"';}?>>&nbsp;&nbsp;20</option>
        <option value="25"<?php if ($open[3] == 25) {echo 'selected="selected"';}?>>&nbsp;&nbsp;25</option>
        <option value="30"<?php if ($open[3] == 30) {echo 'selected="selected"';}?>>&nbsp;&nbsp;30</option>
        <option value="35"<?php if ($open[3] == 35) {echo 'selected="selected"';}?>>&nbsp;&nbsp;35</option>
        <option value="40"<?php if ($open[3] == 40) {echo 'selected="selected"';}?>>&nbsp;&nbsp;40</option>
        <option value="45"<?php if ($open[3] == 45) {echo 'selected="selected"';}?>>&nbsp;&nbsp;45</option>
        <option value="50"<?php if ($open[3] == 50) {echo 'selected="selected"';}?>>&nbsp;&nbsp;50</option>
        <option value="55"<?php if ($open[3] == 55) {echo 'selected="selected"';}?>>&nbsp;&nbsp;55</option>
      </select></td>
     </tr>
     <tr>
      <td height="45" class="Verdana12B">&nbsp;Appointment Duration:</td>
       <td height="45" class="Verdana12"><span class="Verdana11">
       <select name="duration" id="duration" >
        <option value="05"<?php if ($open[4] == 5) {echo 'selected="selected"';}?>>&nbsp;&nbsp;5</option>
        <option value="10"<?php if ($open[4] == 10) {echo 'selected="selected"';}?>>&nbsp;&nbsp;10</option>
        <option value="15"<?php if ($open[4] == 15) {echo 'selected="selected"';}?>>&nbsp;&nbsp;15</option>
        <option value="20"<?php if ($open[4] == 20) {echo 'selected="selected"';}?>>&nbsp;&nbsp;20</option>
        <option value="30"<?php if ($open[4] == 30) {echo 'selected="selected"';}?>>&nbsp;&nbsp;30</option>
        <option value="60"<?php if ($open[4] == 60) {echo 'selected="selected"';}?>>&nbsp;&nbsp;60</option>
      </select></td>
      <td height="45" class="Verdana12B">&nbsp;Minutes:</td>
      </tr>
    <tr>
        <td width="400" height="30" align="center" valign="middle" class="Verdana13BBlue">&nbsp;</td>
    </tr>
              <tr>
                <td colspan="5" height="1" bgcolor="#556453"></td>
              </tr>
    <tr>
        <td width="400" height="40" align="center" valign="middle" class="Verdana13BBlue">&nbsp;</td>
    </tr>
</table>
<table>
    <tr>
      <td width="400" height="40" align="center" class="Verdana12">Today is: &nbsp;&nbsp;<span style="background-color:#FFFF00;"><?php echo date('l m/d/Y'); ?></span></td>
    </tr>

    <tr>
        <td align="center" valign="middle" class="ButtonsTable">
        <input name="save" type="submit" class="button" id="save" value="SAVE" />
        <input name="button" type="button" class="button" id="button" value="CANCEL" onclick="history.back()" />
        </td>
    </tr>
</table>
</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>