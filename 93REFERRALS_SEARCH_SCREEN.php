<?php 
require_once('../../tryconnection.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>REFERRING DOCTORS/CLINICS</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->

<script type="text/javascript">

function bodyonload()
{
var leftpos = opener.window.screenX;
var toppos = opener.window.screenY;
moveTo(leftpos+40,toppos+0);
sessionStorage.setItem('tea','<?php echo $_GET['tea']; ?>');
document.searchreferral.refvet.focus();
document.getElementById('<?php echo $_GET['id']; ?>').bgColor="#FF0099";
document.searchreferral.submit();
}


function setsorting(x,y)
{
self.location='REFERRALS_SEARCH_SCREEN.php?tea=<?php echo $_GET['tea']; ?>&sorting=' + x + '&id=' + y;
}


function bodyonunload()
{
opener.document.referral.refvet.value = document.searchreferral.doctor.value;
opener.document.referral.refclin.value = document.searchreferral.clinic.value;
}
</script>
<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->

  <form action="REFERRALS_SEARCH_IFRAME.php" method="get" target="list" name="searchreferral" style="position:absolute; top:0px; left:0px;">
  <input type="hidden" name="sorting" id="sorting" value="<?php echo $_GET['sorting']; ?>" />

  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="700" align="left" valign="top">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" frame="below" rules="rows">
    <tr class="Verdana11Bwhite">
      <td width="5" bgcolor="#000000">&nbsp;</td>
      <td width="139" align="left" bgcolor="#000000" class="Verdana11Bwhite" id="doctor" onclick="setsorting('REFVET','doctor');" onmouseover="document.getElementById(this.id).style.cursor='pointer';">Doctor</td>
      <td width="" bgcolor="#000000" class="Verdana11Bwhite" id="clinic" onclick="setsorting('REFCLIN','clinic');" onmouseover="document.getElementById(this.id).style.cursor='pointer';">Clinic</td>
    </tr>
  <!--  <tr>
<td colspan="4">

 <table width="100%" cellpadding="0" cellspacing="0">-->  
          <tr bgcolor="#FFFFFF">
            <td width="5" height="10">&nbsp;</td>
        <td height="10" align="left"><input name="refvet" type="text" class="Input" id="refvet" size="15"  onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onkeyup="this.form.submit()"/></td>
        
        <td height="10" align="left"><input name="refclin" type="text" class="Input" id="refclin" size="35"  onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" onkeyup="this.form.submit()"/></td>
          </tr>
          <!--</table>  </td>
  </tr>-->
          <tr>
            <td colspan="6">
              
              <iframe name="list" scrolling="no" height="400" width="100%" frameborder="0" src="REFERRALS_SEARCH_IFRAME.php" ></iframe>  </td>
    </tr>
      </table></td>
    </tr>
    <tr>
      <td align="center" valign="middle"class="ButtonsTable">
        <input class="button" type="button" name="Submit" value="ADD" onclick="window.open('','_self')" disabled="disabled" style="display:none" />
        <input name="add" type="button" class="button" id="add" onclick="window.open('ADD_EDIT_REFERRAL.php?refid=0','_self')" value="ADD" />
      <input class="button" type="reset" name="cancel" value="CLOSE" onclick="self.close()" />  </td>
    </tr>	
  </table>
<input type="hidden" name="doctor" value="" />
<input type="hidden" name="clinic" value="" />
<input type="hidden" name="check" value="1"  />
  </form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
