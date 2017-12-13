<?php 
session_start();
 	if (!isset($_SESSION['table'])){
	$_SESSION['table']=$_POST['table'];	
	}
	
require_once('../../tryconnection.php');
include("../../ASSETS/age.php");

mysqli_select_db($tryconnection, $database_tryconnection);

$query_PATIENT_CLIENT = "SELECT *, DATE_FORMAT(PDOB,'%m/%d/%Y') AS PDOB FROM PETMAST JOIN ARCUSTO ON (ARCUSTO.CUSTNO=PETMAST.CUSTNO) WHERE PETID = '$_SESSION[espatient]' LIMIT 1";
$PATIENT_CLIENT = mysqli_query($tryconnection, $query_PATIENT_CLIENT) or die(mysqli_error($mysqli_link));
$row_PATIENT_CLIENT = mysqli_fetch_assoc($PATIENT_CLIENT);

$custno = $row_PATIENT_CLIENT['CUSTNO'] ;

$query_SEC_INDEX = "SELECT FNAME,LNAME FROM SECINDEX WHERE SECINDEX.CUSTNO = '$custno' " ;
$SEC_INDEX = mysqli_query($tryconnection, $query_SEC_INDEX) or die(mysqli_error($mysqli_link)) ;
$row_SEC_INDEX = mysqli_fetch_assoc($SEC_INDEX) ;


$query_SPECIES = "SELECT ANIMAL FROM ANIMTYPE WHERE ANIMALID = '$row_PATIENT_CLIENT[PETTYPE]' LIMIT 1 " ;
$IS_SPECIES = mysqli_query($tryconnection, $query_SPECIES) or die(mysqli_error($mysqli_link)) ;
$species = mysqli_fetch_array($IS_SPECIES) ;

$query_PATIENTS = "SELECT *, DATE_FORMAT(PDOB,'%m/%d/%Y') AS PDOB, DATE_FORMAT(PRABDAT,'%m/%d/%Y') AS PRABDAT, DATE_FORMAT(POTHDAT,'%m/%d/%Y') AS POTHDAT, DATE_FORMAT(PLEUKDAT,'%m/%d/%Y') AS PLEUKDAT FROM PETMAST WHERE CUSTNO='$row_PATIENT_CLIENT[CUSTNO]' AND (PDEAD + PMOVED ) = 0 ORDER BY PETNAME ASC";
$PATIENTS = mysqli_query($tryconnection, $query_PATIENTS) or die(mysqli_error($mysqli_link));
$row_PATIENTS = mysqli_fetch_assoc($PATIENTS);

$query_CRITDATA = "SELECT * FROM CRITDATA LIMIT 1";
$CRITDATA = mysqli_query($tryconnection, $query_CRITDATA) or die(mysqli_error($mysqli_link));
$row_CRITDATA = mysqli_fetch_assoc($CRITDATA);

$query_RECEP = "SELECT PROBLEM FROM RECEP WHERE RFPETID='$_SESSION[espatient]' LIMIT 1";
$RECEP = mysqli_query($tryconnection, $query_RECEP) or die(mysqli_error($mysqli_link));
$row_RECEP = mysqli_fetch_assoc($RECEP);

$tspecies = $row_PATIENT_CLIENT[PETTYPE] ;

$numrows="SELECT COUNT(TNOPRINT) AS RECORDS FROM VETCAN WHERE TSPECIES = '$tspecies' AND TNOPRINT = 1 " ;
$num_query = mysqli_query($tryconnection, $numrows) or die(mysqli_error($mysqli_link)) ;
$row_numrows = mysqli_fetch_assoc($num_query);

$limit = $row_numrows['RECORDS'] ;

$TNFGET = "SELECT TCATGRY,TTYPE,TNO,TWDESCR FROM VETCAN WHERE TSPECIES = '$tspecies' AND TNOPRINT = 1 ORDER BY TCATGRY, TNO " ;
$TNF_query = mysqli_query($tryconnection, $TNFGET) or die(mysqli_error($mysqli_link)) ;

