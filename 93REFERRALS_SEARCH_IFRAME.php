<?php 
require_once('../../tryconnection.php');
$refvet = $_GET['refvet'];
$refclin = $_GET['refclin'];
$refID=$_GET['refID'];

if (!empty($_GET['sorting'])){
$sortby = $_GET['sorting'];
}
else {
$sortby = REFVET;
}

mysql_select_db($database_tryconnection, $tryconnection);
$query_REFERRAL = sprintf("SELECT * FROM REFER WHERE REFCLIN LIKE '%s' AND REFVET LIKE '%s' ORDER BY $sortby ASC", mysql_real_escape_string($refclin).'%', mysql_real_escape_string($refvet).'%');
$REFERRAL = mysql_query($query_REFERRAL, $tryconnection) or die(mysql_error());
$row_REFERRAL = mysql_fetch_assoc($REFERRAL);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/IFRAME.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

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


<style type="text/css">
</style>


<script type="text/javascript">
function putInReferral(refvet,refclin,refid,tea)
{
//var tea = '<?php echo $_GET['tea']; ?>';
	if (sessionStorage.tea == '1') {
	parent.window.searchreferral.doctor.value=refvet;
	parent.window.searchreferral.clinic.value=refclin;
	parent.window.self.close();
	}
	else {
	window.open('ADD_EDIT_REFERRAL.php?refid='+refid,'_parent');
	}
}

</script>
<!-- InstanceEndEditable -->



<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion1" -->

<div id="WindowBody" style="width:700px">
<form action="" method="post" name="referral" class="FormDisplay">

<div style="height:400px; overflow:auto;">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" frame="below" rules="rows">
   
   <?php do { ?> 
   
   <tr class="Verdana12" id="<?php echo $row_REFERRAL['REFID']; ?>" onclick="putInReferral('<?php echo mysql_real_escape_string($row_REFERRAL['REFVET']); ?>, <?php echo mysql_real_escape_string($row_REFERRAL['REFVETFN']); ?>','<?php echo mysql_real_escape_string($row_REFERRAL['REFCLIN']); ?>','<?php echo $row_REFERRAL['REFID']; ?>',sessionStorage.tea);" onmouseover="document.getElementById(this.id).style.cursor='pointer';">
     <td width="10"></td>
      <td width="140" valign="top"><?php echo $row_REFERRAL['REFVET']; ?>, <?php echo $row_REFERRAL['REFVETFN']; ?></td>
      <td width="250" height="15" align="left" valign="top">&nbsp;<?php echo $row_REFERRAL['REFCLIN']; ?></td>
      <td width="120" align="left" valign="top"><?php echo "(".$row_REFERRAL['CAREA'].") ".$row_REFERRAL['PHONE']; ?><br  /><?php echo "(".$row_REFERRAL['CAREA2'].") ".$row_REFERRAL['PHONE2']; ?></td>
      <td width="" align="left"><?php echo $row_REFERRAL['ADDRESS']; ?><br  /> <?php echo $row_REFERRAL['CITY']; ?><br  /> <?php echo $row_REFERRAL['ZIP']; ?></td>
   </tr>
    
    <?php } while ($row_REFERRAL = mysql_fetch_assoc($REFERRAL)); ?>
</table>
</div>

</form>
</div>

<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
