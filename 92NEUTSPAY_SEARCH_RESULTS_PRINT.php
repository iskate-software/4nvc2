<?php 
session_start();
require_once('../../tryconnection.php');

$xtable = $_GET['xtable'];

mysql_select_db($database_tryconnection, $tryconnection);

$select_RECALL = "SELECT *, DATE_FORMAT(PDOB, '%m/%d/%Y') AS PDOB, DATE_FORMAT(PRABDAT, '%m/%d/%Y') AS PRABDAT, DATE_FORMAT(POTHDAT, '%m/%d/%Y') AS POTHDAT, DATE_FORMAT(POTHFOR, '%m/%d/%Y') AS POTHFOR, 
DATE_FORMAT(POTH8, '%m/%d/%Y') AS POTH8 FROM $xtable ORDER BY COMPANY,CONTACT ASC";
$RECALL = mysql_query($select_RECALL, $tryconnection) or die(mysql_error());
$row_RECALL = mysql_fetch_assoc($RECALL);
$totalRows_RECALL = mysql_num_rows($RECALL);

if ($_GET['report']!='Display List'){

$query_REPLOG="INSERT INTO REPLOG (TYPE, CLIENTSC, REPORT, SEARCH, PATIENTS) VALUES ('$xtable', 'All Clients', '$_GET[report]', '".mysql_real_escape_string($_GET['xsearch'])."', '$totalRows_RECALL')";
$REPLOG = mysql_query($query_REPLOG, $tryconnection) or die(mysql_error());
}

$query_CRITDATA = "SELECT * FROM CRITDATA LIMIT 1";
$CRITDATA = mysql_query($query_CRITDATA, $tryconnection) or die(mysql_error());
$row_CRITDATA = mysql_fetch_assoc($CRITDATA);

$query_POSTCARDS = "SELECT * FROM POSTCARDS WHERE TYPE='$xtable' AND SUBTYPE='$_GET[xsubtype]' LIMIT 1";
$POSTCARDS = mysql_query($query_POSTCARDS, $tryconnection) or die(mysql_error());
$row_POSTCARDS = mysql_fetch_assoc($POSTCARDS);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>NEUTSPAY MAILING LIST</title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">

function bodyonload(){
<?php if ($_GET['report']=='Print List'){
echo "window.print();";
} ?>
}

function OnClose()
{
self.close();
}

function bodyonunload()
{}

</script>

<style type="text/css">
body {
background-color:#FFFFFF;
overflow:auto;
}
#prtclosebuttons{
display:block;
}


</style>
<link rel="stylesheet" type="text/css" href="../../ASSETS/print.css" media="print"/>

