<?php

namespace MOETableGenerator;

use DateTimeZone, DateTime;
use Handlebars\Handlebars;

class FeePayingStudentAuditTable {

  public static function generate($students, $cutoff) {
    $nzdt = new DateTimeZone('Pacific/Auckland');
    $cutoffDate = new DateTime($cutoff, $nzdt);

    $feePayingStudents = array();

    $indices = MOEIndices::getIndices();

    $studentFilter = function($student, $cutoffDate) {
      $allowedTypes = ['FF'];
      $indices = MOEIndices::getIndices();
      $nzdt = new DateTimeZone('Pacific/Auckland');
      $firstAttendance = new DateTime($student[$indices['FIRST ATTENDANCE']], $nzdt);
      $lastAttendance = empty($student[$indices['LAST ATTENDANCE']]) ? null : new DateTime($student[$indices['LAST ATTENDANCE']], $nzdt);
      return (in_array($student[$indices['TYPE']], $allowedTypes) &&
        $firstAttendance->getTimestamp() <= $cutoffDate->getTimestamp() &&
        (is_null($lastAttendance) || $lastAttendance->getTimestamp() >= $cutoffDate->getTimestamp())
      );
    };

    foreach($students as $student) {
      if ($studentFilter($student, $cutoffDate)) {
        $key = $student[$indices['FUNDING YEAR LEVEL']];
        $key .= $student[$indices['SURNAME']];
        $key .= $student[$indices['FIRSTNAME']];
        $key .= $student[$indices['STUDENT_ID']];
        $feePayingStudents[$key] = array(
          $student[$indices['SURNAME']] . ' ' . $student[$indices['FIRSTNAME']],
          $student[$indices['GENDER']],
          $student[$indices['DOB']],
          $student[$indices['COUNTRY OF CITIZENSHIP']],
          $student[$indices['FIRST ATTENDANCE']],
          $student[$indices['FUNDING YEAR LEVEL']],
          $student[$indices['FTE']]
        );
      } 
    }

    ksort($feePayingStudents);


    $handlebarsEngine = new Handlebars;

    $templatePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'feePayingStudentAudit.html';
    $template = file_get_contents($templatePath);
    return $handlebarsEngine->render(
      $template,
      array(
        'feePayingStudents' => $feePayingStudents,
        'totalFeePayingStudents' => count($feePayingStudents)
      )
    );
  }

}
