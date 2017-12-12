<?php
session_start();

require_once('../tryconnection.php');
mysql_select_db($database_tryconnection, $tryconnection);

// On the way out of the detailed doctor's hours page..

// First, assemble the Duty field.
// The room and the raw flag data have to be combined for regular appointments, Hospital and Large. 
// Surgery and Dentistry have their own columns, by definition Emergencies are not booked .


   $x1 = 0 ;
   if ($_POST['A1'] == 1) {$x1 = $_POST['room1'] ;}
   $x2 = 0 ;
   if ($_POST['S1'] == 1) {$x2 = 1 ;}
   $x3 = 0 ;
   if ($_POST['A2'] == 1) {$x3 = $_POST['room2'] ;}
   $x4 = 0 ;
   if ($_POST['S2'] == 1) {$x4 = 1 ;}
   $x5 = 0 ;
   if ($_POST['E1'] == 1) {$x5 = 1 ;}
   $x6 = 0 ;
   if ($_POST['L1'] == 1) {$x6 = $_POST['room1'] ;}
   $x7 = 0 ;
   if ($_POST['L2'] == 1) {$x7 = $_POST['room2'] ;}
   $x8 = 0 ;
   if ($_POST['A3'] == 1) {$x8 = $_POST['room3'] ;}
   $x9 = 0 ;
   if ($_POST['L3'] == 1) {$x9 = $_POST['room3'] ;}
   $x10 = 0 ;
   if ($_POST['E2'] == 1) {$x10 = 1 ;}
   $x11 = 0 ;
   if ($_POST['H1'] == 1) {$x11 = $_POST['room1'] ;}
   $x12 = 0 ;
   if ($_POST['H2'] == 1) {$x12 = $_POST['room2'] ;}
   $x13 = 0 ;
   if ($_POST['D1'] == 1) {$x13 = 1 ;}
   $x14 = 0 ;
   if ($_POST['D2'] == 1) {$x14 = 1 ;}
   
 $duty = $x1.$x2.$x3.$x4.$x5.$x6.$x7.$x8.$x9.$x10.$x11.$x12.$x13.$x14 ;
 

// Next, put up the starting and ending dates, and combine the opening and closing hours 
// and minutes to a single field for each variable. 

// set up a new array for the new record. Keep the old one around.


$weekday2 = array() ;
$weekday2[0] = $_POST['newstart'] ;
$weekday2[1] = $_POST['newend'] ;
$weekday2[2] = $_POST['open1h'] . ':' . $_POST['open1m'] ;
$weekday2[3] = $_POST['close1h'] . ':' . $_POST['close1m'] ;
$weekday2[4] = $_POST['open2h'] . ':' . $_POST['open2m'] ;
$weekday2[5] = $_POST['close2h'] . ':' . $_POST['close2m'] ;
$weekday2[6] = $_POST['open3h'] . ':' . $_POST['open3m'] ;
$weekday2[7] = $_POST['close3h'] . ':' . $_POST['close3m'] ;
$weekday2[8] = $duty ;

$eslots2 = array();
$eslots2[0] = $_POST['opene1h'] . ':' . $_POST['opene1m'] ;
$eslots2[1] = $_POST['closee1h'] . ':' . $_POST['closee1m'] ;
$eslots2[2] = $_POST['opene2h'] . ':' . $_POST['opene2m'] ; 
$eslots2[3] = $_POST['closee2h'] . ':' . $_POST['closee2m'] ;
$eslots2[4] = $_POST['opene3h'] . ':' . $_POST['opene3m'] ; 
$eslots2[5] = $_POST['closee3h'] . ':' . $_POST['closee3m'] ; 
$eslots2[6] = $_POST['opene4h'] . ':' . $_POST['opene4m'] ;
$eslots2[7] = $_POST['closee4h'] . ':' . $_POST['closee4m'] ; 
$eslots2[8] = $_POST['opene5h'] . ':' . $_POST['opene5m'] ;
$eslots2[9] = $_POST['closee5h'] . ':' . $_POST['closee5m'] ; 
$eslots2[10] = $_POST['opene6h'] . ':' . $_POST['opene6m'] ;
$eslots2[11] = $_POST['closee6h'] . ':' . $_POST['closee6m'] ; 
      
