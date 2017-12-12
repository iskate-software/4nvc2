<?php 
session_start();
require_once('../../tryconnection.php'); 

if (isset($_GET['patient'])){
$patient=$_GET['patient'];
$_SESSION['patient']=$_GET['patient'];
}
elseif (isset($_SESSION['patient'])){
$patient=$_SESSION['patient'];
}

mysql_select_db($database_tryconnection, $tryconnection);
$query_PATIENT = "SELECT *, DATE_FORMAT(PRABDAT,'%m/%d/%Y') AS PRABDAT, DATE_FORMAT(POTHDAT,'%m/%d/%Y') AS POTHDAT, DATE_FORMAT(PLEUKDAT,'%m/%d/%Y') AS PLEUKDAT, DATE_FORMAT(POTHTWO,'%m/%d/%Y') AS POTHTWO, DATE_FORMAT(POTHTHR,'%m/%d/%Y') AS POTHTHR, DATE_FORMAT(POTHFOR,'%m/%d/%Y') AS POTHFOR, DATE_FORMAT(POTHFIV,'%m/%d/%Y') AS POTHFIV, DATE_FORMAT(POTHSIX,'%m/%d/%Y') AS POTHSIX, DATE_FORMAT(POTHSEV,'%m/%d/%Y') AS POTHSEV, DATE_FORMAT(POTH8,'%m/%d/%Y') AS POTH8, DATE_FORMAT(POTH9,'%m/%d/%Y') AS POTH9, DATE_FORMAT(POTH10,'%m/%d/%Y') AS POTH10, DATE_FORMAT(POTH11,'%m/%d/%Y') AS POTH11, DATE_FORMAT(POTH12,'%m/%d/%Y') AS POTH12, DATE_FORMAT(POTH13,'%m/%d/%Y') AS POTH13, DATE_FORMAT(POTH14,'%m/%d/%Y') AS POTH14, DATE_FORMAT(POTH15,'%m/%d/%Y') AS POTH15 FROM PETMAST JOIN ARCUSTO ON (ARCUSTO.CUSTNO=PETMAST.CUSTNO) WHERE PETID = '$patient' LIMIT 1";
$PATIENT = mysql_query($query_PATIENT, $tryconnection) or die(mysql_error());
$row_PATIENT = mysql_fetch_assoc($PATIENT);

