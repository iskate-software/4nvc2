<?php 
session_start();
include("../../ASSETS/navigation.php");

require_once('../../tryconnection.php'); 

///////////////// ------FILTER------ ////////////////////
include("../../ASSETS/age.php");
if (!empty($_GET['id'])){
$_SESSION['id']=$_GET['id'];
}

$timeformat=$_SESSION['timeformat'];

$petname;
$credate;
$tdate;
$enteredby;
$whotodo;

if (isset($_SESSION['petname']) && !empty($_SESSION['petname'])){
$petname=$_SESSION['petname'];
$left=" ";
}
else {
$left = " LEFT ";
}

if (isset($_SESSION['credate'])){
$credate=$_SESSION['credate'];
}

if (isset($_SESSION['tdate'])){
$tdate=$_SESSION['tdate'];
}

if (isset($_SESSION['enteredby'])){
$enteredby=$_SESSION['enteredby'];
}

if (isset($_SESSION['whotodo'])){
$whotodo=$_SESSION['whotodo'];
}

//////////////////---------- SORTING -------------///////////////////
if (!empty($_GET['sortingdlog']))
{
$_SESSION['sortingdlog'] = $_GET['sortingdlog'];
$sortby = $_SESSION['sortingdlog'];
}
elseif (!isset($_SESSION['sortingdlog'])){
$sortby='TICKLER.TDATE';
} 
else{
$sortby = $_SESSION['sortingdlog'];
}
//////////////////------------- DISPLAY ONLY HOSPITAL OR ONLY PATIENTS -------------///////////////////
if (isset($_GET['hospital_patient']) && $_GET['hospital_patient']=="ALL"){
$_SESSION['hospital_patient'] = $_GET['hospital_patient'];
$hospital_patient='';
unset($_SESSION['petname']);
unset($_SESSION['credate']);
unset($_SESSION['tdate']);
unset($_SESSION['enteredby']);
unset($_SESSION['whotodo']);
} 


elseif (isset($_SESSION['hospital_patient']) || !empty($_GET['hospital_patient'])){
	if(!empty($_GET['hospital_patient'])){
	$_SESSION['hospital_patient'] = $_GET['hospital_patient'];
	}
	
	if ($_SESSION['hospital_patient']=="HOSPITAL"){
			$hospital_patient = " AND TICKLER.DLPETID='0'";	
			}
	elseif($_SESSION['hospital_patient']=="CLIENT"){
			$hospital_patient = " AND TICKLER.DLPETID!='0'";
			}
	else{
			$hospital_patient = "";
			}
}

/////////////////////////////////////

mysql_select_db($database_tryconnection, $tryconnection);
$query_DLOG = "SELECT *, DATE_FORMAT(CREDATE, '%m/%d/%Y $timeformat') AS CREDATE, DATE_FORMAT(TDATE, '%m/%d/%Y') AS TDATE, DATE_FORMAT(TTIME, '$timeformat') AS TTIME FROM TICKLER ".$left." JOIN  PETMAST ON (PETMAST.PETID=TICKLER.DLPETID AND PETMAST.PETNAME LIKE '$petname%') WHERE TICKLER.CREDATE LIKE '$credate%' AND TICKLER.TDATE LIKE '$tdate%' AND TICKLER.ENTEREDBY LIKE '$enteredby%' AND TICKLER.WHOTODO LIKE '$whotodo%' ".$hospital_patient." ORDER BY ".$sortby." ASC";
$DLOG = mysql_query($query_DLOG, $tryconnection) or die(mysql_error());
$row_DLOG = mysql_fetch_assoc($DLOG);
$totalRows_DLOG = mysql_num_rows($DLOG);


/////////////////////////////////////////


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>DUTY LOG</title>


<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>

<script type="text/javascript">

function bodyonload()
{
document.getElementById('<?php echo $_SESSION['id']; ?>').bgColor="#666666";
}

function setsortingdlog(x,y)
{
self.location='PRINT_DLOG.php?sortingdlog=' + x + '&id=' + y;
}

function setfilter(a)
{
self.location='PRINT_DLOG.php?hospital_patient=' + a;
}

</script>

<style type="text/css">
<!--
body {
	background-color: #FFFFFF;
}

#WindowBody{
	position:absolute;
	top:30px;
	left: 25px;
	width:733px;
	z-index:1;
	font-family: "Verdana";
	background-color: #FFFFFF;
	color: #000000;
	text-align: left;
}


.style2 {
	font-size: 11px;
	color: #000000;
}

.alerttext12 {
	font-size: 12px;
	color: #CC0033;
	background-color: #FF99CC;
	font-weight: bold;
	font-family: "Verdana";
}

.alerttext13 {
	font-size: 13px;
	color: #CC0033;
	font-weight: bold;
	background-color: #FF99CC;
}


.style1 {color: <?php if (empty($row_DLOG)){echo "#FFFFFF";} else {echo "#999999";} ?>
}



-->
</style>
</head>

<body onload="bodyonload()">



