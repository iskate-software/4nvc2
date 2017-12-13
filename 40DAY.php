<?php  

// This function is used when painting the screen, to see what values, if any, should be displayed.

function isitdoc($xval) {

// usage $isitdoc($dummy[$i])
global $ak, $thisdoc,$docassign,$am,$req,$name,$contact,$newcl,$petname,$newpet,$problem,$Doc1A,$Doc2A,$Doc3A,$Doc4A,$Doc5A,$Doc6A,$Doc7A,$Doc8A,$Doc9A,$Doc10A,$Doc11A,$Doc1T,$Doc2T,$Doc3T,$Doc4T,$Doc5T,$Doc6T,$Doc7T,$Doc8T,$Doc9T,$Doc10T,$Doc11T ;

$d1 = array_search($thisdoc,$docassign) ; 

if ($d1 === FALSE) { return 0; }

switch ($d1) {
 case 0;
  $idx = array_search($xval,$Doc1T) ;
  $ak = $Doc1A[$idx] ;
  break;
 case 1;
  $idx = array_search($xval,$Doc2T) ;
  $ak = $Doc2A[$idx] ;
  break;
 case 2;
  $idx = array_search($xval,$Doc3T) ;
  $ak = $Doc3A[$idx] ;
  break;
 case 3;
  $idx = array_search($xval,$Doc4T) ;
  $ak = $Doc4A[$idx] ;
  break;
 case 4;
  $idx = array_search($xval,$Doc5T) ;
  $ak = $Doc5A[$idx] ;
  break;
 case 5;
  $idx = array_search($xval,$Doc6T) ;
  $ak = $Doc6A[$idx] ;
  break;
 case 6;
  $idx = array_search($xval,$Doc7T) ;
  $ak = $Doc7A[$idx] ;
  break;
 case 7;
  $idx = array_search($xval,$Doc8T) ;
  $ak = $Doc8A[$idx] ;
  break;
 case 8;
  $idx = array_search($xval,$Doc9T) ;
  $ak = $Doc9A[$idx] ;
  break;
 case 9;
  $idx = array_search($xval,$Doc10T) ;
  $ak = $Doc10A[$idx] ;
  break;
 case 10;
  $idx = array_search($xval,$Doc11T) ;
  $ak = $Doc11A[$idx] ;
  break;
  }
  if ($idx === FALSE) {return 0 ; }
  $apptnum = $ak ;
  $req = $am[$ak][2] ;
  $name = $am[$ak][3] ;
  $contact = $am[$ak][4] ;
  $newcl = $am[$ak][5] ;
  $petname = $am[$ak][6] ;
  $newpet = $am[$ak][7] ;
  $problem = $am[$ak][8] ;
  
  return  1 ; 
  }
  
// Note. This installation (NHVC) only has 3 small animal doctors. The program allows for 4 for CAH and other large small animal practices.

session_start() ;
require_once('../tryconnection.php');
mysql_select_db($database_tryconnection, $tryconnection);

// set up species abbrev table
$species = array() ;
$q_spec = "SELECT ABBREV FROM ANIMTYPE ORDER BY ANIMALID" ;
$get_spec = mysql_query($q_spec, $tryconnection) or die(mysql_error()) ;
while ($row_spec = mysqli_fetch_assoc($get_spec)) {
 $species[] = $row_spec['ABBREV'] ;
 }

$day = $_GET['day'];
$month = $_GET['month'];
$year = $_GET['year'];

$weekday = strftime('%u', mktime(0,0,0,$month,$day,$year)) ;

// Set up variables which will determine whether surgery and dentistry columns to appear or not.
// These are subsequently modified if surgeons or dentists appear in the schedule.

$_SESSION['surg'] = 0 ;
$_SESSION['dent'] = 0 ; 

if ($_GET['inc'] != 0) {

// They want to go back or forward.
// figure out the timestamp for when they started, then go back or forth 24 hours and find the new timestamp.
   $olddate = mktime(0,0,0,$month,$day,$year) ;
   $newdate = $olddate + 60*60*24 * $_GET['inc'] ;
   $_GET['day'] = strftime('%d',$newdate) ;
   $_GET['month'] = strftime('%m', $newdate) ;
   $_GET['year'] = strftime('%Y', $newdate) ;
   
// and reframe it
   
   $day = $_GET['day'];
   $month = $_GET['month'];
   $year = $_GET['year'];
   $weekday = strftime('%u', mktime(0,0,0,$month,$day,$year)) ;

//   $get_DENT = "SELECT DENTDAYS FROM CRITDATA LIMIT 1" ;
//   $get_it = mysql_query($get_DENT, $tryconnection) or die(mysql_error()) ;
//   $row_DENT = mysql_fetch_assoc($get_it) ;

   $_SESSION['dent'] = 0 ; 
   $_SESSION['surg'] = 0 ;
}

$selected = mktime(0,0,0,$month,$day,$year) ;
$first_of_month = mktime(0,0,0,$month,1,$year);
$first_of_month = strftime('%u',$first_of_month);

$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year); 
$date_to_retrieve = $year.'-'.$month.'-'.$day ;
$mkcal = mktime(0,0,0,$month,$day,$year) ;
$print_day = strftime('%Y-%m-%d',$mkcal) ;
$cal_day = strftime('%u',$mkcal) ;

// Step 1  Pick the next 5 items out of HOSPHOURS.
$query_param = "SELECT STARTHOUR,STARTMIN,ENDHOUR,ENDMIN,APPTTIME FROM HOSPHOURS WHERE DAY = $cal_day LIMIT 1 " ;
$Get_param   = mysql_query($query_param, $tryconnection) or die(mysql_error()) ;
$row_param   = mysqli_fetch_assoc($Get_param) ;
$startahour  = $row_param['STARTHOUR'] ;
$startamin   = $row_param['STARTMIN'] ;
$endahour    = $row_param['ENDHOUR'] ;
$endamin     = $row_param['ENDMIN'] ;
$show        = $row_param['APPTTIME'] ;

$divider = 60/$show ;
$begintest = $startamin + (60 * $startahour) ;
$endtest = $endamin + (60 * $endahour) ;
$numints = 1 + ($endtest - $begintest)/$show ; 
$ctr = $startamin;
$now = $startahour;

// Step 2 Pick up the current appts for this day, and pop them into three arrays. 
// There is a separate entry for each doctor, holding just the time (the key) and the appointment number.
// This is in sparse matrix format.
// The array $docassign holds the array name of the above for each doctor
// The $am array (appointments master) holds the detailed record for each appointment. The key is apptnum.


$get_Appts = "SELECT APPTNUM, TIMEOF, DURATION,SHORTDOC,DOCREQ,NAME,CONTACT,NEWCL,PETNAME,RFPETTYPE,PSEX,NEWPET,PROBLEM FROM APPTS WHERE DATEOF = '$date_to_retrieve' AND CANCELLED = 0 ORDER BY SHORTDOC, TIMEOF" ;
$query_Appts = mysql_query($get_Appts, $tryconnection) or die(mysql_error()) ;

$am = array() ; // Holds each appt line items. Key is APPTNUM
$docassign = array() ; // Holds each doctor's name. Key is numeric, based on doctor sequence of appearance today. This is then
//                        used to pick up the appropriate array where the doctor's appts are placed. See next.

$arraynum = 1 ; // Starting number for the array name into which each doctor's appts are placed. They each
//                 have their own array, created as needed. Each array name is 'Doc' concatenated with this variable. e.g. Doc1, Doc2, etc.

