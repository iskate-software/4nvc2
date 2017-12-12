<?php 
session_start();
require_once('../../tryconnection.php'); 
include('../../ASSETS/age.php');

mysql_select_db($database_tryconnection, $tryconnection);

$Oclient=$_GET['Oclient'];
$Opatient=$_GET['Opatient'];
$Tclient=$_GET['Tclient'];
$Tpatient=$_GET['Tpatient'];

// "MOVE PATIENT": TARGET1
// "MOVE PATIENT+INVOICES": TARGET2
// "MOVE INVOICES": TARGET3
// "MERGE HISTORY": TARGET4

$what2move=$_GET['what2move'];

mysql_select_db($database_tryconnection, $tryconnection);

$query_OCLIENT = "SELECT * FROM ARCUSTO WHERE CUSTNO='$Oclient'LIMIT 1";
$OCLIENT = mysql_query($query_OCLIENT, $tryconnection) or die(mysql_error());
$row_OCLIENT = mysql_fetch_assoc($OCLIENT);

$query_OPATIENT = "SELECT *, DATE_FORMAT(PDOB,'%m/%d/%Y') AS PDOB FROM PETMAST WHERE PETID='$Opatient' LIMIT 1";
$OPATIENT = mysql_query($query_OPATIENT, $tryconnection) or die(mysql_error());
$row_OPATIENT = mysql_fetch_assoc($OPATIENT);

$query_TCLIENT = "SELECT * FROM ARCUSTO WHERE CUSTNO='$Tclient' LIMIT 1";
$TCLIENT = mysql_query($query_TCLIENT, $tryconnection) or die(mysql_error());
$row_TCLIENT = mysql_fetch_assoc($TCLIENT);
$newclient=$row_TCLIENT['TITLE']." ".$row_TCLIENT['CONTACT']." ".$row_TCLIENT['COMPANY'];

$query_TPATIENT = "SELECT *, DATE_FORMAT(PDOB,'%m/%d/%Y') AS PDOB FROM PETMAST WHERE PETID='$Tpatient' LIMIT 1";
$TPATIENT = mysql_query($query_TPATIENT, $tryconnection) or die(mysql_error());
$row_TPATIENT = mysql_fetch_assoc($TPATIENT);