<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->
<?php if ($_GET['report']=='Print List' || $_GET['report']=='Display List'){ ?>
  <table width="854" border="1" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" frame="void" rules="cols">
    <tr>
      <td bgcolor="#000000" class="Verdana11Bwhite">Client/Patient</td>
      <td align="center" bgcolor="#000000" class="Verdana11Bwhite">DOB</td>
      <td align="center" bgcolor="#000000" class="Verdana11Bwhite">Rabies</td>
      <td align="center" bgcolor="#000000" class="Verdana11Bwhite">Booster</td>
      <td align="center" bgcolor="#000000" class="Verdana11Bwhite">Exam</td>
    </tr>    
     <?php do { ?>   
    <tr class="Verdana11">
      <td height="20"><span style="background-color:#FFFF00" class="Verdana13B"><?php echo $row_RECALL['TITLE']." ".$row_RECALL['COMPANY'].", ".$row_RECALL['CONTACT']; ?></span>, <?php echo $row_RECALL['ADDRESS1'].", ".$row_RECALL['ADDRESS2'].", ".$row_RECALL['CITY'].", ".$row_RECALL['STATE'].", ".$row_RECALL['ZIP']; ?>&nbsp;&nbsp;<br />
        <?php echo "(".$row_RECALL['CAREA'].")".$row_RECALL['PHONE']." (".$row_RECALL['CAREA2'].")".$row_RECALL['PHONE2']; ?></td>
      <td height="20">&nbsp;</td>
      <td height="20">&nbsp;</td>
      <td height="20">&nbsp;</td>
      <td height="20">&nbsp;</td>
    </tr>
    <tr class="Verdana12">
      <td height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    <span class="Verdana13B" style="background-color:<?php if ($row_RECALL['PSEX']=='M'){echo '#DBEBF0';} else {echo '#F9DEE9';} ?>"><?php echo $row_RECALL['PETNAME']; ?></span>
      <?php
	  if ($row_RECALL['PETTYPE']=='1'){$species="Canine";} else if ($row_RECALL['PETTYPE']=='2'){$species="Feline";} else if ($row_RECALL['PETTYPE']=='3'){$species="Equine";} else if ($row_RECALL['PETTYPE']=='4'){$species="Bovine";} else if ($row_RECALL['PETTYPE']=='5'){$species="Caprine";} else if ($row_RECALL['PETTYPE']=='6'){$species="Porcine";} else if ($row_RECALL['PETTYPE']=='7'){$species="Avian";} else if ($row_RECALL['PETTYPE']=='8'){$species="Other";}
	  echo $species;
	  echo ", ".$row_RECALL['PETBREED'].", ".$row_RECALL['PSEX']; 
	  
	  if ($row_RECALL['PNEUTER']=='1' && $row_RECALL['PSEX']=='M'){echo "(N)";} elseif ($row_RECALL['PNEUTER']=='1' && $row_RECALL['PSEX']=='F'){echo "(S)";}?></td>
      <td align="center" class="Verdana12"><?php echo $row_RECALL['PDOB']; ?></td>
      <td align="center" class="Verdana12"><?php echo $row_RECALL['PRABDAT']; ?></td>
      <td align="center" class="Verdana12"><?php echo $row_RECALL['POTHDAT']; ?></td>
      <td align="center" class="Verdana12"><?php echo $row_RECALL['POTH8']; ?></td>
    </tr>
    <tr>
      <td colspan="5"><hr size="1" color="#CCCCCC" style="margin:0px;"/></td>
    </tr>
 <?php } while ($row_RECALL = mysql_fetch_assoc($RECALL)); ?>   
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td></td>
      <td width="80"></td>
      <td width="80"></td	>
      <td width="80"></td>
      <td width="80"></td>
    </tr>
  </table>
<?php } 

else if ($_GET['report']=='Post Cards'){ 
// Aggregate all patients within a family.

$AGGRE1 = "DROP TEMPORARY TABLE IF EXISTS FAMILY" ;
$AGGRE2 = "CREATE TEMPORARY TABLE FAMILY LIKE NEUTSPAY" ;
$AGGRE3 = "ALTER TABLE FAMILY MODIFY COLUMN PETNAME VARCHAR(255)" ;
$AGGRE1A = mysql_query($AGGRE1, $tryconnection) or die(mysql_error()) ;
$AGGRE2A = mysql_query($AGGRE2, $tryconnection) or die(mysql_error()) ;
$AGGRE3A = mysql_query($AGGRE3, $tryconnection) or die(mysql_error()) ;

$currentcust = 0 ;
$isitand= 0 ;
do {
 if ($row_RECALL['CUSTNO'] != $currentcust) {
  $fixcomp = mysql_real_escape_string($row_RECALL['COMPANY']) ;
  $fixcontact = mysql_real_escape_string($row_RECALL['CONTACT']) ;
  $fixadd = mysql_real_escape_string($row_RECALL['ADDRESS1']) ;
  $fixcity = mysql_real_escape_string($row_RECALL['CITY']) ;
  $fixbreed = mysql_real_escape_string($row_RECALL['PETBREED']) ;
  $fixpet = mysql_real_escape_string($row_RECALL['PETNAME']) ;
  $AGGRE4 = "INSERT INTO FAMILY (CUSTNO,COMPANY,CONTACT,ADDRESS1,ADDRESS2,CITY,STATE,ZIP,
  CAREA,PHONE,PETNAME,PETTYPE,PSEX,PDOB,PRABDAT,POTHDAT,POTHFOR,POTH8,PETBREED,PNEUTER) 
  VALUES  ('$row_RECALL[CUSTNO]','$fixcomp', '$fixcontact','$fixadd','$row_RECALL[ADDRESS2]','$fixcity','$row_RECALL[STATE]','$row_RECALL[ZIP]',
  '$row_RECALL[CAREA]','$row_RECALL[PHONE]','$fixpet','$row_RECALL[PETTYPE]','$row_RECALL[PSEX]','$row_RECALL[PDOB]','$row_RECALL[PRABDAT]','$row_RECALL[POTHDAT]',
  '$row_RECALL[POTHFOR]', '$row_RECALL[POTH8]','$fixbreed','$row_RECALL[PNEUTER]')";
  $AGGRE4A = mysql_query($AGGRE4, $tryconnection) or die(mysql_error()) ;
  $currentcust = $row_RECALL['CUSTNO'] ;
  $isitand = 1 ;
 }
 else {
 if ($isitand == 1) {
 $AGGRE5 = "UPDATE FAMILY SET FAMILY.PETNAME = CONCAT('$row_RECALL[PETNAME]',' and ',FAMILY.PETNAME ) WHERE CUSTNO = '$currentcust' " ;
 } else {
 $AGGRE5 = "UPDATE FAMILY SET FAMILY.PETNAME = CONCAT('$row_RECALL[PETNAME]',', ',FAMILY.PETNAME ) WHERE CUSTNO = '$currentcust' " ;
 }
 $AGGRE5A = mysql_query($AGGRE5, $tryconnection) or die(mysql_error()) ;
 $isitand ++ ;
 }
} while ($row_RECALL = mysql_fetch_assoc($RECALL));