$weekday = $_SESSION['weekday'] ;
      
      // and turn the date ranges back into date fields

        if (!empty($_POST['newstart'])){
           $startdate=$_POST['newstart'];
        }
        else {
           $startdate=date('%m/%d/%Y');
        }
        
        $startdate1="SELECT STR_TO_DATE('$startdate','%m/%d/%Y') AS START";
        $startdate2=mysql_query($startdate1, $tryconnection) or die(mysql_error());
        $get_startdate=mysql_fetch_assoc($startdate2);
        $startdate = $get_startdate['START'] ;

        
        if (!empty($_POST['newend'])){
           $enddate=$_POST['newend'];
        }
        else {
           $enddate=date('%m/%d/%Y');
        }

$enddate1="SELECT STR_TO_DATE('$enddate','%m/%d/%Y') AS END";
$enddate2=mysql_query($enddate1, $tryconnection) or die(mysql_error());

$get_enddate=mysql_fetch_assoc($enddate2);
$enddate = $get_enddate['END'] ;

  
$begincf = $_SESSION['begincf'] ;
$endcf = $_SESSION['endcf'] ;

//echo ' Coming out endcf is '.$endcf ;
 
$begin_cvt = "SELECT STR_TO_DATE('$begincf','%m/%d/%Y') AS BEGINCF" ;
$begin_cvt2 = mysql_query($begin_cvt, $tryconnection) or die(mysql_error());
$get_begcv = mysql_fetch_assoc($begin_cvt2);
$begincf = $get_begcv['BEGINCF'] ; 

$end_cvt = "SELECT STR_TO_DATE('$endcf','%m/%d/%Y') AS ENDCF" ;
$end_cvt2 = mysql_query($end_cvt, $tryconnection) or die(mysql_error());
$get_endcv = mysql_fetch_assoc($end_cvt2);
$endcf = $get_endcv['ENDCF'] ;
//echo ' after conversion, ' . $endcf ;

 $found = $_SESSION['found'] ;
 $day = $_SESSION['day'] ;
 $doctor = $_SESSION['doctor'] ;
 $shortdoc = $_SESSION['shortdoc'] ;
 $initials = $_SESSION['initials'] ;
 $sequence = $_SESSION['sequence'] ;
 $hrsid = $_SESSION['hrsid'] ;

/*
 echo ' The sequence is ' . $sequence . ' and found was ' . $found ;
 echo ' The required day is ' . $day ;
 echo ' The starting date is ' . $startdate ;
 echo ' Compared to ' . $begincf ;
 echo ' Ending date is ' . $enddate ;
 echo ' Compared to ' . $endcf ;
 */
 // And finally, pump out the new records.
 /*
 THE LOGIC IS AS FOLLOWS:
  1) If there are no old records
     a) Simply insert the new record.
       (Possibly the first entry in the table for that day and that doctor.)

  2) If the New Start date > Existing End date
     a) Simply insert the new record. 
       (The schedule has been extended.)

  3) If the new Start date > Existing Start date and New End date < Existing Start date.
     a) Replicate the portion of the existing up to but not including the New Start date.
     b) Insert the new record.
     c) Create a short new record whose Start date is New End date + 1, End date is the Old End date. 
        (The schedule has had a slice added into it before it had truly ended.

  4) if the new Start Date > Existing Start and < Existing End Date, and the new End date > Exising End.
     a) Replicate the portion of the Existing Record to end with the new Start date -1.
     b) Insert the new record.
        (The tail end of the existing record has been shortened, and a replacement provided that goes beyond the original end.

  5) If the Start date <= Existing Start date and New End Date >= Existing End date.
     a) Delete all records whose starting date is greater than or equal to the New Start date.
     b) Insert the new record.
        (The old record(s) has/have been replaced by a new, possibly longer time period)

  6) If the Start date <= Existing Start date and End Date < Existing End date.
     a) Insert the new record.
     b) Update the existing with a new Start date which is the New End date + 1 
        (The old record has lost the first part of its tenure, but retains the remaining tail.)
        
  7) If the start dates and the end dates are the same as they were before.
     a) Update the old record with the new duty field and e-slot info
        (The duty assignment and e-slot data (may have)/(has) been changed) 

 $row_hours contains the last available record.
 
*/
        if ($found == 0  || ($found == 1 && $startdate > $endcf)) { // Cases 1 & 2
        
  // First set up the master record, then generate the detailed daily records
        
           $insert_new = "INSERT INTO HRSDOC (DOCTOR, SHORTDOC, INITIALS, DAYINWEEK, STARTDT, ENDDT, SEQUENCE, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                VALUES ('".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$day', '$startdate', '$enddate', '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]' )" ;
           $write_it = mysql_query($insert_new, $tryconnection) or die(mysql_error()) ;

           // and generate the daily records, AFTER deleting any which were there before.
           
                     $target = $startdate ;
           
