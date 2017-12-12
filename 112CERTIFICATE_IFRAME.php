<?php 
session_start();
require_once('../../tryconnection.php'); 
include("../../ASSETS/age.php");

mysql_select_db($database_tryconnection, $tryconnection);
$query_CRITDATA = "SELECT * FROM CRITDATA LIMIT 1 ";
$CRITDATA = mysql_query($query_CRITDATA, $tryconnection) or die(mysql_error());
$row_CRITDATA = mysql_fetch_assoc($CRITDATA);

$query_DOCTOR = sprintf("SELECT DOCTOR FROM DOCTOR WHERE DOCTOR <> 'Hospital' AND SIGNEDIN = 1 ORDER BY PRIORITY ASC");
$DOCTOR = mysql_query($query_DOCTOR, $tryconnection) or die(mysql_error());
$row_DOCTOR = mysql_fetch_assoc($DOCTOR);

$query_CERTIFICATES = "SELECT * FROM CERTIFICATES WHERE CERTNAME='$_GET[certname]'";
$CERTIFICATES = mysql_query($query_CERTIFICATES, $tryconnection) or die(mysql_error());
$row_CERTIFICATES = mysql_fetch_assoc($CERTIFICATES);

$query_CLIENT = "SELECT * FROM ARCUSTO WHERE CUSTNO='$_SESSION[client]' LIMIT 1";
$CLIENT = mysql_query($query_CLIENT, $tryconnection) or die(mysql_error());
$row_CLIENT = mysql_fetch_assoc($CLIENT);

if (isset($_GET['certifpetid'])){
$petid=$_GET['certifpetid'];
}
//else {
//$petid=$_SESSION['patient'];
//}

//if ($_GET['certname']=='VACCINATION'){
$query_PATIENT = "SELECT *, DATE_FORMAT(PDOB,'%m/%d/%Y') AS PDOB, DATE_FORMAT(PRABDAT,'%m/%d/%Y') AS PRABDAT, DATE_FORMAT(POTHDAT,'%m/%d/%Y') AS POTHDAT, DATE_FORMAT(PLEUKDAT,'%m/%d/%Y') AS PLEUKDAT, DATE_FORMAT(POTHTWO,'%m/%d/%Y') AS POTHTWO, DATE_FORMAT(POTHTHR,'%m/%d/%Y') AS POTHTHR, DATE_FORMAT(POTHFOR,'%m/%d/%Y') AS POTHFOR, DATE_FORMAT(POTHFIV,'%m/%d/%Y') AS POTHFIV, DATE_FORMAT(POTHSIX,'%m/%d/%Y') AS POTHSIX, DATE_FORMAT(POTHSEV,'%m/%d/%Y') AS POTHSEV, DATE_FORMAT(POTH8,'%m/%d/%Y') AS POTH8, DATE_FORMAT(POTH9,'%m/%d/%Y') AS POTH9, DATE_FORMAT(POTH10,'%m/%d/%Y') AS POTH10, DATE_FORMAT(POTH11,'%m/%d/%Y') AS POTH11, DATE_FORMAT(POTH12,'%m/%d/%Y') AS POTH12, DATE_FORMAT(POTH13,'%m/%d/%Y') AS POTH13, DATE_FORMAT(POTH14,'%m/%d/%Y') AS POTH14, DATE_FORMAT(POTH15,'%m/%d/%Y') AS POTH15 FROM PETMAST WHERE PETID = '$petid'";
$PATIENT = mysql_query($query_PATIENT, $tryconnection) or die(mysql_error());
$row_PATIENT = mysql_fetch_assoc($PATIENT);

$dates=array();