// Then redo the recall search with the new table

$select_RECALL = "SELECT CUSTNO,COMPANY,CONTACT,ADDRESS1,ADDRESS2,CITY,STATE,ZIP, CAREA,PHONE,PETNAME,PETTYPE,PSEX, 
DATE_FORMAT(PDOB, '%m/%d/%Y') AS PDOB, DATE_FORMAT(PRABDAT, '%m/%d/%Y') AS PRABDAT, DATE_FORMAT(POTHDAT, '%m/%d/%Y') AS POTHDAT, DATE_FORMAT(POTHFOR, '%m/%d/%Y') AS POTHFOR, 
DATE_FORMAT(POTH8, '%m/%d/%Y') AS POTH8 FROM FAMILY ORDER BY COMPANY,CONTACT ASC";
$RECALL = mysql_query($select_RECALL, $tryconnection) or die(mysql_error());
$row_RECALL = mysql_fetch_assoc($RECALL);
$totalRows_RECALL = mysql_num_rows($RECALL);

$i = 1;

do { ?>   
  <div class="postcard">
  <table width="854" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="614" height="355" align="left" valign="bottom"><table width="614" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  width="614" height="58" class="Verdana9" > &nbsp; </td>
        </tr>
      <tr>
        <td height="25" colspan="3"><span class="Verdana14B"><?php echo $row_CRITDATA['HOSPNAME']; ?></span><br />
<span class="Verdana11"><?php echo $row_CRITDATA['HSTREET']; ?>, <?php echo $row_CRITDATA['HCITY']; ?>, <?php echo $row_CRITDATA['HPROV']; ?> <?php echo $row_CRITDATA['HCODE']; ?></span></td>
        </tr>
      <tr>
        <td width="337" height="255">
		<?php
		
		//age
		$myears=' years '; $mmonths=' months'; $mpdob=strtotime($row_RECALL['PDOB']); $now=mktime(date('m/d/Y')); $diff=round(($now-$mpdob)/(60*60*24));
		if ($diff < 185) {$week= round($diff/7); $age = $week.' weeks';} 
		else {$years = round($diff / 365.25) ; $months = round($diff/30.4) - $years*12 ;
			 	if ($months < 0) {$months += 12; $years -= 1 ;}
			 	if (round($years) == 1) {$myears = ' year ' ;}
				if (round($months) == 1) {$mmonths = ' month' ;}
				if(round($years) == 0) {$age = $months.$mmonths;}	
			 	elseif (round($months) == 0) {$age = $years.$myears;} 
				else{$age = $years.$myears.$months.$mmonths;}}
		
	  //his/her
	  if ($row_RECALL['PSEX']=='M'){$sex='his';$heshe = 'he' ;} elseif ($row_RECALL['PSEX']=='F'){$sex='her';$heshe = 'she' ;}
	  
	  if (strpos($row_RECALL['PETNAME'], ' and ') !== false) {
	  $sex = 'their' ;
	  $heshe = 'they' ;
	  }
	  
	  //neuter yes/no
	  if ($row_RECALL['PNEUTER']=='1'){$neuter='Yes';} else {$neuter='No';}
		
	  //is/are	
	  $isare = 'is';
	  if (strpos($row_RECALL['PETNAME'], ' and ') !== false) {
	  $isare = 'are' ;
	  }
	  
	  //salutation
	  if (empty($row_RECALL['COMPANY'])){
	  $salutation = "Sir/Madam";
	  }
	  else {
	  $salutation = $row_RECALL['TITLE']." ".$row_RECALL['COMPANY'];
	  }
	  //species
	   if ($row_RECALL['PETTYPE']=='1'){$species="Canine";} else if ($row_RECALL['PETTYPE']=='2'){$species="Feline";} else if ($row_RECALL['PETTYPE']=='3'){$species="Equine";} else if ($row_RECALL['PETTYPE']=='4'){$species="Bovine";} else if ($row_RECALL['PETTYPE']=='5'){$species="Caprine";} else if ($row_RECALL['PETTYPE']=='6'){$species="Porcine";} else if ($row_RECALL['PETTYPE']=='7'){$species="Avian";} else if ($row_RECALL['PETTYPE']=='8'){$species="Other";}
	    
		//message
		$message = str_replace('$PETNAME', $row_RECALL['PETNAME'], $row_POSTCARDS['MESSAGE']); 
		$message = str_replace('$AGE', $age, $message); 
		$message = str_replace('$DOB', $row_RECALL['PDOB'], $message); 
		$message = str_replace('$YEAR', $years, $message); 
		$message = str_replace('$DATE', date('jS F, Y'), $message); 
		$message = str_replace('$TITLE', $row_RECALL['TITLE'], $message); 
		$message = str_replace('$SALUTATION', $salutation, $message); 
		$message = str_replace('$NAME', $row_RECALL['CONTACT'], $message); 
		$message = str_replace('$SURNAME', $row_RECALL['COMPANY'], $message); 
		$message = str_replace('$ADDRESS', $row_RECALL['ADDRESS1']."<br /> ".$row_RECALL['ADDRESS2'], $message); 
		$message = str_replace('$CITY', $row_RECALL['CITY'], $message); 
		$message = str_replace('$PROVINCE', $row_RECALL['STATE'], $message); 
		$message = str_replace('$PCODE', $row_RECALL['ZIP'], $message); 
		$message = str_replace('$PHONE', $row_RECALL['PHONE'], $message); 
		$message = str_replace('$BPHONE', $row_RECALL['PHONE2'], $message); 
		$message = str_replace('$SPECIES', $species, $message); 
		$message = str_replace('$BREED', $row_RECALL['PETBREED'], $message); 
		$message = str_replace('$SEX', $sex, $message); 
		$message = str_replace('$RABIES', $row_RECALL['PRABDAT'], $message); 
		$message = str_replace('$ISARE', $isare, $message);  
		$message = str_replace('$HESHE', $heshe, $message); 
		$message = str_replace('$HISHER', $hisher, $message);
		$message = str_replace('$NEUTER', $neuter, $message); 
		$message = str_replace('$COLOUR', $row_RECALL['PCOLOUR'], $message); 
		$message = str_replace('$CLIENT', $row_RECALL['TITLE']." ".$row_RECALL['CONTACT']." ".$row_RECALL['COMPANY'], $message); 
		//$message = str_replace('$DOCSIGN', '...........', $message); 
				
		echo '<span style="white-space:pre-wrap;">'.$message."</span>";
		?>
        
        </td>
        <td width="20">&nbsp;</td>
        <td width="237">
		<?php 
		//address
		$address = str_replace('$PETNAME', $row_RECALL['PETNAME'], $row_POSTCARDS['ADDRESS']); 
		$address = str_replace('$AGE', $age, $address); 
		$address = str_replace('$DOB', $row_RECALL['PDOB'], $address); 
		$address = str_replace('$YEAR', $years, $address); 
		$address = str_replace('$DATE', date('d F, Y'), $address); 
		$address = str_replace('$TITLE', $row_RECALL['TITLE'], $address); 
		$address = str_replace('$SALUTATION', $salutation, $address); 
		$address = str_replace('$NAME', $row_RECALL['CONTACT'], $address); 
		$address = str_replace('$SURNAME', $row_RECALL['COMPANY'], $address); 
		$address = str_replace('$ADDRESS', $row_RECALL['ADDRESS1']."<br /> ".$row_RECALL['ADDRESS2'], $address); 
		$address = str_replace('$CITY', $row_RECALL['CITY'], $address); 
		$address = str_replace('$PROVINCE', $row_RECALL['STATE'], $address); 
		$address = str_replace('$PCODE', $row_RECALL['ZIP'], $address); 
		$address = str_replace('$PHONE', $row_RECALL['PHONE'], $address); 
		$address = str_replace('$BPHONE', $row_RECALL['PHONE2'], $address); 
		$address = str_replace('$SPECIES', $species, $address); 
		$address = str_replace('$BREED', $row_RECALL['PETBREED'], $address); 
		$address = str_replace('$SEX', $sex, $address); 
		$address = str_replace('$RABIES', $row_RECALL['PRABDAT'], $address); 
		$address = str_replace('$ISARE', $isare, $address); 
		$address = str_replace('$NEUTER', $neuter, $address); 
		$address = str_replace('$COLOUR', $row_RECALL['PCOLOUR'], $address); 
		$address = str_replace('$CLIENT', $row_RECALL['TITLE']." ".$row_RECALL['CONTACT']." ".$row_RECALL['COMPANY'], $address); 
		//$address = str_replace('$DOCSIGN', '...........', $address); 

		echo '<span style="white-space:pre-wrap;">'.$address."</span>"; ?>
		</td>
      </tr>
      <tr>
        <td height="<?php if ($i == 3) {echo '0'; $i = 0;} else {echo "27";} ?>"></td>
        <td></td>
        <td></td>
      </tr>
    </table></td>
    <td width="234">&nbsp;</td>
  </tr>
</table>
  </div>
 <?php $i = $i+1; } while ($row_RECALL = mysql_fetch_assoc($RECALL)); 
  } 
  
  
  else if ($_GET['report']=='Merging'){  
// Aggregate all patients within a family.

$AGGRE1 = "DROP TEMPORARY TABLE IF EXISTS FAMILY" ;
$AGGRE2 = "CREATE TEMPORARY TABLE FAMILY LIKE NEUTSPAY" ;
$AGGRE3 = "ALTER TABLE FAMILY MODIFY COLUMN PETNAME VARCHAR(255)" ;
$AGGRE1A = mysql_query($AGGRE1, $tryconnection) or die(mysql_error()) ;
$AGGRE2A = mysql_query($AGGRE2, $tryconnection) or die(mysql_error()) ;
$AGGRE3A = mysql_query($AGGRE3, $tryconnection) or die(mysql_error()) ;
$currentcust = 0 ;
$isitand= 0 ;
do {
 if ($row_RECALL['CUSTNO'] != $currentcust) {
  $fixcomp = mysql_real_escape_string($row_RECALL['COMPANY']) ;
  $fixcontact = mysql_real_escape_string($row_RECALL['CONTACT']) ;
  $fixpet = mysql_real_escape_string($row_RECALL['PETNAME']) ;
  $fixadd = mysql_real_escape_string($row_RECALL['ADDRESS1']) ;
  $fixcity = mysql_real_escape_string($row_RECALL['CITY']);
  $fixbreed = mysql_real_escape_string($row_RECALL['PETBREED']) ;
  $AGGRE4 = "INSERT INTO FAMILY (CUSTNO,COMPANY,CONTACT,ADDRESS1,ADDRESS2,CITY,STATE,ZIP,
  CAREA,PHONE,PETNAME,PETTYPE,PSEX,PDOB,PRABDAT,POTHDAT,POTHFOR,POTH8,PETBREED,PNEUTER) 
  VALUES  ('$row_RECALL[CUSTNO]','$fixcomp','$fixcontact','$fixadd','$row_RECALL[ADDRESS2]','$fixcity','$row_RECALL[STATE]','$row_RECALL[ZIP]',
  '$row_RECALL[CAREA]','$row_RECALL[PHONE]','$fixpet','$row_RECALL[PETTYPE]','$row_RECALL[PSEX]','$row_RECALL[PDOB]','$row_RECALL[PRABDAT]','$row_RECALL[POTHDAT]',
  '$row_RECALL[POTHFOR]', '$row_RECALL[POTH8]','$fixbreed','$row_RECALL[PNEUTER]')";
  $AGGRE4A = mysql_query($AGGRE4, $tryconnection) or die(mysql_error()) ;
  $currentcust = $row_RECALL['CUSTNO'] ;
  $isitand = 1 ;
 }
 else {
 if ($isitand == 1) {
 $AGGRE5 = "UPDATE FAMILY SET FAMILY.PETNAME = CONCAT('$row_RECALL[PETNAME]',' and ',FAMILY.PETNAME ) WHERE CUSTNO = '$currentcust' LIMIT 1 " ;
 } else {
 $AGGRE5 = "UPDATE FAMILY SET FAMILY.PETNAME = CONCAT('$row_RECALL[PETNAME]',', ',FAMILY.PETNAME ) WHERE CUSTNO = '$currentcust'  LIMIT 1" ;
 }
 $AGGRE5A = mysql_query($AGGRE5, $tryconnection) or die(mysql_error()) ;
 $isitand ++ ;
 }
} while ($row_RECALL = mysql_fetch_assoc($RECALL));
// Then redo the recall search with the new table