// make sure they have chosen a starting date which is in fact the day of the week that this is all about. 
// Allow for Sundays, as MySQL returns 1, we want 7. 

             $convert = array("6","7","1","2","3","4","5","6","7") ;
           
          while ($target <= $enddate)  { 


// First, the day of the week check

               $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               $get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               $row_dow = mysql_fetch_assoc($get_it) ;
               $dayofweek = $row_dow['DOW'] ;  
 
 
// if not the actual starting day, then add a day to that date
// until we come back around the calendar to the correct day of the week on which to start

                        while ((round($day,0))  <> round($convert[$dayofweek[0]],0) ) {

	// try good 'ol MySQL
                                $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 DAY) AS NEXTDAY " ;
                                $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
                                $row_Date = mysql_fetch_assoc($get_it) ;
                                $target = $row_Date['NEXTDAY'] ;
                                
                    
                                $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               					$get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               					$row_dow = mysql_fetch_assoc($get_it) ;
               					$dayofweek = $row_dow['DOW'] ;
               					
                       } //while ((round($day,0))  <> round($convert[$dayofweek],00 ) )

           
           $delete_old = "DELETE FROM APPTDOCS WHERE DOCTOR = '$doctor' AND DATEIS = '$target' " ;
           $trash_it = mysql_query($delete_old, $tryconnection) or die(mysql_error()) ;
           
           $insert_details = "INSERT INTO APPTDOCS (DATEIS, DOCTOR, SHORTDOC, INITIALS, SEQ, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                 VALUES ('$target', '".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]'  )" ; 
           $add_it = mysql_query($insert_details, $tryconnection) or die(mysql_error())  ;
         
           // Add a week, and try again.
               $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 WEEK) AS NEXTWEEK " ;
               $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
               $_rowDate = mysql_fetch_assoc($get_it) ;
               $target = $_rowDate['NEXTWEEK'] ;
               
           }  // while ($target <= $enddate) ;
       } // if ($found = 0  || $startdate > $endcf)  // Cases 1 & 2
       
//////////////////////////////////////// CASE 3 ///////////////////////////////////////// 
      
       else if ($startdate > $begincf && $enddate < $endcf) {  //Case 3
       
       // The existing record is split into two, with the new slice between the old two.
       // The new ending date on the existing record will be one day before new start date.
       
         $new_end = "SELECT DATE_SUB('$startdate', INTERVAL 1 DAY) AS NEWEND " ;
         $get_it = mysql_query($new_end, $tryconnection) or die(mysql_error()) ;
         $row_newend = mysql_fetch_assoc($get_it) ;
         $new_ending = $row_newend['NEWEND'] ;
       
       // The existing record is changed to run from the old start date to the new begin date minus one day.
       
         $update_firstpart = "UPDATE HRSDOC SET ENDDT = '$new_ending' WHERE HRSID = '$_SESSION[hrsid]'" ;
         $write_it = mysql_query($update_firstpart, $tryconnection) or die(mysql_error()) ;
       
       // The new slice inserted into the range is from the new start date to the new end date 
          $insert_new = "INSERT INTO HRSDOC (DOCTOR, SHORTDOC, INITIALS, DAYINWEEK, STARTDT, ENDDT, SEQUENCE, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                VALUES ('".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$day', '$startdate', '$enddate', '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]' )" ;
          $write_it = mysql_query($insert_new, $tryconnection) or die(mysql_error()) ;
          
        // and generate the daily records, AFTER deleting any which were there before.
        
                     $target = $startdate ;
           