if (isset($_POST['save']) && $patient!='0'){
$updateSQL = "UPDATE PETMAST SET PRABDAT=STR_TO_DATE('".$_POST['PRABDAT']."','%m/%d/%Y'), POTHDAT=STR_TO_DATE('".$_POST['POTHDAT']."','%m/%d/%Y'), PLEUKDAT=STR_TO_DATE('".$_POST['PLEUKDAT']."','%m/%d/%Y'), POTHTWO=STR_TO_DATE('".$_POST['POTHTWO']."','%m/%d/%Y'), POTHTHR=STR_TO_DATE('".$_POST['POTHTHR']."','%m/%d/%Y'), POTHFOR=STR_TO_DATE('".$_POST['POTHFOR']."','%m/%d/%Y'), POTHFIV=STR_TO_DATE('".$_POST['POTHFIV']."','%m/%d/%Y'), POTHSIX=STR_TO_DATE('".$_POST['POTHSIX']."','%m/%d/%Y'), POTHSEV=STR_TO_DATE('".$_POST['POTHSEV']."','%m/%d/%Y'), POTH8=STR_TO_DATE('".$_POST['POTH8']."','%m/%d/%Y'), POTH9=STR_TO_DATE('".$_POST['POTH9']."','%m/%d/%Y'), POTH10=STR_TO_DATE('".$_POST['POTH10']."','%m/%d/%Y'), POTH11=STR_TO_DATE('".$_POST['POTH11']."','%m/%d/%Y'), POTH12=STR_TO_DATE('".$_POST['POTH12']."','%m/%d/%Y'), POTH13=STR_TO_DATE('".$_POST['POTH13']."','%m/%d/%Y'), POTH14=STR_TO_DATE('".$_POST['POTH14']."','%m/%d/%Y'), POTH15=STR_TO_DATE('".$_POST['POTH15']."','%m/%d/%Y'), PRABYEARS='$_POST[PRABYEARS]', POTHYEARS='$_POST[POTHYEARS]', PLEUKYEARS='$_POST[PLEUKYEARS]', POTH02YEARS='$_POST[POTH02YEARS]', POTH03YEARS='$_POST[POTH03YEARS]', POTH04YEARS='$_POST[POTH04YEARS]', POTH05YEARS='$_POST[POTH05YEARS]', POTH06YEARS='$_POST[POTH06YEARS]', POTH07YEARS='$_POST[POTH07YEARS]', POTH08YEARS='$_POST[POTH08YEARS]', POTH09YEARS='$_POST[POTH09YEARS]', POTH10YEARS='$_POST[POTH10YEARS]', POTH11YEARS='$_POST[POTH11YEARS]', POTH12YEARS='$_POST[POTH12YEARS]', POTH13YEARS='$_POST[POTH13YEARS]', POTH14YEARS='$_POST[POTH14YEARS]', POTH15YEARS='$_POST[POTH15YEARS]' WHERE PETID='$patient' LIMIT 1";
$Result1 = mysql_query($updateSQL, $tryconnection) or die(mysql_error());
$closewin='self.close();';
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width, maximum-scale=1.0" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>DATES <?php if ($_GET['species']=='1'){echo "CANINE";} else if ($_GET['species']=='2'){echo "FELINE";} else if ($_GET['species']=='3'){echo "EQUINE";} else if ($_GET['species']=='4'){echo "BOVINE";} else if ($_GET['species']=='5'){echo "CAPRINE";} else if ($_GET['species']=='6'){echo "PORCINE";} else if ($_GET['species']=='7'){echo "AVIAN";} else if ($_GET['species']=='8'){echo "OTHER";}; ?></title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.style1 {color: #0000FF}
-->
</style>

<script type="text/javascript">
function bodyonload(){
<?php echo $closewin; ?>
}

function bodyonunload(){
//DATES <!--
opener.document.patients.PRABDAT.value=document.dates.PRABDAT.value;
opener.document.patients.POTHDAT.value=document.dates.POTHDAT.value;
opener.document.patients.PLEUKDAT.value=document.dates.PLEUKDAT.value;
opener.document.patients.POTHTWO.value=document.dates.POTHTWO.value;
opener.document.patients.POTHTHR.value=document.dates.POTHTHR.value;
opener.document.patients.POTHFOR.value=document.dates.POTHFOR.value;
opener.document.patients.POTHFIV.value=document.dates.POTHFIV.value;
opener.document.patients.POTHSIX.value=document.dates.POTHSIX.value;
opener.document.patients.POTHSEV.value=document.dates.POTHSEV.value;
opener.document.patients.POTH8.value=document.dates.POTH8.value;
opener.document.patients.POTH9.value=document.dates.POTH9.value;
opener.document.patients.POTH10.value=document.dates.POTH10.value;
opener.document.patients.POTH11.value=document.dates.POTH11.value;
opener.document.patients.POTH12.value=document.dates.POTH12.value;
opener.document.patients.POTH13.value=document.dates.POTH13.value;
opener.document.patients.POTH14.value=document.dates.POTH14.value;
opener.document.patients.POTH15.value=document.dates.POTH15.value;
//YEARS
opener.document.patients.PRABYEARS.value=document.dates.PRABYEARS.value;
opener.document.patients.POTHYEARS.value=document.dates.POTHYEARS.value;
opener.document.patients.PLEUKYEARS.value=document.dates.PLEUKYEARS.value;
opener.document.patients.POTH02YEARS.value=document.dates.POTH02YEARS.value;
opener.document.patients.POTH03YEARS.value=document.dates.POTH03YEARS.value;
opener.document.patients.POTH04YEARS.value=document.dates.POTH04YEARS.value;
opener.document.patients.POTH05YEARS.value=document.dates.POTH05YEARS.value;
opener.document.patients.POTH06YEARS.value=document.dates.POTH06YEARS.value;
opener.document.patients.POTH07YEARS.value=document.dates.POTH07YEARS.value;
opener.document.patients.POTH08YEARS.value=document.dates.POTH08YEARS.value;
opener.document.patients.POTH09YEARS.value=document.dates.POTH09YEARS.value;
opener.document.patients.POTH10YEARS.value=document.dates.POTH10YEARS.value;
opener.document.patients.POTH11YEARS.value=document.dates.POTH11YEARS.value;
opener.document.patients.POTH12YEARS.value=document.dates.POTH12YEARS.value;
opener.document.patients.POTH13YEARS.value=document.dates.POTH13YEARS.value;
opener.document.patients.POTH14YEARS.value=document.dates.POTH14YEARS.value;
opener.document.patients.POTH15YEARS.value=document.dates.POTH15YEARS.value;
-->
}
</script>

<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;" >
<tr><td id="ds_calclass"></td></tr>
</table>
<script type="text/javascript" src="../../ASSETS/calendar.js"></script>


<form action="" method="post" name="dates" style="position:absolute; top:0px; left:0px; background-color:#FFFFFF; z-index:10">
<table width="350" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="55" colspan="4" align="center" valign="middle" class="Verdana12B">        Dates: <?php if ($_GET['species']=='1'){echo "Canine";} else if ($_GET['species']=='2'){echo "Feline";} else if ($_GET['species']=='3'){echo "Equine";} else if ($_GET['species']=='4'){echo "Bovine";} else if ($_GET['species']=='5'){echo "Caprine";} else if ($_GET['species']=='6'){echo "Porcine";} else if ($_GET['species']=='7'){echo "Avian";} else if ($_GET['species']=='8'){echo "Other";}; ?></td>
  </tr>
  
  <tr>
    <td width="141" height="30" align="right" valign="middle" class="Verdana11">Annual
      Exam </td>
    <td width="27" height="30" class="Verdana11">&nbsp;</td>
    <td width="85" height="30" class="Verdana11"><input name="POTH8" type="text" class="Input" id="POTH8" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['POTH8']!="00/00/0000" && !empty($row_PATIENT['POTH8'])) {echo ", '".substr($row_PATIENT['POTH8'],0,2)."','".substr($row_PATIENT['POTH8'],3,2)."','".substr($row_PATIENT['POTH8'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['POTH8']=="00/00/0000"){echo "";} else {echo $row_PATIENT['POTH8']; }?>"/></td>
    <td width="85" height="30" class="Verdana11">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="30" align="right" valign="middle" class="Verdana11">Rabies</td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="PRABDAT" type="text" class="Input" id="PRABDAT" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['PRABDAT']!="00/00/0000" && !empty($row_PATIENT['PRABDAT'])) {echo ", '".substr($row_PATIENT['PRABDAT'],0,2)."','".substr($row_PATIENT['PRABDAT'],3,2)."','".substr($row_PATIENT['PRABDAT'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['PRABDAT']=="00/00/0000"){echo "";} else {echo $row_PATIENT['PRABDAT']; }?>"/></td>
    <td height="30" class="Verdana11"><input name="PRABYEARS" id="PRABYEARS" type="text" class="Input" size="1" maxlength="1" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if ($row_PATIENT['PRABYEARS']==0){echo "";} else {echo $row_PATIENT['PRABYEARS'];} ?>" /></td>
  </tr>
  
  <tr>
    <td height="30" align="right" valign="middle" class="Verdana11"><?php if($_GET['species']=='1'){echo "DA2P";} elseif ($_GET['species']=='2'){echo "FVRCP";} else {echo "N/A";}?></td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="POTHDAT" type="text" class="Input" id="POTHDAT" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['POTHDAT']!="00/00/0000" && !empty($row_PATIENT['POTHDAT'])) {echo ", '".substr($row_PATIENT['POTHDAT'],0,2)."','".substr($row_PATIENT['POTHDAT'],3,2)."','".substr($row_PATIENT['POTHDAT'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['POTHDAT']=="00/00/0000"){echo "";} else {echo $row_PATIENT['POTHDAT']; }?>"/></td>
    <td height="30" class="Verdana11"><input name="POTHYEARS" id="POTHYEARS" type="text" class="Input" size="1" maxlength="1" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if ($row_PATIENT['POTHYEARS']==0){echo "";} else {echo $row_PATIENT['POTHYEARS'];} ?>" /></td>
  </tr>
  
  <tr>
    <td height="30" align="right" valign="middle" class="Verdana11"><?php if($_GET['species']=='1'){echo "Lepto";} elseif ($_GET['species']=='2'){echo "Feline Leukemia";} else {echo "N/A";}?></td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="PLEUKDAT" type="text" class="Input" id="PLEUKDAT" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['PLEUKDAT']!="00/00/0000" && !empty($row_PATIENT['PLEUKDAT'])) {echo ", '".substr($row_PATIENT['PLEUKDAT'],0,2)."','".substr($row_PATIENT['PLEUKDAT'],3,2)."','".substr($row_PATIENT['PLEUKDAT'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['PLEUKDAT']=="00/00/0000"){echo "";} else {echo $row_PATIENT['PLEUKDAT']; }?>"/></td>
    <td height="30" class="Verdana11"><input name="PLEUKYEARS" id="PLEUKYEARS" type="text" class="Input" size="1" maxlength="1" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if ($row_PATIENT['PLEUKYEARS']==0){echo "";} else {echo $row_PATIENT['PLEUKYEARS'];} ?>" /></td>
  </tr>
  
  <tr>
    <td height="30" align="right" valign="middle" class="Verdana11"><?php if($_GET['species']=='1'){echo "Corona";} elseif ($_GET['species']=='2'){echo "Chlamydia";} else {echo "";} ?></td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="POTHTWO" type="text" class="Input" id="POTHTWO" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['POTHTWO']!="00/00/0000" && !empty($row_PATIENT['POTHTWO'])) {echo ", '".substr($row_PATIENT['POTHTWO'],0,2)."','".substr($row_PATIENT['POTHTWO'],3,2)."','".substr($row_PATIENT['POTHTWO'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['POTHTWO']=="00/00/0000"){echo "";} else {echo $row_PATIENT['POTHTWO']; }?>"/></td>
    <td height="30" class="Verdana11"><input name="POTH02YEARS" id="POTH02YEARS" type="text" class="Input" size="1" maxlength="1" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if ($row_PATIENT['POTH02YEARS']==0){echo "";} else {echo $row_PATIENT['POTH02YEARS'];} ?>" /></td>
  </tr>
  
  <tr>
    <td height="30" align="right" valign="middle" class="Verdana11"><?php if($_GET['species']=='1'){echo "Parvo";} elseif ($_GET['species']=='2'){echo "FIP";} else {echo "N/A";} ?></td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="POTHTHR" type="text" class="Input" id="POTHTHR" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['POTHTHR']!="00/00/0000" && !empty($row_PATIENT['POTHTHR'])) {echo ", '".substr($row_PATIENT['POTHTHR'],0,2)."','".substr($row_PATIENT['POTHTHR'],3,2)."','".substr($row_PATIENT['POTHTHR'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['POTHTHR']=="00/00/0000"){echo "";} else {echo $row_PATIENT['POTHTHR']; }?>"/></td>
    <td height="30" class="Verdana11"><input name="POTH03YEARS" id="POTH03YEARS" type="text" class="Input" size="1" maxlength="1" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if ($row_PATIENT['POTH03YEARS']==0){echo "";} else {echo $row_PATIENT['POTH03YEARS'];} ?>" /></td>
  </tr>
  
  <tr <?php if($_GET['species']=='2') {echo "style='display:none'";} ?>>
    <td height="30" align="right" valign="middle" class="Verdana11"><?php if($_GET['species']=='1'){echo "Bordetella";} else {echo "";} ?></td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="POTHSIX" type="text" class="Input" id="POTHSIX" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['POTHSIX']!="00/00/0000" && !empty($row_PATIENT['POTHSIX'])) {echo ", '".substr($row_PATIENT['POTHSIX'],0,2)."','".substr($row_PATIENT['POTHSIX'],3,2)."','".substr($row_PATIENT['POTHSIX'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['POTHSIX']=="00/00/0000"){echo "";} else {echo $row_PATIENT['POTHSIX']; }?>"/></td>
    <td height="30" class="Verdana11"><input name="POTH06YEARS" id="POTH06YEARS" type="text" class="Input" size="1" maxlength="1" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if ($row_PATIENT['POTH06YEARS']==0){echo "";} else {echo $row_PATIENT['POTH06YEARS'];} ?>" /></td>
  </tr>
  
  <tr>
    <td height="30" align="right" valign="middle" class="Verdana11"><?php if($_GET['species']=='1'){echo "Lyme disease";} elseif ($_GET['species']=='2'){echo "Declawed";} elseif ($_GET['species']=='4'){echo "Magnet";} else {echo "N/A";} ?></td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="POTHSEV" type="text" class="Input" id="POTHSEV" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['POTHSEV']!="00/00/0000" && !empty($row_PATIENT['POTHSEV'])) {echo ", '".substr($row_PATIENT['POTHSEV'],0,2)."','".substr($row_PATIENT['POTHSEV'],3,2)."','".substr($row_PATIENT['POTHSEV'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['POTHSEV']=="00/00/0000"){echo "";} else {echo $row_PATIENT['POTHSEV']; }?>"/></td>
    <td height="30" class="Verdana11">&nbsp;</td>
  </tr>
  
  <tr <?php if($_GET['species']=='2') {echo "style='display:none'";} ?>>
    <td height="30" align="right" valign="middle" class="Verdana11"><?php if($_GET['species']=='1'){echo "Distemper";} elseif($_GET['species']=='3'){echo "Equine Arteritis";} else {echo "";} ?></td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="POTH9" type="text" class="Input" id="POTH9" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['POTH9']!="00/00/0000" && !empty($row_PATIENT['POTH9'])) {echo ", '".substr($row_PATIENT['POTH9'],0,2)."','".substr($row_PATIENT['POTH9'],3,2)."','".substr($row_PATIENT['POTH9'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['POTH9']=="00/00/0000"){echo "";} else {echo $row_PATIENT['POTH9']; }?>"/></td>
    <td height="30" class="Verdana11"><input name="POTH09YEARS" id="POTH09YEARS" type="text" class="Input" size="1" maxlength="1" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if ($row_PATIENT['POTH09YEARS']==0){echo "";} else {echo $row_PATIENT['POTH09YEARS'];} ?>" /></td>
  </tr>
  
  <tr <?php if($_GET['species']=='2') {echo "style='display:none'";} ?>>
    <td height="30" align="right" valign="middle" class="Verdana11"><?php if($_GET['species']=='1'){echo "Giardia";} else {echo "N/A";} ?></td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="POTH10" type="text" class="Input" id="POTH10" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['POTH10']!="00/00/0000" && !empty($row_PATIENT['POTH10'])) {echo ", '".substr($row_PATIENT['POTH10'],0,2)."','".substr($row_PATIENT['POTH10'],3,2)."','".substr($row_PATIENT['POTH10'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['POTH10']=="00/00/0000"){echo "";} else {echo $row_PATIENT['POTH10']; }?>"/></td>
    <td height="30" class="Verdana11"><input name="POTH10YEARS2" id="POTH10YEARS2" type="text" class="Input" size="1" maxlength="1" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php if ($row_PATIENT['POTH10YEARS2']==0){echo "";} else {echo $row_PATIENT['POTH10YEARS2'];} ?>" /></td>
  </tr>
  
  <tr <?php if($_GET['species']=='2') {echo "style='display:none'";} ?>>
    <td height="30" align="right" valign="middle" class="Verdana11"><?php if($_GET['species']=='1'){echo "Heartworm";} elseif ($_GET['species']=='3'){echo "West Nile";} else {echo "";} ?></td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="POTHFOR" type="text" class="Input" id="POTHFOR" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['POTHFOR']!="00/00/0000" && !empty($row_PATIENT['POTHFOR'])) {echo ", '".substr($row_PATIENT['POTHFOR'],0,2)."','".substr($row_PATIENT['POTHFOR'],3,2)."','".substr($row_PATIENT['POTHFOR'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['POTHFOR']=="00/00/0000"){echo "";} else {echo $row_PATIENT['POTHFOR']; }?>"/></td>
    <td height="30" class="Verdana11">&nbsp;</td>
  </tr>
  
  <tr>
    <td height="30" align="right" valign="middle" class="Verdana11">Fecal</td>
    <td height="30" class="Verdana11">&nbsp;</td>
    <td height="30" class="Verdana11"><input name="POTHFIV" type="text" class="Input" id="POTHFIV" size="10" maxlength="10" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" title="MM/DD/YYYY" onclick="ds_sh(this<?php if ($row_PATIENT['POTHFIV']!="00/00/0000" && !empty($row_PATIENT['POTHFIV'])) {echo ", '".substr($row_PATIENT['POTHFIV'],0,2)."','".substr($row_PATIENT['POTHFIV'],3,2)."','".substr($row_PATIENT['POTHFIV'],6,4)."'";} ?>)" value="<?php if ($row_PATIENT['POTHFIV']=="00/00/0000"){echo "";} else {echo $row_PATIENT['POTHFIV']; }?>"/></td>
    <td height="30" class="Verdana11">&nbsp;</td>
  </tr>
  <?php if($_GET['species']=='2') {echo '<tr><td height="119" colspan="4" align="center" valign="bottom" class="Verdana11Blue">&nbsp;</td></tr>';} ?>
  
  <tr>
    <td height="31" colspan="4" align="center" valign="bottom" class="Verdana11Blue">1,2,3
      Years; 4,8 Weeks; 6 Months</td>
    </tr>
  <tr>
    <td height="35" colspan="4" align="center" valign="middle" bgcolor="#B1B4FF">
    <input name="save" type="submit" class="button" id="save" value="SAVE"  <?php if ($patient=='0') {echo 'onclick="self.close();"';} ?>/>
    <input name="cancel" type="reset" class="button" id="cancel" value="CLOSE" onclick="self.close();" />    </td>
  </tr>
</table>
<input type="hidden" name="POTH11" value="" />
<input type="hidden" name="POTH12" value="" />
<input type="hidden" name="POTH13" value="" />
<input type="hidden" name="POTH14" value="" />
<input type="hidden" name="POTH15" value="" />



<input type="hidden" name="POTH04YEARS" value="" />
<input type="hidden" name="POTH05YEARS" value="" />
<input type="hidden" name="POTH07YEARS" value="" />
<input type="hidden" name="POTH08YEARS" value="" />
<input type="hidden" name="POTH11YEARS" value="" />
<input type="hidden" name="POTH12YEARS" value="" />
<input type="hidden" name="POTH13YEARS" value="" />
<input type="hidden" name="POTH14YEARS" value="" />
<input type="hidden" name="POTH15YEARS" value="" />

</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
