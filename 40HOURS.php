<?php 
session_start();
require_once('../tryconnection.php');
mysqli_select_db($tryconnection, $database_tryconnection);

// At the beginning....Put the Holiday file into an array, after checking to see
// if this is the first time this year that this routine has been used. In this case,
// update the bonus holidays by 364 days, and the base holidays by one year.

$get_one = "SELECT HOLIDATE,NYDATE FROM HOLIDAY ORDER BY HOLID LIMIT 1" ;
$which_one = mysqli_query($tryconnection, $get_one) or die(mysqli_error($mysqli_link)) ;
$row_date = mysqli_fetch_assoc($which_one) ;
$newyear = date('Y') ;
$lastdone = substr($row_date['HOLIDATE'],0,4) ;
if ($newyear > $lastdone) {
  $update_them = "UPDATE HOLIDAY SET HOLIDATE = NYDATE" ;
  $doit = mysql_unbuffered_query($update_them, $tryconnection) or die(mysqli_query($mysqli_link, )) ;
  $plus_364 = "UPDATE HOLIDAY SET NYDATE = DATE_ADD(NYDATE, INTERVAL 364 DAY) WHERE HOLID > 1 AND HOLID < 10 " ;
  $doit2 = mysql_unbuffered_query($plus_364, $tryconnection) or die(mysqli_error($mysqli_link)) ;
  $standards = "UPDATE HOLIDAY SET NYDATE = DATE_ADD(NYDATE,INTERVAL 1 YEAR) WHERE HOLID = 1 OR HOLID > 9 " ;
  $doit3 = mysql_unbuffered_query($standards, $tryconnection) or die(mysqli_error($mysqli_link)) ;
}
if (!isset($_POST["save"])) {
$get_all = "SELECT SHORTHOL, OBSERVED,HOLIDATE,NYDATE FROM HOLIDAY " ;
$get_hol = mysqli_query($tryconnection, $get_all) or die(mysqli_error($mysqli_link)) ;
// how many holidays? 
$howmany = "SELECT FOUND_ROWS() AS IMAX" ;
$getmax = mysqli_query($tryconnection, $howmany) or die(mysqli_error($mysqli_link)) ;
$max = mysqli_fetch_assoc($getmax) ;
$lines = $max['IMAX'] - 1 ;

$holarray = array() ;

for ($i = 0; $i<=$lines; $i++) {
    
    $get_hrow = mysqli_fetch_assoc($get_hol) ;
    $holarray[$i][0] = $get_hrow['SHORTHOL'] ;
    $holarray[$i][1] = $get_hrow['OBSERVED'] ;
    $holarray[$i][2] = $get_hrow['HOLIDATE'] ;
    list ($year, $month, $day) = explode('-',$holarray[$i][2]) ;
    $holarray[$i][2] = strftime('%m/%d/%Y',mktime(0,0,0,$month,$day,$year)) ;
    $holarray[$i][3] = $get_hrow['NYDATE'] ;
    list ($year, $month, $day) = explode('-',$holarray[$i][3]) ;
    $holarray[$i][3] = strftime('%m/%d/%Y',mktime(0,0,0,$month,$day,$year)) ;
    }
    $TOTALHOLS = $lines+1 ;
    
    $_POST['startdate0'] = $holarray[0][2] ;
    $_POST['startdate1'] = $holarray[1][2] ;
    $_POST['startdate2'] = $holarray[2][2] ;
    $_POST['startdate3'] = $holarray[3][2] ;
    $_POST['startdate4'] = $holarray[4][2] ;
    $_POST['startdate5'] = $holarray[5][2] ;
    $_POST['startdate6'] = $holarray[6][2] ;
    $_POST['startdate7'] = $holarray[7][2] ;
    $_POST['startdate8'] = $holarray[8][2] ;
    $_POST['startdate9'] = $holarray[9][2] ;
    $_POST['startdate10'] = $holarray[10][2] ;
    $_POST['startdate11'] = $holarray[11][2] ;
    $_POST['startdate12'] = $holarray[0][3] ;
    $_POST['startdate13'] = $holarray[1][3] ;
    $_POST['startdate14'] = $holarray[2][3] ;
    $_POST['startdate15'] = $holarray[3][3] ;
    $_POST['startdate16'] = $holarray[4][3] ;
    $_POST['startdate17'] = $holarray[5][3] ;
    $_POST['startdate18'] = $holarray[6][3] ;
    $_POST['startdate19'] = $holarray[7][3] ;
    $_POST['startdate20'] = $holarray[8][3] ;
    $_POST['startdate21'] = $holarray[9][3] ;
    $_POST['startdate22'] = $holarray[10][3] ;
    $_POST['startdate23'] = $holarray[11][3] ;
    

    
}