if (isset($_POST['save'])){

	//if moving only the patient
	if ($what2move=='TARGET1' || $what2move=='TARGET2' || $what2move=='TARGET4'){

		if ($what2move=='TARGET1' || $what2move=='TARGET2'){
		//duplicate the patient
		$query_NEWPETNO = "SELECT MAX(PETNO) AS PETNO FROM PETMAST WHERE CUSTNO = '$Tclient'";
		$NEWPETNO = mysql_query($query_NEWPETNO, $tryconnection) or die(mysql_error());
		$row_NEWPETNO = mysql_fetch_assoc($NEWPETNO);
		
		$newpetno=($row_NEWPETNO['PETNO']+1);
		
		$query_MOVEPET1 = "DROP TEMPORARY TABLE IF EXISTS MOVEPET";
		$MOVEPET1 = mysql_query($query_MOVEPET1, $tryconnection) or die(mysql_error());
		$query_MOVEPET2 = "CREATE TEMPORARY TABLE MOVEPET LIKE PETMAST";
		$MOVEPET2 = mysql_query($query_MOVEPET2, $tryconnection) or die(mysql_error());
		$query_MOVEPET3 = "ALTER TABLE MOVEPET DROP COLUMN PETID";
		$MOVEPET3 = mysql_query($query_MOVEPET3, $tryconnection) or die(mysql_error());
		$query_MOVEPET4 = "ALTER TABLE MOVEPET ADD COLUMN PETID INT(10) FIRST";
		$MOVEPET4 = mysql_query($query_MOVEPET4, $tryconnection) or die(mysql_error());
		$query_MOVEPET5 = "INSERT INTO MOVEPET SELECT * FROM PETMAST WHERE PETID='$Opatient'";
		$MOVEPET5 = mysql_query($query_MOVEPET5, $tryconnection) or die(mysql_error());
		$query_MOVEPET6 = "UPDATE MOVEPET SET PETID='0', CUSTNO='$Tclient', PETNO='$newpetno'";
		$MOVEPET6 = mysql_query($query_MOVEPET6, $tryconnection) or die(mysql_error());
		$query_MOVEPET7 = "INSERT INTO PETMAST SELECT * FROM MOVEPET WHERE PETID='0'";
		$MOVEPET7 = mysql_query($query_MOVEPET7, $tryconnection) or die(mysql_error());
		$query_MOVEPET8 = "DROP TEMPORARY TABLE MOVEPET";
		$MOVEPET8 = mysql_query($query_MOVEPET8, $tryconnection) or die(mysql_error());
		
		$query_NEWPETID = "SELECT PETID FROM PETMAST WHERE CUSTNO = '$Tclient' ORDER BY PETNO DESC LIMIT 1";
		$NEWPETID = mysql_query($query_NEWPETID, $tryconnection) or die(mysql_error());
		$row_NEWPETID = mysql_fetch_assoc($NEWPETID);
		$newpetid=$row_NEWPETID['PETID'];
		}
		
		//This next section deals with the merges. If the Target Patient is called "Replace", 
		// pick up everything except the Petid and the Petno from the Old Patient, and update the Target 
		// Patient with the old data.
		else {
		$newpetid=$Tpatient;
		$query_MERGE = "SELECT * FROM PETMAST WHERE PETID = '$Tpatient' " ;
		$ISITMERGE = mysql_query($query_MERGE, $tryconnection) or die(mysql_error()) ;
		$row_ISITMERGE = mysql_fetch_assoc($ISITMERGE) ;
		$tpatname = $row_ISITMERGE['PETNAME'] ;
		if ($tpatname == "Replace") {
		$query_COPYPET = "SELECT * FROM PETMAST WHERE PETID = '$Opatient'" ;
		$M_M = mysql_query($query_COPYPET, $tryconnection) or die(mysql_error()) ;
		$row_M_M = mysql_fetch_assoc($M_M) ;
		$NEW1IN = "UPDATE PETMAST SET PETNAME='".mysql_real_escape_string($row_M_M[PETNAME])."',PETTYPE= '$row_M_M[PETTYPE]',PETBREED ='".mysql_real_escape_string($row_M_M[PETBREED])."',
		PCOLOUR='".mysql_real_escape_string($row_M_M[PCOLOUR])."',PSEX='$row_M_M[PSEX]',PNEUTER='$row_M_M[PNEUTER]',PDOB='$row_M_M[PDOB]',
		PFLAGS='$row_M_M[PFLAGS]',PRABIES='$row_M_M[PRABIES]',POTHER='$row_M_M[POTHER]' WHERE PETID = '$Tpatient'" ;
		$query_NEW1IN = mysql_query($NEW1IN, $tryconnection) or die(mysql_error()) ;
		$NEW1IN2 = "UPDATE PETMAST SET PRABYEARS='$row_M_M[PRABYEARS]',PLEUKYEARS='$row_M_M[PLEUKYEARS]',POTH02YEARS='$row_M_M[POTH02YEARS]',
		POTH03YEARS='$row_M_M[POTH03YEARS]',POTH04YEARS='$row_M_M[POTH04YEARS]',POTH05YEARS='$row_M_M[POTH05YEARS]',POTH06YEARS='$row_M_M[POTH06YEARS]',
		POTH07YEARS='$row_M_M[POTH07YEARS]',POTH08YEARS='$row_M_M[POTH08YEARS]',POTH09YEARS='$row_M_M[POTH09YEARS]',POTH10YEARS='$row_M_M[POTH10YEARS]',
		POTH11YEARS='$row_M_M[POTH11YEARS]',POTH12YEARS='$row_M_M[POTH12YEARS]',POTH13YEARS='$row_M_M[POTH13YEARS]',POTH14YEARS='$row_M_M[POTH14YEARS]',
		POTH15YEARS='$row_M_M[POTH15YEARS]' WHERE PETID = '$Tpatient'" ;
		$query_NEW1IN2 = mysql_query($NEW1IN2, $tryconnection) or die(mysql_error()) ;
		$NEW1IN3 = "UPDATE PETMAST SET PFELHW='$row_M_M[PFELHW]',PRABDAT='$row_M_M[PRABDAT]',POTHDAT='$row_M_M[POTHDAT]',POTHTWO='$row_M_M[POTHTWO]', PLEUKDAT='$row_M_M[PLEUKDAT]',
		POTHTHR='$row_M_M[POTHTHR]',POTHFOR='$row_M_M[POTHFOR]',POTHFIV='$row_M_M[POTHFIV]',POTHSIX='$row_M_M[POTHSIX]',POTHSEV='$row_M_M[POTHSEV]',
		POTH8='$row_M_M[POTH8]',POTH9='$row_M_M[POTH9]',POTH10='$row_M_M[POTH10]',POTH11='$row_M_M[POTH11]',POTH12='$row_M_M[POTH12]',
		POTH13='$row_M_M[POTH13]',POTH14='$row_M_M[POTH14]',POTH15='$row_M_M[POTH15]' WHERE PETID = '$Tpatient'" ;
		$query_NEW1IN3 = mysql_query($NEW1IN3, $tryconnection) or die(mysql_error()) ;
		
		$NEW1IN4 = "UPDATE PETMAST SET PRABTAG='$row_M_M[PRABTAG]',PXRAYFILE='$row_M_M[PXRAYFILE]',PFIRSTDATE='$row_M_M[PFIRSTDATE]', 
		PLASTDATE='$row_M_M[PLASTDATE]',PDATA='".mysql_real_escape_string($row_M_M[PDATA])."',PHERD='$row_M_M[PHERD]',PSTAB='$row_M_M[PSTAB]',
		PMAGNET='$row_M_M[PMAGNET]',PTENDX='$row_M_M[PTENDX]',PTATNO='$row_M_M[PTATNO]',PMAILDATE='$row_M_M[PMAILDATE]',
		PRABLAST='$row_M_M[PRABLAST]',PDECLAW='$row_M_M[PDECLAW]',PFILENO='$row_M_M[PFILENO]',PWEIGHT='$row_M_M[PWEIGHT]',
		PVACOUNT='$row_M_M[PVACOUNT]',PRABSER='$row_M_M[PRABSER]',PLIFE='$row_M_M[PLIFE]' WHERE PETID = '$Tpatient'" ;
		$query_NEW1IN4 = mysql_query($NEW1IN4, $tryconnection) or die(mysql_error()) ;
		
		$NEW1IN5 = "UPDATE PETMAST SET PSOSP='$row_M_M[PSOSP]', PWELL='$row_M_M[PWELL]',P6EXAM='$row_M_M[P6EXAM]',
		PSORTKEY='$row_M_M[PSORTKEY]',POFEI='$row_M_M[POFEI]',STICKIE='".mysql_real_escape_string($row_M_M[STICKIE])."'  WHERE PETID = '$Tpatient'" ;
		$query_NEW1IN5 = mysql_query($NEW1IN5, $tryconnection) or die(mysql_error()) ;
		}
		}

		$query_MOVEPET = "UPDATE PETMAST SET PMOVED='1' WHERE PETID='$Opatient'";
		$MOVEPET = mysql_query($query_MOVEPET, $tryconnection) or die(mysql_error());

		$query_PREFER="SELECT TRTMCOUNT FROM PREFER LIMIT 1";
		$PREFER= mysql_query($query_PREFER, $tryconnection) or die(mysql_error());
		$row_PREFER = mysql_fetch_assoc($PREFER);
		
		$Otreatmxx=$Oclient/$row_PREFER['TRTMCOUNT'] ;
		$Otreatmxx="TREATM".floor($Otreatmxx) ;
		$Ttreatmxx=$Tclient/$row_PREFER['TRTMCOUNT'] ;
		$Ttreatmxx="TREATM".floor($Ttreatmxx) ;
			//check if the target table exists
			$query_CHECKTABLE="SELECT * FROM $Ttreatmxx LIMIT 1";
			$CHECKTABLE= mysql_query($query_CHECKTABLE, $tryconnection) or $none=1;
			
			if (isset($none)){
			$create_TREATMXX="CREATE TABLE $Ttreatmxx LIKE TREATM0";
			$result=mysql_query($create_TREATMXX, $tryconnection) or die(mysql_error());
			}
		
		$query_MOVEHIST1 = "DROP TEMPORARY TABLE IF EXISTS MOVEHIST";
		$MOVEHIST1 = mysql_query($query_MOVEHIST1, $tryconnection) or die(mysql_error());
		$query_MOVEHIST2 = "CREATE TEMPORARY TABLE MOVEHIST LIKE TREATM0";
		$MOVEHIST2 = mysql_query($query_MOVEHIST2, $tryconnection) or die(mysql_error());
		$query_MOVEHIST3 = "ALTER TABLE MOVEHIST DROP COLUMN LINENUMBER" ;
		$MOVEHIST3 = mysql_query($query_MOVEHIST3, $tryconnection) or die(mysql_error());
		$query_MOVEHIST4="ALTER TABLE MOVEHIST ADD COLUMN LINENUMBER INT(10)" ;
		$MOVEHIST4 = mysql_query($query_MOVEHIST4, $tryconnection) or die(mysql_error());
		$query_MOVEHIST5 = "INSERT INTO MOVEHIST SELECT * FROM $Otreatmxx WHERE PETID='$Opatient'";
		$MOVEHIST5 = mysql_query($query_MOVEHIST5, $tryconnection) or die(mysql_error());
		$query_MOVEHIST6 = "UPDATE MOVEHIST SET PETID='$newpetid', CUSTNO='$Tclient', LINENUMBER = 0";
		$MOVEHIST6 = mysql_query($query_MOVEHIST6, $tryconnection) or die(mysql_error());
		$query_MOVEHIST7="INSERT INTO $Ttreatmxx SELECT * FROM MOVEHIST";
		$MOVEHIST7= mysql_query($query_MOVEHIST7, $tryconnection) or die(mysql_error());
	}//if ($what2move=='TARGET1'){


//if we are moving the invoices as well
	if ($what2move=='TARGET2' || $what2move=='TARGET3'){
	
		//this array is for the invoices that contain more than 1 patient and cannot be moved
		$dblTARGET2=array();

		//if we are moving only the invoices, we have to set the newpetid
		if ($what2move=='TARGET3'){
		$newpetid=$Tpatient;
		}
	
		foreach ($_POST['inv2move'] as $inv2move){

			//check in DVMINV if there's only one pet on the invoice
			$query_CHECKINV = "SELECT DISTINCT INVPET FROM DVMINV WHERE INVNO='$inv2move'";
			$CHECKINV = mysql_query($query_CHECKINV, $tryconnection) or die(mysql_error());
			$totalRows_CHECKINV = mysql_num_rows($CHECKINV);
			
				//if there's more than 1 pet, collect the invoice number for alerting the user
				if ($totalRows_CHECKINV > 1){
				$dblTARGET2[]=$row_MOVEINV['INVNO'];
				}//if ($totalRows_CHECKINV > 1)
				
				//if there's one pet only in the invoice, update the record with the new custno and petid
				else if ($totalRows_CHECKINV == 1) {
				$query_UPDATEINV = "UPDATE ARARECV SET CUSTNO='$Tclient', COMPANY='$newclient'  WHERE INVNO='$inv2move'";
				$UPDATEINV = mysql_query($query_UPDATEINV, $tryconnection) or die(mysql_error());

				$query_ARARECV = "SELECT IBAL FROM ARARECV WHERE INVNO='$inv2move'";
				$ARARECV = mysql_query($query_ARARECV, $tryconnection) or die(mysql_error());
				$row_ARARECV=mysql_fetch_assoc($ARARECV);
				$ibal=$row_ARARECV['IBAL'];

				$query_UPDATEINV = "UPDATE DVMINV SET INVCUST='$Tclient', INVPET='$newpetid'  WHERE INVNO='$row_MOVEINV[INVNO]'";
				$UPDATEINV = mysql_query($query_UPDATEINV, $tryconnection) or die(mysql_error());
				
				$query_UPDATEINV = "UPDATE ARCUSTO SET BALANCE=(BALANCE-$ibal) WHERE CUSTNO='$Oclient'";
				$UPDATEINV = mysql_query($query_UPDATEINV, $tryconnection) or die(mysql_error());
				$query_UPDATEINV = "UPDATE ARCUSTO SET BALANCE=(BALANCE+$ibal) WHERE CUSTNO='$Tclient'";
				$UPDATEINV = mysql_query($query_UPDATEINV, $tryconnection) or die(mysql_error());
				}//else if ($totalRows_CHECKINV == 1)
				
				//if there is no record in DVMINV, look into DVMILAST and repeat the procedure
				else if ($totalRows_CHECKINV == 0) {
					$query_CHECKINV = "SELECT DISTINCT INVPET FROM DVMILAST WHERE INVNO='$inv2move'";
					$CHECKINV = mysql_query($query_CHECKINV, $tryconnection) or die(mysql_error());
					$totalRows_CHECKINV = mysql_num_rows($CHECKINV);
					
						if ($totalRows_CHECKINV > 1){
						$dblTARGET2[]=$row_MOVEINV['INVNO'];
						}	
						
						else if ($totalRows_CHECKINV == 1) {
						$query_UPDATEINV = "UPDATE DVMILAST SET INVCUST='$Tclient', INVPET='$newpetid'  WHERE INVNO='$inv2move'";
						$UPDATEINV = mysql_query($query_UPDATEINV, $tryconnection) or die(mysql_error());
						}
						
						//look into ARYDVMI
						else if ($totalRows_CHECKINV == 0) {
							$query_CHECKINV = "SELECT DISTINCT INVPET FROM ARYDVMI WHERE INVNO='$inv2move'";
							$CHECKINV = mysql_query($query_CHECKINV, $tryconnection) or die(mysql_error());
							$totalRows_CHECKINV = mysql_num_rows($CHECKINV);
								if ($totalRows_CHECKINV > 1){
								$dblTARGET2[]=$row_MOVEINV['INVNO'];
								}	
								else if ($totalRows_CHECKINV == 1) {
								$query_UPDATEINV = "UPDATE ARYDVMI SET INVCUST='$Tclient', INVPET='$newpetid'  WHERE INVNO='$inv2move'";
								$UPDATEINV = mysql_query($query_UPDATEINV, $tryconnection) or die(mysql_error());
								}
						}//else if ($totalRows_CHECKINV == 0)
				}//else if ($totalRows_CHECKINV == 0)
			
			}

	}//if ($what2move=='TARGET2')

//session_destroy();
$closewin="sessionStorage.clear(); document.location='../../CLIENT/CLIENT_PATIENT_FILE.php?client=$Tclient';";
}//else if (isset($_POST['save'])){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/DVMBasicTemplate.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>FINISH <?php if ($what2move=='TARGET1'){echo "MOVING PATIENT";}if ($what2move=='TARGET2'){echo "MOVING PATIENT+INVOICES";}if ($what2move=='TARGET3'){echo "MOVING INVOICES";}if ($what2move=='TARGET4'){echo "MERGING HISTORY";} ?></title>
<!-- InstanceEndEditable -->