function validity($mydate,$interv){
	if ($interv=='1' || $interv=='2' || $interv=='3'){
	$interv=$interv." year";
	}
	else if ($interv=='4' || $interv=='8'){
	$interv=$interv." weeks";	
	}
	else if ($interv=='6'){
	$interv=$interv." months";	
	}
	else {
	$interv="1 year";	
	}
	
	
	$mydate = strtotime($mydate." + ".$interv);
	$mydate = date('m/d/Y',$mydate);
	
	return $mydate;
}
	//Rabies
	if ($row_PATIENT['PRABDAT']!='00/00/0000'){
	$dates[]=array('ANTIGEN' => 'Rabies', 'VACCON' => $row_PATIENT['PRABDAT'], 'VALTIL' => validity($row_PATIENT['PRABDAT'],$row_PATIENT['PRABYEARS']));
	}
	//FVRCP/DA2P
	if ($row_PATIENT['POTHDAT']!='00/00/0000'){
	if($row_PATIENT['PETTYPE']=='1'){$datename="DA2P";} elseif ($row_PATIENT['PETTYPE']=='2'){$datename="FVRCP";} elseif ($row_PATIENT['PETTYPE']=='8'){$datename="Distemper";} else {$datename="N/A";}
	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTHDAT'], 'VALTIL' => validity($row_PATIENT['POTHDAT'],$row_PATIENT['POTHYEARS']));
	}
	//Lepto/Feline Leukemia
	if ($row_PATIENT['PLEUKDAT']!='00/00/0000'){
	if($row_PATIENT['PETTYPE']=='1'){$datename="Lepto";} elseif ($row_PATIENT['PETTYPE']=='2'){$datename="Feline Leukemia";}
	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['PLEUKDAT'], 'VALTIL' => validity($row_PATIENT['PLEUKDAT'],$row_PATIENT['PLEUKYEARS']));
	}
	//Corona/Chlamydia POTHTWO
	if ($row_PATIENT['POTHTWO']!='00/00/0000' && $row_PATIENT['PETTYPE']=='1'){
	if($row_PATIENT['PETTYPE']=='1'){$datename="Corona";} 
	
	//elseif ($row_PATIENT['PETTYPE']=='2'){$datename="Chlamydia";}
	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTHTWO'], 'VALTIL' => validity($row_PATIENT['POTHTWO'],$row_PATIENT['POTH02YEARS']));
	}
	//Parvo/FIP POTHTHR
	if ($row_PATIENT['POTHTHR']!='00/00/0000'){
	if($row_PATIENT['PETTYPE']=='1'){$datename="Parvo";} elseif ($row_PATIENT['PETTYPE']=='2'){$datename="FIP";}
	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTHTHR'], 'VALTIL' => validity($row_PATIENT['POTHTHR'],$row_PATIENT['POTH03YEARS']));
	}

	//Bordetella POTHSIX
	if ($row_PATIENT['POTHSIX']!='00/00/0000'){
	if($row_PATIENT['PETTYPE']=='1'){$datename="Bordetella";}
	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTHSIX'], 'VALTIL' => validity($row_PATIENT['POTHSIX'],$row_PATIENT['POTH06YEARS']));
	}
	//Lyme disease POTHSEV (no feline - it's declawed - not a vaccine)
	if ($row_PATIENT['POTHSEV']!='00/00/0000' && $row_PATIENT['PETTYPE']=='1'){
	$datename="Lyme disease";
	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTHSEV'], 'VALTIL' => validity($row_PATIENT['POTHSEV'],$row_PATIENT['POTH07YEARS']));
	}
//	//Annual Exam POTH8
//	if ($row_PATIENT['POTH8']!='00/00/0000'){
//	$dates[]=array('ANTIGEN' => 'Annual Exam', 'VACCON' => $row_PATIENT['POTH8'], 'VALTIL' => validity($row_PATIENT['POTH8'],$row_PATIENT['POTH08YEARS']));
//	}
	//Distemper POTH9
	if ($row_PATIENT['POTH9']!='00/00/0000'){
	if($row_PATIENT['PETTYPE']=='1'){$datename="Distemper";} elseif($row_PATIENT['PETTYPE']=='3'){$datename="Equine Arteritis";} else {$datename="";}
	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTH9'], 'VALTIL' => validity($row_PATIENT['POTH9'],$row_PATIENT['POTH09YEARS']));
	}
	//Giardia POTH10
	if ($row_PATIENT['POTH10']!='00/00/0000'){
	if($row_PATIENT['PETTYPE']=='1'){$datename="Giardia";}
	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTH10'], 'VALTIL' => validity($row_PATIENT['POTH10'],$row_PATIENT['POTH10YEARS']));
	}