while ($row_Appts = mysqli_fetch_assoc($query_Appts)) {
$entries = 1 ;

  $key = array_search($row_Appts['SHORTDOC'],$docassign) ;
  if ($key === FALSE) {
  
//   Make two parallel arrays, one for the apptnums, the other for the times.

   ${'Doc'.$arraynum.'A'} = array() ;
   ${'Doc'.$arraynum.'T'} = array() ;
   
   $key = $arraynum -1 ;
   $thedoc = $row_Appts['SHORTDOC'];
   $arraynum++ ;
   $docassign[] =  $thedoc;
  }
  else {
 } // not ($key === FALSE)
 
  $akey = $row_Appts['APPTNUM'] ;
  
  $am[$akey][] = $row_Appts['TIMEOF'] ;
  $am[$akey][] = $row_Appts['SHORTDOC'] ;
  $am[$akey][] = $row_Appts['DOCREQ'] ; // 2
  $am[$akey][] = $row_Appts['NAME'] ; // 3
  $am[$akey][] = $row_Appts['CONTACT'] ;  // 4
  $am[$akey][] = $row_Appts['NEWCL'] ; // 5
  $am[$akey][] = $row_Appts['PETNAME'].' (' . $species[$row_Appts['RFPETTYPE']-1].'-'.$row_Appts['PSEX'].')'  ; // 6
  $am[$akey][] = $row_Appts['NEWPET'] ; // 7
  $am[$akey][] = $row_Appts['PROBLEM'] ; // 8
  $am[$akey][] = $row_Appts['DURATION'] ; // 9
  
  $reftime =  $row_Appts['DURATION'] ;
  
  // and add to the slot detail arrays
  
  $d = $key ; 
  $akey = $row_Appts['TIMEOF']  ;
  $value = $row_Appts['APPTNUM'] ;
  
 // The switch variable below is actually one behind the array name, i.e. $d = 0 for Doc1, = 1 for $Doc2, etc.
 // also applies in the next section.
 
  switch ($d) {
  case 0;
    $Doc1T[] = $akey ;
    $Doc1A[] = $value  ;  
   break;
  case 1;
    $Doc2T[] = $akey ;
    $Doc2A[] = $value  ; 
   break;
  case 2;
    $Doc3T[] = $akey ;
    $Doc3A[] = $value  ; 
   break;
  case 3;
    $Doc4T[] = $akey ;
    $Doc4A[] = $value  ; 
   break;
  case 4;
    $Doc5T[] = $akey ;
    $Doc5A[] = $value  ; 
   break;
  case 5;
    $Doc6T[] = $akey ;
    $Doc6A[] = $value  ; 
   break;
  case 6;
    $Doc7T[] = $akey ;
    $Doc7A[] = $value  ; 
   break;
  case 7;
    $Doc8T[] = $akey ;
    $Doc8A[] = $value  ; 
   break;
  case 8;
    $Doc9T[] = $akey ;
    $Doc9A[] = $value  ; 
   break;
  case 9;
    $Doc10T[] = $akey ;
    $Doc10A[] = $value  ; 
   break;
  case 10;
    $Doc11T[] = $akey ;
    $Doc11A[] = $value  ; 
   break;
  }

  $entries++ ;
  
  // is this appointment for more than the minimum amount of time?
  // Add trailers to the detailed appt breakdown.
  
    $newhr = substr($row_Appts['TIMEOF'],0,2) ;
    $newmin = substr($row_Appts['TIMEOF'],3,2) ;
         
  while ($reftime  > $show) {
  
  // work out the new time.
         $hr  = $newhr ;
         $min = $newmin +$show ;
     
         if ($min >= '60') {
            $hr  = substr($hr +101,1,2);
            $min = substr(($min + 40),1,2);
         }
      
         $newtime = $hr.':'.$min ;
         $akey = $newtime ;
         $value = $row_Appts['APPTNUM'] ;
     
         switch ($d) {
         case 0;
          $Doc1T[] = $akey ;
          $Doc1A[] = $value  ; 
          break;
         case 1;
          $Doc2T[] = $akey ;
          $Doc2A[] = $value  ;  
          break;
         case 2;
          $Doc3T[] = $akey ;
          $Doc3A[] = $value  ; 
          break;
         case 3;
          $Doc4T[] = $akey ;
          $Doc4A[] = $value  ; 
          break;
         case 4;
          $Doc5T[] = $akey ;
          $Doc5A[] = $value  ; 
          break;
         case 5;
          $Doc6T[] = $akey ;
          $Doc6A[] = $value  ; 
          break;
         case 6;
          $Doc7T[] = $akey ;
          $Doc7A[] = $value  ; 
          break;
         case 7;
          $Doc8T[] = $akey ;
          $Doc8A[] = $value  ; 
          break;
         case 8;
          $Doc9T[] = $akey ;
          $Doc9A[] = $value  ; 
          break;
         case 9;
          $Doc10T[] = $akey ;
          $Doc10A[] = $value  ; 
          break;
         case 10;
          $Doc11T[] = $akey ;
          $Doc11A[] = $value  ; 
          break;
         }    
         
         $duration = $reftime - $show ;
         $reftime = $duration ;
         $entries ++ ;
         
         $newhr = $hr;
         $newmin = $min ;
 
   } //($reftime  > $show)
   
} // while $row_Appts = mysql_fetch_assoc($query_Appts)

// and set the thing up so that it can be referenced by the edit module.
$_SESSION['am'] = $am ;

// Step 3. Make a master array for the doctors names. Determine how many doctors there are (which determines the screen size.)
// If in appts, the column in which they are to appear is given by the number in the DUTY field. 0 means they are off duty.
// There can only be one surgeon or dentist on at one time, so those columns can only have flags of 1 or 0.
/* 
APPOINTMENT FLAGS by position in the DUTY field, and how they are depicted on the month screen.

1 am appt A
2 am surg S
3 pm apt   A
4 pm surg S
5 Emerg 1 E1
6 am Gv G (L at NHVC)
7 pm Gv G (L at NHVC)
8 Evening A
9 Evening Gv G (L at NHVC)
10 Emerg 2 E2
11 am Hosp doctor H
12 pm Hosp doctor H
13 am Dentistry D
14 pm Dentistry D
*/

	 $query_APPTDOCS = "SELECT * FROM APPTDOCS WHERE `DATEIS`='$date_to_retrieve' AND DUTY <> '00000000000000' ORDER BY substr(duty,1,1) ";
	 
	 //  The assumptions are: The SEQ column is a proxy for "pecking order" within the clinic, (which can be over-ruled by the assignment of columns)
	 //  and the DUTY field is all zeroes if they are not working.
	 
	 $APPTDOCS = mysql_query($query_APPTDOCS, $tryconnection) or die(mysql_error());
     $howmany = "SELECT FOUND_ROWS() AS IMAX" ;
     $mdocarray = array() ;
     $docarray  = array() ;
     $surgarray = array() ;
     $dentarray = array() ;
     $larray    = array() ;
     $eslarray  = array() ;
     $getmax = mysql_query($howmany, $tryconnection) or die(mysql_error()) ;
     $max = mysqli_fetch_assoc($getmax) ;

     $lines = $max['IMAX'] - 1 ;   
// There are four arrays associated with the doctor names array. They are the times and location for: appts,  surgery,  dentistry and large animal.

// Each array is structured with one entry for each session the doctor is working (am, pm, evening).
// It contains the short name, the column on the screen in which it is to appear, and the start and stop times.
// So if a doctor is working three sessions, there would be three entries for him/her.

     $i  = 0 ;
     $j  = 0 ; // this variable is for the small appts. It marches with $i, but it is bumped up manually if there is more than one session for the doctor.
     $js = 0 ; // for surgery 
     $jd = 0 ; // for dentistry
     $jl = 0 ; // for large animals It marches with $i, but it is bumped up manually if there is more than one session for the doctor.
     
     // and run the query again, as it has been superseded by the howmany..
     
	 $APPTDOCS = mysql_query($query_APPTDOCS, $tryconnection) or die(mysql_error());
	 
	 // Set up a little tally to determine how many active columns there are before the tech column (small animal doctors).
	 // Also, a count of how many large animal columns there are to be.
	$actcol = 0 ;
	$lactcol = 0 ;


 /////// Edit the duties, to identify any duplicates in the duties. //////
 
   $chkduty=array() ;
   $docduty=array() ;
   $z = 0 ;
   
	while ($row_APPTDOCS = mysqli_fetch_assoc($APPTDOCS) ) {
	      $chkduty[] = $row_APPTDOCS['SHORTDOC'] ;
	      $docduty[] = $row_APPTDOCS['DUTY'] ;
	      $z++ ;
	}
	$zmax = $z ;