<link rel="stylesheet" type="text/css" href="../../ASSETS/styles.css" />
<script type="text/javascript" src="../../ASSETS/scripts.js"></script>

<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">

/*HIGHLIGHTS THE OPTION PREVIOUSLY SELECTED*/
function bodyonload(){
<?php echo $closewin; ?>
	if (sessionStorage.filetype!='0'){
	document.getElementById('inuse').innerText=sessionStorage.fileused;
	}
	else {
	document.getElementById('inuse').innerHTML="&nbsp;";
	}
}


function tick(x){
if (document.getElementById(x).checked==false){document.getElementById(x).checked=true;}
else {document.getElementById(x).checked=false;}
}
</script>

<style type="text/css">
<!--
.table {
	border-color: #CCCCCC;
	border-style: ridge;
	border-width: 1px;
	border-collapse: separate;
	border-spacing: 1px;
}
.SelectList {
	width: 100%;
	height: 100%;
	font-family: "Verdana";
	font-size: 12px;
	border-width: 0px;
	padding: 5 px;
	outline-width: 0px;
}
#table2 {	border-color: #CCCCCC;
	border-collapse: separate;
	border-spacing: 1px;
}
.table1 {	border-color: #CCCCCC;
	border-style: ridge;
	border-width: 1px;
	border-collapse: separate;
	border-spacing: 1px;
}