//	if ($row_PATIENT['POTH11']!='00/00/0000'){
//	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTH11'], 'VALTIL' => validity($row_PATIENT['POTH11'],$row_PATIENT['POTH11YEARS']));
//	}
//	if ($row_PATIENT['POTH12']!='00/00/0000'){
//	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTH12'], 'VALTIL' => validity($row_PATIENT['POTH12'],$row_PATIENT['POTH12YEARS']));
//	}
//	if ($row_PATIENT['POTH13']!='00/00/0000'){
//	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTH13'], 'VALTIL' => validity($row_PATIENT['POTH13'],$row_PATIENT['POTH13YEARS']));
//	}
//	if ($row_PATIENT['POTH14']!='00/00/0000'){
//	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTH14'], 'VALTIL' => validity($row_PATIENT['POTH14'],$row_PATIENT['POTH14YEARS']));
//	}
//	if ($row_PATIENT['POTH15']!='00/00/0000'){
//	$dates[]=array('ANTIGEN' => $datename, 'VACCON' => $row_PATIENT['POTH15'], 'VALTIL' => validity($row_PATIENT['POTH15'],$row_PATIENT['POTH14YEARS']));
//	}

//}

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
<script type="text/javascript">

function bodyonload()
{
<?php 
if (isset($_GET['prtnclose'])){
echo "window.print(); self.close();";
}
?>
}
</script>

<style type="text/css">
#signed_doctor2{
display:none;
}
#cert_iframe{
height:450px; 
width:725px; 
overflow:auto;
}
#certdate {

}
</style>
<link rel="stylesheet" type="text/css" href="../../ASSETS/print.css" media="print"/>
<!-- InstanceEndEditable -->



<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion1" -->
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;" >
<tr><td id="ds_calclass"></td></tr>
</table>
<script type="text/javascript" src="../../ASSETS/calendar.js"></script>

<div id="WindowBody">

<div id="cert_iframe">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="450" width="730" valign="middle" align="center">
    

<div <?php if ($_GET['certname']=='EUTHANASIA'){echo "style='display:none'";} ?>><table width="511"
<tr>
   <td height="100" colspan="3">&nbsp;</td>
   </tr>  
   </table>
