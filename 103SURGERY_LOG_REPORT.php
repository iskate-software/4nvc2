<?php 
session_start();
require_once('../../tryconnection.php');

mysql_select_db($database_tryconnection, $tryconnection);

if (!empty($_GET['startdate'])){
$startdate=$_GET['startdate'];
}
else {
$startdate='00/00/0000';
}
$stdum = $startdate ;

$startdate="SELECT STR_TO_DATE('$startdate','%m/%d/%Y')";
$startdate=mysql_query($startdate, $tryconnection) or die(mysql_error());
$startdate=mysql_fetch_array($startdate);

if (!empty($_GET['enddate'])){
$enddate=$_GET['enddate'];
}
else {
$enddate=date('m/d/Y');
}
$enddum = $enddate ;

$enddate="SELECT STR_TO_DATE('$enddate','%m/%d/%Y')";
$enddate=mysql_query($enddate, $tryconnection) or die(mysql_error());
$enddate=mysql_fetch_array($enddate);


$Wtunit_get = "SELECT HOSPNAME, WEIGHTUNIT FROM CRITDATA LIMIT 1" ;
$query_wt = mysql_query($Wtunit_get, $tryconnection) or die(mysql_error()) ;
$row_Wt = mysql_fetch_assoc($query_wt) ;

$Wtunit = $row_Wt['WEIGHTUNIT'].',' ;
$Hosp = $row_Wt['HOSPNAME'] ;

$surg_get = "SELECT INVDTE, DATE_FORMAT(INVDTE, '%m/%d/%Y') AS SHOWDTE, SURGLOG.CUSTNO, SURGLOG.PETID, TITLE,CONTACT,COMPANY,CAREA,PHONE,CONCAT(ADDRESS1,' ',ADDRESS2) AS ADDRESS, CITY, ZIP, PETNAME, CONCAT(PSEX,', ', PETBREED,', Weight: ',
             CONCAT(SURGLOG.WEIGHT,' ', '$Wtunit'), ' Age; ',SYEAR,' Yr(s) ', SMONTH, ' Mths') AS SPECIES,CONDPRE, CONDPOST, TIME, INVDOC, SWHODID, SWHOMON, SWHOREC, SURGLOG.COMMENT AS COMMENT 
             FROM SURGLOG LEFT JOIN ARCUSTO ON SURGLOG.CUSTNO = ARCUSTO.CUSTNO 
             LEFT JOIN PETMAST ON SURGLOG.PETID = PETMAST.PETID WHERE INVDTE  >= '$startdate[0]' AND INVDTE <= '$enddate[0]' ORDER BY INVDTE " ;
             
$query_surg = mysql_query($surg_get, $tryconnection) or die(mysql_error()) ;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=1.5" />
<title>Surgery Log Display</title>

<style type="text/css">
body {
background-color:#FFFFFF;
overflow:auto;
}
#prtclosebuttons{
display:block;
}

</style>
<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
</head>

<body>
  <h3 style="position:relative; left:160px;">
  <?php echo $Hosp ;?>
  <h3>
  <h5 style="position:relative; left:250px;">
    SURGERY LOG
  </h5>
      <div id="prtclosebuttons">
      <input type="button" value="PRINT" onclick="window.print();"/>
      <input type="button" value="CLOSE" onclick="history.back();"/>
      </div>
  <?php while ($row_SURGLOG = mysql_fetch_assoc($query_surg) ) { ?>
  <table width="95%" cellspacing="0" cellpadding="0" style="border:2px solid blue">
  <tr>
    <td width="15%" class="Verdana12BBlue">
      <div align="center">Date</div></td>
    <td width="41%" class="Verdana12BBlue"><div style="text-align:center">&nbsp;Client</div></td>  
    <td width="19%" class="Verdana12BBlue"><div style="text-align:center">&nbsp;Phone</div></td>
    <td width="25%" class="Verdana12BBlue">
      <div align="center">&nbsp;Patient</div></td>
  </tr>
  <tr>
    <td class="Verdana12B">&nbsp;<?php echo $row_SURGLOG['SHOWDTE'] ;?></td>
    <td class="Verdana12BHL">&nbsp;<?php echo $row_SURGLOG['TITLE'].' '.$row_SURGLOG['CONTACT'].' '.$row_SURGLOG['COMPANY'];?></td>
    <td class="Verdana12B">&nbsp;<?php echo '('. $row_SURGLOG['CAREA'] .') '.$row_SURGLOG['PHONE'] ;?></td>
    <td class="Verdana12BHL">&nbsp;<?php echo $row_SURGLOG['PETNAME'] ;?>&nbsp;</td>
    <td width="0%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Verdana12B">&nbsp;<?php echo $row_SURGLOG['ADDRESS'];?>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td class="Verdana12B">&nbsp;<?php echo $row_SURGLOG['CITY'].'  '.$row_SURGLOG['ZIP'];?>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<table height="30px" width="95%" cellspacing="0" cellpadding="0" style="border:1px solid blue">
<tr >
<td class="Verdana12">&nbsp;<?php echo $row_SURGLOG['SPECIES'] ;?></td>
</tr>
</table>

<table height="40px" width="95%" cellspacing="0" cellpadding="0" style="border:1px solid blue">
<tr >
<td width="35%" class="Verdana12">&nbsp;<?php echo 'Doctor: ' .$row_SURGLOG['INVDOC'] ;?></td>
<td width="31%" class="Verdana12">&nbsp;<?php echo 'Time(mins): ' .$row_SURGLOG['TIME'] ;?></td>
<td width="34%" class="Verdana12">&nbsp;<?php echo 'Pre: ' .$row_SURGLOG['CONDPRE']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo '    Post: '. $row_SURGLOG['CONDPOST'];?></td>
</tr>
</table>

<table height="40px" width="95%" cellspacing="0" cellpadding="0" style="border:1px solid blue">
<tr >
<td width="35%" class="Verdana12">&nbsp;<?php echo 'Performed by: ' .$row_SURGLOG['SWHODID'] ;?></td>
<td width="31%" class="Verdana12">&nbsp;<?php echo 'Monitored by: ' .$row_SURGLOG['SWHOMON'] ;?></td>
<td width="34%" class="Verdana12">&nbsp;<?php echo 'Recorded by: ' .$row_SURGLOG['SWHOREC'];?></td>
</tr>
</table>

<table height="60px" width="95%" cellspacing="0" cellpadding="0" style="border:1px solid blue">
<tr>
<td width="100%" style="background-color:#FFFFFF;"class="Verdana12"><?php echo $row_SURGLOG['COMMENT'] ;?></td>
</tr>
</table>
<table height="10px" width="95%" cellspacing="0" cellpadding="0" >
<tr>
<td width="100%">&nbsp;</td>
</tr>
</table>
<?php } ?>

</body>
</html>