-->
</style>
<!-- InstanceEndEditable -->
<script type="text/javascript" src="../../ASSETS/navigation.js"></script>
</head>

<body onload="bodyonload()" onunload="bodyonunload()">
<!-- InstanceBeginEditable name="EditRegion4" -->
<!-- InstanceEndEditable -->

<!-- InstanceBeginEditable name="HOME" -->
<div id="LogoHead" onclick="window.open('/'+localStorage.xdatabase+'/INDEX.php','_self');" onmouseover="CursorToPointer(this.id)" title="Home">DVM</div>
<!-- InstanceEndEditable -->

<div id="MenuBar">

	<ul id="navlist">
                
<!--FILE-->                
                
		<li><a href="#" id="current">File</a> 
			<ul id="subnavlist">
                <li><a href="#"><span class="">About DV Manager</span></a></li>
                <li><a onclick="utilities();">Utilities</a></li>
			</ul>
		</li>
                
<!--INVOICE-->                
                
		<li><a href="#" id="current">Invoice</a> 
			<ul id="subnavlist">
                <li><a href="#" onclick="window.open('','_self'/'+localStorage.xdatabase+'/INVOICE/CASUAL_SALE_INVOICING/STAFF.php?refID=SCI)"><span class="">Casual Sale Invoicing</span></a></li>
                <li><!-- InstanceBeginEditable name="reg_nav" --><a href="#" onclick="nav0();">Regular Invoicing</a><!-- InstanceEndEditable --></li>
                <li><a href="#" onclick="nav11();">Estimate</a></li>
                <li><a href="#" onclick=""><span class="">Barn/Group Invoicing</span></a></li>
                <li><a href="#" onclick="suminvoices()"><span class="">Summary Invoices</span></a></li>
                <li><a href="#" onclick="cashreceipts()"><span class="">Cash Receipts</span></a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Cancel Invoices</span></a></li>
                <li><a href="#" onclick="window.open('/'+localStorage.xdatabase+'/INVOICE/COMMENTS/COMMENTS_LIST.php?path=DIRECTORY','_blank','width=733,height=553,toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no')">Comments</a></li>
                <li><a href="#" onclick="tffdirectory()">Treatment and Fee File</a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Worksheet File</span></a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Procedure Invoicing File</span></a></li>
                <li><a href="#" onclick="invreports();"><span class="">Invoicing Reports</span></a></li>
			</ul>
		</li>
                
