<?php
// Appointment scheduling code.
// First, the doctor selection for the month screen. This is run for each day in the month, with
// the day in calling being given by $datenow. The month in question is contained in the variable
// $month. the year is $year. Each day is called in turn, starting with day 1 in the month until the
// end of the month.
$day = 1 ;
while($day <= 31) {
$today = strtotime('$year'. '-'. '$month'. '-' . '$day') ;
if (checkdate($month,$day,$year) {
 $DOCMONTH = "SELECT INITIALS,DUTY ORDER BY SEQ FROM HRSDOC WHERE DATE = '$today' AND DUTY <> '00000000000000' " ;
 $Get_Doc = mysql_query($DOCMONTH, $tryconnection) or die(mysql_error()) ;
// and paint the screen.
 while($row = mysqli_fetch_assoc($Get_Doc))
// now look for the individual flags that show where they are.. (PHP starts at posn 0. This table 
// starts at 1....)
// 1 am appt  Shows as A
// 2 am surg  Shows as S
// 3 pm apt   Shows as A
// 4 pm surg  Shows as S
// 5 Emerg 1  Shows as E1
// 6 am Gv    Shows as G
// 7 pm Gv    Shows as G
// 8 Evening appt      A
// 9 Even. Gv          G
//10 Emerg 2           E2
//11 am Hosp doctor    H1
//12 pm Hosp doctor    H2
//13 am Dentistry      D
//14 pm Dentistry      D
//
  if (substr($row['duty'],0,1) {
  }
  if (substr($row['duty'],1,1) {
  }
  if (substr($row['duty'],2,1) {
  }
  if (substr($row['duty'],3,1) {
  }
  if (substr($row['duty'],4,1) {
  }
  if (substr($row['duty'],5,1) {
  }
  if (substr($row['duty'],6,1) {
  }
  if (substr($row['duty'],7,1) {
  }
  if (substr($row['duty'],8,1) {
  }
  if (substr($row['duty'],9,1) {
  }
  if (substr($row['duty'],10,1) {
  }
  if (substr($row['duty'],11,1) {
  }
  if (substr($row['duty'],12,1) {
  }
  if (substr($row['duty'],13,1) {
  }
 
 }
 $day ++ ;
}
?>