/*
// now if there is more than one doctor on duty,  step through their assignments (cols 1 - 8 are the only important ones) to make sure there are no duplicate columns
   if ($zmax > 1) {
        $compdoc = array() ;
       // check each column
       for ($col = 0 ; $col < 8 ; $col++ ; ) {
             $comp = array() ;
             // pick up each doctor's entry for that column
              for ($z=0; $z < $zmax; $z++) {
                 if (substr($chkduty[$z],$col,1) != '0' ) {
                 // build up the array of non zero entries....
                  $comp[] = substr($chkduty[$z],$col,1) ;
                  
                 }
             $compdoc[] = $docduty[$z] ;
              }
              $warn = array_count_values($comp) ;
              // and if any of them are more than 1, there is a duplicate entry.
          }
        unset($comp) ;
     
   }
*/
 // Then run the query again as the index has been maxed.

	$APPTDOCS = mysql_query($query_APPTDOCS, $tryconnection) or die(mysql_error());
	while ($row_APPTDOCS = mysqli_fetch_assoc($APPTDOCS) ) {

            // This holds all doctors info, regardless of task.
            
    $mdocarray[$i] = $row_APPTDOCS['SHORTDOC'] ;
    $duty = $row_APPTDOCS['DUTY'] ;
    $start1 = $row_APPTDOCS['OPEN1'] ;
    $end1 = $row_APPTDOCS['CLOSE1'] ;
    $start2 = $row_APPTDOCS['OPEN2'] ;
    $end2 = $row_APPTDOCS['CLOSE2'] ;
    $start3 = $row_APPTDOCS['OPEN3'] ;
    $end3 = $row_APPTDOCS['CLOSE3'] ;
    $es1st = $row_APPTDOCS['ES1ST'] ;
    $es1sp = $row_APPTDOCS['ES1SP'] ;
    $es1Bst = $row_APPTDOCS['ES1BST'] ;
    $es1Bsp = $row_APPTDOCS['ES1BSP'] ;
    $es2st = $row_APPTDOCS['ES2ST'] ;
    $es2sp = $row_APPTDOCS['ES2SP'] ;
    $es2Bst = $row_APPTDOCS['ES2BST'] ;
    $es2Bsp = $row_APPTDOCS['ES2BSP'] ;
    $es3st = $row_APPTDOCS['ES3ST'] ;
    $es3sp = $row_APPTDOCS['ES3SP'] ;
    $es3Bst = $row_APPTDOCS['ES3BST'] ;
    $es3Bsp = $row_APPTDOCS['ES3BSP'] ;
    
  
    // now figure out which session this is. Check the am appts first.
    if (substr($duty,0,1) > 0) {
     $docarray[$j][1] = substr($duty,0,1) ; //That determines the display column
     if (substr($duty,0,1) > $actcol && $actcol < 5) {
        $actcol = substr($duty,0,1) ;
     }
     $docarray[$j][2] = substr($start1,0,5) ;
     $docarray[$j][3] = substr($end1,0,5) ;
     $docarray[$j][4] = $mdocarray[$i] ; // The current doctor name
     // and put the starting and ending times for the first two eslots here.
     $docarray[$j][5] = $es1st ;
     $docarray[$j][6] = $es1sp ;
     $docarray[$j][7] = $es1Bst ;
     $docarray[$j][8] = $es1Bsp ; 
     //and as that row has been assigned, $j should be bumped.
     $j++ ;
     }
    // Now the pm appts
    if (substr($duty,2,1) > 0) {
     if (substr($duty,2,1) > $actcol && $actcol < 5) {
        $actcol = substr($duty,2,1) ;
     }
     $docarray[$j][1] = substr($duty,2,1) ; //That determines the display column
     $docarray[$j][2] = substr($start2,0,5) ;
     $docarray[$j][3] = substr($end2,0,5) ;
     $docarray[$j][4] = $mdocarray[$i] ;
     // and put the starting and ending times for the next two eslots here.
     $docarray[$j][5] = $es2st ;
     $docarray[$j][6] = $es2sp ;
     $docarray[$j][7] = $es2Bst ;
     $docarray[$j][8] = $es2Bsp ;
     //and as that row has been assigned, $j should be bumped.
     $j++ ;
    }
    // And the evening appts
    if (substr($duty,7,1) > 0) {
     if (substr($duty,7,1) > $actcol && $actcol < 5) {
        $actcol = substr($duty,7,1) ;
     }
     $docarray[$j][1] = substr($duty,7,1) ; //That determines the display column
     $docarray[$j][2] = substr($start3,0,5) ;
     $docarray[$j][3] = substr($end3,0,5) ;
     $docarray[$j][4] = $mdocarray[$i] ;
     // and put the starting and ending times for the last two eslots here.
     $docarray[$j][5] = $es3st ;
     $docarray[$j][6] = $es3sp ;
     $docarray[$j][7] = $es3Bst ;
     $docarray[$j][8] = $es3Bsp ;
     //and as that row has been assigned, $j should be bumped.
     $j++ ;
    }
    // Check for am surgery.There can only be 1
    if (substr($duty,1,1) > 0) {
     $_SESSION['surg'] = 1 ;
     $surgarray[$js][1] = 'S' ; //That determines the display column
     $surgarray[$js][2] = substr($start1,0,5) ;
     $surgarray[$js][3] = substr($end1,0,5) ;
     $surgarray[$js][4] = $mdocarray[$i] ;
     
     //and as that row has been assigned, $js should be bumped.
     $js++ ;
    }
    // And pm surgery.There can only be 1
    if (substr($duty,3,1) > 0) {
     $_SESSION['surg'] = 1 ;
     $surgarray[$js][1] = 'S' ; //That determines the display column
     $surgarray[$js][2] = substr($start2,0,5) ;
     $surgarray[$js][3] = substr($end2,0,5) ;
     $surgarray[$js][4] = $mdocarray[$i] ;
     
     //and as that row has been assigned, $js should be bumped.
     $js++ ;
    }
    // Check for am dentistry.There can only be 1
    if (substr($duty,12,1) > 0) {
     $_SESSION['dent'] = 1 ; 
     $dentarray[$jd][1] = 'D' ; //That determines the display column
     $dentarray[$jd][2] = substr($start1,0,5) ;
     $dentarray[$jd][3] = substr($end1,0,5) ;
     $dentarray[$jd][4] = $mdocarray[$i] ;
     
     //and as that row has been assigned, $jd should be bumped.
     $jd++ ;
    }
    // And pm dentistry.There can only be 1
    if (substr($duty,13,1) > 0) {
     $_SESSION['dent'] = 1 ; 
     $dentarray[$jd][1] = 'D' ; //That determines the display column
     $dentarray[$jd][2] = substr($start2,0,5) ;
     $dentarray[$jd][3] = substr($end2,0,5) ;
     $dentarray[$jd][4] = $mdocarray[$i] ;
     //and as that row has been assigned, $jd should be bumped.
     $jd++ ;
     
     } 
   
   // large animal data 
   
    // am appts
    if (substr($duty,5,1) > 0) {
     if (substr($duty,5,1) > $lactcol +2) {
        $lactcol = substr($duty,5,1) - 2; //       $lactcol++ ;
        
     }
     $larray[$jl][1] = substr($duty,5,1) ; //That determines the display column
     $larray[$jl][2] = substr($start1,0,5) ;
     $larray[$jl][3] = substr($end1,0,5) ;
     $larray[$jl][4] = $mdocarray[$i] ; // The current doctor name
     // and put the starting and ending times for the first two eslots here.
     $docarray[$j][5] = $es1st ;
     $docarray[$j][6] = $es1sp ;
     $docarray[$j][7] = $es1Bst ;
     $docarray[$j][8] = $es1Bsp ;
               //and as that row has been assigned, $jl should be bumped.
               
     $jl++ ;
     
     }
     
    // Now the pm appts
    if (substr($duty,6,1) > 0) {
     if (substr($duty,6,1) > $lactcol +2) {
                $lactcol = substr($duty,6,1) -2; //$lactcol++ ;
     }
     $larray[$jl][1] = substr($duty,6,1) ; //That determines the display column
     $larray[$jl][2] = substr($start2,0,5) ;
     $larray[$jl][3] = substr($end2,0,5) ;
     $larray[$jl][4] = $mdocarray[$i] ;
     // and put the starting and ending times for the first two eslots here.
     $docarray[$j][5] = $es2st ;
     $docarray[$j][6] = $es2sp ;
     $docarray[$j][7] = $es2Bst ;
     $docarray[$j][8] = $es2Bsp ;
     
               //and as that row has been assigned, $jl should be bumped.
     $jl++ ;
     
    }
    
    // And the evening appts
    if (substr($duty,8,1) > 0) {
     if (substr($duty,8,1) > $lactcol +2) {
                $lactcol = substr($duty,8,1) -2; //
     }
     $larray[$jl][1] = substr($duty,8,1) ; //That determines the display column
     $larray[$jl][2] = substr($start3,0,5) ;
     $larray[$jl][3] = substr($end3,0,5) ;
     $larray[$jl][4] = $mdocarray[$i] ;
     // and put the starting and ending times for the first two eslots here.
     $docarray[$j][5] = $es3st ;
     $docarray[$j][6] = $es3sp ;
     $docarray[$j][7] = $es3Bst ;
     $docarray[$j][8] = $es3Bsp ;
     
     //and as that row has been assigned, $jl should be bumped.
     $jl++ ;
     
    }
    
    $i++ ;
  }  
 
     
    // So that should have all the doctor data lined up. (Hospital docs would replace large animal coding .)
    

    $TOTALDOCS = $lines+1 ;
    $totalaptdocs = $j - 1 ;
    $totalsurdocs = $js -1 ;
    $totaldendocs = $jd -1  ;
    $totalladocs  = $jl - 1 ;
    
// Step 4  From the above, make an array for the times they are on, and what it is that they do when they are on. (A dummy of the screen.)
// Except that it is always the maximum size - 4 small, 1 tech, 1 surg, 1 dent, 3 large.  At screen write time, the 
// empty columns are simply not presented.
// The format is: Time, doc name col 1, doc name col 2, (more docs if necessary, for Billings two max.) surg name, dent name. LA1, LA2, LA3 Nulls if not applicable.

$dummy = array() ;
$dumtime = array() ;
$dumeslot = array() ;
$js = 0 ;
$jd = 0 ;
$jl = 0 ;

