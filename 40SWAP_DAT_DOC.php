<?php
session_start();
require_once('../tryconnection.php');
mysql_select_db($database_tryconnection, $tryconnection);
$effective_day = $_SESSION['effective_day'] ;
echo ' Day is ' . $effective_day ;
if (!isset($_GET['changedoc']) && $_GET['replacement_doctor']== '') {
  echo ' New doc is ' . $_GET['replacement_doctor'] ;
  $back = $_SERVER['HTTP_REFERER'] ;
  header("Location: $back") ;
}
echo ' The New doc is ' . $_GET['replacement_doctor'] ;

// find out which doctor has been targeted, and who the replacement is.

// first, are they doing an "everything" This case is just a simple swap, inheriting all start and end times, and e-slots.
if (isset($_GET['changedoc'])) {
// Get the basic data which is going to be changed.
   $olddoc = $_GET['changedoc'] ;
   $newdoc = $_GET['replacement_doctor'] ;
   echo ' For everything, New doctor is ' . $newdoc ;
   $oldunique = "SELECT UNIQUE1,DUTY FROM APPTDOCS WHERE DOCTOR = '$olddoc' AND DATEIS = '$effective_day' ORDER BY UNIQUE1 DESC LIMIT 1 " ;
   $get_old = mysql_query($oldunique, $tryconnection) or die(mysql_error()) ;
   $query_old = mysqli_fetch_assoc($get_old) ;
   $row_doc_u = $query_old['UNIQUE1'] ;
   echo ' Unique is '  . $row_doc_u ;
   $row_doc_d = $query_old['DUTY'] ;
    if ($newdoc == ' '){
    echo ' passed the blank test ' ;
      $doctor = '' ;
      $shortdoc =  '' ;
      $initials = '  ' ;
      $seq = 99;
      $duty = '00000000000000' ;
    } //if ($newdoc == ' ')
    else { echo ' looking for new doctor ' . $newdoc ;
          $repl_query = "SELECT DOCTOR,SHORTDOC, DOCINIT, PRIORITY FROM DOCTOR WHERE SHORTDOC = '$newdoc' LIMIT 1" ;
          $get_rep_doc = mysql_query($repl_query, $tryconnection) or die(mysql_error()) ;
           echo ' did the query ' ;
          $row_rep_doc = mysqli_fetch_assoc($get_rep_doc) ;
          $doctor = $row_rep_doc['DOCTOR'] ;
          $shortdoc = $row_rep_doc['SHORTDOC'] ;
          $initials = $row_rep_doc['DOCINIT'] ;
          $duty =  $row_doc_d ;
          $seq = $row_rep_doc['PRIORITY'] ;
         echo ' found new doctor ' .$doctor . ' ' . $effective_day;
    } // get details of replacement doctor
  
   if ($seq == 99) { // means the replacement doctor is "No Doctor"
        $replace_all = "UPDATE APPTDOCS SET SHORTDOC = '$shortdoc', DOCTOR = '$doctor', INITIALS = '$initials', SEQ = '$seq', DUTY = '00000000000000' WHERE UNIQUE1 ='$row_doc_u' LIMIT 1" ;
   }
   else {
        $replace_all = "UPDATE APPTDOCS SET SHORTDOC = '$shortdoc', DOCTOR = '$doctor', INITIALS = '$initials', SEQ = '$seq' WHERE UNIQUE1 ='$row_doc_u' LIMIT 1" ;
        $move_clients = "UPDATE APPTS SET SHORTDOC = '$shortdoc' WHERE DATEOF = '$effective_day' AND SHORTDOC = '$olddoc' " ;
        $out_they_go = mysql_query($move_clients, $tryconnection) or die(mysql_error()) ;
        }
   $query_replace = mysql_query($replace_all, $tryconnection) or die(mysql_error()) ;
  } // if (isset($_GET['changedoc']))
  
   else { // not a simple all type swap
  
    $parm =  $_SERVER['QUERY_STRING'] ;
    $out = explode('&',$parm) ;
    $first = explode("=",$out[0]) ;
    $olddoc = $first[1] ;
    $olddoc = str_replace("+"," ",$olddoc) ;
    $within = explode('=',$first[0]) ;
    $checkbox = explode("%3A",$within[0]) ;
    echo ' and the pieces are : checkbox ' . $checkbox[0] . ' with id ' . $checkbox[1] . ' and the doctor '. $olddoc;
    $second = explode("=",$out[1]) ;
    $repl = $_GET['replacement_doctor'] ;
    
    // The options are:
    // 1) The assigned doctor is simply being removed, with no replacement for that assignment. The doctor still has other
    //    assignment(s).
    // 2) The assignment is being given to another doctor that was not previously working that day. New record required.
    //    The original doctor still has other assignment(s).
    // 3) The assignment is being given to another doctor that does not have a conflicting duty that day.The original doctor 
    //    still has other assignment(s).
    // Note: The option of creating a brand new assignment that was not previously scheduled for that day has to be handled by 
    //       the Month Admin route - Set Up Resources.
    
    // So to handle these cases, first determine whether the replacement doctor is already working that day. In this case,  
    // the duty flags need to be adjusted, and the additional start, stop and eslot times added to that record.
    // If the replacement doctor is not supposed to be working that day, a new record must be generated if the replacement doctor 
    // is not blank.
    
    // Start by finding the record for the original doctor, as this is going to have to be modified by stripping the assignment from it.
    // The start, stop and eslot times are going to have to be saved, as the relevant pieces are inherited by the replacement doctor.
    echo ' Looking for it ' ;
    $original = "SELECT * FROM APPTDOCS WHERE UNIQUE1 = '$checkbox[1]' LIMIT 1 " ;
    $query_orig = mysql_query($original, $tryconnection) or die(mysql_error()) ;
    $row_orig = mysqli_fetch_assoc($query_orig) ;
    $origduty = $row_orig['DUTY'] ;
    $origopen1 = $row_orig['OPEN1'] ;
    $origclose1 = $row_orig['CLOSE1'] ;
    $origopen2 = $row_orig['OPEN2'] ;
    $origclose2 = $row_orig['CLOSE2'] ;
    $origopen3 = $row_orig['OPEN3'] ;
    $origclose3 = $row_orig['CLOSE3'] ;
    $orige1st = $row_orig['ES1ST'] ;
    $orige1sp = $row_orig['ES1SP'] ;
    $orige1bst = $row_orig['ES1BST'] ;
    $orige1bsp = $row_orig['ES1BSP'] ;
    $orige2st = $row_orig['ES2ST'] ;
    $orige2sp = $row_orig['ES2SP'] ;
    $orige2bst = $row_orig['ES2BST'] ;
    $orige2bsp = $row_orig['ES2BSP'] ;
    $orige3st = $row_orig['ES3ST'] ;
    $orige3sp = $row_orig['ES3SP'] ;
    $orige3bst = $row_orig['ES3BST'] ;
    $orige3bsp = $row_orig['ES3BSP'] ;
    echo ' got all the old record ' ;
     // now go through all the possibilities, to see which of the 14 flags has to be taken out of the original doctor's record,
     // and turned on for the incoming doctor. Because PHP starts substrings at zero, deduct one from the actual position in each case
     $assingnm = $checkbox[0] ;
     switch ($assingnm) {
      case 'amappt';
       $fl= 0 ;
       $O1 = $origopen1 ;
       $C1 = $origclose1 ;
       $E1S = $orige1st ;
       $E1P = $orige1sp ;
       $E1BS = $orige1bst ;
       $E1BP = $orige1bsp ;
       break ;
      case 'pmappt';
       $fl = 2 ;
       $O2 = $origopen2 ;
       $C2 = $origclose2 ;
       $E2S = $orige2st ;
       $E2P = $orige2sp ;
       $E2BS = $orige2bst ;
       $E2BP = $orige2bsp ;
       break ;
      case 'evappt';
       $fl = 7 ;
       $O3 = $origopen3 ;
       $C3 = $origclose3 ;
       $E3S = $orige3st ;
       $E3P = $orige3sp ;
       $E3BS = $orige3bst ;
       $E3BP = $orige3bsp ;
       break ;
      case 'amsurg';
       $fl = 1 ;
       $O1 = $origopen1 ;
       $C1 = $origclose1 ;
       break ;
      case 'pmsurg';
       $fl = 3 ;
       $O2 = $origopen2 ;
       $C2 = $origclose2 ;
       break ;
      case 'amdent';
       $fl = 12 ;
       $O1 = $origopen1 ;
       $C1 = $origclose1 ;
       break ;
      case 'pmdent';
       $fl = 13 ;
       $O2 = $origopen2 ;
       $C2 = $origclose2 ;
       break ;
      case 'amhosp';
       $fl = 10 ;
       $O1 = $origopen1 ;
       $C1 = $origclose1 ;
       $E1S = $orige1st ;
       $E1P = $orige1sp ;
       $E1BS = $orige1bst ;
       $E1BP = $orige1bsp ;
       break ;
      case 'pmhosp';
       $fl = 11 ;
       $O2 = $origopen2 ;
       $C2 = $origclose2 ;
       $E2S = $orige2st ;
       $E2P = $orige2sp ;
       $E2BS = $orige2bst ;
       $E2BP = $orige2bsp ;
       break ;
      case 'amex';
       $fl = 5 ;
       break ;
      case 'pmex';
       $fl = 6 ;
       break ;
      case 'evex';
       $fl = 8 ;
       break ;
      case 'emerg1';
       $fl = 4 ;
       break ;
      case 'emerg2';
       $fl = 9 ;
       break ;
     }
    // now we have the original data.
    echo ' Figured out what slot it is ' ;
    $newrec = 0 ;
    
    // find out which time period it is in. 
     
     if (substr('11000100001010',$fl,1) !=0 ) {$time = 1 ;}
     else if (substr('00110010000101',$fl,1) !=0 ) {$time = 2 ;}
     else if (substr('00000001100000',$fl,1) !=0 ) {$time = 3 ;}
     else {$time = 4 ;}
     echo ' Time period is ' . $time ;
    
        if ($repl == ' ') {              
           $origduty = substr_replace($origduty,'0',$fl,1) ;
           $update_old = "UPDATE APPTDOCS SET DUTY = '$origduty' WHERE UNIQUE1 = '$checkbox[1]' LIMIT 1 " ;
           $query_old = mysql_query($update_old, $tryconnection) or die(mysql_error()) ;
        } //if ($repl == ' ')
        else { echo ' closing in ' ; 
              $newrec = 1 ;
              $is_current = "SELECT UNIQUE1,DUTY FROM APPTDOCS WHERE DATEIS = '$effective_day' AND SHORTDOC = '$repl' LIMIT 1 " ;
              $ask = mysql_query($is_current, $tryconnection) or die(mysql_error()) ;
     
     // if the replacement doctor is not on that day, 
    
              if (mysqli_num_rows($ask) == 0) { 
               
                 echo ' not on today.. ' ; 
   
                 //  retrieve the doctor info for the new record and set up a dummy duty 
                 $newdoc = $_GET['replacement_doctor'] ;
                 $repl_query = "SELECT DOCTOR,SHORTDOC, DOCINIT, PRIORITY FROM DOCTOR WHERE SHORTDOC = '$newdoc' LIMIT 1" ;
                 $get_rep_doc = mysql_query($repl_query, $tryconnection) or die(mysql_error()) ;
                 $row_rep_doc = mysqli_fetch_assoc($get_rep_doc) ;
          
                 $doctor = $row_rep_doc['DOCTOR'] ;
                 $shortdoc = $row_rep_doc['SHORTDOC'] ;
                 $initials = $row_rep_doc['DOCINIT'] ;
                 $seq = $row_rep_doc['PRIORITY'] ;
                 $repl_duty = '00000000000000' ;
                 $room = substr($origduty,$fl,1) ;
                 $repl_duty = substr_replace($repl_duty,$room,$fl,1) ;
                  
                 switch ($time) {
                  case 1;
                  $make_new = "INSERT INTO APPTDOCS (DATEIS,SEQ,DOCTOR,SHORTDOC,INITIALS,DUTY,OPEN1,CLOSE1,ES1ST,ES1SP,ES1BST,ES1BSP)
                          VALUES ('$effective_day','$seq', '".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."', '$repl_duty','$O1', '$C1', '$E1S', '$E1P',
                          '$E1BS', '$E1BP' )";
                  break ;
                  case 2;
                  $make_new = "INSERT INTO APPTDOCS (DATEIS,SEQ,DOCTOR,SHORTDOC,INITIALS,DUTY,OPEN2,CLOSE2,ES2ST,ES2SP,ES2BST,ES2BSP)
                          VALUES ('$effective_day','$seq', '".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."', '$repl_duty','$O2', '$C2', '$E2S', '$E2P',
                          '$E2BS', '$E2BP' )";
                  break ;
                  case 3;
                  $make_new = "INSERT INTO APPTDOCS (DATEIS,SEQ,DOCTOR,SHORTDOC,INITIALS,DUTY,OPEN3,CLOSE3,ES3ST,ES3SP,ES3BST,ES3BSP)
                          VALUES ('$effective_day','$seq', '".mysql_real_escape_string($doctor)."', '".mysql_real_escape_string($shortdoc)."', '".mysql_real_escape_string($initials)."', '$repl_duty','$O3', '$C3', '$E3S', '$E3P',
                          '$E3BS', '$E3BP' )";
                  break ;
                  }
                  $query_new = mysql_query($make_new, $tryconnection) or die(mysql_error())  ;
                  
                   // now downgrade the original doctor
        
                   $origduty = substr_replace($origduty,'0',$fl,1) ;
                   $update_old = "UPDATE APPTDOCS SET DUTY = '$origduty' WHERE UNIQUE1 = '$checkbox[1]' LIMIT 1 " ;
                   $query_old = mysql_query($update_old, $tryconnection) or die(mysql_error()) ;
  //    /*
                   }
     
                   else {  // just find the replacement doctor's record, check for conflicts then update the duty, and start and end times.
                        echo ' New doc is working . ' ;
                        $ask = mysql_query($is_current, $tryconnection) or die(mysql_error()) ;
                        $newrec = 0;
                        $row_current = mysqli_fetch_assoc($ask) ;
                        $replunique = $row_current['UNIQUE1'];
                        $repl_duty = $row_current['DUTY'];
 
                        $conflict = 0 ;
     // then check the replacement doctor's duty to ensure no conflict.
                        switch ($time) {
                        case 1;
                         if (substr($repl_duty,0,1) != 0) {$conflict = 1 ;}
                         if (substr($repl_duty,1,1) != 0) {$conflict = 1 ;}
                         if (substr($repl_duty,5,1) != 0) {$conflict = 1 ;}
                         if (substr($repl_duty,10,1) != 0) {$conflict = 1 ;}
                         if (substr($repl_duty,12,1) != 0) {$conflict = 1 ;}
                         break ;
                        case 2;
                         if (substr($repl_duty,2,1) != 0) {$conflict = 1 ;}
                         if (substr($repl_duty,3,1) != 0) {$conflict = 1 ;}
                         if (substr($repl_duty,6,1) != 0) {$conflict = 1 ;}
                         if (substr($repl_duty,11,1) != 0) {$conflict = 1 ;}
                         if (substr($repl_duty,13,1) != 0) {$conflict = 1 ;}
                         break ;
                        case 3;
                         if (substr($repl_duty,7,1) != 0) {$conflict = 1 ;}
                         if (substr($repl_duty,8,1) != 0) {$conflict = 1 ;}
                         break ;
                        case 4;
                         if (substr($repl_duty,4,1) != 0) {$conflict = 1 ;}
                         if (substr($repl_duty,9,1) != 0) {$conflict = 1 ;}
                         break ;
                        }  
                        echo ' Time is  ' .$time;
                        echo ' Conflict is ' . $conflict ;
                        echo ' New duty is ' . $repl_duty ;
   //                     */
                          if ($conflict == 0) { 
                          echo ' query was ' . $ask ;
                                    $ask = mysql_query($is_current, $tryconnection) or die(mysql_error()) ;
                                    $query_a = mysqli_fetch_assoc($ask) ;   
                                    $oduty = $query_a['DUTY'] ;
                                    echo ' old duty was ' . $oduty ;
                                    $ounique = $query_a['UNIQUE1'] ;
                                    $room = substr($origduty,$fl,1) ;
                                    echo ' Room ' . $room ;
                                    $newduty = substr_replace($oduty,$room,$fl,1) ;
          
          // Different replacement doctor updates, depending on the timeslot involved.
          
                                    switch ($time) {
                                    case 1;
                                     $update_repl = "UPDATE APPTDOCS SET DUTY = '$newduty', OPEN1 = '$O1', CLOSE1 = '$C1', ES1ST = '$E1S', ES1SP = '$E1P',
                                                     ES1BST = '$E1BS', ES1BSP = '$E1BP'  WHERE UNIQUE1 = '$ounique' LIMIT 1" ;
                                     break ;
                                    case 2;
                                     $update_repl = "UPDATE APPTDOCS SET DUTY = '$newduty', OPEN2 = '$O2', CLOSE2 = '$C2', ES2ST = '$E2S', ES2SP = '$E2P',
                                                     ES2BST = '$E2BS', ES2BSP = '$E2BP'  WHERE UNIQUE1 = '$ounique' LIMIT 1" ;
                                     break ;
                                    case 3;
                                     $update_repl = "UPDATE APPTDOCS SET DUTY = '$newduty', OPEN3 = '$O3', CLOSE3 = '$C3', ES3ST = '$E3S', ES3SP = '$E3P',
                                                     ES3BST = '$E3BS', ES3BSP = '$E3BP'  WHERE UNIQUE1 = '$ounique' LIMIT 1" ;
                                     break ;
                                    }  // end of switch
          
                                    $query_repl = mysql_query($update_repl, $tryconnection) or die(mysql_error()) ; 
                                    // now downgrade the original doctor
   
                                    $origduty = substr_replace($origduty,'0',$fl,1) ;
                                    $update_old = "UPDATE APPTDOCS SET DUTY = '$origduty' WHERE UNIQUE1 = '$checkbox[1]' LIMIT 1 " ;
                                    $query_old = mysql_query($update_old, $tryconnection) or die(mysql_error()) ;  
                                     
                                   }  // conflict == 0
                    } // if new doctor not working
             } // $repl != ' '
             
      } // end of second else
   
   echo ' end of the line..' ;
$back = $_SERVER['HTTP_REFERER'] ;
header("Location: $back") ;
   ?>