<!--RECEPTION-->                
                
		<li><a href="#" id="current">Reception</a> 
			<ul id="subnavlist">
                <li><a href="#" onclick="window.open('','_self')"><span class="">Appointment Scheduling</span></a></li>
                <li><a href="#" onclick="reception();">Patient Registration</a></li>
                <li><a href="#" onclick="window.open('/'+localStorage.xdatabase+'/RECEPTION/USING_REG_FILE.php','_blank','width=550,height=535')">Using Reception File</a></li>
                <li><a href="#" onclick="nav2();"><span class="hidden"></span>Examination Sheets</a></li>
                <li><a href="#" onclick="gexamsheets()"><span class="">Generic Examination Sheets</span></a></li>
                <li><a href="#" onclick="nav3();">Duty Log</a></li>
                <li><a href="#" onclick="staffsiso()">Staff Sign In &amp; Out</a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">End of Day Accounting Reports</span></a></li>
                    </ul>
                </li>
                
<!--PATIENT-->                
                
                <li><a href="#" id="current">Patient</a> 
			<ul id="subnavlist">
                <li><a href="#" onclick="nav4();">Processing Menu</a> </li>
                <li><a href="#" onclick="nav5();">Review Patient Medical History</a></li>
                <li><a href="#" onclick="nav6();">Enter New Medical History</a></li>
                <li><a href="#" onclick="nav7();">Enter Patient Lab Results</a></li>
                <li><a href="#" onclick=""window.open('/'+localStorage.xdatabase+'/CLIENT/CLIENT_SEARCH_SCREEN.php?refID=ENTER SURG. TEMPLATES','_self')><span class="">Enter Surgical Templates</span></a></li>
                <li><a href="#" onclick="window.open('/'+localStorage.xdatabase+'/CLIENT/CLIENT_SEARCH_SCREEN.php?refID=CREATE NEW CLIENT','_self','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no');">Create New Client</a></li>
                <li><a href="#" onclick="movepatient();">Move Patient to a New Client</a></li>
                <li><a href="#" onclick="searchpatient()">Rabies Tags</a></li>
                <li><a href="#" onclick="searchpatient()">Tattoo Numbers</a></li>
                <li><a href="#" onclick="nav8();"><span class="">Certificates</span></a></li>
                <li><a href="#" onclick="nav9();"><span class="">Clinical Logs</span></a></li>
                <li><a href="#" onclick="nav10();"><span class="">Patient Categorization</span></a></li>
                <li><a href="#" onclick="">Laboratory Templates</a></li>
                <li><a href="#" onclick="nav1();"><span class="">Quick Weight</span></a></li>