// make sure they have chosen a starting date which is in fact the day of the week that this is all about. 
// Allow for Sundays, as MySQL returns 1, we want 7. 

             $convert = array("6","7","1","2","3","4","5","6","7") ;
           
          while ($target <= $enddate)  { 
           
// First, the day of the week check

               $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               $get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               $row_dow = mysql_fetch_assoc($get_it) ;
               $dayofweek = $row_dow['DOW'] ;  
               
 
// if not the actual starting day, then add a day to that date
// until we come back around the calendar to the correct day of the week on which to start

                        while ((round($day,0))  <> round($convert[$dayofweek[0]],0) ) {

	// try good 'ol MySQL
                                $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 DAY) AS NEXTDAY " ;
                                $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
                                $row_Date = mysql_fetch_assoc($get_it) ;
                                $target = $row_Date['NEXTDAY'] ;
                                
                    
                                $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               					$get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               					$row_dow = mysql_fetch_assoc($get_it) ;
               					$dayofweek = $row_dow['DOW'] ;
               					
                       } //while ((round($day,0))  <> round($convert[$dayofweek],00 ) )

           
           $delete_old = "DELETE FROM APPTDOCS WHERE DOCTOR = '$doctor' AND DATEIS = '$target' " ;
           $trash_it = mysql_query($delete_old, $tryconnection) or die(mysql_error()) ;
           
           $insert_details = "INSERT INTO APPTDOCS (DATEIS, DOCTOR, SHORTDOC, INITIALS, SEQ, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                 VALUES ('$target', '".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]'  )" ;
           $add_it = mysql_query($insert_details, $tryconnection) or die(mysql_error())  ;
         
           // Add a week, and try again.
               $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 WEEK) AS NEXTWEEK " ;
               $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
               $_rowDate = mysql_fetch_assoc($get_it) ;
               $target = $_rowDate['NEXTWEEK'] ;
               
           }  // while ($target <= $enddate) ;
       
       // Finally, the new start date on the replicated stub will be one week after the new end date. 
       
         $new_start = "SELECT DATE_ADD('$enddate', INTERVAL 1 WEEK) AS NEWSTART " ;
         $get_it = mysql_query($new_start, $tryconnection) or die(mysql_error()) ;
         $row_newstart = mysql_fetch_assoc($get_it) ;
         $new_starting = $row_newstart['NEWSTART'] ;
       
         $insert_new = "INSERT INTO HRSDOC (DOCTOR, SHORTDOC, INITIALS, DAYINWEEK, STARTDT, ENDDT, SEQUENCE, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY)
                VALUES ('".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$day', '$new_starting', '$endcf', '$sequence', '$weekday[2]', '$weekday[3]', '$weekday[4]', '$weekday[5]', '$weekday[6]', '$weekday[7]', '$weekday[8]' )" ;
         $write_it = mysql_query($insert_new, $tryconnection) or die(mysql_error()) ;
       } // ($startdate > $beginccf && enddate < $endcf) Case 3
       
       ///////////////////////// Case 4 //////////////////////////////////////////
       
       else if ($startdate > $begincf && $startdate < $endcf && $enddate > $endcf) { // Case 4
       
       // The existing record is truncated, and a new ending stub is generated
       
       echo ' Case 4.. ' ;  
       
       $new_end = "SELECT DATE_SUB('$startdate', INTERVAL 1 DAY) AS NEWEND " ;
         $get_it = mysql_query($new_end, $tryconnection) or die(mysql_error()) ;
         $row_newend = mysql_fetch_assoc($get_it) ;
         $new_ending = $row_newend['NEWEND'] ;
       
       // The existing record is changed to run from the old start date to the new begin date minus one day.
       
         $update_firstpart = "UPDATE HRSDOC SET ENDDT = '$new_ending' WHERE HRSID = '$_SESSION[hrsid]'" ;
         $write_it = mysql_query($update_firstpart, $tryconnection) or die(mysql_error()) ;
       
       // The new slice inserted into the range is from the new start date to the new end date 
        $insert_new = "INSERT INTO HRSDOC (DOCTOR, SHORTDOC, INITIALS, DAYINWEEK, STARTDT, ENDDT, SEQUENCE, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                VALUES ('".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$day', '$startdate', '$enddate', '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]' )" ;
          $write_it = mysql_query($insert_new, $tryconnection) or die(mysql_error()) ;
                  // and generate the daily records, AFTER deleting any which were there before.
        
                     $target = $startdate ;
           
