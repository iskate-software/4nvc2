<?php 
session_start();
require_once('../tryconnection.php'); 
mysqli_select_db($tryconnection, $database_tryconnection);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width, maximum-scale=2" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>APPOINTMENT SCHEDULING</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->

<style type="text/css">
<!--
.SphereBg {
	color: #000000;
	font-size: 12px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-weight:400;
	background-image:url(../IMAGES/svetle_zelena_koule.jpg) ;
	background-repeat: no-repeat;
	padding-left: 25px;
	padding-top: 3px;
}

.newSphereBg {
color: #000000;
	font-size: 12px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-weight:400;
	background-image: url(../IMAGES/tmave_zelena_koule.jpg);
	background-repeat: no-repeat;
	padding-left: 25px;
	padding-top: 3px;
font-weight:bold;
cursor:pointer;
}

-->
</style>

<script type="text/javascript">

var leftpos = opener.window.screenX;
var toppos = opener.window.screenY;
moveTo(leftpos+150,toppos+20);

function bodyonload(){
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function SphereBgOnMouseOver(x) {
	x.className=(x.className=="SphereBg")?"newSphereBg":"newSphereBg";
}

function SphereBgOnMouseOut(x) {
	x.className=(x.className=="newSphereBg")?"SphereBg":"SphereBg";
}

</script>

<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload();" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->
<form method="post" action="" name="admin_dir" id="admin_dir" style="position:absolute; top:0px; left:0px; background-color:#FFFFFF; z-index:10">
<table width="400" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
        <td height="57" colspan="3" align="center" valign="bottom" class="Verdana13B">Appointments Administration</td>
    </tr>
    <tr>
      <td height="30">&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td width="86" height="31">&nbsp;</td>
      <td width="242" align="left" valign="top" class="SphereBg" onmouseover="SphereBgOnMouseOver(this);" onmouseout="SphereBgOnMouseOut(this);" onclick="window.open('','_self','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no')">Make/Change Appointments</td>
      <td width="72">&nbsp;</td>
    </tr>
    <tr>
        <td width="86" height="31">&nbsp;</td>
      <td width="242" align="left" valign="top" class="SphereBg" onmouseover="SphereBgOnMouseOver(this);" onmouseout="SphereBgOnMouseOut(this);" onclick="window.open('','_self','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no')">E-Slots</td>
      <td width="72">&nbsp;</td>
    </tr>
    <tr>
        <td width="86" height="31">&nbsp;</td>
      <td width="242" align="left" valign="top" class="SphereBg" onmouseover="SphereBgOnMouseOver(this);" onmouseout="SphereBgOnMouseOut(this);" onclick="window.open('','_self','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no')">Block-off Appts</td>
      <td width="72">&nbsp;</td>
    </tr>
    <tr>
        <td width="86" height="31">&nbsp;</td>
      <td width="242" align="left" valign="top" class="SphereBg" onmouseover="SphereBgOnMouseOver(this);" onmouseout="SphereBgOnMouseOut(this);" onclick="window.open('','_self','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no')">Ultrasound</td>
      <td width="72">&nbsp;</td>
    </tr>
    <tr>
        <td width="86" height="31">&nbsp;</td>
      <td width="242" align="left" valign="top" class="SphereBg" onmouseover="SphereBgOnMouseOver(this);" onmouseout="SphereBgOnMouseOut(this);" onclick="window.open('','_self','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no')">Multi-pet</td>
      <td width="72">&nbsp;</td>
    </tr>
    <tr>
        <td width="86" height="31">&nbsp;</td>
      <td width="242" align="left" valign="top" class="SphereBg" onmouseover="SphereBgOnMouseOver(this);" onmouseout="SphereBgOnMouseOut(this);" onclick="window.open('','_self','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no')">Cats</td>
      <td width="72">&nbsp;</td>
    </tr>
    <tr>
        <td width="86" height="31">&nbsp;</td>
      <td width="242" align="left" valign="top" class="SphereBg" onmouseover="SphereBgOnMouseOver(this);" onmouseout="SphereBgOnMouseOut(this);" onclick="window.open('HOURS.php','_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, height=495, width=405, copyhistory=no')">Set up Holidays and Hospital Hours</td>
      <td width="72">&nbsp;</td>
    </tr>
    <tr>
        <td width="86" height="31">&nbsp;</td>
      <td width="242" align="left" valign="top" class="SphereBg" onmouseover="SphereBgOnMouseOver(this);" onmouseout="SphereBgOnMouseOut(this);" onclick="window.open('DOCHOURS.php','_blank','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, height=432, width=400, copyhistory=no')">Set up Resources</td>
      <td width="72">&nbsp;</td>
    </tr>
    <tr>
      <td height="30">&nbsp;</td>
      <td></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" align="center" valign="middle" class="ButtonsTable">
        <input name="button" type="button" class="button" id="button" value="CANCEL" onclick="self.close()" />      </td>
    </tr>
</table>
</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