<table width="511" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" frame="box" rules="none" style="border-width:medium; ">
  <tr>
    <td height="104" colspan="3" align="center"><span class="Verdana14B"><?php echo $row_CRITDATA['HOSPNAME']; ?></span><br />
      <span class="Verdana12"><?php echo $row_CRITDATA['HSTREET']; ?><br />
      <?php echo $row_CRITDATA['HCITY'].", ".$row_CRITDATA['HPROV'].", ".$row_CRITDATA['HCODE']; ?><br />
      <?php echo "(".$row_CRITDATA['HPACD'].") ".$row_CRITDATA['HPPHONE']; ?></span></td>
    </tr>
  <tr>
    <td height="48" colspan="3" align="center">
    <span class="Verdana13B"><?php echo $row_CERTIFICATES['CERTNAME']; if ($_GET['certname']=='VACCINATION'){echo " STATUS";}?> CERTIFICATE</span>    <br />
     <span class="Verdana13"><input type="text" size="10" name="certdate" id="certdate" class="Input" onclick="ds_sh(this);" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php echo date('m/d/Y') ?>" /></span></td>
    </tr>
  <tr>
    <td height="15" colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td width="40" class="Verdana12">&nbsp;</td>
    <td width="417" class="Verdana12"><?php echo $row_CERTIFICATES['CERTTEXT']; ?></td>
    <td width="40" class="Verdana12">&nbsp;</td>
  </tr>
  <tr <?php if ($_GET['certname']=='VACCINATION'){echo "style='display:'";} else {echo "style='display:none'";} ?>>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12" align="center">
    
    <div>
    
    <table width="390" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30" width="130" class="Verdana11"><u>Antigen</u></td>
    <td width="130" class="Verdana11"><u>Vaccinated on</u></td>
    <td width="130" class="Verdana11"><u>Valid Until</u></td>
  </tr>
  <tr>
    <td class="Verdana12" colspan="3">
    
    <table border="0" cellpadding="0" cellspacing="0">
    
    <?php 
	
	foreach ($dates as $dates2){
	
	if (strtotime($dates2['VALTIL']) > time()){
		echo "<tr class='Verdana12'>";
		echo "<td height='20' width='130'>".$dates2['ANTIGEN']."</td>";
		echo "<td width='130'>".$dates2['VACCON']."</td>";
		echo "<td>".$dates2['VALTIL']."</td>";	
		echo "</tr>";	
		
			if ($dates2['ANTIGEN']=='Rabies'){
		echo "<tr class='Verdana11'>";
		echo "<td height='15' width='130'>&nbsp;&nbsp;&nbsp;Rabies Tag</td>";
		echo "<td width='130'>".$row_PATIENT['PRABTAG']."</td>";
		echo "<td></td>";	
		echo "</tr>";	
		echo "<tr class='Verdana11'>";
		echo "<td height='20' width='130'>&nbsp;&nbsp;&nbsp;Serum</td>";
		echo "<td width='130' colspan='2'>".$row_PATIENT['PRABSER']."</td>";
		echo "</tr>";	
			}//if ($dates2['ANTIGEN']=='Rabies')
	  }//if ($dates2['VALTIL']>date('m/d/Y'))
	}
	
	?>
    
    </table>
    </td>
  </tr>
</table>
    
    
    </div>
    
    </td>
    <td class="Verdana12">&nbsp;</td>
  </tr>
  <tr>
    <td height="84" align="center">&nbsp;</td>
    <td align="center" class="Verdana12">
    
        <img name="signature" id="signature" src="../../IMAGES/SIGNATURES/<?php if (isset($_GET['prtnclose'])) {echo $_SESSION['doctor'];} else {echo $row_DOCTOR['DOCTOR'];} ?>.jpg"  width="175" height="45" alt="signature"  style="margin-bottom:-30px;" /> 
        <br  />
        Signed ____________________________________DVM
        <br />
       <span id="signed_doctor2"><?php if (isset($_GET['prtnclose'])) {echo $_SESSION['doctor'];} else {echo $row_DOCTOR['DOCTOR'];} ?></span> 
      <select name="signed_doctor" id="signed_doctor" onchange="document.getElementById('signature').src='../../IMAGES/SIGNATURES/'+document.getElementById('signed_doctor').value+'.jpg'; document.getElementById('signed_doctor2').innerText=document.getElementById('signed_doctor').value;">
            <?php do { ?>
		<option value="<?php echo $row_DOCTOR['DOCTOR']; ?>" <?php if (isset($_GET['prtnclose']) && $_SESSION['doctor']==$row_DOCTOR['DOCTOR']) {echo "checked";}?>><?php echo $row_DOCTOR['DOCTOR']; ?></option>
<?php } while ($row_DOCTOR = mysql_fetch_assoc($DOCTOR)); ?>
        </select>      </td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td height="66" class="Verdana12"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="45%">Name: <?php echo $row_PATIENT['PETNAME']; ?><script type="text/javascript">//document.write(sessionStorage.petname);</script></td>
        <td width="55%">&nbsp;</td>
      </tr>
      <tr>
        <td>Species: <?php
		if ($row_PATIENT['PETTYPE']=='1'){$pettype = "Canine";} 
		else if ($row_PATIENT['PETTYPE']=='2'){$pettype = "Feline";} 
		else if ($row_PATIENT['PETTYPE']=='3'){$pettype = "Equine";}
		else if ($row_PATIENT['PETTYPE']=='4'){$pettype = "Bovine";}
		else if ($row_PATIENT['PETTYPE']=='5'){$pettype = "Caprine";}
		else if ($row_PATIENT['PETTYPE']=='6'){$pettype = "Porcine";}
		else if ($row_PATIENT['PETTYPE']=='7'){$pettype = "Avian";}
		else if ($row_PATIENT['PETTYPE']=='8'){$pettype = "Other";}

		 echo $pettype; ?>
		<script type="text/javascript">
		//specbree=sessionStorage.desco.split(',');
		//document.write(specbree[0]);
        </script></td>
        <td>Breed: <?php echo $row_PATIENT['PETBREED']; ?><script type="text/javascript">//document.write(specbree[1]);</script></td>
      </tr>
      <tr>
        <td colspan="2">Sex: <?php 
		if ($row_PATIENT['PNEUTER']=='1' && $row_PATIENT['PSEX']=='M'){$pneuter = "(N)";} 
		elseif ($row_PATIENT['PNEUTER']=='1' && $row_PATIENT['PSEX']=='F'){$pneuter = "(S)";}
		$desct=$row_PATIENT['PSEX'].$pneuter.', '.$row_PATIENT['PWEIGHT'].' Kgs '.$row_PATIENT['PCOLOUR'].', Born: '. $row_PATIENT['PDOB'];

		echo $desct; ?><script type="text/javascript">//document.write(sessionStorage.desct);</script></td>
        </tr>
        <tr>
        <td colspan= "2">Microchip: <?php
        echo $row_PATIENT['PTATNO'] ;
        ?> </td>
        </tr>
    </table></td>
    <td class="Verdana12">&nbsp;</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="11%">Client:</td>
        <td width="89%"><?php echo $row_CLIENT['TITLE']." ".$row_CLIENT['CONTACT']." ".$row_CLIENT['COMPANY']; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><?php echo $row_CLIENT['ADDRESS1']." ".$row_CLIENT['ADDRESS2']; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><?php echo $row_CLIENT['CITY']." ".$row_CLIENT['STATE']." ".$row_CLIENT['ZIP']; ?></td>
      </tr>
    </table></td>
    <td class="Verdana12">&nbsp;</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">&nbsp;</td>
  </tr>
