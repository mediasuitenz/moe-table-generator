<?php

namespace MOETableGenerator;
use Handlebars\Handlebars;
use DateTime, DateTimeZone;

class FullSchoolAuditTable {

  /**
   * Returns a sorted array of students for AuditRequirement 1
   * @param $students Array of students from .moe
   * @param $classes  Array of class objects from SMS
   */
  public static function generateData($students, $classes, $cutoff) {

    $nzdt = new DateTimeZone('Pacific/Auckland');
    $cutoffDate = new DateTime($cutoff, $nzdt);

    $indices = MOEIndices::getIndices();

    foreach ($classes as $class){
      $studentIdToGroupName[$class->person_id]= $class->group_name;
    }

    $studentFilter = function($collectionDate, $student) {
      $validStudentTypes = array('FF', 'EX', 'AE', 'RA', 'AD', 'RE', 'TPREOM', 'TPRAOM');
      $nzdt = new DateTimeZone('Pacific/Auckland');
      $indices = MOEIndices::getIndices();
      $startDate = new DateTime($student[$indices['FIRST ATTENDANCE']], $nzdt);
      $lastAttendance = empty($student[$indices['LAST ATTENDANCE']]) ? null : new DateTime($student[$indices['LAST ATTENDANCE']], $nzdt);
      return (in_array($student[$indices['TYPE']], $validStudentTypes) &&
        $startDate->getTimestamp() <= $collectionDate->getTimestamp() &&
        (is_null($lastAttendance) || $lastAttendance->getTimestamp() >= $collectionDate->getTimestamp())
      );
    };

    foreach ($students as $student) {
      if ($studentFilter($cutoffDate, $student) === true) {
        //Concatenate student id to surname to prevent key collision
        $studentRows[$student[$indices['SURNAME']].$student[$indices['FIRSTNAME']].$student[$indices['STUDENT_ID']]] = array( 
          $student[$indices['STUDENT_ID']], 
          $student[$indices['SURNAME']] . ' ' . $student[$indices['FIRSTNAME']],
          $student[$indices['PREFERRED LAST NAME']] . ' ' . $student[$indices['PREFERRED FIRST NAME']], 
          $student[$indices['FUNDING YEAR LEVEL']],
          $student[$indices['DOB']] ,
          $student[$indices['TYPE']],
          $student[$indices['FTE']],
          $studentIdToGroupName[$student[$indices['STUDENT_ID']]]
        );
      }
    }

    ksort($studentRows);

    return $studentRows;
  }

  public static function generateHtml($fullSchoolAuditData, $schoolName, $schoolNumber, $rollReturnDate) {
    $nzdt = new DateTimeZone('Pacific/Auckland');
    $now = new DateTime('now', $nzdt);
    $rollReturnDate = new DateTime($rollReturnDate, $nzdt);
    $handlebarsEngine = new Handlebars;
    $template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' .
      DIRECTORY_SEPARATOR . 'fullSchoolAudit.html');
    return $handlebarsEngine->render(
      $template,
      array(
        'schoolName' => $schoolName,
        'schoolNumber' => $schoolNumber,
        'totalRoll' => count($fullSchoolAuditData),
        'datePrinted' => $now->format('dmY'),
        'rollReturnDate' => $rollReturnDate->format('dmY'),
        'fullSchoolAuditData' => $fullSchoolAuditData
      )
    );
  }

  public static function writeCsv($fullSchoolAuditData, $schoolName, $schoolNumber, $rollReturnDate, $filePath) {
    $fh = fopen ($filePath, "w");

    $nzdt = new DateTimeZone('Pacific/Auckland');
    $now = new DateTime('now', $nzdt);
    $rollReturnDate = new DateTime($rollReturnDate, $nzdt);

    fputcsv($fh, array('Full School Audit Roll') );
    fputcsv($fh, array('School Number', $schoolNumber, 'Date Printed:', $now->format('dmY')) );
    fputcsv($fh, array('School Name', $schoolName, 'Roll Return Date:', $rollReturnDate->format('dmY')) );
    fputcsv($fh, array('Total School Roll', count($fullSchoolAuditData)));

    fputcsv($fh, array('Student Number', 'Student Legal Name', 'Student Preferred Name', 'Funding Year Level', 'Date of Birth', 'Student Type', 'FTE', 'Group/ Class'));

    foreach ($fullSchoolAuditData as $row) {
      fputcsv($fh, $row);
    }

    fclose($fh);
  }
}