if (empty($row_RECEP)){
$query_insertSQL="INSERT INTO RECEP (CUSTNO, NAME, RFPETID, PETNAME, PSEX, RFPETTYPE, LOCATION, DESCRIP, FNAME, PROBLEM, AREA1, PH1, AREA2, PH2, AREA3, PH3, DATEIN, TIME, DATETIME) 
               VALUES ('$row_PATIENT_CLIENT[CUSTNO]', '".mysqli_real_escape_string($mysqli_link, $row_PATIENT_CLIENT['COMPANY'])."', '$row_PATIENT_CLIENT[PETID]', '".mysqli_real_escape_string($mysqli_link, $row_PATIENT_CLIENT['PETNAME'])."', '$row_PATIENT_CLIENT[PSEX]', '$row_PATIENT_CLIENT[PETTYPE]', '1', 
                       '".mysqli_real_escape_string($mysqli_link, $row_PATIENT_CLIENT['PETBREED'])."','".mysqli_real_escape_string($mysqli_link, $row_PATIENT_CLIENT['CONTACT'])."','".mysqli_real_escape_string($mysqli_link, $_POST['problem'])."', '$row_PATIENT_CLIENT[CAREA]', '$row_PATIENT_CLIENT[PHONE]', '$row_PATIENT_CLIENT[CAREA2]', '$row_PATIENT_CLIENT[PHONE2]', '$row_PATIENT_CLIENT[CAREA3]', '$row_PATIENT_CLIENT[PHONE3]', NOW(), NOW(), NOW())";
$insertSQL=mysqli_query($tryconnection, $query_insertSQL) or die(mysqli_error($mysqli_link));
//$closewin="self.close();";
}

else {
$query_insertSQL="UPDATE RECEP SET PROBLEM='".mysqli_real_escape_string($mysqli_link, $_POST['problem'])."' WHERE RFPETID='$_SESSION[espatient]'";
$insertSQL=mysqli_query($tryconnection, $query_insertSQL) or die(mysqli_error($mysqli_link));
$closewin="self.close();";
}

//else {
//$closewin="self.close();";
//}

if (!isset($_SESSION['whatdate'])){
	$_SESSION['whatdate']=$_POST['whatdate'];	
	}
	else {// $_SESSION['whatdate'] = date('m/d/Y') ;
	}
$edate = $_SESSION['whatdate'] ;
$whatdate = strtotime($edate) ;
$mydate = date('l F j Y',$whatdate) ;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/POP UP WINDOWS TEMPLATE.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php if ($_SESSION['table'][0]=='ADMIS') {echo "ADMISSION SHEET";} else if ($_SESSION['table'][0]=='EXAM') {echo "EXAMINATION SHEET";} else if ($_SESSION['table'][0]=='INHOSP'){echo "HOSPITAL SHEET";} else if ($_SESSION['table'][0]=='TREAT'){echo "TREATMENT SHEET";} else if ($_SESSION['table'][0]=='HW'){echo "HEARTWORM RELEASE";}else {echo " ";}?></title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>

<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">