// Start at the top of the dummy array (time slot 1), and work down.The main difference between this and the real screen is that here, 
// the time is only included once, rather than before each doctor column. Also, empty columns are dropped.
// All the scrabbling right after the "for" is simply to format the time in human readable form...
// The $dumtime array allows for non military time display....

for ($i = 1 ; $i <= $numints; $i++) {  $ctr=$ctr+$show; $min = substr((100 + $ctr-$show),1,2) ; 
                                       if ($ctr == $show*($divider+1) ) {
                                           $now = substr($now+101,1,2); 
                                           $now = "$now" ;
                                           $ctr= $show; $min = '00';
                                           } 
                                           
// and continue the loop. Indented back to left hand side for readability....

 $k = $i-1 ;
 $dumtime[$k] = $now.':'.$min;
 
 // and put leading zeroes in times before 10:00.
//  if (strlen(trim($now == 4) )) {$now = '0'.substr($now,1,4) ;}
 
 $dummy[$k][0] = $now.':'.$min;  // this is the time.
 if ($now < '13') {
 $dumtime[$k] = $dummy[$k][0];
 }
 else {
 $dumtime[$k] = $now-12 .':'.$min;
 }
 
// Now work across the screen, doing appts (based on $actcol (max 4) cols), tech (1 col), surg (1 col) and dent (1 col) plus the three large animal columns.
// The dummy array allows for the maximum number of doctors. If there is only one small animal doctor, the next three columns will be blank.
// So Tech will always be 5, Surgery column 6, and Dentistry 7. Even when there are less than four doctors doing small animal appts.
// Check each doctor array (col. field) to see if they are on here. Look at the current time, and whether it fits between the start and stop times for that doctor.

// The e-slots flags are held in a separate array ($dumeslot), 4 columns for small, 1 each for tech, surgery and dentistry, and 3 for large animals. The values are 
// either non-existent or 1. They are set by looking at the start and stop times in the individual doctor arrays. In practice there are no e-slots for tech, surgery and dentistry.

// Columns 1 - 4
 for ($j = 0; $j <= $totalaptdocs; $j++ ) {  
 // is he/she in column 1?
    if ($docarray[$j][1] == 1) { // is it within the working period?
       if ($dummy[$k][0] >= $docarray[$j][2] && $dummy[$k][0] < $docarray[$j][3]) {
       $dummy[$k][1] = $docarray[$j][4] ;  
       // and see if there are any applicable e-slots.
           if (($dummy[$k][0] >= $docarray[$j][5] && $dummy[$k][0] < $docarray[$j][6]) || ($dummy[$k][0] >= $docarray[$j][7] && $dummy[$k][0] < $docarray[$j][8])) {
              $dumeslot[$k][0] = 1 ;
           }
   
       } // doctor's name
    } // end of column 1 check
    if ($actcol > 1) {
             // is he/she in column 2?
      if ($docarray[$j][1] == 2) { // is it within the working period?
         if ($dummy[$k][0] >= $docarray[$j][2] && $dummy[$k][0] < $docarray[$j][3]) {
         $dummy[$k][2] = $docarray[$j][4] ; 
       // and see if there are any applicable e-slots.
           if (($dummy[$k][0] >= $docarray[$j][5] && $dummy[$k][0] < $docarray[$j][6]) || ($dummy[$k][0] >= $docarray[$j][7] && $dummy[$k][0] < $docarray[$j][8])) {
              $dumeslot[$k][1] = 1 ;
           } 
  
         }// doctor's name
      } // end of column 2 check
    }
    if ($actcol > 2)  {
             // is he/she in column 3?
      if ($docarray[$j][1] == 3) { // is it within the working period?
         if ($dummy[$k][0] >= $docarray[$j][2] && $dummy[$k][0] < $docarray[$j][3]) {
         $dummy[$k][3] = $docarray[$j][4] ; 
       // and see if there are any applicable e-slots.
           if (($dummy[$k][0] >= $docarray[$j][5] && $dummy[$k][0] < $docarray[$j][6]) || ($dummy[$k][0] >= $docarray[$j][7] && $dummy[$k][0] < $docarray[$j][8])) {
              $dumeslot[$k][2] = 1 ;
           } 
   
         }// doctor's name, 
      } // end of column 3 check
    }
    if ($actcol > 3)  {
             // is he/she in column 4?
      if ($docarray[$j][1] == 4) { // is it within the working period?
         if ($dummy[$k][0] >= $docarray[$j][2] && $dummy[$k][0] < $docarray[$j][3]) {
         $dummy[$k][4] = $docarray[$j][4] ;  
       // and see if there are any applicable e-slots.
           if (($dummy[$k][0] >= $docarray[$j][5] && $dummy[$k][0] < $docarray[$j][6]) || ($dummy[$k][0] >= $docarray[$j][7] && $dummy[$k][0] < $docarray[$j][8])) {
              $dumeslot[$k][3] = 1 ;
           } 
  
         }// doctor's name
      } // end of column 4 check
    }
  } // end of max assigned small animal doctors for that day check
    
// The next column (5) for techs will always be there. 

// So, moving on to 6

 // is he/she in surgery column (6) (if it is in fact used) ? 
 // if not, leave space for it so that it can be optional by day.
    if ($_SESSION['surg'] == 1) {
     for ($js = 0; $js <= $totalsurdocs; $js++ ) { 
       if ($surgarray[$js][1] == 'S') { // is it within the working period?
         if ($dummy[$k][0] >= $surgarray[$js][2] && $dummy[$k][0] < $surgarray[$js][3]) {
             $dummy[$k][6] = $surgarray[$js][4] ; 
          } // doctor's name, 
        } //then  go to the next time slot.
     } // end of surgery column (6) check
    } // if surg.
    
 // is he/she in dentistry column (7) (if it is in fact used) ? 
 // if not, leave space for it so that it can be optional by day.
    if ($_SESSION['dent'] == 1) {
      for ($jd = 0; $jd <= $totaldendocs; $jd++ ) { 
       if ($dentarray[$jd][1] == 'D') { // is it within the working period?
         if ($dummy[$k][0] >= $dentarray[$jd][2] && $dummy[$k][0] < $dentarray[$jd][3]) {
            $dummy[$k][7] = $dentarray[$jd][4] ;  
         } // doctor's name, 
        }  //then  go to the next time slot.
      } // end of dentistry column (7) check
   } // if dent.


// The first large animal column on the screen would be $actcol + 1 (tech) + $totalsurdocs + $totaldendocs  

// $lastactcol = $actcol + 1  +  $_SESSION['surg'] + $_SESSION['dent'] ;


 for ($jl = 0; $jl <= $totalladocs; $jl++ ) { 
 // is he/she in Large animal column (8)? (Which for NHVC is the third effective column.... ie large animal doctors go in 3,4,5 at the time they book.
    if ($larray[$jl][1] == 3) { // so now is it within the working period?
    
       if ($dummy[$k][0] >= $larray[$jl][2] && $dummy[$k][0] < $larray[$jl][3]) {
        $dummy[$k][8] = $larray[$jl][4] ;
       // and see if there are any applicable e-slots.
           if (($dummy[$k][0] >= $larray[$jl][5] && $dummy[$k][0] < $larray[$jl][6]) || ($dummy[$k][0] >= $larray[$jl][7] && $dummy[$k][0] < $larray[$jl][8])) {
              $dumeslot[$k][7] = 1 ;
           } 
        } // doctor's name
    } // end of Large animals column (8) check
    
    // is he/she in Large animal column (9)?
    if ($larray[$jl][1] == 4) { // so now is it within the working period?
       if ($dummy[$k][0] >= $larray[$jl][2] && $dummy[$k][0] < $larray[$jl][3]) {
        $dummy[$k][9] = $larray[$jl][4] ;
       // and see if there are any applicable e-slots.
           if (($dummy[$k][0] >= $larray[$jl][5] && $dummy[$k][0] < $larray[$jl][6]) || ($dummy[$k][0] >= $larray[$jl][7] && $dummy[$k][0] < $larray[$jl][8])) {
              $dumeslot[$k][8] = 1 ;
           } 
        } // doctor's name
    } // end of Large animals column (9) check
    
    // is he/she in Large animal column (10)?
    if ($larray[$jl][1] == 5) { // is it within the working period?
       if ($dummy[$k][0] >= $larray[$jl][2] && $dummy[$k][0] < $larray[$jl][3]) {
        $dummy[$k][10] = $larray[$jl][4] ;
       // and see if there are any applicable e-slots.
           if (($dummy[$k][0] >= $larray[$jl][5] && $dummy[$k][0] < $larray[$jl][6]) || ($dummy[$k][0] >= $larray[$jl][7] && $dummy[$k][0] < $larray[$jl][8])) {
              $dumeslot[$k][9] = 1 ;
           } 
        } // doctor's name
    } // end of Large animals column (10) check
        
  } //for ($jl = 0; $jl <= $totalladocs; $jl++ ) i.e. the large animal section.
    
} // end of dummy creation loop.

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, maximum-scale=2.0" />
<title><?php echo strtoupper(strftime('%A %B %e, %Y', mktime(0,0,0,$month,$day,$year))); ?></title>
<link rel="stylesheet" type="text/css" href="../ASSETS/styles.css" />
<script type="text/javascript" src="../ASSETS/scripts.js"></script>
<style type="text/css">
body {
background-color:#FFFFFF;
overflow:auto;
}


