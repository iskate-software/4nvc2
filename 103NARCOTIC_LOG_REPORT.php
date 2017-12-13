<?php 
session_start();
require_once('../../tryconnection.php');

mysql_select_db($database_tryconnection, $tryconnection);

if (!empty($_GET['startdate'])){
 $startdate=$_GET['startdate'];
}

else {
 $startdate = '00/00/0000' ;
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

$enddate="SELECT STR_TO_DATE('$enddate','%m/%d/%Y') AS ENDING";
$enddate = mysql_query($enddate, $tryconnection) or die(mysql_error());
$enddate = mysqli_fetch_array($enddate) ;

$search = "" ;

$file2search = $_GET['file2search'];
if ($file2search == 1) {
 $search = " ORDER BY INVDTE,NARCLOG.ITEM,NARCLOG.DATETIME " ;
 $dsearch = "ORDER BY DATEPURCH,NPID,ITEM " ;
 $fsearch = "ORDER BY INVDTE ASC, ITEM ASC, B4 DESC" ;
}

else if ($file2search == 2) {
 $search = " ORDER BY NARCLOG.ITEM, INVDTE,NARCLOG.DATETIME " ;
 $dsearch = "ORDER BY ITEM, DATEPURCH,NPID" ;
 $fsearch = "ORDER BY ITEM, SORT1 ASC,TIMEACT ASC,B4 DESC" ;
// 
//This was  $fsearch = "ORDER BY ITEM,INVDTE ASC,B4 DESC, SORT1 ASC" ;
// 
}

else if ($file2search == 3)  {
 $patient = $_GET['patient'] ;
 $search = " AND NARCLOG.PETID = '$patient' ORDER BY INVDTE, NLID " ;
 $dsearch = " " ;
 $fsearch = " WHERE NARDUMMY.PETID = '$patient' ORDER BY INVDTE, ITEM " ;
}

$Wtunit_get = "SELECT HOSPNAME, WEIGHTUNIT FROM CRITDATA LIMIT 1" ;
$query_wt = mysql_query($Wtunit_get, $tryconnection) or die(mysql_error()) ;
$row_Wt = mysqli_fetch_assoc($query_wt) ;

$Wtunit = $row_Wt['WEIGHTUNIT'].',' ;
$Hosp = $row_Wt['HOSPNAME'] ;

//Find the client called DRUG PURCHASES, so dummy records can be created to show the shipments blended in with the usage.
$Druggie_get = "SELECT ARCUSTO.CUSTNO AS CUST,CONTACT,COMPANY,PETNAME,PETID FROM ARCUSTO LEFT JOIN PETMAST ON ARCUSTO.CUSTNO = PETMAST.CUSTNO WHERE COMPANY = 'PURCHASES' AND CONTACT = 'DRUG' AND PETNAME = 'SHIPMENT IN' LIMIT 1" ;
$query_purch = mysql_query($Druggie_get, $tryconnection) or die(mysql_error()) ;
$row_purch = mysqli_fetch_assoc($query_purch) ;
$purch_custno = $row_purch['CUST'] ;
$purch_company = $row_purch['COMPANY'] ;
$purch_contact = $row_purch['CONTACT'] ;

$make_dummy1 = "DROP TABLE IF EXISTS NARDUMMY" ;
$purch_pet = $row_purch['PETNAME'] ;
$query_dummy1 = mysql_query($make_dummy1, $tryconnection) or die(mysql_error()) ;
$make_dummy2 = "CREATE TABLE NARDUMMY (INVDTE DATE, TIMEACT INT(10), SORT1 INT(1) UNSIGNED, CUSTNO CHAR(8),PETID INT(10) UNSIGNED,TITLE VARCHAR(10),CONTACT VARCHAR(25),COMPANY VARCHAR(50),
                 CAREA INT(3), PHONE CHAR(8), ADDRESS VARCHAR(50), CITY VARCHAR(50), ZIP CHAR(7), PETNAME VARCHAR(50),SPECIES VARCHAR(80), 
                 ITEM CHAR(8),DESCRIP VARCHAR(30), B4 FLOAT(10,2), DRAWN FLOAT(8,2),USED FLOAT(8,2), QTYREM FLOAT(10,2), INVDOC VARCHAR(50), ROA CHAR(10), TYPE CHAR(20),TIME SMALLINT(3) UNSIGNED, SEQ MEDIUMINT(5), COMMENT VARCHAR(160))ENGINE = MYISAM " ;