function php_file_tree($directory, $return_link, $extensions = array()) {
	// Generates a valid XHTML list of all directories, sub-directories, and files in $directory
	// Remove trailing slash
	if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
	$code .= php_file_tree_dir($directory, $return_link, $extensions);
	return $code;
}
/*
function php_file_tree_dir($directory, $return_link, $extensions = array(), $first_call = true) {
	// Recursive function called by php_file_tree() to list directories/files
	
	// Get and sort directories/files
	if( function_exists("scandir") ) $file = scandir($directory); else $file = php4_scandir($directory);
	natcasesort($file);
	// Make directories first
	$files = $dirs = array();
	foreach($file as $this_file) {
		if( is_dir("$directory/$this_file" ) ) $dirs[] = $this_file; else $files[] = $this_file;
	}
	$file = array_merge($dirs, $files);
	
	// Filter unwanted extensions
	if( !empty($extensions) ) {
		foreach( array_keys($file) as $key ) {
			if( !is_dir("$directory/$file[$key]") ) {
				$ext = substr($file[$key], strrpos($file[$key], ".") + 1); 
				if( !in_array($ext, $extensions) ) unset($file[$key]);
			}
		}
	}
	
	if( count($file) > 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
		$php_file_tree = "<ul";
		if( $first_call ) { $php_file_tree .= " class=\"php-file-tree\""; $first_call = false; }
		$php_file_tree .= ">";
		foreach( $file as $this_file ) {
			if( $this_file != "." && $this_file != ".." ) {
				if( is_dir("$directory/$this_file") ) {
					// Directory
					$php_file_tree .= "<li class=\"pft-directory\"><a href=\"#\">" . htmlspecialchars($this_file) . "</a>";
					$php_file_tree .= php_file_tree_dir("$directory/$this_file", $return_link ,$extensions, false);
					$php_file_tree .= "</li>";
				} else {
					// File
					// Get extension (prepend 'ext-' to prevent invalid classes from extensions that begin with numbers)
					$ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1); 
					$link = str_replace("[link]", "$directory/" . urlencode($this_file), $return_link);
					$php_file_tree .= "<li class=\"pft-file " . strtolower($ext) . "\"><a href=\"$link\">" . htmlspecialchars($this_file) . "</a></li>";
				}
			}
		}
		$php_file_tree .= "</ul>";
	}
	return $php_file_tree;
}
*/
function bodyonload(){
<?php
	if ((isset($_SESSION['table']) && empty($_SESSION['table'])) || (!isset($_POST['table']) && !isset($_SESSION['table']))){
	unset($_SESSION['table']);
	echo "self.close();";
	}

	// 
	else { 
	
	     $typeof = "" ;
/*	     
	// look for custom files.
	     if ($_SESSION['table'][0] == 'CUSTOM') {
	     // The first script captures the absolute path for this folder...
	        $conflen=strlen('EXAM_SHEETS');
            $B=substr(__FILE__,0,strrpos(__FILE__,'/'));
            $A=substr($_SERVER['DOCUMENT_ROOT'], strrpos($_SERVER['DOCUMENT_ROOT'], $_SERVER['PHP_SELF']));
            $C=substr($B,strlen($A));
            $posconf=strlen($C)-$conflen-1;
            $D=substr($C,1,$posconf);
            $host='Web-server.local//'.$_SERVER['SERVER_NAME'].'/'.$D;
            
            
	     {
*/
         
	     if ($_SESSION['table'][0] == 'ADMIS') {
	       $typeof = 'Admission' ;
	      }
          elseif ($_SESSION['table'][0] == 'TREAT') {
	       $typeof = 'Treatment' ;
	      }
	      elseif ($_SESSION['table'][0] == 'WORK') {
	       $typeof = 'Worksheet' ;
	       $handle = fopen("WORK.txt", "r") ;
	      }
	      elseif ($_SESSION['table'][0] == 'HW') {
	       $typeof = 'Heartworm Release' ;
	      }
	       if ($_SESSION['table'][0] == 'INHOSP') {
	       $typeof = 'In Hospital' ;
	         if ($row_PATIENT_CLIENT['PETTYPE'] == 2) {
	             $handle = fopen("INHOSP_Fel.txt", "r") ;
	              }
	         else {
	 	          $handle = fopen("INHOSP_K9.txt", "r") ;
	              }
	        }
	      elseif ($_SESSION['table'][0] == 'EXAM') {
	        $typeof = 'Examination' ;
	         if ($row_PATIENT_CLIENT['PETTYPE'] == 2) {
	             $handle = fopen("EXAM_Fel.txt", "r") ;
	              }
	         else {
	 	          $handle = fopen("EXAM_K9.txt", "r") ;
	              }
	        }
	      else {
	          $sheet = $_SESSION['table'][0].'.txt' ;
	          $handle = fopen("$sheet", "r") ;
	      }
	     
     }
     
 $row_INHOSP = array();
// Now read the file into the array.

          if ($handle) {      
               while (!feof($handle)) {
                     $text_line = fgets($handle) ;
                     $isdata = nl2br($text_line) ;
                     $row_INHOSP[] = $isdata ;
               }
               fclose($handle);
          }
         
	       echo "window.print();";
    if ($_SESSION['table'][0] != 'WORK' )  {     
	unset($_SESSION['table'][0]); }
	$_SESSION['table']=array_merge($_SESSION['table']);
	echo "document.location.reload();";
//	}

?>

}
</script>
<style type="text/css">
body{
background-color:#FFFFFF;
}
</style>
<!-- InstanceEndEditable -->