</style>

<script type="text/javascript">
function pageScroll() {
 window.moveTo(0,0) ;
 var dayis = <?php echo date("N",mktime(0,0,0,$month,$day,$year)); ?> ;
 if (dayis < 6 ) {
  window.resizeTo(screen.width-10,screen.height-40);
  window.scrollBy(0,600) ; 
 }
 else {
  window.resizeTo(screen.width-250,screen.height-200);
  } 
 }

</script>
</head>

<body onLoad='pageScroll()'>

<div id="WindowBody">
<!-- InstanceBeginEditable name="DVMBasicTemplate" -->
<form name="day" method="get" action="" >

<!-- There are 440 pixels per active doctor column before the techs. Dentistry is optional So 440 px for no doctor or surgeon (tech only), 1320 for tech,surg and dentistry included, 1760 px for 1 doctor, 2200 for 2, 2640 for 3 etc   -->

<table border="1" cellspacing="0" cellpadding="0" style="position:absolute; top:0px; left:0px; width:<?php echo 440 + 440*($_SESSION['dent']+$_SESSION['surg']) + ($actcol+$lactcol)*440 ;?>px;" bordercolor="#CCCCCC" rules="all" frame="box">
  <tr>
  <?php if ($actcol > 0) {
          echo
    '<th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Time</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">SA Doctor 1</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Rq</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Client</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Patient</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">N</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Reason</th> ' ;
    } 
     if ($actcol > 1) {
          echo
    '<th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Time</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">SA Doctor 2</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">R</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Client</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Patient</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">N</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Reason</th>' ;
    } 
     if ($actcol > 2) {
          echo  
    '<th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Time</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">SA Doctor 3</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">R</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Client</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Patient</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">N</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Reason</th> ' ;
    } 
     if ($actcol > 3) {
          echo  
    '<th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Time</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">SA Doctor 3</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">R</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Client</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Patient</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">N</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Reason</th> ' ;
    } ?>
    
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Time</th>
    <th bgcolor="#FFFFFF" class="Verdana11BBlue"  scope="col">Tech</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">R</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Client</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Patient</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">N</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Reason</th>
    
     <?php if ($_SESSION['surg'] ==1 ) {
    echo
    '<th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Time</th>
    <th bgcolor="#FFFFFF" class="Verdana11BRed"   scope="col">Surgery</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">R</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Client</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Patient</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">N</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Reason</th>' ;
    } 
     if ($_SESSION['dent'] ==1 ) {
    echo
    '<th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Time</th>
    <th bgcolor="#333331" class="Verdana11BPink"  scope="col">Dentistry</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">R</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Client</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Patient</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">N</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Reason</th>' ;
    } 
    if ($lactcol > 0) {
      echo    
    '<th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Time</th> 
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">LA 1 Doctor</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">R</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Client</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Patient</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">N</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Reason</th>' ;
     }
    if ($lactcol > 1) {
      echo    
    '<th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Time</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">LA 2 Doctor</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">R</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Client</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Patient</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">N</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Reason</th>' ;
    } 
     if ($lactcol > 2) {
      echo 
    '<th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Time</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">LA 3 Doctor</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">R</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Client</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Patient</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">N</th>
    <th bgcolor="#000000" class="Verdana11Bwhite" scope="col">Reason</th>' ;
    } ?>
    
  </tr>
	<tr>  <?php if ($actcol > 0) {
          echo
      '<td width="40" height="0"></td>
      <td width="70" height="0"></td>
      <td width="15" height="0"></td>
      <td width="120" height="0"></td>
      <td width="80" height="0"></td>
      <td width="15" height="0"></td>
      <td width="100" height="0"></td>' ;
      } 
       if ($actcol > 1) {
          echo
      '<td width="40" height="0"></td>
      <td width="70" height="0"></td>
      <td width="15" height="0"></td>
      <td width="120" height="0"></td>
      <td width="80" height="0"></td>
      <td width="15" height="0"></td>
      <td width="100" height="0"></td>' ;
      } 
       if ($actcol > 2) {
          echo
      '<td width="40" height="0"></td>
      <td width="70" height="0"></td>
      <td width="15" height="0"></td>
      <td width="120" height="0"></td>
      <td width="80" height="0"></td>
      <td width="15" height="0"></td>
      <td width="100" height="0"></td>' ; 
      } 
       if ($actcol > 3) {
          echo
      '<td width="40" height="0"></td>
      <td width="70" height="0"></td>
      <td width="15" height="0"></td>
      <td width="120" height="0"></td>
      <td width="80" height="0"></td>
      <td width="15" height="0"></td>
      <td width="100" height="0"></td>' ;
      } ?>
      
        <!-- Techs -->
      <td width="40" height="0"></td>
      <td width="70" height="0"></td>
      <td width="15" height="0"></td>
      <td width="120" height="0"></td>
      <td width="80" height="0"></td>
      <td width="15" height="0"></td>
      <td width="100" height="0"></td>
        <!-- Surgery --> 
    <?php if ($_SESSION['surg'] ==1 ) {
    echo
    '<td width="40" height="0"></td>
      <td width="70" height="0"></td>
      <td width="15" height="0"></td>
      <td width="120" height="0"></td>
      <td width="80" height="0"></td>
      <td width="15" height="0"></td>
      <td width="100" height="0"></td>' ;
      } 
       // <!-- Dentistry --> 
    if ($_SESSION['dent'] ==1 ) {
    echo
      '<td width="40" height="0"></td>
      <td width="70" height="0"></td>
      <td width="15" height="0"></td>
      <td width="120" height="0"></td>
      <td width="80" height="0"></td>
      <td width="15" height="0"></td>
      <td width="100" height="0"></td>' ;
      } 
        //<!-- LA 1  if needed -->
     if ($lactcol > 0) {
      echo
      '<td width="40" height="0"></td>
      <td width="70" height="0"></td>
      <td width="15" height="0"></td>
      <td width="120" height="0"></td>
      <td width="80" height="0"></td>
      <td width="15" height="0"></td>
      <td width="100" height="0"></td>' ;
      }
        //<!-- LA 2 if needed -->
      if ($lactcol > 1) {
      echo
       '<td width="40" height="0"></td>
      <td width="70" height="0"></td>
      <td width="15" height="0"></td>
      <td width="120" height="0"></td>
      <td width="80" height="0"></td>
      <td width="15" height="0"></td>
      <td width="100" height="0"></td> ' ; 
      } 
        //<!-- LA 3 if needed -->
      if ($lactcol > 2) {
      echo
       '<td width="40" height="0"></td>
      <td width="70" height="0"></td>
      <td width="15" height="0"></td>
      <td width="120" height="0"></td>
      <td width="80" height="0"></td>
      <td width="15" height="0"></td>
      <td width="100" height="0"></td> ' ; 
      } ?>
	</tr>  
<!-- ****************************************-->
	<?php
	
// Now each row gets filled out Each column has a unique id, as $ix gets bumped by one as we go to the right across the screen, then increased by 100 for the next row (so no more than 100 doctors....)
// Define the content variables first...
// The current apptnum for each doc has to be passed back to the Name, Patient and Problem painting routines to minimize vertical waste if the problem is long.  So the variable has 
// to be defined here, to make it available to the isitdoc function to pass back as a global variable.
$ak = 0 ;

