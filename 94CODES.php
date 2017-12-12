<?php 
session_start();
require_once('../../tryconnection.php');


mysql_select_db($database_tryconnection, $tryconnection);


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<title>DVMANAGER CODE LIST</title>


<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>


<script type="text/javascript">

function bodyonload()
{
var leftpos = opener.window.screenX;
var toppos = opener.window.screenY;
moveTo(leftpos+678,toppos-35);
}


function highliteline(x,y){
}
</script>

<style type="text/css">
.commentarea{
font-family:"Times New Roman", Times, serif;
font-size:16px;
}
</style>
</head>

<body onload="bodyonload()" onunload="bodyonunload()">

<form method="post" action="" name="modify_text" style="position:absolute; top:0px; left:0px; background-color:#FFFFFF;">

<table border="1" cellspacing="0" cellpadding="0" bordercolor="#CCCCCC" rules="rows" frame="below">
  <tr>
    <td width="10" class="Verdana12">&nbsp;</td>
    <td width="300" class="Verdana12">Patient's name</td>
    <td width="105" class="Verdana12">$PETNAME</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Patient's age in years and months</td>
    <td class="Verdana12">$AGE</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Date of birth in words (10 January, 2009)</td>
    <td class="Verdana12">$DOB</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Age in years</td>
    <td class="Verdana12">$YEAR</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Todays date in words</td>
    <td class="Verdana12">$DATE</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12"> Title</td>
    <td class="Verdana12">$TITLE</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Title + Surname (Sir/Madam for blank)</td>
    <td class="Verdana12">$SALUTATION</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Client's first name</td>
    <td class="Verdana12">$NAME</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Client's last name</td>
    <td class="Verdana12">$SURNAME</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Street, Unit and Rural Roads</td>
    <td class="Verdana12">$ADDRESS</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">City</td>
    <td class="Verdana12">$CITY</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Province</td>
    <td class="Verdana12">$PROVINCE</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Postal Code</td>
    <td class="Verdana12">$PCODE</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Telephone (705)111-1111</td>
    <td class="Verdana12">$PHONE</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Telephone2 (705)222-2222</td>
    <td class="Verdana12">$BPHONE</td>
  </tr>
  <tr>
    <td class="hidden">&nbsp;</td>
    <td class="hidden">From Invorddoc if called from Search Invoice Line Item.</td>
    <td class="hidden">$DOCTOR</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Species</td>
    <td class="Verdana12">$SPECIES</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Breed</td>
    <td class="Verdana12">$BREED</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">&quot;his/her/their&quot;</td>
    <td class="Verdana12">$SEX</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">&quot;he/she/they&quot;</td>
    <td class="Verdana12">$HESHE</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Rabies date</td>
    <td class="Verdana12">$RABIES</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">&quot;is/are&quot;</td>
    <td class="Verdana12">$ISARE</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">&quot;yes/no&quot; for Neutered</td>
    <td class="Verdana12">$NEUTER</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Colour</td>
    <td class="Verdana12">$COLOUR</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12"> Title + First name + Last name</td>
    <td class="Verdana12"> $CLIENT</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">Doctor's signature</td>
    <td class="Verdana12">$DOCSIGN</td>
  </tr>  
  <tr class="ButtonsTable">
    <td colspan="3" align="center"><input name="close" type="button" class="button" id="close" value="CLOSE" onclick="self.close();"/>     </td>
   </tr>
</table>

</form>
</body>
</html>
