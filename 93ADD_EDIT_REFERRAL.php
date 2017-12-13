<?php 
session_start();
require_once('../../tryconnection.php');
$refid = $_GET['refid'];
$tea = $_GET['tea'];

mysql_select_db($database_tryconnection, $tryconnection);

if ($refid!='0'){
$query_REFERRAL = "SELECT * FROM REFER WHERE REFID='$refid'";
$REFERRAL = mysql_query($query_REFERRAL, $tryconnection) or die(mysql_error());
$row_REFERRAL = mysqli_fetch_assoc($REFERRAL);
}


if (!empty($_POST['phonea'])){$phone=$_POST['phonea'].'-'.$_POST['phoneb'];}
if (!empty($_POST['phone2a'])){$phone2=$_POST['phone2a'].'-'.$_POST['phone2b'];}


if (isset($_POST['save']) && $refid!='0'){
$query_REFERRAL = sprintf("UPDATE REFER SET REFCLIN='%s', REFVET='%s', REFVETFN='%s', VETTIT='%s', ADDRESS='%s', CITY='%s', STATE='%s', ZIP='%s', CAREA='%s', CAREA2='%s', PHONE='%s', PHONE2='%s'  WHERE REFID='$refid'",
					mysql_real_escape_string($_POST['refclin']),
					mysql_real_escape_string($_POST['refvet']),
					mysql_real_escape_string($_POST['refvetfn']),
					$_POST['vettit'],
					mysql_real_escape_string($_POST['address']),
					mysql_real_escape_string($_POST['city']),
					mysql_real_escape_string($_POST['state']),
                    strtoupper($_POST['zip']), 
					$_POST['carea'],
					$_POST['carea2'],
					$phone,
					$phone2
					);
$REFERRAL = mysql_query($query_REFERRAL, $tryconnection) or die(mysql_error());
header("Location:REFERRALS_SEARCH_SCREEN.php");
}
else if (isset($_POST['save']) && $refid=='0'){
$query_REFERRAL = sprintf("INSERT INTO REFER  (REFCLIN, REFVET, REFVETFN, VETTIT, ADDRESS, CITY, STATE, ZIP, CAREA, CAREA2, PHONE, PHONE2) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
					mysql_real_escape_string($_POST['refclin']),
					mysql_real_escape_string($_POST['refvet']),
					mysql_real_escape_string($_POST['refvetfn']),
					$_POST['vettit'],
					mysql_real_escape_string($_POST['address']),
					mysql_real_escape_string($_POST['city']),
					mysql_real_escape_string($_POST['state']),
                    strtoupper($_POST['zip']), 
					$_POST['carea'],
					$_POST['carea2'],
					$phone,
					$phone2
					);
$REFERRAL = mysql_query($query_REFERRAL, $tryconnection) or die(mysql_error());
header("Location:REFERRALS_SEARCH_SCREEN.php");
}