for ($i = 0 ; $i < $numints; $i++) { 
 
  echo '<tr >  '; 
  $ix = $i*100 ;
  $ikx = $i*10000 ;
  // This is the first small animal column, 
  if ($actcol > 0) {
   $req = '' ;
   $name = '' ;
   $contact = '' ;
   $newcl = '' ;
   $petname = '' ;
   $newpet = '' ;
   $problem = '' ;
  $thisdoc = '' ;
  $gono = 0 ;
  $repeat = 0 ;
  $ak = 0 ;
  $red = 0 ;
  echo '<td height="10" class="Verdana10Grey" align="center">'.$dumtime[$i].'</td>' ;
  echo '<td id="'.$ix.'"'; if ($dummy[$i][1]) {$thisdoc = $dummy[$i][1]; $gono = isitdoc($dummy[$i][0]) ;  if ($gono == 1 && $dummy[$i][0] == $am[$ak][0]) { $repeat = 1 ;} $red = stripos($problem,'euth') ; if ($red !== FALSE) {$red = 1 ;}
  if ($gono == 0) {echo ' onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#58DA58\');" onmouseout="whiteoutline(this.id)"  onclick="window.open(\'MAKE_APP.php?date='. mktime(0,0,0,$month,$day,$year).'&time='.  $dummy[$i][0].'&doctor='. $dummy[$i][1].'\',\'_blank\')" ';} echo ' class="Verdana10Blue">&nbsp;'; echo $dummy[$i][1] ;} else {echo 'class="Verdana10Blue">&nbsp;';} echo '</td>' ;
  echo '<td class="Verdana9Red" >'; if ($gono == 1 && $req == 1){echo '&hearts;';} else {if ($gono == 1 && $newcl ==1){echo '+';}}
  echo '</td>
  <td class='; if ($gono == 1){echo '"Verdana10" align="left">'; } else {echo '"Verdana10GreyL" align="left">';} if ($gono == 1 ){if ($repeat == 1 ) {echo $name ;} else {echo '&nbsp;&nbsp;**' ;}} else if ($dummy[$i][1]) {echo 'Small';} echo ' </td>
  <td id="'.$ikx.'" class='; if ($gono == 1){echo '"Verdana10" align="left" onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#F3F781\');" onmouseout="whiteoutline(this.id)" onclick="window.open(\'EDIT_APP.php?apptnum='.$ak.'\',\'_self\')">'; if ($repeat == 1 ) {echo $petname .'</td>' ;} else {echo '&nbsp;&nbsp;**</td>' ;}} else if($dumeslot[$i][0]) {echo '"Verdana10Grey" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-slot</td>';} else {echo '"Verdana10">&nbsp;</td>"';}
  echo '<td class="Verdana10Green">';if ($gono == 1 && $newpet == 1){echo '+' ; } echo'</td>
  <td class='; if ($red == 1) {echo '"Verdana10Red">';}else {echo '"Verdana10">';} if ($gono == 1){if ($repeat == 1 ) {echo $problem ;} else {echo '&nbsp;&nbsp;**' ;}} echo '</td>'; 
  }
  // The second small animal column, IF IT IS NEEDED!
  if ($actcol > 1) {
  echo '<td height="10" class="Verdana10Grey" align="center">'.$dumtime[$i].'</td>' ;
  $ix++ ;
  $ikx++;
   $req = '' ;
   $name = '' ;
   $contact = '' ;
   $newcl = '' ;
   $petname = '' ;
   $newpet = '' ;
   $problem = '' ;
  $thisdoc = '' ;
  $gono = 0 ;
  $repeat = 0 ;
  $ak = 0 ;
  $red = 0 ;
  echo '<td  id="'.$ix.'"'; if ($dummy[$i][2]) {$thisdoc = $dummy[$i][2]; $gono = isitdoc($dummy[$i][0]) ; if ($gono == 1 && $dummy[$i][0] == $am[$ak][0]) { $repeat = 1 ;} $red = stripos($problem,'euth') ; if ($red !== FALSE) {$red = 1 ;}
  if ($gono == 0) {echo ' onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#58DA58\');" onmouseout="whiteoutline(this.id)"  onclick="window.open(\'MAKE_APP.php?date='. mktime(0,0,0,$month,$day,$year).'&time='.  $dummy[$i][0].'&doctor='. $dummy[$i][2].'\',\'_blank\')" ';} echo ' class="Verdana10Blue">&nbsp;'; echo $dummy[$i][2] ;} else {echo 'class="Verdana10Blue">&nbsp;';} echo '</td>' ;
  echo '<td class="Verdana9Red" >'; if ($gono == 1 && $req == 1){echo '&hearts;';} else {if ($gono == 1 && $newcl ==1){echo '+';}}
  echo '</td>
  <td class='; if ($gono == 1){echo '"Verdana10" align="left">'; } else {echo '"Verdana10GreyL" align="left">';} if ($gono == 1 ){if ($repeat == 1 ) {echo $name ;} else {echo '&nbsp;&nbsp;**' ;}} else if ($dummy[$i][2]) {echo 'Small';} echo ' </td>
  <td id="'.$ikx.'" class='; if ($gono == 1){echo '"Verdana10" align="left" onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#F3F781\');" onmouseout="whiteoutline(this.id)" onclick="window.open(\'EDIT_APP.php?apptnum='.$ak.'\',\'_self\')">'; if ($repeat == 1 ) {echo $petname .'</td>' ;} else {echo '&nbsp;&nbsp;**</td>' ;}} else if($dumeslot[$i][1]) {echo '"Verdana10Grey" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-slot</td>';} else {echo '"Verdana10">&nbsp;</td>"';}
  echo '<td class="Verdana10Green">';if ($gono == 1 && $newpet == 1){echo '+' ; } echo'</td>
  <td class='; if ($red == 1) {echo '"Verdana10Red">';}else {echo '"Verdana10">';} if ($gono == 1){if ($repeat == 1 ) {echo $problem ;} else {echo '&nbsp;&nbsp;**' ;}} echo '</td>'; 
  }  
  // The third small animal column, IF IT IS NEEDED
  if ($actcol > 2) {
  echo '<td height="10" class="Verdana10Grey" align="center">'.$dumtime[$i].'</td>' ;
  $ix++ ;
  $ikx++;
   $req = '' ;
   $name = '' ;
   $contact = '' ;
   $newcl = '' ;
   $petname = '' ;
   $newpet = '' ;
   $problem = '' ;
  $thisdoc = '' ;
  $gono = 0 ;
  $repeat = 0 ;
  $ak = 0 ;
  $red = 0 ;
  echo '<td  id="'.$ix.'"'; if ($dummy[$i][3]) {$thisdoc = $dummy[$i][3]; $gono = isitdoc($dummy[$i][0]) ; if ($gono == 1 && $dummy[$i][0] == $am[$ak][0]) { $repeat = 1 ;} $red = stripos($problem,'euth') ; if ($red !== FALSE) {$red = 1 ;}
  if ($gono == 0) {echo ' onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#58DA58\');" onmouseout="whiteoutline(this.id)"  onclick="window.open(\'MAKE_APP.php?date='. mktime(0,0,0,$month,$day,$year).'&time='.  $dummy[$i][0].'&doctor='. $dummy[$i][3].'\',\'_blank\')" ';} echo ' class="Verdana10Blue">&nbsp;'; echo $dummy[$i][3] ;} else {echo 'class="Verdana10Blue">&nbsp;';} echo '</td>' ;
  echo '<td class="Verdana9Red" >'; if ($gono == 1 && $req == 1){echo '&hearts;';} else {if ($gono == 1 && $newcl ==1){echo '+';}}
  echo '</td>
  <td class='; if ($gono == 1){echo '"Verdana10" align="left">'; } else {echo '"Verdana10GreyL" align="left">';} if ($gono == 1 ){if ($repeat == 1 ) {echo $name ;} else {echo '&nbsp;&nbsp;**' ;}} else if ($dummy[$i][3]) {echo 'Small';} echo ' </td>
  <td id="'.$ikx.'" class='; if ($gono == 1){echo '"Verdana10" align="left" onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#F3F781\');" onmouseout="whiteoutline(this.id)" onclick="window.open(\'EDIT_APP.php?apptnum='.$ak.'\',\'_self\')">'; if ($repeat == 1 ) {echo $petname .'</td>' ;} else {echo '&nbsp;&nbsp;**</td>' ;}} else if($dumeslot[$i][2]) {echo '"Verdana10Grey" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-slot</td>';} else {echo '"Verdana10">&nbsp;</td>"';}
  echo '<td class="Verdana10Green">';if ($gono == 1 && $newpet == 1){echo '+' ; } echo'</td>
  <td class='; if ($red == 1) {echo '"Verdana10Red">';}else {echo '"Verdana10">';} if ($gono == 1){if ($repeat == 1 ) {echo $problem ;} else {echo '&nbsp;&nbsp;**' ;}} echo '</td>'; 
  }
   
  // Next comes the Tech column. The doctor name is just "Technician".
   $req = '' ;
   $name = '' ;
   $contact = '' ;
   $newcl = '' ;
   $petname = '' ;
   $newpet = '' ;
   $problem = '' ;
  $thisdoc = '' ;
  $gono = 0 ;
  $repeat = 0 ;
  $ak = 0 ;
  $red = 0 ;
  echo '<td height="10" class="Verdana10Grey" align="center">'.$dumtime[$i].'</td>' ;
  $ix++ ;
  $ikx++;
   echo '<td  id="'.$ix.'"'; $thisdoc = 'Technician'; $gono = isitdoc($dummy[$i][0]) ; if ($gono == 1 && $dummy[$i][0] == $am[$ak][0]) { $repeat = 1 ;} $red = stripos($problem,'euth') ; if ($red !== FALSE) {$red = 1 ;}
   if ($gono == 0) {echo ' onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#58DA58\');" onmouseout="whiteoutline(this.id)"  onclick="window.open(\'MAKE_APP.php?date='. mktime(0,0,0,$month,$day,$year).'&time='.  $dummy[$i][0].'&doctor=Technician\',\'_blank\')" ';} echo ' class="Verdana10Blue">&nbsp;'; if ($i < $numints/2 -8) {echo 'Tech';} else {echo 'Tech' ;} echo '</td>' ;
  echo '<td class="Verdana9Red" >'; if ($gono == 1 && $req == 1){echo '&hearts;';} else {if ($gono == 1 && $newcl ==1){echo '+';}}
  echo '</td>
  <td class='; if ($gono == 1){echo '"Verdana10" align="left">'; } else {echo '"Verdana10GreyL" align="left">';} if ($gono == 1 ){if ($repeat == 1 ) {echo $name ;} else {echo '&nbsp;&nbsp;**' ;}} else if ($dummy[$i][4]) {echo 'Small';} echo ' </td>
  <td id="'.$ikx.'" class='; if ($gono == 1){echo '"Verdana10" align="left" onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#F3F781\');" onmouseout="whiteoutline(this.id)" onclick="window.open(\'EDIT_APP.php?apptnum='.$ak.'\',\'_self\')">'; if ($repeat == 1 ) {echo $petname .'</td>' ;} else {echo '&nbsp;&nbsp;**</td>' ;}} else if($dumeslot[$i][5]) {echo '"Verdana10Grey" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-slot</td>';} else {echo '"Verdana10">&nbsp;</td>"';}
  echo '<td class="Verdana10Green">';if ($gono == 1 && $newpet == 1){echo '+' ; } echo'</td>
  <td class='; if ($red == 1) {echo '"Verdana10Red">';}else {echo '"Verdana10">';} if ($gono == 1){if ($repeat == 1 ) {echo $problem ;} else {echo '&nbsp;&nbsp;**' ;}} echo '</td>'; 
  
// Now the surgery column
if ($_SESSION['surg'] == 1) {
   $req = '' ;
   $name = '' ;
   $contact = '' ;
   $newcl = '' ;
   $petname = '' ;
   $newpet = '' ;
   $problem = '' ;
  $thisdoc = '' ;
  $gono = 0 ;
  $repeat = 0 ;
  $ak = 0 ;
  $red = 0 ;
echo '<td height="10" class="Verdana10Grey" align="center">'.$dumtime[$i].'</td>' ;
  $ix++ ;
  $ikx++;
  echo '<td  id="'.$ix.'"'; if ($dummy[$i][6]) {$thisdoc = $dummy[$i][6]; $gono = isitdoc($dummy[$i][0]) ;  if ($gono == 1 && $dummy[$i][0] == $am[$ak][0]) { $repeat = 1 ;} $red = stripos($problem,'euth') ; if ($red !== FALSE) {$red = 1 ;}
  if ($gono == 0) {echo ' onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#58DA58\');" onmouseout="whiteoutline(this.id)"  onclick="window.open(\'MAKE_APP.php?date='. mktime(0,0,0,$month,$day,$year).'&time='.  $dummy[$i][0].'&doctor='. $dummy[$i][6].'\',\'_blank\')" ';} echo ' class="Verdana10Blue">&nbsp;'; echo $dummy[$i][6] ;} else {echo 'class="Verdana10Blue">&nbsp;';} echo '</td>' ;
  echo '<td class="Verdana9Red" >'; if ($gono == 1 && $req == 1){echo '&hearts;';} else {if ($gono == 1 && $newcl ==1){echo '+';}}
  echo '</td>
  <td class='; if ($gono == 1){echo '"Verdana10" align="left">'; } else {echo '"Verdana10GreyL" align="left">';} if ($gono == 1 ){if ($repeat == 1 ) {echo $name ;} else {echo '&nbsp;&nbsp;**' ;}} else if ($dummy[$i][6]) {echo 'Surgery';} echo ' </td>
  <td id="'.$ikx.'" class='; if ($gono == 1){echo '"Verdana10" align="left" onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#F3F781\');" onmouseout="whiteoutline(this.id)" onclick="window.open(\'EDIT_APP.php?apptnum='.$ak.'\',\'_self\')">'; if ($repeat == 1 ) {echo $petname .'</td>' ;} else {echo '&nbsp;&nbsp;**</td>' ;}} else if($dumeslot[$i][6]) {echo '"Verdana10Grey" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-slot</td>';} else {echo '"Verdana10">&nbsp;</td>"';}
  echo '<td class="Verdana10Green">';if ($gono == 1 && $newpet == 1){echo '+' ; } echo'</td>
  <td class='; if ($red == 1) {echo '"Verdana10Red">';}else {echo '"Verdana10">';} if ($gono == 1){if ($repeat == 1 ) {echo $problem ;} else {echo '&nbsp;&nbsp;**' ;}} echo '</td>'; 
  }
   
//<!--  Dentistry Column  --> 
if ($_SESSION['dent'] == 1) {
   $req = '' ;
   $name = '' ;
   $contact = '' ;
   $newcl = '' ;
   $petname = '' ;
   $newpet = '' ;
   $problem = '' ;
  $thisdoc = '' ;
  $gono = 0 ;
  $repeat = 0 ;
  $ak = 0 ;
  $red = 0 ;
  echo '<td height="10" class="Verdana10Grey" align="center">'.$dumtime[$i].'</td>' ;
  $ix++ ;
  $ikx++;
  echo '<td  id="'.$ix.'"'; if ($dummy[$i][7]) {$thisdoc = $dummy[$i][7]; $gono = isitdoc($dummy[$i][0]) ;   if ($gono == 1 && $dummy[$i][0] == $am[$ak][0]) { $repeat = 1 ;} $red = stripos($problem,'euth') ; if ($red !== FALSE) {$red = 1 ;}
  if ($gono == 0) {echo ' onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#58DA58\');" onmouseout="whiteoutline(this.id)"  onclick="window.open(\'MAKE_APP.php?date='. mktime(0,0,0,$month,$day,$year).'&time='.  $dummy[$i][0].'&doctor='. $dummy[$i][7].'\',\'_blank\')" ';} echo ' class="Verdana10Blue">&nbsp;'; echo $dummy[$i][7] ;} else {echo 'class="Verdana10Blue">&nbsp;';} echo '</td>' ;
  echo '<td class="Verdana9Red" >'; if ($gono == 1 && $req == 1){echo '&hearts;';} else {if ($gono == 1 && $newcl ==1){echo '+';}}
  echo '</td>
  <td class='; if ($gono == 1){echo '"Verdana10" align="left">'; } else {echo '"Verdana10GreyL" align="left">';} if ($gono == 1 ){if ($repeat == 1 ) {echo $name ;} else {echo '&nbsp;&nbsp;**' ;}} else if ($dummy[$i][7]) {echo 'Dentistry';} echo ' </td>
  <td id="'.$ikx.'" class='; if ($gono == 1){echo '"Verdana10" align="left" onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#F3F781\');" onmouseout="whiteoutline(this.id)" onclick="window.open(\'EDIT_APP.php?apptnum='.$ak.'\',\'_self\')">'; if ($repeat == 1 ) {echo $petname .'</td>' ;} else {echo '&nbsp;&nbsp;**</td>' ;}} else if($dumeslot[$i][7]) {echo '"Verdana10Grey" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-slot</td>';} else {echo '"Verdana10">&nbsp;</td>"';}
  echo '<td class="Verdana10Green">';if ($gono == 1 && $newpet == 1){echo '+' ; } echo'</td>
  <td class='; if ($red == 1) {echo '"Verdana10Red">';}else {echo '"Verdana10">';} if ($gono == 1){if ($repeat == 1 ) {echo $problem ;} else {echo '&nbsp;&nbsp;**' ;}} echo '</td>'; 
  
} 
// First Large Animal Column
echo '<td height="10" class="Verdana10Grey" align="center">'.$dumtime[$i].'</td>' ;
  $ix++ ;
  $ikx++;
   $req = '' ;
   $name = '' ;
   $contact = '' ;
   $newcl = '' ;
   $petname = '' ;
   $newpet = '' ;
   $problem = '' ; 
  $thisdoc = '' ;
  $gono = 0 ;
  $repeat = 0 ;
  $ak = 0 ;
  $red = 0 ;
  echo '<td  id="'.$ix.'"'; if ($dummy[$i][8]) {$thisdoc = $dummy[$i][8]; $gono = isitdoc($dummy[$i][0]) ;    if ($gono == 1 && $dummy[$i][0] == $am[$ak][0]) { $repeat = 1 ;} $red = stripos($problem,'euth') ; if ($red !== FALSE) {$red = 1 ;}
  if ($gono == 0) {echo ' onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#58DA58\');" onmouseout="whiteoutline(this.id)"  onclick="window.open(\'MAKE_APP.php?date='. mktime(0,0,0,$month,$day,$year).'&time='.  $dummy[$i][0].'&doctor='. $dummy[$i][8].'\',\'_blank\')" ';} echo ' class="Verdana10Blue">&nbsp;'; echo $dummy[$i][8] ;} else {echo 'class="Verdana10Blue">&nbsp;';} echo '</td>' ;
  echo '<td class="Verdana9Red" >'; if ($gono == 1 && $req == 1){echo '&hearts;';} else {if ($gono == 1 && $newcl ==1){echo '+';}}
  echo '</td>
  <td class='; if ($gono == 1){echo '"Verdana10" align="left">'; } else {echo '"Verdana10GreyL" align="left">';} if ($gono == 1 ){if ($repeat == 1 ) {echo $name ;} else {echo '&nbsp;&nbsp;**' ;}} else if ($dummy[$i][8]) {echo 'Large';} echo ' </td>
  <td id="'.$ikx.'" class='; if ($gono == 1){echo '"Verdana10" align="left" onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#F3F781\');" onmouseout="whiteoutline(this.id)" onclick="window.open(\'EDIT_APP.php?apptnum='.$ak.'\',\'_self\')">'; if ($repeat == 1 ) {echo $petname .'</td>' ;} else {echo '&nbsp;&nbsp;**</td>' ;}} else if($dumeslot[$i][8]) {echo '"Verdana10Grey" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-slot</td>';} else {echo '"Verdana10">&nbsp;</td>"';}
  echo '<td class="Verdana10Green">';if ($gono == 1 && $newpet == 1){echo '+' ; } echo'</td>
  <td class='; if ($red == 1) {echo '"Verdana10Red">';}else {echo '"Verdana10">';} if ($gono == 1){if ($repeat == 1 ) {echo $problem ;} else {echo '&nbsp;&nbsp;**' ;}} echo '</td>'; 
  
   if ($lactcol > 1) {
// Second Large Animal Column
echo '<td height="10" class="Verdana10Grey" align="center">'.$dumtime[$i].'</td>' ;
  $ix++ ;
  $ikx++;

   $req = '' ;
   $name = '' ;
   $contact = '' ;
   $newcl = '' ;
   $petname = '' ;
   $newpet = '' ;
   $problem = '' ; 
  $thisdoc = '' ;
  $gono = 0 ;
  $repeat = 0 ;
  $ak = 0 ;
  $red = 0 ;

  echo '<td  id="'.$ix.'"'; if ($dummy[$i][9]) {$thisdoc = $dummy[$i][9]; $gono = isitdoc($dummy[$i][0]) ;    if ($gono == 1 && $dummy[$i][0] == $am[$ak][0]) { $repeat = 1 ;} $red = stripos($problem,'euth') ; if ($red !== FALSE) {$red = 1 ;}
  if ($gono == 0) {echo ' onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#58DA58\');" onmouseout="whiteoutline(this.id)"  onclick="window.open(\'MAKE_APP.php?date='. mktime(0,0,0,$month,$day,$year).'&time='.  $dummy[$i][0].'&doctor='. $dummy[$i][9].'\',\'_blank\')" ';} echo ' class="Verdana10Blue">&nbsp;'; echo $dummy[$i][9] ;} else {echo 'class="Verdana10Blue">&nbsp;';} echo '</td>' ;
  echo '<td class="Verdana9Red" >'; if ($gono == 1 && $req == 1){echo '&hearts;';} else {if ($gono == 1 && $newcl ==1){echo '+';}}
  echo '</td>
  <td class='; if ($gono == 1){echo '"Verdana10" align="left">'; } else {echo '"Verdana10GreyL" align="left">';} if ($gono == 1 ){if ($repeat == 1 ) {echo $name ;} else {echo '&nbsp;&nbsp;**' ;}} else if ($dummy[$i][9]) {echo 'Large';} echo ' </td>
  <td id="'.$ikx.'" class='; if ($gono == 1){echo '"Verdana10" align="left" onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#F3F781\');" onmouseout="whiteoutline(this.id)" onclick="window.open(\'EDIT_APP.php?apptnum='.$ak.'\',\'_self\')">'; if ($repeat == 1 ) {echo $petname .'</td>' ;} else {echo '&nbsp;&nbsp;**</td>' ;}} else if($dumeslot[$i][9]) {echo '"Verdana10Grey" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-slot</td>';} else {echo '"Verdana10">&nbsp;</td>"';}
  echo '<td class="Verdana10Green">';if ($gono == 1 && $newpet == 1){echo '+' ; } echo'</td>
  <td class='; if ($red == 1) {echo '"Verdana10Red">';}else {echo '"Verdana10">';} if ($gono == 1){if ($repeat == 1 ) {echo $problem ;} else {echo '&nbsp;&nbsp;**' ;}} echo '</td>'; 

   }  
    if ($lactcol > 2) {
// Third Large Animal Column
echo '<td height="10" class="Verdana10Grey" align="center">'.$dumtime[$i].'</td>' ;
  $ix++ ;
  $ikx++;
   $req = '' ;
   $name = '' ;
   $contact = '' ;
   $newcl = '' ;
   $petname = '' ;
   $newpet = '' ;
   $problem = '' ; 
  $thisdoc = '' ;
  $gono = 0 ;
  $repeat = 0 ;
  $ak = 0 ;
  $red = 0 ;
  echo '<td  id="'.$ix.'"'; if ($dummy[$i][10]) {$thisdoc = $dummy[$i][10]; $gono = isitdoc($dummy[$i][0]) ;    if ($gono == 1 && $dummy[$i][0] == $am[$ak][0]) { $repeat = 1 ;} $red = stripos($problem,'euth') ; if ($red !== FALSE) {$red = 1 ;}
  if ($gono == 0) {echo ' onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#58DA58\');" onmouseout="whiteoutline(this.id)"  onclick="window.open(\'MAKE_APP.php?date='. mktime(0,0,0,$month,$day,$year).'&time='.  $dummy[$i][0].'&doctor='. $dummy[$i][10].'\',\'_blank\')" ';} echo ' class="Verdana10Blue">&nbsp;'; echo $dummy[$i][10] ;} else {echo 'class="Verdana10Blue">&nbsp;';} echo '</td>' ;
   echo '<td class="Verdana9Red" >'; if ($gono == 1 && $req == 1){echo '&hearts;';} else {if ($gono == 1 && $newcl ==1){echo '+';}}
  echo '</td>
  <td class='; if ($gono == 1){echo '"Verdana10" align="left">'; } else {echo '"Verdana10GreyL" align="left">';} if ($gono == 1 ){if ($repeat == 1 ) {echo $name ;} else {echo '&nbsp;&nbsp;**' ;}} else if ($dummy[$i][10]) {echo 'Large';} echo ' </td>
  <td id="'.$ikx.'" class='; if ($gono == 1){echo '"Verdana10" align="left" onmouseover="CursorToPointer(this.id); highliteline(this.id, \'#F3F781\');" onmouseout="whiteoutline(this.id)" onclick="window.open(\'EDIT_APP.php?apptnum='.$ak.'\',\'_self\')">'; if ($repeat == 1 ) {echo $petname .'</td>' ;} else {echo '&nbsp;&nbsp;**</td>' ;}} else if($dumeslot[$i][10]) {echo '"Verdana10Grey" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-slot</td>';} else {echo '"Verdana10">&nbsp;</td>"';}
  echo '<td class="Verdana10Green">';if ($gono == 1 && $newpet == 1){echo '+' ; } echo'</td>
  <td class='; if ($red == 1) {echo '"Verdana10Red">';}else {echo '"Verdana10">';} if ($gono == 1){if ($repeat == 1 ) {echo $problem ;} else {echo '&nbsp;&nbsp;**' ;}} echo '</td>'; 
   }  ?>
</tr> 

<?php
	}
	?>