// make sure they have chosen a starting date which is in fact the day of the week that this is all about. 
// Allow for Sundays, as MySQL returns 1, we want 7. 

             $convert = array("6","7","1","2","3","4","5","6","7") ;
           
          while ($target <= $enddate)  { 
           
// First, the day of the week check

               $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               $get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               $row_dow = mysql_fetch_assoc($get_it) ;
               $dayofweek = $row_dow['DOW'] ;  
               
 
// if not the actual starting day, then add a day to that date
// until we come back around the calendar to the correct day of the week on which to start

                        while ((round($day,0))  <> round($convert[$dayofweek[0]],0) ) {

	// try good 'ol MySQL
                                $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 DAY) AS NEXTDAY " ;
                                $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
                                $row_Date = mysql_fetch_assoc($get_it) ;
                                $target = $row_Date['NEXTDAY'] ;
                                
                    
                                $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               					$get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               					$row_dow = mysql_fetch_assoc($get_it) ;
               					$dayofweek = $row_dow['DOW'] ;
               					
                       } //while ((round($day,0))  <> round($convert[$dayofweek],00 ) )

           
           $delete_old = "DELETE FROM APPTDOCS WHERE DOCTOR = '$doctor' AND DATEIS = '$target' " ;
           $trash_it = mysql_query($delete_old, $tryconnection) or die(mysql_error()) ;
           
           $insert_details = "INSERT INTO APPTDOCS (DATEIS, DOCTOR, SHORTDOC, INITIALS, SEQ, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                 VALUES ('$target', '".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]'  )" ;
           $add_it = mysql_query($insert_details, $tryconnection) or die(mysql_error())  ;
         
           // Add a week, and try again.
               $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 WEEK) AS NEXTWEEK " ;
               $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
               $_rowDate = mysql_fetch_assoc($get_it) ;
               $target = $_rowDate['NEXTWEEK'] ;
               
           }  // while ($target <= $enddate) ;
       
       } // $startdate > $begincf && $startdate < $endcf && $enddate > $endcf  Case 4
       
       //////////////////////// Case 5 ///////////////////////////////////
       
       else if ($startdate <= $begincf && $enddate >= $endcf) {  // Case 5
       
       // The new record inserted into the range is from the new start date to the new end date 
       
           $insert_new = "INSERT INTO HRSDOC (DOCTOR, SHORTDOC, INITIALS, DAYINWEEK, STARTDT, ENDDT, SEQUENCE, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                VALUES ('".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$day', '$startdate', '$enddate', '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]' )" ;
          $write_it = mysql_query($insert_new, $tryconnection) or die(mysql_error()) ;
            
          $target = $startdate ;
           