else if (isset($_POST['delete'])){
$query_REFERRAL = "DELETE FROM REFER WHERE REFID='$refid'";
$REFERRAL = mysql_query($query_REFERRAL, $tryconnection) or die(mysql_error());
header("Location:REFERRALS_SEARCH_SCREEN.php");
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>ADD / EDIT REFERRING CLINIC AND DOCTOR</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->

<script langauge="javascript">

function bodyonload()
{
document.add_edit_referral.vettit.focus();
}

function OnClose()
{
self.close();
}

function bodyonunload()
{

}

function skip(x,y){
	if (y.length==x.maxLength){
	next=x.tabIndex;
	document.forms[0].elements[next+2].focus();
	document.forms[0].elements[next+2].select();
	}
}


</script>


<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->
<form method="post" action="" name="add_edit_referral" id="add_edit_referral" class="FormDisplay" style="position:absolute; top:0px; left:0px;">

  <table width="700" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
      <td height="44" colspan="3" align="center" class="Verdana12B">ADD/EDIT REFERRING CLINIC AND DOCTOR</td>
    </tr>
    <tr>
      <td width="146" height="30" align="right" class="Labels">Title</td>
      <td width="144" height="30" class="Labels"><input name="vettit" type="text" class="Input" id="vettit" value="<?php echo $row_REFERRAL['VETTIT'] ?>" size="4" maxlength="4" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" /></td>
      <td width="280" height="30" class="Verdana11">&nbsp;</td>
    </tr>
    <tr>
      <td height="30" align="right" class="Labels">First Name</td>
      <td height="30" colspan="2" class="Labels"><input name="refvetfn" type="text" class="Input" id="refvetfn" value="<?php echo $row_REFERRAL['REFVETFN'] ?>" size="20" maxlength="20"  onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"/></td>
    </tr>
    <tr>
      <td height="30" align="right" class="Labels">Last Name</td>
      <td height="30" colspan="2" class="Labels"><input name="refvet" type="text" class="Input" id="refvet" value="<?php echo $row_REFERRAL['REFVET'] ?>" size="25" maxlength="25"  onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" /></td>
    </tr>
    <tr>
      <td height="30" align="right" class="Labels">Clinic Name</td>
      <td height="30" colspan="2" class="Labels"><input name="refclin" type="text" class="Input" id="refclin" value="<?php echo $row_REFERRAL['REFCLIN'] ?>" size="35" maxlength="35"  onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" /></td>
    </tr>
    <tr>
      <td height="30" align="right" class="Labels">Street</td>
      <td height="30" colspan="2" class="Labels"><input name="address" type="text" class="Input" id="address" value="<?php echo $row_REFERRAL['ADDRESS'] ?>" size="30" maxlength="30" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)"  /></td>
    </tr>
    <tr>
      <td height="30" align="right" class="Labels">City</td>
      <td height="30" colspan="2" class="Labels"><input name="city" type="text" class="Input" id="city" value="<?php echo $row_REFERRAL['CITY'] ?>" size="20" maxlength="20"  onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" /></td>
    </tr>
    <tr>
      <td height="30" align="right" class="Labels">Province</td>
      <td height="30" colspan="2" class="Labels"><input name="state" type="text" class="Input" id="state" value="<?php echo $row_REFERRAL['STATE'] ?>" size="15" maxlength="15"  onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" /></td>
    </tr>
    <tr>
      <td height="30" align="right" class="Labels">Postal Code</td>
      <td height="30" class="Labels"><input name="zip" type="text" class="Input" id="zip" value="<?php echo $row_REFERRAL['ZIP'] ?>" size="8" maxlength="7"  onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" /></td>
      <td height="30" class="Verdana11">&nbsp;</td>
    </tr>
    <tr>
      <td height="30" align="right" class="Labels">Phone</td>
      <td height="30" class="Labels"><input type="text" class="Input" size="1" style="margin-left:0px;margin-right:0px; width:5px;" value="(" disabled="disabled" /><input name="carea" type="text" class="Input" id="carea" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onkeyup="skip(this, this.value);" size="3" maxlength="3" value="<?php echo $row_REFERRAL['CAREA']; ?>" style="margin-left:0px;margin-right:0px; width:22px;" tabindex="9"/><input type="text" class="Input" size="1" style="margin-left:0px;margin-right:0px; width:5px;" value=")" disabled="disabled" /><input name="phonea" type="text" class="Input" id="phonea" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php echo substr($row_REFERRAL['PHONE'],0,3); ?>"  size="3" maxlength="3" style="margin-left:0px;margin-right:0px; width:22px;" onkeyup="skip(this, this.value);" tabindex="11"/><input type="text" class="Input" size="1" style="margin-left:0px;margin-right:0px; width:5px;" value="-" disabled="disabled" /><input name="phoneb" type="text" class="Input" id="phoneb" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php echo substr($row_REFERRAL['PHONE'],4,4); ?>" size="4" maxlength="4" style="margin-left:0px;margin-right:0px; width:30px;" onkeyup="skip(this, this.value);" tabindex="13"/></td>
      <td height="30" class="Verdana11">
      <label>&nbsp;&nbsp;&nbsp;Fax 
      <input type="text" class="Input" size="1" style="margin-left:0px;margin-right:0px; width:5px;" value="(" disabled="disabled" /><input name="carea2" type="text" class="Input" id="carea2" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" size="3" maxlength="3" value="<?php echo $row_REFERRAL['CAREA2']; ?>" style="margin-left:0px;margin-right:0px; width:22px;" onkeyup="skip(this, this.value);" tabindex="15"/><input type="text" class="Input" size="1" style="margin-left:0px;margin-right:0px; width:5px;" value=")" disabled="disabled" /><input name="phone2a" type="text" class="Input" id="phone2a" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php echo substr($row_REFERRAL['PHONE2'],0,3); ?>" size="3" maxlength="3" style="margin-left:0px;margin-right:0px; width:22px;" onkeyup="skip(this, this.value);" tabindex="17"/><input type="text" class="Input" size="1" style="margin-left:0px;margin-right:0px; width:5px;" value="-" disabled="disabled" /><input name="phone2b" type="text" class="Input" id="phone2b" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php echo substr($row_REFERRAL['PHONE2'],4,4); ?>" size="4" maxlength="4" style="margin-left:0px;margin-right:0px; width:30px;" onkeyup="skip(this, this.value);" tabindex="19"/></label></td>
    </tr>
    <tr>
      <td height="30" align="right" class="Labels">Refers?</td>
      <td height="30" class="Labels"><input name="x" type="text" class="Input" id="x"  onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" size="1" maxlength="1" /></td>
      <td height="30" class="Verdana11">&nbsp;</td>
    </tr>
    <tr>
      <td height="26" align="right" class="Labels">&nbsp;</td>
      <td height="26" class="Labels">&nbsp;</td>
      <td height="26" class="Verdana11">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center" class="ButtonsTable">
      <input name="save" type="submit" class="button" id="save" value="SAVE" />
      <input name="delete" type="submit" class="button" id="delete" value="DELETE" />
      <input name="scan" type="button" class="hidden" id="scan" value="SCAN" onclick="window.open('REFERRALS_SEARCH_SCREEN.php','_self')" />
      <input name="cancel" type="reset" class="button" id="cancel" value="CANCEL" onclick="history.back()"/></td>
    </tr>
  </table>

</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