</table>
</div>



<div <?php if ($_GET['certname']=='EUTHANASIA'){echo "style='display:'";} else {echo "style='display:none'";} ?>>
<table width="511"
<tr>
   <td height="200" colspan="3">&nbsp;</td>
   </tr>  
   </table>
<table width="511" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000" frame="box" rules="none" style="border-width:medium; ">
  <tr>
    <td height="104" colspan="3" align="center"><span class="Verdana14B"><?php echo $row_CRITDATA['HOSPNAME']; ?></span><br />
      <span class="Verdana12"><?php echo $row_CRITDATA['HSTREET']; ?><br />
      <?php echo $row_CRITDATA['HCITY'].", ".$row_CRITDATA['HPROV'].", ".$row_CRITDATA['HCODE']; ?><br />
      <?php echo "(".$row_CRITDATA['HPACD'].") ".$row_CRITDATA['HPPHONE']; ?></span></td>
    </tr>
  <tr>
    <td height="48" colspan="3" align="center">
    <span class="Verdana13B">REQUEST TO PERFORM <?php echo $row_CERTIFICATES['CERTNAME']; ?></span>    <br />
     <span class="Verdana13"><input type="text" size="10" name="certdate2" id="certdate2" class="Input" onclick="ds_sh(this);" onfocus="InputOnFocus(this.id)" onblur="InputOnBlur(this.id)" value="<?php echo date('m/d/Y') ?>" /></span></td>
    </tr>
  <tr>
    <td height="15" colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td width="40" class="Verdana12">&nbsp;</td>
    <td width="417" class="Verdana12">
	<?php $certtext=explode('.',$row_CERTIFICATES['CERTTEXT']);
	foreach ($certtext as $certtext2){
		if ($certtext2!=""){
	echo $certtext2.".<br /><br />";
		}
	} 
	?>
    </td>
    <td width="40" class="Verdana12">&nbsp;</td>
  </tr>
  <tr>
    <td height="84" align="center">&nbsp;</td>
    <td align="center" class="Verdana12">
    Signed ____________________________________
    <br />
	Signature of Owner or Authorized Agent
    </td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td height="66" class="Verdana12"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="45%">Name: <?php //echo $row_PATIENT['PETNAME']; ?><script type="text/javascript">document.write(sessionStorage.petname);</script></td>
        <td width="55%">&nbsp;</td>
      </tr>
      <tr>
        <td>Species: <?php
