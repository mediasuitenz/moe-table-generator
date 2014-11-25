<?php

namespace MOETableGenerator;
use Handlebars\Handlebars;
use DateTime, DateTimeZone;

class FullSchoolAuditTable {

  /**
   * Generates zoning status for schools with a valid enrolment scheme
   * @param  String $smsName
   * @param  String $smsVersion
   * @param  String $schoolName
   * @param  String $schoolNumber
   * @param  String $enrolmentSchemeDate (Ymd)
   * @param  String $cutoff - The cutoff date (Y-m-d) for this roll return table
   * @param  Array $students 
   * @return String
   */
  public static function generate( $smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students, $moeDir, $month, $totalNumber){
    
    $nzdt = new DateTimeZone('Pacific/Auckland');
    $now = date('dmY');  

 

 $indices = MOEIndices::getIndices();

global $wpdb;
  $groups_table = $wpdb->prefix."groups";
  $group_relationships_table = $wpdb->prefix."group_relationships";
    $user_table = $wpdb->prefix."users";
     $usermeta_table = $wpdb->prefix."usermeta";

foreach ($students as $student) {

$classes = $wpdb->get_row($wpdb->prepare("select $group_relationships_table .`person_id`, $groups_table .`group_id` AS `group_id`,$groups_table .`user_id` AS `user_id`, CASE WHEN $usermeta_table.meta_key = 'first_name' then $usermeta_table.meta_value end AS `teacher_first`, CASE WHEN $usermeta_table.meta_key = 'last_name' then $usermeta_table.meta_value end AS `teacher_last`,  $groups_table .`room` AS `room`,$groups_table .`year` AS `year`,$groups_table .`type` AS `type`,$groups_table .`group_name` AS `group_name`,$groups_table .`Team` AS `Team`,$groups_table .`team_order` AS `team_order`,$groups_table .`YearGroup` AS `YearGroup`,$groups_table .`ref_id` AS `ref_id`,$groups_table .`shared` AS `shared` from $groups_table left join $group_relationships_table on $groups_table .`group_id` = $group_relationships_table.group_id 
left join $usermeta_table on $groups_table.`user_id` = $usermeta_table.user_id where ($groups_table .`type` = 'Class') and $group_relationships_table.person_id = %d", $student[$indices['STUDENT_ID']] ));

$class[$classes->group_id] = $classes;


        $row[$student[$indices['SURNAME']].$student[$indices['STUDENT_ID']]] = array( 
        $student[$indices['STUDENT_ID']], 
        $student[$indices['SURNAME']].$student[$indices['FIRSTNAME']],
        $student[$indices['PREFERRED LAST NAME']].$student[$indices['PREFERRED FIRST NAME']], 
        $student[$indices['FUNDING YEAR LEVEL']],
        $student[$indices['DOB']] ,
        $student[$indices['TYPE']],
        $student[$indices['FTE']],
        $classes->group_name);

        $classrow[$classes->group_id][$student[$indices['SURNAME']].$student[$indices['STUDENT_ID']]] = array( 
        $student[$indices['STUDENT_ID']], 
        $student[$indices['SURNAME']].$student[$indices['FIRSTNAME']],
        $student[$indices['PREFERRED LAST NAME']].$student[$indices['PREFERRED FIRST NAME']], 
        $student[$indices['FUNDING YEAR LEVEL']],
        $student[$indices['DOB']] ,
         $student[$indices['TYPE']],
       '',
        '',
         ''
       );
    }
ksort($row);

      $fileName = $moeDir . DIRECTORY_SEPARATOR . '/FullAuditList.csv'; 
   //   $fileurl = $moeDir . DIRECTORY_SEPARATOR . '/FullAuditList'.$cutoffDate.'.csv'; 
      $fh = fopen ($fileName, "w");
    
    fputcsv($fh, array('Full School Audit Roll') );
    fputcsv($fh, array('School Number', $schoolNumber, 'Date Printed:', $now) );
    fputcsv($fh, array('School Name', $schoolName, 'Roll Return Date:', $cutoffDate) );
    fputcsv($fh, array('Total School Roll', $totalNumber) );

      fputcsv($fh, array( 'Student Number', 'Student Legal Name', 'Student Preferred Name', 'Funding Year Level', 'Date of Birth', 'Student Type', 'FTE', 'Group/ Class' ) );
          $cell[0]= 'A';
          $cell[1]= 'B';
          $cell[2]= 'C';
          $cell[3]= 'D';
          $cell[4]= 'E';
          $cell[5]= 'F';
          $cell[6]= 'G';
          $cell[7]= 'H';


    $i=8;
    foreach ($row as $r){
     fputcsv($fh, $r );
     
      $i++;
    }

 
  


foreach ($class as $key=>$c){

   $fileName = $moeDir . DIRECTORY_SEPARATOR . '/ClassAuditList'.$key.'.csv'; 
     // $fileurl = $moeDir . DIRECTORY_SEPARATOR . '/'.$schoolNumber.'ClassAuditList'.$key.'.csv'; 
      $fh = fopen ($fileName, "w");
    

    fputcsv($fh, array('Audit Class List') );

    fputcsv($fh, array('School Number', $schoolNumber, 'Date Printed:', $now) );
    fputcsv($fh, array('School Name', $schoolName) );
    fputcsv($fh, array('Class', $c->group_name) );
    fputcsv($fh, array('Class Teacher', $c->teacher_first. " ". $c->teacher_last) );
      if ($month=='M'){
       fputcsv($fh, array( 'Student Number', 'Student Legal Name', 'Student Preferred Name', 'Funding Year Level', 'Date of Birth', 'Student Type', '27th Feb', '2nd March', '3rd March' ) );

      }
      else {
         fputcsv($fh, array( 'Student Number', 'Student Legal Name', 'Student Preferred Name', 'Funding Year Level', 'Date of Birth', 'Student Type', '30th June', '1st July', '2nd July' ) );

      }
     
    ksort($classrow[$c->group_id]);
    foreach ($classrow[$c->group_id] as $r){
    fputcsv($fh, $r );
}
  
   fputcsv($fh, array(
'This audit class list has been completed by the classroom teacher during class time. All of the students in this class are shown on this class list. The attendance shown accurately records whether the student was physically present or not in the class for each of the times when this class was taught during the relevant three day period. Present must be clearly recorded with a P and Absence with an A. 
This audit class list accurately records the attendance of each student in this class.') );

   fputcsv($fh, array('Teacher Signature', ' ', 'Teacher Name:', ' ', 'Date') );
}


  // Close the file
      fclose($fh);

    

  }

}
