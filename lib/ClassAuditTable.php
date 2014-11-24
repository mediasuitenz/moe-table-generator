<?php

namespace ClassAuditTable;
use Handlebars\Handlebars;
use DateTime, DateTimeZone;

class ClassAuditTable {

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
  public static function generate( $smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students){
    
    $nzdt = new DateTimeZone('Pacific/Auckland');
    $now = date('dmY');  

$upload_dir = wp_upload_dir(); 
  
      $fileName = $upload_dir['basedir'].'/'.$schoolNumber.'AuditList'.$cutoffDate.'.csv'; 
      $fileurl = $upload_dir['baseurl'].'/'.$schoolNumber.'AuditList'.$cutoffDate.'.csv'; 
      $fh = fopen ($fileName, "w");
    

    fputcsv($fh, array('Full School Audit Roll') );

    fputcsv($fh, array('School Number', $schoolNumber, 'Date Printed:', $now) );
     fputcsv($fh, array('School Name', $schoolName, 'Roll Return Date:', $cutoffDate) );
      fputcsv($fh, array('Total School Roll', $totalRoll) );

      fputcsv($fh, array( 'Student Number', 'Student Legal Name', 'Student Preferred Name', 'Funding Year Level', 'Date of Birth', 'Student Type', 'FTE', 'Group/ Class' ) );

 $indices = MOEIndices::getIndices();

foreach ($students as $student) {

      $row[$student[$indices['SURNAME']].$student[$indices['STUDENT_ID']]] = array( 
        $student[$indices['STUDENT_ID']], 
        $student[$indices['SURNAME']].$student[$indices['FIRSTNAME']],
        $student[$indices['PREFERRED LAST NAME']].$student[$indices['PREFERRED FIRST NAME']], 
        $student[$indices['FUNDING YEAR LEVEL']],
        $student[$indices['DOB']] ,
        $student[$indices['TYPE']],
        $student[$indices['FTE']],
        $student['Class'] );

  
    }

ksort($row);
foreach ($row as $r){
 fputcsv($fh, $r );

}


  // Close the file
      fclose($fh);

  }

}