// on exiting the routine.....
if (isset($_POST["save"])) {

 function IsChecked($chkname,$value) {
 if(!empty($_POST[$chkname])) {
   foreach($_POST[$chkname] as $chkval) {
     if($chkval == $value) {
        return true;
        }
      }
    }
      return false;
   }
// New Years...   
    $obs = 0 ;
    if (IsChecked('checkbox', '1')) {
     $obs = 1 ;}
    $holi = $_POST['startdate0'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate12'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 1 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;
// Family Day..... 
    $obs = 0 ;
    if (IsChecked('checkbox', '2')) {
    $obs = 1 ;}
    $holi = $_POST['startdate1'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate13'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 2 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;
    
// Good Friday..... 
    $obs = 0 ;
    if (IsChecked('checkbox', '3')) {
    $obs = 1 ;}
    $holi = $_POST['startdate2'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate14'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 3 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;
    
// Easter Monday..... 
    $obs = 0 ;
    if (IsChecked('checkbox', '4')) {
    $obs = 1 ;}
    $holi = $_POST['startdate3'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate15'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 4 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;  
    
// Victoria day..... 
    $obs = 0 ;
    if (IsChecked('checkbox', '5')) {
    $obs = 1 ;}
    $holi = $_POST['startdate4'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate16'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 5 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;    

// Canada day..... 
    $obs = 0 ;
    if (IsChecked('checkbox', '6')) {
    $obs = 1 ;}
    $holi = $_POST['startdate5'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate17'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 6 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;

// Civic Holiday..... 
    $obs = 0 ;
    if (IsChecked('checkbox', '7')) {
    $obs = 1 ;}
    $holi = $_POST['startdate6'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate18'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 7 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;

// Labor Day..... 
    $obs = 0 ;
    if (IsChecked('checkbox', '8')) {
    $obs = 1 ;}
    $holi = $_POST['startdate7'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate19'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 8 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;

// Thanksgiving..... 
    $obs = 0 ;
    if (IsChecked('checkbox', '9')) {
    $obs = 1 ;}
    $holi = $_POST['startdate8'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate20'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 9 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;

// Christmas Day..... 
    $obs = 0 ;
    if (IsChecked('checkbox', 'A')) {
    $obs = 1 ;}
    $holi = $_POST['startdate9'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate21'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 10 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;

// Boxing Day..... 
    $obs = 0 ;
    if (IsChecked('checkbox', 'B')) {
    $obs = 1 ;}
    $holi = $_POST['startdate10'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate22'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 11 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;

// Next New Years..... 
    $obs = 0 ;
    if (IsChecked('checkbox', 'C')) {
    $obs = 1 ;}
    $holi = $_POST['startdate11'] ;
    $doit = "SELECT STR_TO_DATE('$holi','%m/%d/%Y') as holi1" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace1 = mysqli_fetch_array($replace) ; 
    $replace1 = $replace1['holi1'] ;
    
    $holi2 = $_POST['startdate23'] ;
    $doit = "SELECT STR_TO_DATE('$holi2','%m/%d/%Y') as holi2" ;
    $replace = mysqli_query($tryconnection, $doit) or die(mysqli_error($mysqli_link)) ;
    $replace2 = mysqli_fetch_array($replace) ;
    $replace2 = $replace2['holi2'] ;
    $Upd_SQL = "UPDATE HOLIDAY SET OBSERVED = '$obs', HOLIDATE = '$replace1', NYDATE = '$replace2' WHERE HOLID = 12 LIMIT 1" ;
    $Update_it  = mysqli_query($tryconnection, $Upd_SQL) or die(mysqli_error($mysqli_link)) ;
  /*
    for ($i = 0; $i<=6; $i++) {
     $starth = $open[$i][0] ;
     $startm = $open[$i][1] ; 
     $endh   = $open[$i][2] ;
     $endm   = $open[$i][3] ;
     $appts  = $open[$i][4] ;
     $dayn   = $i +1 ;
     $Updh_SQL = "UPDATE HOSPHOURS SET STARTHOUR = '$starth', STARTMIN = '$startm', ENDHOUR = '$endh', ENDMIN = '$endm', APPTTIME = '$appts' WHERE DAY = '$dayn' ";
     $Update_hrs = mysql_query($Updh_SQL) ;
 }
 */
header("Location: ../APPOINTMENTS/ADMIN_DIRECTORY.php");
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width, maximum-scale=1.5" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>SET UP WORKING DAYS AND HOLIDAYS</title>
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
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;" >
<tr><td id="ds_calclass"></td></tr>
</table>
<script type="text/javascript" src="../ASSETS/calendar.js"></script>

<form method="post" action="" name="admin_dir" id="admin_dir" style="position:absolute; top:0px; left:0px; background-color:#FFFFFF; z-index:10">
<table width="400"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
        <td width="400" height="35" align="center" valign="middle" class="Verdana13BBlue">WORKING DAYS AND HOLIDAYS</td>
    </tr>
    <tr>
      <td height="20" align="center"><table width="80%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="20" class="Verdana12"><label for = "Monday">
            <input type="button" class="button" name="button" value="Monday" id="Monday" onclick = "document.location='MONDAY.php?day=0'"/>
            </label></td>
          <td class="Verdana12"><label for = "Tuesday">
             <input type="button" class="button" name="button" value="Tuesday" id="Tuesday" onclick = "document.location='MONDAY.php?day=1'" /> 
            </label></td>
          <td class="Verdana12"><label for = "Wednesday">
             <input type="button" class="button" name="button" value="Wednesday" id="Wednesday" onclick = "document.location='MONDAY.php?day=2'" /> 
            </label></td>
        </tr>
        <tr>
          <td height="20" class="Verdana12"><label for = "Thursday">
            <input type="button" class="button" name="button" value="Thursday" id="Thursday" onclick = "document.location='MONDAY.php?day=3'"/>
            </label></td>
          <td class="Verdana12"><label for = "Friday">
             <input type="button" class="button" name="button" value="Friday" id="Friday" onclick = "document.location='MONDAY.php?day=4'" /> 
            </label></td>
          <td class="Verdana12"><label for = "Saturday">
             <input type="button" class="button" name="button" value="Saturday" id="Saturday" onclick = "document.location='MONDAY.php?day=5'" /> 
            </label></td>
        </tr>
        <tr align = "centre"><td class="Verdana12">&nbsp;</td>
          <td height="20" class="Verdana12"><label for = "Sunday">
             <input type="button" class="button" name="button" value="Sunday" id="Sunday" onclick = "document.location='MONDAY.php?day=6'" /> 
            </label></td>
           
          <td class="Verdana12">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="10"></td>
    </tr>
    <tr>
      <td height="20" align="center"><table width="320" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="130" align="center" class="Verdana13BBlue">&nbsp;</td>
          <td width="95" align="center" class="Verdana13BBlue"><?php echo strftime('%Y');?></td>
          <td width="95" align="center" class="Verdana13BBlue"><?php echo strftime('%Y')+1 ;?></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox0"><input type="checkbox" name="checkbox[]" id="checkbox0" value = '1' <?php if ($holarray[0][1] == 1) {echo 'checked = "checked"';}?> /> 
            <?php echo $holarray[0][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate0" id="startdate0" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php  echo $holarray[0][2] ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate12" id="startdate13" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[0][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox1"><input type="checkbox" name="checkbox[]" id="checkbox1" value = '2'   <?php if ($holarray[1][1] == 1) {echo 'checked = "checked"';}?>/> 
            <?php echo $holarray[1][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate1" id="startdate2" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[1][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate13" id="startdate14" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[1][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox2"><input type="checkbox" name="checkbox[]" id="checkbox2"  value = '3'  <?php if ($holarray[2][1] == 1) {echo 'checked = "checked"';}?>/> 
            <?php echo $holarray[2][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate2" id="startdate3" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[2][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate14" id="startdate15" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[2][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox3"><input type="checkbox" name="checkbox[]" id="checkbox3" value = '4'  <?php if ($holarray[3][1] == 1) {echo 'checked = "checked"';}?> /> 
            <?php echo $holarray[3][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate3" id="startdate4" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[3][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate15" id="startdate16" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[3][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox4"><input type="checkbox" name="checkbox[]" id="checkbox4" value = '5'  <?php if ($holarray[4][1] == 1) {echo 'checked = "checked"';}?> /> 
            <?php echo $holarray[4][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate4" id="startdate5" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[4][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate16" id="startdate17" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[4][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox5"><input type="checkbox" name="checkbox[]" id="checkbox5"  value = '6'  <?php if ($holarray[5][1] == 1) {echo 'checked = "checked"';}?>/>
            <?php echo $holarray[5][0] ;?>
          </label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate5" id="startdate6" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[5][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate17" id="startdate18" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[5][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox6"><input type="checkbox" name="checkbox[]" id="checkbox6"  value = '7' <?php if ($holarray[6][1] == 1) {echo 'checked = "checked"';}?> /> 
            <?php echo $holarray[6][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate6" id="startdate7" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[6][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate18" id="startdate19" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[6][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox7"><input type="checkbox" name="checkbox[]" id="checkbox7"  value = '8'  <?php if ($holarray[7][1] == 1) {echo 'checked = "checked"';}?>/> 
            <?php echo $holarray[7][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate7" id="startdate8" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[7][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate19" id="startdate20" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[7][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox8"><input type="checkbox" name="checkbox[]" id="checkbox8"  value = '9'  <?php if ($holarray[8][1] == 1) {echo 'checked = "checked"';}?>/> 
            <?php echo $holarray[8][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate8" id="startdate9" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[8][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate20" id="startdate21" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[8][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox9"><input type="checkbox" name="checkbox[]" id="checkbox9" value = 'A'  <?php if ($holarray[9][1] == 1) {echo 'checked = "checked"';}?> /> 
            <?php echo $holarray[9][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate9" id="startdate10" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[9][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate21" id="startdate22" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[9][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox10"><input type="checkbox" name="checkbox[]" id="checkbox10" value = 'B'  <?php if ($holarray[10][1] == 1) {echo 'checked = "checked"';}?> /> 
            <?php echo $holarray[10][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate10" id="startdate11" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[10][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate22" id="startdate23" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[10][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
        <tr>
          <td height="25" class="Verdana12"><label for = "checkbox11"><input type="checkbox" name="checkbox[]" id="checkbox11"  value = 'C'  <?php if ($holarray[11][1] == 1) {echo 'checked = "checked"';}?>/> 
            <?php echo $holarray[11][0] ;?></label></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate11" id="startdate12" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[11][2]; ?>" style="width:70px;"/>
          </span></td>
          <td align="center"><span class="Verdana12">
            <input name="startdate23" id="startdate24" type="text" class="Input" size="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onclick="ds_sh(this);" value="<?php echo $holarray[11][3]; ?>" style="width:70px;"/>
          </span></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="35" align="center" class="Verdana12">Today is:&nbsp;&nbsp;<span style="background-color:#FFFF00;"><?php echo date('l m/d/Y'); ?></span></td>
    </tr>
    <tr>
        <td align="center" valign="middle" class="ButtonsTable">
        <input name="save" type="submit" class="button" id="save" value="SAVE" />
        <input name="button" type="button" class="button" id="button" value="CANCEL" onclick="self.close()" />
        </td>
    </tr>
</table>
</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