<!--                <li><a href="#" onclick="window.open('','_self')"><span class="">All Treatments Due</span></a></li>
-->			</ul>
		</li>
        
<!--ACCOUNTING-->        
		
        <li><a href="#" id="current">Accounting</a> 
			<ul id="subnavlist">
                <li><a href="#" onclick=""accreports()>Accounting Reports</a></li>
                <li><a href="#" onclick="inventorydir();" id="inventory" name="inventory">Inventory</a></li>
                <li><a href="#" onclick="" id="busstatreport" name="busstatreport"><span class="">Business Status Report</span></a></li>
                <li><a href="#" onclick="" id="hospstatistics" name="hospstatistics"><span class="">Hospital Statistics</span></a></li>
                <li><a href="#" onclick="" id="monthend" name="monthend"><span class="">Month End Closing</span></a></li>
			</ul>
		</li>
        
<!--MAILING-->        
		
        <li><a href="#" id="current">Mailing</a> 
			<ul id="subnavlist">
                <li><a href="#" onclick="window.open('','_self')" ><span class="">Recalls and Searches</span></a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Handouts</span></a></li>
                <li><a href="#" onclick="window.open('','_self')MAILING/MAILING_LOG/MAILING_LOG.php?refID=">Mailing Log</a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Vaccine Efficiency Report</span></a></li>
                <li><a href="#" onclick="window.open('/'+localStorage.xdatabase+'/MAILING/REFERRALS/REFERRALS_SEARCH_SCREEN.php?refID=1','_blank','width=567,height=473')">Referring Clinics and Doctors</a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Referral Adjustments</span></a></li>
                <li><a href="#" onclick="window.open('','_self')"><span class="">Labels</span></a></li>
			</ul>
		</li>
	</ul>
</div>
<div id="inuse" title="File in memory"><!-- InstanceBeginEditable name="fileinuse" --><!-- InstanceEndEditable --></div>



<div id="WindowBody">
<!-- InstanceBeginEditable name="DVMBasicTemplate" -->
<form action="" method="post" name="searchclient">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td align="center" valign="middle" class="Verdana13">
     <br  /> You are moving <strong class="Verdana13BBlue"><?php echo $row_OCLIENT['TITLE'].' '.$row_OCLIENT['CONTACT'].' '.$row_OCLIENT['COMPANY'];?></strong>'s 
     
     <?php if ($what2move=='TARGET1'){echo "patient";}if ($what2move=='TARGET2'){echo "patient and invoices";}if ($what2move=='TARGET3'){echo "invoices";}if ($what2move=='TARGET4'){echo "patient's history: ";} ?>
     
      <strong class="Verdana13BBlue"><?php echo $row_OPATIENT['PETNAME']; ?></strong><br  /><br />    