$query_dummy2 = mysql_query($make_dummy2, $tryconnection) or die(mysql_error()) ; 
$make_dummy3 = "TRUNCATE NARDUMMY" ;   
$query_dummy3 = mysql_query($make_dummy3, $tryconnection) or die(mysql_error()) ;            
                 
                 
if ($file2search != 3) {
 $Ship_in = "INSERT INTO NARDUMMY (INVDTE,TIMEACT, SORT1,CUSTNO,CONTACT,COMPANY,PETNAME,ITEM,DESCRIP,B4, DRAWN,	QTYREM,COMMENT) 
             SELECT DATEPURCH,'00:00:00' ,'1','$purch_custno','$purch_contact','$purch_company','$purch_pet', ITEM,DESCRIP, B4, 0-QTY, B4+QTY, 'Brought in' FROM NARCPUR WHERE DATEPURCH  >= '$startdate[0]' AND DATEPURCH <= '$enddate[0]'  $dsearch" ;

 //           SELECT DATEPURCH,NARCPUR.DATETIME,'2','$purch_custno','$purch_contact','$purch_company','$purch_pet', ITEM,DESCRIP, B4, 0-QTY, B4+QTY, 'Brought in' FROM NARCPUR WHERE DATEPURCH  >= '$startdate[0]' AND DATEPURCH <= '$enddate[0]'  $dsearch" ;
 $query_in = mysql_query($Ship_in, $tryconnection) or die(mysql_error()) ; 
}
$NARC_get = "INSERT INTO NARDUMMY SELECT INVDTE, NARCLOG.DATETIME, '3', NARCLOG.CUSTNO, NARCLOG.PETID, TITLE,CONTACT,COMPANY,CAREA,PHONE,CONCAT(ADDRESS1,' ',ADDRESS2) AS ADDRESS, CITY, ZIP, PETNAME, CONCAT(PSEX,', ', PETBREED,', Weight: ',
             CONCAT(NARCLOG.WEIGHT,' ', '$Wtunit'), ' Age; ',NYEAR,' Yr(s) ', NMONTH, ' Mths') AS SPECIES, NARCLOG.ITEM, DESCRIP, NARCLOG.B4,DRAWN, USED, QTYREM,INVDOC, ROA, NARCLOG.TYPE,TIME, NARCLOG.SEQ,  
             NARCLOG.COMMENT AS COMMENT
             FROM NARCLOG LEFT JOIN ARCUSTO ON NARCLOG.CUSTNO = ARCUSTO.CUSTNO 
             LEFT JOIN PETMAST ON NARCLOG.PETID = PETMAST.PETID
             LEFT JOIN ARINVT ON NARCLOG.ITEM = ARINVT.ITEM WHERE INVDTE  >= '$startdate[0]' AND INVDTE <= '$enddate[0]' $search " ;
            
$query_narc = mysql_query($NARC_get, $tryconnection) or die(mysql_error()) ;

$final_mix = "SELECT * FROM NARDUMMY $fsearch" ;   
$query_final = mysql_query($final_mix, $tryconnection) or die(mysql_error()) ;     

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=1.5" />
<title>NARCOTIC Log Display</title>

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
    NARCOTIC LOG
  </h5>
      <div id="prtclosebuttons">
      <input type="button" value="PRINT" onclick="window.print();"/>
      <input type="button" value="CLOSE" onclick="history.back();"/>
      </div>
  <?php while ($row_NARCLOG = mysqli_fetch_assoc($query_final) ) { ?>
  <table width="95%" cellspacing="0" cellpadding="0" style="border:2px solid blue">
  <tr>
  <td width="33%" class="Verdana12BHL">&nbsp;<?php echo $row_NARCLOG['DESCRIP'];?></td>
  <td colspan="2" width="66%" class="Verdana12">&nbsp;&nbsp;Quantity In-Clinic before procedure:&nbsp;<?php echo $row_NARCLOG['B4'] ;?></td>
  
  </tr>
  </table>
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
    <td class="Verdana12B">&nbsp;<?php echo $row_NARCLOG['INVDTE'] ;?></td>
    <td class="Verdana12BHL">&nbsp;<?php echo $row_NARCLOG['TITLE'].' '.$row_NARCLOG['CONTACT'].' '.$row_NARCLOG['COMPANY'];?></td>
    <td class="Verdana12B">&nbsp;<?php echo '('. $row_NARCLOG['CAREA'] .') '.$row_NARCLOG['PHONE'] ;?></td>
    <td class="Verdana12BHL">&nbsp;<?php echo $row_NARCLOG['PETNAME'] ;?>&nbsp;</td>
    <td width="0%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="Verdana12B">&nbsp;<?php echo $row_NARCLOG['ADDRESS'];?>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td class="Verdana12B">&nbsp;<?php echo $row_NARCLOG['CITY'].'  '.$row_NARCLOG['ZIP'];?>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<table height="30px" width="95%" cellspacing="0" cellpadding="0" style="border:1px solid blue">
<tr >
<td class="Verdana12">&nbsp;<?php echo $row_NARCLOG['SPECIES'] ;?></td>
</tr>
</table>

<table height="40px" width="95%" cellspacing="0" cellpadding="0" style="border:1px solid blue">
<tr >
<td width="34%" class="Verdana12">&nbsp;<?php echo 'Doctor: ' .$row_NARCLOG['INVDOC'] ;?></td>
<td width="22%" class="Verdana12">&nbsp;<?php echo 'Drawn: ' .$row_NARCLOG['DRAWN'] ;?></td>
<td width="22%" class="Verdana12">&nbsp;<?php echo 'Used: ' .$row_NARCLOG['USED']; ?></td>
<td width="22%" class="Verdana12">&nbsp;<?php echo 'Qty.Rem: ' .$row_NARCLOG['QTYREM']; ?></td>
</tr>
</table>

<table height="40px" width="95%" cellspacing="0" cellpadding="0" style="border:1px solid blue">
<tr >
<td width="35%" class="Verdana12">&nbsp;<?php echo 'Route of Administration: ' .$row_NARCLOG['ROA'] ;?></td>
<td width="31%" class="Verdana12">&nbsp;<?php echo 'Time (Mins): ' .$row_NARCLOG['TIME'] ;?></td>
<td width="34%" class="Verdana12">&nbsp;<?php echo 'Type: ' .$row_NARCLOG['TYPE'] ;?></td>
</tr>
</table>

<table height="30px" width="95%" cellspacing="0" cellpadding="0" style="border:1px solid blue">
<tr>
<td width="100%" style="background-color:#FFFFFF;"class="Verdana12"><?php echo $row_NARCLOG['COMMENT'] ;?></td>
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