// make sure they have chosen a starting date which is in fact the day of the week that this is all about. 
// Allow for Sundays, as MySQL returns 1, we want 7. 

             $convert = array("6","7","1","2","3","4","5","6","7") ;
           
          while ($target <= $enddate)  { 
           
// First, the day of the week check

               $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               $get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               $row_dow = mysql_fetch_assoc($get_it) ;
               $dayofweek = $row_dow['DOW'] ;  
               
 
// if not the actual starting day, then add a day to that date
// until we come back around the calendar to the correct day of the week on which to start

                        while ((round($day,0))  <> round($convert[$dayofweek[0]],0) ) {

	// try good 'ol MySQL
                                $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 DAY) AS NEXTDAY " ;
                                $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
                                $row_Date = mysql_fetch_assoc($get_it) ;
                                $target = $row_Date['NEXTDAY'] ;
                                
                    
                                $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               					$get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               					$row_dow = mysql_fetch_assoc($get_it) ;
               					$dayofweek = $row_dow['DOW'] ;
               					
                       } //while ((round($day,0))  <> round($convert[$dayofweek],00 ) )

           
           $delete_old = "DELETE FROM APPTDOCS WHERE DOCTOR = '$doctor' AND DATEIS = '$target' " ;
           $trash_it = mysql_query($delete_old, $tryconnection) or die(mysql_error()) ;
           
           $insert_details = "INSERT INTO APPTDOCS (DATEIS, DOCTOR, SHORTDOC, INITIALS, SEQ, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                 VALUES ('$target', '".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]'  )" ; 
           $add_it = mysql_query($insert_details, $tryconnection) or die(mysql_error())  ;
         
           // Add a week, and try again.
               $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 WEEK) AS NEXTWEEK " ;
               $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
               $_rowDate = mysql_fetch_assoc($get_it) ;
               $target = $_rowDate['NEXTWEEK'] ;
               
           }  // while ($target <= $enddate) ;
       } // ($startdate <= $begincf && $enddate >= $endcf) end of Case 5
       
       /////////////////////// Case 6 /////////////////////////////////////
       
       else if ($startdate <= $begincf && $enddate < $endcf ) {
       
         // The new record inserted into the range is from the new start date to the new end date 
       
           $insert_new = "INSERT INTO HRSDOC (DOCTOR, SHORTDOC, INITIALS, DAYINWEEK, STARTDT, ENDDT, SEQUENCE, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                VALUES ('".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$day', '$startdate', '$enddate', '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]' )" ;
                
          $write_it = mysql_query($insert_new, $tryconnection) or die(mysql_error()) ;
          
         $new_start = "SELECT DATE_ADD('$enddate', INTERVAL 1 DAY) AS NEWSTART " ;
         $get_it = mysql_query($new_start, $tryconnection) or die(mysql_error()) ;
         $row_newstart = mysql_fetch_assoc($get_it) ;
         $new_starting = $row_newstart['NEWSTART'] ;
       
       // The existing record is changed to run starting from the new ending date plus one day to the old ending date .
      
        
         $update_secpart = "UPDATE HRSDOC SET STARTDT = '$new_starting' WHERE HRSID = '$_SESSION[hrsid]'" ;
         $write_it = mysql_query($update_secpart, $tryconnection) or die(mysql_error()) ;
       
            
          $target = $startdate ;    
           