$select_RECALL = "SELECT CUSTNO,COMPANY,CONTACT,ADDRESS1,ADDRESS2,CITY,STATE,ZIP, CAREA,PHONE,PETNAME,PETTYPE,PSEX, 
DATE_FORMAT(PDOB, '%m/%d/%Y') AS PDOB, DATE_FORMAT(PRABDAT, '%m/%d/%Y') AS PRABDAT, DATE_FORMAT(POTHDAT, '%m/%d/%Y') AS POTHDAT, DATE_FORMAT(POTHFOR, '%m/%d/%Y') AS POTHFOR, 
DATE_FORMAT(POTH8, '%m/%d/%Y') AS POTH8 FROM FAMILY ORDER BY COMPANY,CONTACT ASC";
$RECALL = mysql_query($select_RECALL, $tryconnection) or die(mysql_error());
$row_RECALL = mysql_fetch_assoc($RECALL);
$totalRows_RECALL = mysql_num_rows($RECALL);
  
  $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'] ;
  
  $fp = fopen("$DOCUMENT_ROOT/Merges/PUPKIT.TXT", "w") ;
  if ($fp) {
  echo "Opened Successfully" ;
  }

  do { 
  
  if ($row_RECALL['PSEX']=='M'){$sex='his'; $heshe='he' ;} elseif ($row_RECALL['PSEX']=='F'){$sex='her';; $heshe='she' ;}
  
	  if (strpos($row_RECALL['PETNAME'], ' and ') !== false) {
	  $sex = 'their' ;
	  $heshe = 'they' ;
	  }  
	  
  if ($row_RECALL['PNEUTER']=='1'){$neuter='Yes';} else {$neuter='No';}

	  //is/are	
	  $isare = 'is';
	  if (strpos($row_RECALL['PETNAME'], ' and ') !== false) {
	  $isare = 'are' ;
	  }
	  
  $line = array($row_RECALL['TITLE'], $row_RECALL['CONTACT'], $row_RECALL['COMPANY'], $row_RECALL['ADDRESS1']. ' '.$row_RECALL['ADDRESS2'], 
  $row_RECALL['TITLE'].' '.$row_RECALL['COMPANY'], $row_RECALL['CITY'], $row_RECALL['STATE'], $row_RECALL['ZIP'],  $row_RECALL['PHONE'],
  date('F jS Y'), $row_RECALL['PETNAME'],  $sex,'  ', $row_RECALL['PDOB'], $row_RECALL['PRABDAT'], $row_RECALL['POTHFOR'], 
  $row_RECALL['POTHDAT'], $row_RECALL['POTH8'],  $isare ,$row_RECALL['CYCLE'], $row_RECALL['PETTYPE'],$neuter,) ;
 
  fputcsv($fp, $line, chr(9)) ;
  
  echo "<span class='Verdana12'>".$row_RECALL['TITLE'].'", "'.$row_RECALL['CONTACT'].'", "'.$row_RECALL['COMPANY'].'", "'.$row_RECALL['ADDRESS1'].' '.$row_RECALL['ADDRESS2'].
  '", "'.$row_RECALL['TITLE'].' '.$row_RECALL['COMPANY'].'", "'.$row_RECALL['CITY'].'", "'.$row_RECALL['STATE'].'", "'.$row_RECALL['ZIP'].$row_RECALL['PHONE'].'", "'.
  '", "'.date('F jS Y').'", "'.$row_RECALL['PETNAME'].'", "'.$sex.'", "'.$row_RECALL['PDOB'].'", "'.$row_RECALL['PRABDAT'].'", "'.$row_RECALL['POTHFOR'].'", "'.$row_RECALL['POTHDAT'].
  '", "'.$row_RECALL['POTH8'].'", "'.$isare.'", "'.$row_RECALL['CYCLE'].'", "'.$row_RECALL['PETTYPE'].'", "'.$neuter.'"'."</span><br />";

	} while ($row_RECALL = mysql_fetch_assoc($RECALL));
	fclose($fp) ;
 if ($fp) {
 echo "closed" ; }
 }


  else if ($_GET['report']=='Labels'){  
	echo "Labels";
	}

  else if ($_GET['report']=='Custom Letters'){  
	echo "Custom Letters";
$i = 1;

do { ?>   
  <div class="postcard">
  <table width="854" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="614" height="355" align="left" valign="top"><table width="614" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="25" colspan="3"><span class="Verdana14B"><?php echo $row_CRITDATA['HOSPNAME']; ?></span><br />
<span class="Verdana11"><?php echo $row_CRITDATA['HSTREET']; ?>, <?php echo $row_CRITDATA['HCITY']; ?>, <?php echo $row_CRITDATA['HPROV']; ?> <?php echo $row_CRITDATA['HCODE']; ?></span></td>
        </tr>
      <tr>
        <td width="337" height="315">
		<?php
		
		//age
		$myears=' years '; $mmonths=' months'; $mpdob=strtotime($row_RECALL['PDOB']); $now=mktime(date('m/d/Y')); $diff=round(($now-$mpdob)/(60*60*24));
		if ($diff < 185) {$week= round($diff/7); $age = $week.' weeks';} 
		else {$years = round($diff / 365.25) ; $months = round($diff/30.4) - $years*12 ;
			 	if ($months < 0) {$months += 12; $years -= 1 ;}
			 	if (round($years) == 1) {$myears = ' year ' ;}
				if (round($months) == 1) {$mmonths = ' month' ;}
				if(round($years) == 0) {$age = $months.$mmonths;}	
			 	elseif (round($months) == 0) {$age = $years.$myears;} 
				else{$age = $years.$myears.$months.$mmonths;}}
		
	  //his/her
	  if ($row_RECALL['PSEX']=='M'){$sex='his';} elseif ($row_RECALL['PSEX']=='F'){$sex='her';}
	  
	  //neuter yes/no
	  if ($row_RECALL['PNEUTER']=='1'){$neuter='Yes';} else {$neuter='No';}
		
	  //is/are	
	  $isare = 'is';
	  
	  //salutation
	  if (empty($row_RECALL['COMPANY'])){
	  $salutation = "Sir/Madam";
	  }
	  else {
	  $salutation = $row_RECALL['TITLE']." ".$row_RECALL['COMPANY'];
	  }
	  //species
	   if ($row_RECALL['PETTYPE']=='1'){$species="Canine";} else if ($row_RECALL['PETTYPE']=='2'){$species="Feline";} else if ($row_RECALL['PETTYPE']=='3'){$species="Equine";} else if ($row_RECALL['PETTYPE']=='4'){$species="Bovine";} else if ($row_RECALL['PETTYPE']=='5'){$species="Caprine";} else if ($row_RECALL['PETTYPE']=='6'){$species="Porcine";} else if ($row_RECALL['PETTYPE']=='7'){$species="Avian";} else if ($row_RECALL['PETTYPE']=='8'){$species="Other";}
	    
		//message
		$message = str_replace('$PETNAME', $row_RECALL['PETNAME'], $row_POSTCARDS['MESSAGE']); 
		$message = str_replace('$AGE', $age, $message); 
		$message = str_replace('$DOB', $row_RECALL['PDOB'], $message); 
		$message = str_replace('$YEAR', $years, $message); 
		$message = str_replace('$DATE', date('n F, Y'), $message); 
		$message = str_replace('$TITLE', $row_RECALL['TITLE'], $message); 
		$message = str_replace('$SALUTATION', $salutation, $message); 
		$message = str_replace('$NAME', $row_RECALL['CONTACT'], $message); 
		$message = str_replace('$SURNAME', $row_RECALL['COMPANY'], $message); 
		$message = str_replace('$ADDRESS', $row_RECALL['ADDRESS1']."<br /> ".$row_RECALL['ADDRESS2'], $message); 
		$message = str_replace('$CITY', $row_RECALL['CITY'], $message); 
		$message = str_replace('$PROVINCE', $row_RECALL['STATE'], $message); 
		$message = str_replace('$PCODE', $row_RECALL['ZIP'], $message); 
		$message = str_replace('$PHONE', $row_RECALL['PHONE'], $message); 
		$message = str_replace('$BPHONE', $row_RECALL['PHONE2'], $message); 
		$message = str_replace('$SPECIES', $species, $message); 
		$message = str_replace('$BREED', $row_RECALL['PETBREED'], $message); 
		$message = str_replace('$SEX', $sex, $message); 
		$message = str_replace('$RABIES', $row_RECALL['PRABDAT'], $message); 
		$message = str_replace('$ISARE', $isare, $message); 
		$message = str_replace('$NEUTER', $neuter, $message); 
		$message = str_replace('$COLOUR', $row_RECALL['PCOLOUR'], $message); 
		$message = str_replace('$CLIENT', $row_RECALL['TITLE']." ".$row_RECALL['CONTACT']." ".$row_RECALL['COMPANY'], $message); 
		//$message = str_replace('$DOCSIGN', '...........', $message); 
				
		echo '<span style="white-space:pre-wrap;">'.$message."</span>";
		?>
        
        </td>
        <td width="40">&nbsp;</td>
        <td width="237">
		<?php 
		//address
		$address = str_replace('$PETNAME', $row_RECALL['PETNAME'], $row_POSTCARDS['ADDRESS']); 
		$address = str_replace('$AGE', $age, $address); 
		$address = str_replace('$DOB', $row_RECALL['PDOB'], $address); 
		$address = str_replace('$YEAR', $years, $address); 
		$address = str_replace('$DATE', date('n F, Y'), $address); 
		$address = str_replace('$TITLE', $row_RECALL['TITLE'], $address); 
		$address = str_replace('$SALUTATION', $salutation, $address); 
		$address = str_replace('$NAME', $row_RECALL['CONTACT'], $address); 
		$address = str_replace('$SURNAME', $row_RECALL['COMPANY'], $address); 
		$address = str_replace('$ADDRESS', $row_RECALL['ADDRESS1']."<br /> ".$row_RECALL['ADDRESS2'], $address); 
		$address = str_replace('$CITY', $row_RECALL['CITY'], $address); 
		$address = str_replace('$PROVINCE', $row_RECALL['STATE'], $address); 
		$address = str_replace('$PCODE', $row_RECALL['ZIP'], $address); 
		$address = str_replace('$PHONE', $row_RECALL['PHONE'], $address); 
		$address = str_replace('$BPHONE', $row_RECALL['PHONE2'], $address); 
		$address = str_replace('$SPECIES', $species, $address); 
		$address = str_replace('$BREED', $row_RECALL['PETBREED'], $address); 
		$address = str_replace('$SEX', $sex, $address); 
		$address = str_replace('$RABIES', $row_RECALL['PRABDAT'], $address); 
		$address = str_replace('$ISARE', $isare, $address); 
		$address = str_replace('$NEUTER', $neuter, $address); 
		$address = str_replace('$COLOUR', $row_RECALL['PCOLOUR'], $address); 
		$address = str_replace('$CLIENT', $row_RECALL['TITLE']." ".$row_RECALL['CONTACT']." ".$row_RECALL['COMPANY'], $address); 
		//$address = str_replace('$DOCSIGN', '...........', $address); 

		echo '<span style="white-space:pre-wrap;">'.$address."</span>"; ?>
		</td>
      </tr>
      <tr>
        <td height="<?php if ($i == 0) {echo '0'; $i = 0;} else {echo "30";} ?>"></td>
        <td></td>
        <td></td>
      </tr>
    </table></td>
    <td width="234">&nbsp;</td>
  </tr>
</table>
  </div>
 <?php $i = $i+1; } while ($row_RECALL = mysql_fetch_assoc($RECALL)); 
  } 

?>

<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd --></html>