<table width="75%" border="1" cellspacing="0" cellpadding="0" class="table">
      <tr>
        <td colspan="3" align="left" valign="top" class="Verdana12">
        <span style="background-color:#FFFF00">
		<strong><?php echo $row_OCLIENT['TITLE'].' '.$row_OCLIENT['CONTACT'].' '.$row_OCLIENT['COMPANY']; ?></strong>
        </span>
          <br  />      
          &nbsp;<?php echo $row_OCLIENT['AREA'].'-'.$row_OCLIENT['PHONE'].', '.$row_OCLIENT['CAREA2'].'-'.$row_OCLIENT['PHONE2'].', '.$row_OCLIENT['CAREA3'].'-'.$row_OCLIENT['PHONE3'].', '.$row_OCLIENT['CAREA4'].'-'.$row_OCLIENT['PHONE4']; ?>
          <br  />      
          &nbsp;<?php echo $row_OCLIENT['ADDRESS1']; ?>
          <br  />
          &nbsp;<?php echo $row_OCLIENT['CITY'].' '.$row_OCLIENT['STATE'].' '.$row_OCLIENT['ZIP']; ?>
        </td>
        <td width="49%" colspan="2" align="left" valign="top" <?php if(empty($row_OPATIENT)){echo "class='hidden'";} else {echo 'class="Verdana12"';} ?>>
            <span class="Verdana12B" style="background-color:#FFFF00">
            <strong>
            &nbsp;<?php echo $row_OPATIENT['PETNAME']; ?></strong>
            </span>        
            <br />    
            &nbsp;<?php 	if ($row_OPATIENT['PETTYPE']=='1'){$pettype = "Canine";} 
							else if ($row_OPATIENT['PETTYPE']=='2'){$pettype = "Feline";} 
							else if ($row_OPATIENT['PETTYPE']=='3'){$pettype = "Equine";}
							else if ($row_OPATIENT['PETTYPE']=='4'){$pettype = "Bovine";}
							else if ($row_OPATIENT['PETTYPE']=='5'){$pettype = "Caprine";}
							else if ($row_OPATIENT['PETTYPE']=='6'){$pettype = "Porcine";}
							else if ($row_OPATIENT['PETTYPE']=='7'){$pettype = "Avian";}
							else if ($row_OPATIENT['PETTYPE']=='8'){$pettype = "Other";}
							$desco=$pettype.', '.$row_OPATIENT['PETBREED'];
							echo $desco; ?>    
            <br />    
		    &nbsp;<?php 
			$psex=$row_OPATIENT['PSEX'];
			$pdob=$row_OPATIENT['PDOB'];
			if ($row_OPATIENT['PNEUTER']=='1' && $row_OPATIENT['PSEX']=='M'){$pneuter = "(N)";} 
			elseif ($row_OPATIENT['PNEUTER']=='1' && $row_OPATIENT['PSEX']=='F'){$pneuter = "(S)";}
			
			echo $row_OPATIENT['PSEX'].$pneuter.', '.$row_OPATIENT['PWEIGHT'];?> <script type="text/javascript">document.write(localStorage.weightunit);</script> <?php echo $row_OPATIENT['PCOLOUR'].', Born: '.$row_OPATIENT['PDOB']; ?> (<?php agecalculation($tryconnection,$pdob); ?>)
         </td>
        </tr>
</table>  
</td>
</tr>
<tr>
  <td align="center" valign="top" class="Verdana13" height="266">
  <span class="Verdana11Grey">Plase select the invoices you would like to move:</span>
  <br  />
<div id="whatinvoices" style="height:240px; width:546px; overflow:auto; border:double #CCCCCC; display:<?php if ($what2move=='TARGET1'){echo "none;";}if ($what2move=='TARGET2'){echo "";}if ($what2move=='TARGET3'){echo "";}if ($what2move=='TARGET4'){echo "none;";} ?>">  	
<table border="0" width="100%" cellpadding="0" cellspacing="0">
  <tr bgcolor="#000000" class="Verdana11Bwhite">
    <td height="10" ></td>
    <td>Invoice #</td>
    <td>Invoice Date</td>
    <td align="right">Amount</td>
    <td width="60" align="right">Paid</td>
    <td align="right">Balance</td>
    <td width="150" align="center">Inv. Reason</td>
  </tr>
  <?php 
  		$query_MOVEINV = "SELECT *,DATE_FORMAT(INVDTE, '%m/%d/%Y') AS INVDTE, DATE_FORMAT(DTEPAID, '%m/%d/%Y') AS DTEPAID FROM ARARECV WHERE CUSTNO='$Oclient' AND IBAL!=0";
		$MOVEINV = mysql_query($query_MOVEINV, $tryconnection) or die(mysql_error());
		$row_MOVEINV=mysql_fetch_assoc($MOVEINV);
		do { ?>

      <tr>
      	<td><input type="checkbox" name="inv2move[]" id="<?php echo $row_MOVEINV['INVNO']; ?>" value="<?php echo $row_MOVEINV['INVNO']; ?>"/></td>
      	<td onclick="tick('<?php echo $row_MOVEINV['INVNO']; ?>');"><?php echo $row_MOVEINV['INVNO']; ?></td>
      	<td onclick="tick('<?php echo $row_MOVEINV['INVNO']; ?>');"><?php echo $row_MOVEINV['INVDTE']; ?></td>
      	<td align="right" onclick="tick('<?php echo $row_MOVEINV['INVNO']; ?>');"><?php echo $row_MOVEINV['ITOTAL']; ?></td>
      	<td align="right" onclick="tick('<?php echo $row_MOVEINV['INVNO']; ?>');"><?php echo $row_MOVEINV['AMTPAID']; ?></td>
      	<td align="right" onclick="tick('<?php echo $row_MOVEINV['INVNO']; ?>');"><?php echo $row_MOVEINV['IBAL']; ?></td>
      	<td align="center" onclick="tick('<?php echo $row_MOVEINV['INVNO']; ?>');"><?php echo $row_MOVEINV['PONUM']; ?></td>
      </tr>  

   <?php }
		while ($row_MOVEINV=mysql_fetch_assoc($MOVEINV));
  ?>
  	</table>