//		if ($row_PATIENT['PETTYPE']=='1'){$pettype = "Canine";} 
//		else if ($row_PATIENT['PETTYPE']=='2'){$pettype = "Feline";} 
//		else if ($row_PATIENT['PETTYPE']=='3'){$pettype = "Equine";}
//		else if ($row_PATIENT['PETTYPE']=='4'){$pettype = "Bovine";}
//		else if ($row_PATIENT['PETTYPE']=='5'){$pettype = "Caprine";}
//		else if ($row_PATIENT['PETTYPE']=='6'){$pettype = "Porcine";}
//		else if ($row_PATIENT['PETTYPE']=='7'){$pettype = "Avian";}
//		else if ($row_PATIENT['PETTYPE']=='8'){$pettype = "Other";}
//		echo $pettype; 
		 ?>
		<script type="text/javascript">
		specbree=sessionStorage.desco.split(',');
		document.write(specbree[0]);
        </script></td>
        <td>Breed: <?php //echo $row_PATIENT['PETBREED']; ?><script type="text/javascript">document.write(specbree[1]);</script></td>
      </tr>
      <tr>
        <td colspan="2">Sex: <?php 
//		if ($row_PATIENT['PNEUTER']=='1' && $row_PATIENT['PSEX']=='M'){$pneuter = "(N)";} 
//		elseif ($row_PATIENT['PNEUTER']=='1' && $row_PATIENT['PSEX']=='F'){$pneuter = "(S)";}
//		$desct=$row_PATIENT['PSEX'].$pneuter.', '.$row_PATIENT['PWEIGHT'].' Lbs '.$row_PATIENT['PCOLOUR'].', Born: '. $row_PATIENT['PDOB'];
//
//		echo $desct; 
		?><script type="text/javascript">document.write(sessionStorage.desct);</script></td>
        </tr>
    </table></td>
    <td class="Verdana12">&nbsp;</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="11%">Client:</td>
        <td width="89%"><?php echo $row_CLIENT['TITLE']." ".$row_CLIENT['CONTACT']." ".$row_CLIENT['COMPANY']; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><?php echo $row_CLIENT['ADDRESS1']." ".$row_CLIENT['ADDRESS2']; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><?php echo $row_CLIENT['CITY']." ".$row_CLIENT['STATE']." ".$row_CLIENT['ZIP']; ?></td>
      </tr>
  <?php if ($_GET['certname']=='EUTHANASIA') {
  echo '<tr height="40"> <td colspan="3">Reason for euthanasia&nbsp;&nbsp;_______________________________</td>' ;
  echo '<tr> ' ;
  echo '<tr height="30"> <td colspan="3">Received for euthanasia by ____________________________</td>' ;
  echo '<tr> ' ;
  echo '<td class="Verdana12" colspan="3">Ace____cc(IM\SQ)&nbsp;&nbsp;Ket____cc(IM\SQ)&nbsp;&nbsp;K\V_____cc(IM\SQ)&nbsp;Torb_____cc(IM\SQ) Euth/IV____cc&nbsp;&nbsp;Card:(Y\N)&nbsp;&nbsp;&nbsp;PT:Y\N&nbsp;&nbsp;&nbsp;Logged_____</td>' ;
  echo '</tr>' ;
  } ?>
    </table></td>
    <td class="Verdana12">&nbsp;</td>
  </tr>
  <tr>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">&nbsp;</td>
    <td class="Verdana12">&nbsp;</td>
  </tr>
</table></div>


    </td>
  </tr>
</table></div>

</div>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
