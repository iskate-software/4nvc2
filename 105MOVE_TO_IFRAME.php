<?php 
session_start();
require_once('../../tryconnection.php'); 
$client=$_GET['client'];
mysqli_select_db($tryconnection, $database_tryconnection);
$query_CLIENT = "SELECT * FROM ARCUSTO WHERE CUSTNO='$client'";
$CLIENT = mysqli_query($tryconnection, $query_CLIENT) or die(mysqli_error($mysqli_link));
$row_CLIENT = mysqli_fetch_assoc($CLIENT);


$pdead=" AND PDEAD=0 AND PMOVED=0";
$query_PATIENTS = "SELECT *, DATE_FORMAT(PDOB,'%m/%d/%Y') AS PDOB FROM PETMAST WHERE CUSTNO = '$client'".$pdead." ORDER BY PETNAME ASC";
$PATIENTS = mysqli_query($tryconnection, $query_PATIENTS) or die(mysqli_error($mysqli_link));
$row_PATIENTS = mysqli_fetch_assoc($PATIENTS);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/IFRAME.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=1.0" />

<title>DV MANAGER MAC</title>

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<style type="text/css">
<!--
#WindowBody {
	position:absolute;
	top:0px;
	width:733px;
	height:553px;
	z-index:1;
	font-family: "Verdana";
	outline-style: ridge;
	outline-color: #FFFFFF;
	outline-width: medium;
	background-color: #FFFFFF;
	left: 0px;
	color: #000000;
	text-align: left;
}
-->
</style>

</head>
<!-- InstanceBeginEditable name="EditRegion2" -->

<script type="text/javascript">

function bodyonload()
{
parent.document.submit_move.tar_client.value='<?php echo $_GET['client']; ?>';
}
</script>

<style type="text/css">
.table {
	border-color: #CCCCCC;
	border-style: ridge;
	border-width: 1px;
	border-collapse: separate;
	border-spacing: 1px;
}
</style>

<!-- InstanceEndEditable -->



<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion1" -->
<div id="WindowBody">
<table width="549" border="1" cellpadding="0" cellspacing="0" class="table">
	<tr class="Verdana12">
		<td width="278">
		<span style="background-color:#FFFF00"> <strong>
        &nbsp;<?php echo $row_CLIENT['TITLE']." ".$row_CLIENT['CONTACT']." ".$row_CLIENT['COMPANY']; ?>
        </strong> </span> <br  />
		&nbsp;<?php echo $row_CLIENT['AREA'].'-'.$row_CLIENT['PHONE'].', '.$row_CLIENT['CAREA2'].'-'.$row_CLIENT['PHONE2'].', '.$row_CLIENT['CAREA3'].'-'.$row_CLIENT['PHONE3'].', '.$row_CLIENT['CAREA4'].'-'.$row_CLIENT['PHONE4']; ?>        
		<br  />
		&nbsp;<?php echo $row_CLIENT['ADDRESS1']; ?>        
        <br  />
		&nbsp;<?php echo $row_CLIENT['CITY'].", ".$row_CLIENT['STATE'].", ".$row_CLIENT['ZIP']; ?>        
		</td>
		<td>
		</td>
    </tr>
</table>
</div>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