<div id="WindowBody">

  <form name="duty_log" action="" method="post" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr height="10" class="Verdana11Bwhite" bgcolor="#000000">
        <td width="73" id="patient" onclick="setsortingdlog('PETMAST.PETNAME',this.id);" onmouseover="CursorToPointer(this.id)" title="Click to sort by patient name">Patient</td>
        <td width="73"></td>
        <td width="249">&nbsp;</td>
        <td width="87" id="entered" align="center" onclick="setsortingdlog('TICKLER.CREDATE',this.id);" onmouseover="CursorToPointer(this.id)" title="Click to sort by date entered">Entered</td>
        <td width="75" id="due" align="center" onclick="setsortingdlog('TICKLER.TDATE',this.id);" onmouseover="CursorToPointer(this.id)" title="Click to sory by date due">Due</td>
        <td width="79" id="entryby" align="center" onclick="setsortingdlog('TICKLER.ENTEREDBY',this.id);" onmouseover="CursorToPointer(this.id)" title="Click to sort by who entered the duty logs">Entered By</td>
        <td width="37" id="dueby" onclick="setsortingdlog('TICKLER.WHOTODO',this.id);" onmouseover="CursorToPointer(this.id)" align="center" title="Click to sort by who the duty logs are for">For</td>
        <td width="60" id="complete" onclick="setsortingdlog('TICKLER.WHOTODO',this.id);" onmouseover="CursorToPointer(this.id)">Complete</td>
      </tr>
    <tr>
      <td height="488" colspan="8" valign="top">
        
        <div id="dlog">
          
          <table width="100%"border="0" cellspacing="0" cellpadding="0">
                  <?php do { ?>
                    <tr bgcolor="#E1E1E1" height="10">
                        <td colspan="6" class="Labels2">
                        <span style="background-color:#FFFF00" class="Verdana12B"><?php if ($row_DLOG['DLPETID']==0){echo "HOSPITAL";} else { echo $row_DLOG['PETNAME'];} ?></span></td>
                        <td colspan="4" align="right" class="Verdana11B">
						<?php 
						if($row_DLOG['RECUR']=="1"){
							if ($row_DLOG['DAYS'] <= 7){
							echo "Recurring every ".$row_DLOG['DAYS']." days";
							}
							elseif ($row_DLOG['DAYS']>7 && $row_DLOG['DAYS']<30){
							$numdays = $row_DLOG['DAYS']/7;
							echo "Recurring every $numdays weeks";
							}
							elseif ($row_DLOG['DAYS'] = 30){
							echo "Recurring every month";
							}
							elseif ($row_DLOG['DAYS'] > 30 && $row_DLOG['DAYS']<= 180){
							$numdays = $row_DLOG['DAYS']/30;
							echo "Recurring every $numdays months";
							}
							else {
							echo "Recurring every year";
							}
						}
							
							 ?>
                        </td>
                    </tr>
                    <tr height="20" bgcolor="<?php if ($row_DLOG['PSEX']=='M'){echo '#DBEBF0';} else {echo '#F9DEE9';}; ?>" <?php if ($row_DLOG['DLPETID']==0){echo "style='display:none'";}?>>
                        <td>&nbsp;</td>
                        <td colspan="8" class="Labels2" >
                        <?php if ($row_DLOG['PETTYPE']=='1'){echo "Canine";} else if ($row_DLOG['PETTYPE']=='2'){echo "Feline";} else if ($row_DLOG['PETTYPE']=='3'){echo "Equine";}else if ($row_DLOG['PETTYPE']=='4'){echo "Bovine";}else if ($row_DLOG['PETTYPE']=='5'){echo "Caprine";}else if ($row_DLOG['PETTYPE']=='6'){echo "Porcine";}else if ($row_DLOG['PETTYPE']=='7'){echo "Avian";}else if ($row_DLOG['PETTYPE']=='8'){echo "Other";} 
                        echo ", ".$row_DLOG['PETBREED'].", ".$row_DLOG['PWEIGHT']."&nbsp;".$_SESSION['weightunit'].",&nbsp;"; 
                        if ($row_DLOG['PSEX']=='M'){echo "M";} else{echo "F";} 
                        if ($row_DLOG['PNEUTER']=='1' && $row_DLOG['PSEX']=='M'){echo "(N), ";} elseif ($row_DLOG['PNEUTER']=='1' && $row_DLOG['PSEX']=='F'){echo "(S), ";} else {echo ", ";}
                        echo "age: ";
                        agecalculation($tryconnection,$row_DLOG['PDOB']); 
                        ?>
                    	</td>
                    	<td width="24"></td>
                  </tr>
                  <tr height="35">
                        <td width="10">&nbsp;</td>
                        <td width="18">&nbsp;</td>
                        <td valign="top" class="Verdana11" id="<?php echo $row_DLOG['DUTYLOGID']; ?>"><?php echo $row_DLOG['REASON']; ?></td>
                        <td width="7">&nbsp;</td>
                        <td width="85" align="center" valign="middle" class="Verdana12"><?php echo $row_DLOG['CREDATE']."<br />".$row_DLOG['TIMEIN']; ?></td>
                        <td width="85" align="center" valign="middle">
                        <span class="<?php if ($row_DLOG['TDATE'] < date("m/d/Y")){echo "alerttext12";} else {echo "Verdana12Blue";} ?> "><?php echo $row_DLOG['TDATE']; ?></span>
                        <br />
                        <span class="<?php $datetime=$row_DLOG['TDATE'].$row_DLOG['TTIME']; if ( $datetime < date("m/d/Y".$_SESSION['phptimeformat'])){echo "alerttext12";} else {echo "Verdana12Blue";} ?>" ><?php  echo $row_DLOG['TTIME']; ?> </span></td>
                        <td width="60" align="center" valign="middle" class="Verdana12"><?php echo $row_DLOG['ENTEREDBY']; ?></td>
                        <td width="60" align="center" valign="middle" class="Verdana12"><?php echo $row_DLOG['WHOTODO']; ?></td>
                        <td width="29" align="center" valign="middle"></td>
                        <td align="center" valign="middle"><input type="checkbox" name="complete[]" id="complete" value="<?php echo $row_DLOG['DUTYLOGID']; ?>" title="Tick to mark completed duties and click SAVE"/></td>
                  </tr>
                  <?php } while ($row_DLOG = mysql_fetch_assoc($DLOG)); ?>
				</table>
            </div>
        </td>
    </tr>
  </table>
  </form>
</div>
</body>
</html>
<?php
mysql_free_result($DLOG);
?>
