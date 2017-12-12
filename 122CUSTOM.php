<?php 
session_start();
 	if (!isset($_SESSION['table'])){
	$_SESSION['table']=$_POST['table'];	
	}
	
require_once('../../tryconnection.php');
mysql_select_db($database_tryconnection, $tryconnection);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>POP UP WINDOWS TEMPLATE</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">

function bodyonload(){
<?php 
	if (isset($_SESSION['table']) && empty($_SESSION['table'])){
	unset($_SESSION['table']);
	echo "document.location='EXAM_SHEETS_DIR.php?patient=".$_SESSION['patient']."';";
	}
	
	else {
	$query_INHOSP = "SELECT TEXT FROM ".$_SESSION['table'][0];
	$INHOSP = mysql_query($query_INHOSP, $tryconnection) or die(mysql_error());
	$row_INHOSP = mysql_fetch_assoc($INHOSP);
	$dataline = EXPLODE (' ', $row_INHOSP)
	foreach ($dataline as $dollarsign) {
	  if substr($dollarsign,1,1) = '$' {
	    `
	  }
	  
	}
	echo "window.print();";
	unset($_SESSION['table'][0]);
	$_SESSION['table']=array_merge($_SESSION['table']);
	echo "document.location.reload();";
	}
?>
}

</script>
<style type="text/css">
body{
background-color:#FFFFFF;
}
</style>
<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->

<form method="post" action="print_sheet" name="print_sheet" id="" style="position:absolute; top:0px; left:0px; font-family:'Courier New', Courier, monospace;">
<!--<iframe src="../../IMAGES/inhosp.txt" width="732" height="100%" scrolling="no" ></iframe>-->
<?php 

if (!empty($row_INHOSP)){

do{
echo $row_INHOSP['TEXT']."<br />";
}
while ($row_INHOSP = mysql_fetch_assoc($INHOSP));
}


?>


</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
