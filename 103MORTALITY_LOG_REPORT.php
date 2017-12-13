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
$startdate=mysqli_fetch_array($startdate);

if (!empty($_GET['enddate'])){
$enddate=$_GET['enddate'];
}
else {
$enddate=date('m/d/Y');
}
$enddum = $enddate ;

$enddate="SELECT STR_TO_DATE('$enddate','%m/%d/%Y')";
$enddate=mysql_query($enddate, $tryconnection) or die(mysql_error());
$enddate=mysqli_fetch_array($enddate);

$search = "" ;
 
$file2search = $_GET['file2search'];

if ($file2search == 1) {
$search = " ORDER BY INVDTE " ;
}

else if ($file2search == 2) {
$search = " ORDER BY MORT.COMPANY, INVDTE " ;
}


$Wtunit_get = "SELECT HOSPNAME, WEIGHTUNIT FROM CRITDATA LIMIT 1" ;
$query_wt = mysql_query($Wtunit_get, $tryconnection) or die(mysql_error()) ;
$row_Wt = mysqli_fetch_assoc($query_wt) ;

$Wtunit = $row_Wt['WEIGHTUNIT'].',' ;
$Hosp = $row_Wt['HOSPNAME'] ;

$wot_died = "SELECT TCATGRY FROM VETCAN WHERE INSTR(TDESCR,'EUTH') <> 0  LIMIT 1 " ;
$query_wot = mysql_query($wot_died, $tryconnection) or die(mysql_error()) ;
$row_itis = mysqli_fetch_assoc($query_wot) ;
$tcat = $row_itis['TCATGRY'] ;

$clean_up = "DROP TEMPORARY TABLE IF EXISTS MORT" ;
$do_it = mysql_query($clean_up, $tryconnection) or die(mysql_error()) ;

$make_dead = "CREATE TEMPORARY TABLE MORT LIKE RADLOG " ;
$die = mysql_query($make_dead, $tryconnection) or die(mysql_error()) ;

$get_dead = "INSERT INTO MORT (INVDTE,PETID,CUSTNO,INVDOC) SELECT INVDATETIME,INVPET,INVCUST,INVORDDOC FROM ARYDVMI WHERE INVDATETIME  >= '$startdate[0]' AND INVDATETIME <= '$enddate[0]' AND INVMAJ = '$tcat' " ;
$lay_them_out = mysql_query($get_dead, $tryconnection) or die(mysql_error()) ;

$get_dead2 = "INSERT INTO MORT (INVDTE,PETID,CUSTNO,INVDOC) SELECT INVDATETIME,INVPET,INVCUST,INVORDDOC FROM DVMINV WHERE INVDATETIME  >= '$startdate[0]' AND INVDATETIME <= '$enddate[0]' AND INVMAJ = '$tcat' " ;
$lay_them_out2 = mysql_query($get_dead2, $tryconnection) or die(mysql_error()) ;

$dead_get = "SELECT DISTINCT INVDTE, MORT.CUSTNO, MORT.PETID, TITLE,CONTACT,COMPANY,CAREA,PHONE,CONCAT(ADDRESS1,' ',ADDRESS2) AS ADDRESS, CITY, ZIP, PETNAME, 
              CONCAT(PSEX,', ', PETBREED,', Weight: ', '$Wtunit', ' Age; ', ROUND(DATEDIFF(INVDTE,PDOB)/365.25,1), ' Yr(s) ') AS SPECIES, INVDOC  
             FROM MORT LEFT JOIN ARCUSTO ON MORT.CUSTNO = ARCUSTO.CUSTNO 
             LEFT JOIN PETMAST ON MORT.PETID = PETMAST.PETID " ;
             
$query_dead = mysql_query($dead_get, $tryconnection) or die(mysql_error()) ;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=1.5" />
<title>Mortalityy Log Display</title>

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
    MORTALITYY LOG
  </h5>
      <div id="prtclosebuttons">
      <input type="button" value="PRINT" onclick="window.print();"/>
      <input type="button" value="CLOSE" onclick="history.back();"/>
      </div>
  <?php while ($row_MORT = mysqli_fetch_assoc($query_dead) ) { ?>
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
    <td class="Verdana12B">&nbsp;<?php echo $row_MORT['INVDTE'] ;?></td>
    <td class="Verdana12BHL">&nbsp;<?php echo $row_MORT['TITLE'].' '.$row_MORT['CONTACT'].' '.$row_MORT['COMPANY'];?></td>
    <td class="Verdana12B">&nbsp;<?php echo '('. $row_MORT['CAREA'] .') '.$row_MORT['PHONE'] ;?></td>
    <td class="Verdana12BHL">&nbsp;<?php echo $row_MORT['PETNAME'] ;?>&nbsp;</td>
    <td width="0%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Verdana12B">&nbsp;<?php echo $row_MORT['ADDRESS'];?>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td class="Verdana12B">&nbsp;<?php echo $row_MORT['CITY'].'  '.$row_MORT['ZIP'];?>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<table height="30px" width="95%" cellspacing="0" cellpadding="0" style="border:1px solid blue">
<tr >
<td class="Verdana12">&nbsp;<?php echo $row_MORT['SPECIES'] ;?></td>
</tr>
</table>

<table height="40px" width="95%" cellspacing="0" cellpadding="0" style="border:1px solid blue">
<tr >
<td width="35%" class="Verdana12">&nbsp;<?php echo 'Doctor: ' .$row_MORT['INVDOC'] ;?></td>
<td width="31%" class="Verdana12">&nbsp;</td>
<td width="34%" class="Verdana12">&nbsp;</td>
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