// make sure they have chosen a starting date which is in fact the day of the week that this is all about. 
// Allow for Sundays, as MySQL returns 1, we want 7. 

             $convert = array("6","7","1","2","3","4","5","6","7") ;
           
          while ($target <= $enddate)  { 
           
// First, the day of the week check

               $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               $get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               $row_dow = mysql_fetch_assoc($get_it) ;
               $dayofweek = $row_dow['DOW'] ;  
               
 
// if not the actual starting day, then add a day to that date
// until we come back around the calendar to the correct day of the week on which to start

                        while ((round($day,0))  <> round($convert[$dayofweek[0]],0) ) {

	// try good 'ol MySQL
                                $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 DAY) AS NEXTDAY " ;
                                $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
                                $row_Date = mysql_fetch_assoc($get_it) ;
                                $target = $row_Date['NEXTDAY'] ;
                                
                    
                                $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               					$get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               					$row_dow = mysql_fetch_assoc($get_it) ;
               					$dayofweek = $row_dow['DOW'] ;
               					
                       } //while ((round($day,0))  <> round($convert[$dayofweek],00 ) )

           
           $delete_old = "DELETE FROM APPTDOCS WHERE DOCTOR = '$doctor' AND DATEIS = '$target' " ;
           $trash_it = mysql_query($delete_old, $tryconnection) or die(mysql_error()) ;
           
           $insert_details = "INSERT INTO APPTDOCS (DATEIS, DOCTOR, SHORTDOC, INITIALS, SEQ, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                 VALUES ('$target', '".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]'  )" ;
           $add_it = mysql_query($insert_details, $tryconnection) or die(mysql_error())  ;
         
           // Add a week, and try again.
               $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 WEEK) AS NEXTWEEK " ;
               $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
               $_rowDate = mysql_fetch_assoc($get_it) ;
               $target = $_rowDate['NEXTWEEK'] ;
               
        	} // ($target <= $enddate)
       } // ($startdate <= $begincf && $enddate < $endcf ) Case 6
       
       /////////////////// Case 7 ////////////////////////////
       
       if ($startdate == $begincf && $enddate == $endcf) {  // Case 7
       
        $update_duties = "UPDATE HRSDOC SET DUTY = '$duty' WHERE HRSID = '$_SESSION[hrsid]'" ;
         $write_it = mysql_query($update_duties, $tryconnection) or die(mysql_error()) ;
         
                     
          $target = $startdate ;    
           
// make sure they have chosen a starting date which is in fact the day of the week that this is all about. 
// Allow for Sundays, as MySQL returns 1, we want 7. 

             $convert = array("6","7","1","2","3","4","5","6","7") ;
           
          while ($target <= $enddate)  { 
           
// First, the day of the week check

               $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               $get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               $row_dow = mysql_fetch_assoc($get_it) ;
               $dayofweek = $row_dow['DOW'] ;  
               
 
// if not the actual starting day, then add a day to that date
// until we come back around the calendar to the correct day of the week on which to start

                        while ((round($day,0))  <> round($convert[$dayofweek[0]],0) ) {

	// try good 'ol MySQL
                                $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 DAY) AS NEXTDAY " ;
                                $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
                                $row_Date = mysql_fetch_assoc($get_it) ;
                                $target = $row_Date['NEXTDAY'] ;
                                
                    
                                $get_dow = "SELECT DAYOFWEEK('$target') AS DOW" ;
               					$get_it = mysql_query($get_dow, $tryconnection) or die(mysql_error()) ;
               					$row_dow = mysql_fetch_assoc($get_it) ;
               					$dayofweek = $row_dow['DOW'] ;
               					
                       } //while ((round($day,0))  <> round($convert[$dayofweek],00 ) )

           
           $delete_old = "DELETE FROM APPTDOCS WHERE DOCTOR = '$doctor' AND DATEIS = '$target' " ;
           $trash_it = mysql_query($delete_old, $tryconnection) or die(mysql_error()) ;
           
           $insert_details = "INSERT INTO APPTDOCS (DATEIS, DOCTOR, SHORTDOC, INITIALS, SEQ, OPEN1, CLOSE1, OPEN2, CLOSE2, OPEN3, CLOSE3, DUTY,
                          ES1ST,ES1SP, ES1BST,ES1BSP, ES2ST,ES2SP, ES2BST,ES2BSP, ES3ST,ES3SP, ES3BST,ES3BSP)
                 VALUES ('$target', '".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."',
                '$sequence', '$weekday2[2]', '$weekday2[3]', '$weekday2[4]', '$weekday2[5]', '$weekday2[6]', '$weekday2[7]', '$duty',
                '$eslots2[0]','$eslots2[1]','$eslots2[2]','$eslots2[3]','$eslots2[4]','$eslots2[5]','$eslots2[6]','$eslots2[7]','$eslots2[8]','$eslots2[9]','$eslots2[10]','$eslots2[11]'  )" ;
           $add_it = mysql_query($insert_details, $tryconnection) or die(mysql_error())  ;
         
           // Add a week, and try again.
               $bump_it = "SELECT DATE_ADD('$target', INTERVAL 1 WEEK) AS NEXTWEEK " ;
               $get_it  = mysql_query($bump_it, $tryconnection) or die(mysql_error()) ;
               $_rowDate = mysql_fetch_assoc($get_it) ;
               $target = $_rowDate['NEXTWEEK'] ;
               
        	} // ($target <= $enddate)
       }  //($startdate == $begincf && $enddate == $endcf) Case 7
       
 echo '<script type="text/javascript">  self.close(); window.open("DOCHOURS.php");</script> ' ;
?>