</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion3" -->

<form method="post" action="print_sheet" name="print_sheet" id="" style="position:absolute; top:0px; left:0px; font-family:'Courier New', Courier, monospace; font-size:13px; letter-spacing:-1px;">
<?php 
/*
if ($_SESSION['table'][0] =='CUSTOM') {
     $allowed = array("txt");
     echo '<h1>Custom Document File Picker c</h1>
		
		
		<pre>' ;	echo php_file_tree($_SERVER['DOCUMENT_ROOT'], $allowed, &quot;javascript:alert('You clicked on [link]')&quot;)';</pre>
		
		
		<pre>	&lt;script src=&quot;php_file_tree.js&quot; type=&quot;text/javascript&quot;&gt;&lt;/script&gt;</pre> ' ;

}
*/
echo  '<table border="0" cellspacing="0" cellpadding="0">';
echo '<tr>';
echo '<td  aligh="left" width="200" class="Verdana14B">'.$mydate .'</td>' ;
echo '<td colspan="2" align="center" width="600"  class="Verdana14B">';
echo $row_CRITDATA['HOSPNAME'] ;
echo '</td>';
echo '<td  align="right" width="150"  class="Verdana14B">'.$typeof ;
echo '</td>' ;
echo '</tr>';
echo '<tr>';
echo '<td > &nbsp;<br /></td>' ;



if ($_SESSION['espatient']!='0'){

echo '<tr>';
// echo '<td height="15" align="center"></td>';
echo '<td  height="15 colspan="4"><strong>';
echo $row_PATIENT_CLIENT['CUSTNO'].'&nbsp;&nbsp;'.$row_PATIENT_CLIENT['TITLE'].' '.$row_PATIENT_CLIENT['CONTACT'].' '.$row_PATIENT_CLIENT['COMPANY'];
if ($row_PATIENT_CLIENT['CONTWO'] != $row_PATIENT_CLIENT['COMPANY']  || $row_PATIENT_CLIENT['CONTWOCH'] != $row_PATIENT_CLIENT['CONTACT'] ) { 
echo '&nbsp; Alternate: '.$row_PATIENT_CLIENT['CONTWOCH'].' '.$row_PATIENT_CLIENT['CONTWO']; }
echo '</strong></td>';
echo '</tr>';

echo '<tr>';
//echo '<td height="15" align="center"></td>';
echo '<td height="15"  colspan="4"><strong>';
echo "(".$row_PATIENT_CLIENT['CAREA'].")".$row_PATIENT_CLIENT['PHONE'].", (".$row_PATIENT_CLIENT['CAREA2'].")".$row_PATIENT_CLIENT['PHONE2']."</strong>, ".$row_PATIENT_CLIENT['ADDRESS1'].", ".$row_PATIENT_CLIENT['CITY'].", ".$row_PATIENT_CLIENT['STATE']." ".$row_PATIENT_CLIENT['ZIP'];
echo '</td>';
echo '</tr>';


echo '<tr>';
echo '<td height="2" align="center"></td>';
echo '<td colspan="4">';
echo '</td>';
echo '</tr>';

/*
echo '<tr>';
echo '<td align="center"></td>';
echo '<td><strong>All current pets</strong></td>';
echo '<td>Rabies</td>';
echo '<td>Other</td>';
echo '<td>FL/HW</td>';
echo '</tr>';

do {
echo '<tr>';
echo '<td height="15" width="15"></td>';
echo '<td height="15" width="500">'.$row_PATIENTS['PETNAME'].' '.$row_PATIENTS['PETBREED'].' '.$row_PATIENTS['PSEX'];
	if ($row_PATIENTS['PNEUTER']=='1' && $row_PATIENTS['PSEX']=='M'){$pneuter = "(N)";} 
	elseif ($row_PATIENTS['PNEUTER']=='1' && $row_PATIENTS['PSEX']=='F'){$pneuter = "(S)";}
echo $pneuter.' '.$row_PATIENTS['PDOB'];
echo '</td>';
echo '<td height="15" width="130">'.$row_PATIENTS['PRABDAT'].'</td>';
echo '<td height="15" width="130">'.$row_PATIENTS['POTHDAT'].'</td>';
echo '<td height="15" width="130">'.$row_PATIENTS['PLEUKDAT'].'</td>';
echo '</tr>';

} while ($row_PATIENTS = mysql_fetch_assoc($PATIENTS));
*/
    $pneuter = '' ;
	if ($row_PATIENT_CLIENT['PNEUTER']=='1' && $row_PATIENT_CLIENT['PSEX']=='M'){$pneuter = "(N)";} 
	elseif ($row_PATIENT_CLIENT['PNEUTER']=='1' && $row_PATIENT_CLIENT['PSEX']=='F'){$pneuter = "(S)";}
echo '<tr>';
echo '<td height="15" align="center"></td>';
echo '<td colspan="4">';
echo '</td>';
echo '</tr>';
echo '<tr>';
//echo '<td height="15" align="center"></td>';
echo '<td height="15" colspan="4"><strong>Patient: <span class="Verdana14B">';
echo $row_PATIENT_CLIENT['PETNAME'].'</span> '.$species[0].'</span> '.$row_PATIENT_CLIENT['PETBREED'].'</span> '.$row_PATIENT_CLIENT['PSEX'].$pneuter.'</span> '.$row_PATIENT_CLIENT['PCOLOUR'].' Wt.'.$row_PATIENT_CLIENT['PWEIGHT'].' '.$row_CRITDATA['WEIGHTUNIT'].'</span> ' . 'Born:'.$row_PATIENT_CLIENT['PDOB']; ?> <?php agecalculation($tryconnection, $row_PATIENT_CLIENT['PDOB']);
echo '</strong></td>';
echo '</tr>';

echo '<tr>';
echo '<td height="2" align="center"></td>';
echo '<td colspan="4">';
echo '</td>';
echo '</tr>';
}