<tr>  
<!--      <td colspan="56"> -->
      <td colspan="35">
<table border="1" cellspacing="0" cellpadding="0" bordercolor="#00FF00">
</table>	</td>
  </tr>  
    <tr class="ButtonsTable">  
        <td colspan='<?php echo 14*($actcol + $lactcol + $_SESSION['dent'] + $_SESSION['surg']) ; ?>' align="left">
          <input type="button" class="button" name="button" id="button" value="BACK"  onclick="document.location='DAY.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&day=<?php echo $day ;?>&inc=-1'"/>
          <input type="button" class="button" name="button" id="button" value="NEXT"  onclick="document.location='DAY.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&day=<?php echo $day ;?>&inc=1'"/>
          <input type="button" class="button" name="button" id="button" value="MONTH" onclick="document.location='MONTH.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>'"/>
          <input type="button" class="button" name="button" id="button" value="DAY SHEET" onclick="document.location='DAY_PRINT.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&day=<?php echo $day ;?>'"/>
          <input type="button" class="button" name="button" id="button" value="ADMIN" onclick="document.location='SWAP_A_DOC.php?year=<?php echo $year; ?>&month=<?php echo $month; ?>&day=<?php echo $day ;?>'"/>
          <input type="button" class="button" name="button" id="button" value="CANCEL" onclick="self.close()" />       </td>
    </tr>  
</table>


<input type="hidden" value="0" name="coffee" id="coffee"/>
</form>
</div>
</body>
</html>
