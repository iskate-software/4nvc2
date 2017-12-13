<?php
session_start();
require_once('../../tryconnection.php'); 

$patient = $_GET['patient'];

mysql_select_db($database_tryconnection, $tryconnection);
$query_DESCRIPTION = "SELECT PDESCRIPTION FROM EQDESC WHERE DPETID = '$patient'";
$DESCRIPTION = mysql_query($query_DESCRIPTION, $tryconnection) or die(mysql_error());
$row_DESCRIPTION = mysqli_fetch_assoc($DESCRIPTION);

if (isset($_POST['save'])){
	if (empty($row_DESCRIPTION['PDESCRIPTION'])){
	$insert_PDESC="INSERT INTO EQDESC (DPETID, PDESCRIPTION) VALUES ('$patient','".mysql_real_escape_string($_POST['description'])."')";
	$result=mysql_query($insert_PDESC, $tryconnection) or die(mysql_error());
	}
	else {
	$update_PDESC="UPDATE EQDESC SET PDESCRIPTION='".mysql_real_escape_string($_POST['description'])."' WHERE DPETID='$patient'";
	$result=mysql_query($update_PDESC, $tryconnection) or die(mysql_error());	
	}
$closewin='self.close();';
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width, maximum-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>PATIENT'S DESCRIPTION</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->

<script type="text/javascript">
function bodyonload(){
<?php echo $closewin; ?>
}


function TextareaOnFocus(x) 
{
document.getElementById(x).style.background="white";
document.getElementById(x).style.outline="none";
document.getElementById(x).style.color="black";
}


function TextareaOnBlur(x) 
{
document.getElementById(x).style.background="white";
document.getElementById(x).style.color="black";
}

</script>


<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->
<form action="" class="FormDisplay" method="post" style="position:absolute; top:0px; left:0px;">
<table width="538" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="32" align="center" valign="bottom" class="Verdana14B">Patient's Description</td>
  </tr>
  <tr>
    <td width="538" height="520" align="center" valign="middle"><textarea name="description" id="description" cols="60" rows="35" wrap="virtual" onfocus="TextareaOnFocus(this.id)" onblur="TextareaOnBlur(this.id)" style="font-size:12px;"><?php echo $row_DESCRIPTION['PDESCRIPTION']; ?></textarea></td>
  </tr>
  <tr>
    <td height="35" align="center" valign="middle" bgcolor="#B1B4FF">
    <input name="save" type="submit" class="button" id="save" value="SAVE" />
    <input name="cancel" type="reset" class="button" id="cancel" value="CLOSE" onclick="self.close();"/>
    </td>
  </tr>	
</table>
</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
