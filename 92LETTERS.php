<?php
$i = 1;

do { ?>   
  <div class="customletter" style="width:855px; height:1050px; overflow:auto;" id="realpreview>
  <table width="855" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="855" height="1050" align="left" valign="top"><table width="855" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="45" colspan="1"><span class="Verdana14B"><?php echo $row_CRITDATA['HOSPNAME']; ?></span><br />
<span class="Verdana11"><?php echo $row_CRITDATA['HSTREET']; ?>, <?php echo $row_CRITDATA['HCITY']; ?>, <?php echo $row_CRITDATA['HPROV']; ?> <?php echo $row_CRITDATA['HCODE']; ?></span>
<tr>
<!--<td height="8" class="Verdana9" > www.huntsvilleanimalhospital.com </td> -->
       
        </tr>
      <tr> <td width="237" height="60">
		<?php 
		//address
		$address = str_replace('$PETNAME', $row_RECALL['PETNAME'], $row_POSTCARDS['ADDRESS']); 
		$address = str_replace('$TITLE', $row_RECALL['TITLE'], $address); 
		$address = str_replace('$SALUTATION', $salutation, $address); 
		$address = str_replace('$NAME', $row_RECALL['CONTACT'], $address); 
		$address = str_replace('$SURNAME', $row_RECALL['COMPANY'], $address); 
		$address = str_replace('$ADDRESS', $row_RECALL['ADDRESS1']."  ".$row_RECALL['ADDRESS2'], $address); 
		$address = str_replace('$CITY', $row_RECALL['CITY'], $address); 
		$address = str_replace('$PROVINCE', $row_RECALL['STATE'], $address); 
		$address = str_replace('$PCODE', $row_RECALL['ZIP'], $address); 
		$address = str_replace('$CLIENT', $row_RECALL['TITLE']." ".$row_RECALL['CONTACT']." ".$row_RECALL['COMPANY'], $address); 

		echo '<span style="white-space:pre-wrap;">'.$address."</span>"; ?>
		</td></tr>
        <td width="850" height="890">
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
		$message = str_replace('$ADDRESS', $row_RECALL['ADDRESS1']."   ".$row_RECALL['ADDRESS2'], $message); 
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
		?>        </td>
      </tr>
      <tr>
        <td height="2"></td>
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