</div>  
  </td>
</tr>
<tr>
  <td align="center" valign="top" class="Verdana13">

            to <span class="Verdana13BBlue"><?php echo $row_TCLIENT['TITLE'].' '.$row_TCLIENT['CONTACT'].' '.$row_TCLIENT['COMPANY']; ?></span><?php if ($what2move=='TARGET1'){echo "";}if ($what2move=='TARGET2'){echo "";}if ($what2move=='TARGET3'){echo "'s patient ".'<span class="Verdana13BBlue">'.$row_TPATIENT['PETNAME'].'</span>';}if ($what2move=='TARGET4'){echo "'s patient ".'<span class="Verdana13BBlue">'.$row_TPATIENT['PETNAME'].'</span>';} ?>
            <br />    
            <br />
            <table width="75%" border="1" cellspacing="0" cellpadding="0" class="table">
      <tr>
        <td colspan="3" align="left" valign="top" class="Verdana12">
        <span style="background-color:#FFFF00">
		<strong><?php echo $row_TCLIENT['TITLE'].' '.$row_TCLIENT['CONTACT'].' '.$row_TCLIENT['COMPANY']; ?></strong>
        </span>
          <br  />      
          &nbsp;<?php echo $row_TCLIENT['AREA'].'-'.$row_TCLIENT['PHONE'].', '.$row_TCLIENT['CAREA2'].'-'.$row_TCLIENT['PHONE2'].', '.$row_TCLIENT['CAREA3'].'-'.$row_TCLIENT['PHONE3'].', '.$row_TCLIENT['CAREA4'].'-'.$row_TCLIENT['PHONE4']; ?>
          <br  />      
          &nbsp;<?php echo $row_TCLIENT['ADDRESS1']; ?>
          <br  />
          &nbsp;<?php echo $row_TCLIENT['CITY'].' '.$row_TCLIENT['STATE'].' '.$row_TCLIENT['ZIP']; ?>
        </td>
        <td width="49%" colspan="2" align="left" valign="top" <?php if(empty($row_TPATIENT)){echo "class='hidden'";} else {echo 'class="Verdana12"';} ?>>
            <span class="Verdana12B" style="background-color:#FFFF00">
            <strong>
            &nbsp;<?php echo $row_TPATIENT['PETNAME']; ?></strong>
            </span>        
            <br />    
            &nbsp;<?php 	if ($row_TPATIENT['PETTYPE']=='1'){$pettype = "Canine";} 
							else if ($row_TPATIENT['PETTYPE']=='2'){$pettype = "Feline";} 
							else if ($row_TPATIENT['PETTYPE']=='3'){$pettype = "Equine";}
							else if ($row_TPATIENT['PETTYPE']=='4'){$pettype = "Bovine";}
							else if ($row_TPATIENT['PETTYPE']=='5'){$pettype = "Caprine";}
							else if ($row_TPATIENT['PETTYPE']=='6'){$pettype = "Porcine";}
							else if ($row_TPATIENT['PETTYPE']=='7'){$pettype = "Avian";}
							else if ($row_TPATIENT['PETTYPE']=='8'){$pettype = "Other";}
							$desco=$pettype.', '.$row_TPATIENT['PETBREED'];
							echo $desco; ?>    
            <br />    
		    &nbsp;<?php 
			$psex=$row_TPATIENT['PSEX'];
			$pdob=$row_TPATIENT['PDOB'];
			if ($row_TPATIENT['PNEUTER']=='1' && $row_TPATIENT['PSEX']=='M'){$pneuter = "(N)";} 
			elseif ($row_TPATIENT['PNEUTER']=='1' && $row_TPATIENT['PSEX']=='F'){$pneuter = "(S)";}
			
			echo $row_TPATIENT['PSEX'].$pneuter.', '.$row_TPATIENT['PWEIGHT'];?> <script type="text/javascript">document.write(localStorage.weightunit);</script> <?php echo $row_TPATIENT['PCOLOUR'].', Born: '.$row_TPATIENT['PDOB']; ?> (<?php agecalculation($tryconnection,$pdob); ?>)
		
        </td>
        </tr>
</table>  
  
  </td>
</tr>
<tr>
	<td height="40">
    </td>
</tr>
<tr>
  <td class="ButtonsTable" align="center">
    <input type="submit" class="button" value="SAVE" name="save" />
    <input type="button" class="button" value="CANCEL" onclick="history.back();" />  </td>
</tr>
</table>
</form>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
