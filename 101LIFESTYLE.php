<?php
session_start();
require_once('../../tryconnection.php'); 

$patient = $_GET['patient'];
mysqli_select_db($tryconnection, $database_tryconnection);
$query_PATIENT = sprintf("SELECT PLIFE FROM PETMAST WHERE PETMAST.PETID = %s", $patient);
$PATIENT = mysqli_query($tryconnection, $query_PATIENT) or die(mysqli_error($mysqli_link));
$row_PATIENT = mysqli_fetch_assoc($PATIENT);

$species=$_GET['species'];

$query_LIFESTYLE = "SELECT * FROM PETLIFESTYLE WHERE LSPECIES='$species' ORDER BY LIFESTYLE";
$LIFESTYLE = mysqli_query($tryconnection, $query_LIFESTYLE) or die(mysqli_error($mysqli_link));
$row_LIFESTYLE = mysqli_fetch_assoc($LIFESTYLE);
$totalRows_LIFESTYLE = mysqli_num_rows($LIFESTYLE);

$plife=0;
if (isset($_POST['save'])){
$plife=implode(",",$_POST['plife']);
	if ($patient!=0){
	$updateSQL = "UPDATE PETMAST SET PLIFE='$plife'WHERE PETID='$patient'";
	$Result1 = mysqli_query($tryconnection, $updateSQL) or die(mysqli_error($mysqli_link));
	}
$closewin='self.close();';
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width, maximum-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>LIFESTYLE</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">
function bodyonload()
{
var life="<?php echo $plife; ?>"
if (life != "0"){
opener.document.patients.plife.value = document.petlifestyle.plife.value;
}
<?php echo $closewin; ?>
}
</script>
<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->
<form name="petlifestyle" action="" method="post" style="position:absolute; top:0px; left:0px;">
<input type="hidden" name="plife" value="<?php echo $plife; ?>" />
<table width="340" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="40" align="center" valign="middle" class="Verdana14B">Editing Lifestyle: <?php if ($_GET['species']=='1'){echo "Canine";} else if ($_GET['species']=='2'){echo "Feline";} else if ($_GET['species']=='3'){echo "Equine";}else if ($_GET['species']=='4'){echo "Bovine";}else if ($_GET['species']=='5'){echo "Caprine";}else if ($_GET['species']=='6'){echo "Porcine";}else if ($_GET['species']=='7'){echo "Avian";}else if ($_GET['species']=='8'){echo "Other";}; ?></td>
  </tr>
  
  <tr>
    <td align="left">
  
  <div style="height:450px;overflow:auto;">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">

   <?php 
    
	do { ?>

    <tr>
    <td width="19%" class="Labels">&nbsp;</td>
    <td width="81%" align="left" class="Labels">
    <label><input type="checkbox" name="plife[]" id="<?php echo $row_LIFESTYLE['LIFESTYLEID']; ?>" value="<?php echo $row_LIFESTYLE['LIFESTYLEID']; ?>"  <?php 
		$life = explode(",",$row_PATIENT['PLIFE']);
		if (in_array($row_LIFESTYLE['LIFESTYLEID'], $life))
		{echo "CHECKED";} 
		?> />
    <?php echo $row_LIFESTYLE['LIFESTYLE']; ?></label> </td>
    </tr>
   
   <?php } while ($row_LIFESTYLE = mysqli_fetch_assoc($LIFESTYLE)); ?>
</table>
  </div>
</td>
</tr>  
  <tr>
    <td height="5"></td>
  </tr>
  <tr>
    <td height="35" align="center" valign="middle" bgcolor="#B1B4FF">
    <input name="save" type="submit" class="button" id="SAVE" value="SAVE" onclick="onsave();"/>
    <input name="cancel" type="reset" class="button" id="SAVE2" value="CLOSE" onclick="self.close();" /></td>
  </tr>
</table>
</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
<?php
mysqli_free_result($LIFESTYLE);
?>