echo '<tr>';
//echo '<td height="15" align="center"></td>';
echo '<td height="15" colspan="4"><strong>Admitting Information:';
echo '</strong></td>';
echo '</tr>';

echo '<tr>';
//echo '<td height="15" align="center"></td>';
echo '<td height="15" colspan="4">';
echo $row_RECEP['PROBLEM'];
echo '</td>';
echo '</tr>';

echo "</table>";
//echo "<br />";
// Now make a decision whether this is just a text file dump, or whether we have to run through the 
// detailed logic of the worksheet first.
if ($_SESSION['table'][0] == 'WORK' ) {

$categ = 0 ;
 echo  '<table width="800" height="15" border="0" cellspacing="0" cellpadding="0" >' ;
 echo  '<tr>
       <td width="16">&nbsp;</td>
       <td width="16">&nbsp;</td>
       <td width="16">&nbsp;</td>
       <td width="16">&nbsp;</td>
       <td width="16">&nbsp;</td>
       <td width="16">&nbsp;</td>
       <td width="16">&nbsp;</td>
       </tr> ';
 $col_pos = 1 ;
 while ($row_TNF = mysqli_fetch_assoc($TNF_query)) {
 if ($row_TNF['TCATGRY'] != $categ) {
   echo    '<tr>' ;
   echo    '<td class="Verdana12B" colspan="4" align=left>'. $row_TNF['TTYPE']. '</td>' ;
   $categ = $row_TNF['TCATGRY'] ; 
   echo     '</tr>' ;
   $col_pos = 1 ;
 }
     
        if ($col_pos > 7) {
  
          echo '</tr>' ;
          $col_pos = 1 ;
          echo '<tr>' ;
         }
        echo '<td class="Arial10" height="12" align=left >'.$row_TNF['TNO'].'. '.$row_TNF['TWDESCR'] .'</td>';
        $col_pos++ ;
    }   //$row_TNF = mysql_fetch_assoc($TNF_query)
    
    echo '</tr>' ;
    
}
  echo     '</table>';
  if (!empty($row_INHOSP)) {
   
   $count = count($row_INHOSP); 
   echo '<td colspan="4">' ;
   
   for ($i = 0;  $i< $count; $i++) {
       echo "<class='Verdana9B' ><strong>&nbsp;".$row_INHOSP[$i]."<br /></strong>";
       }
  
  }
// }
?>


</form>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
