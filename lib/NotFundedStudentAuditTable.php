<?php

namespace MOETableGenerator;

use DateTimeZone, DateTime;
use Handlebars\Handlebars;

class NotFundedStudentAuditTable {

  public static function generate($students, $cutoff) {
    $nzdt = new DateTimeZone('Pacific/Auckland');
    $cutoffDate = new DateTime($cutoff, $nzdt);

    $notFundedStudents = array();

    $indices = MOEIndices::getIndices();

    $studentFilter = function($student, $cutoffDate) {
      $allowedTypes = ['NF'];
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
        switch ($student[$indices['ELIGIBILITY CRITERIA']]) {
          case '60010':
            // 28 Day Waiver
            $key = 'a';
            break;
            // Extended 28 Day Waiver
          case '60011':
            $key = 'b';
            break;
          default:
            $key = 'c';
            break;
        }
        $key .= $student[$indices['FUNDING YEAR LEVEL']];
        $key .= $student[$indices['SURNAME']];
        $key .= $student[$indices['FIRSTNAME']];
        $key .= $student[$indices['STUDENT_ID']];
        $notFundedStudents[$key] = array(
          $student[$indices['SURNAME']] . ' ' . $student[$indices['FIRSTNAME']],
          $student[$indices['GENDER']],
          $student[$indices['DOB']],
          $student[$indices['COUNTRY OF CITIZENSHIP']],
          $student[$indices['ELIGIBILITY CRITERIA']],
          $student[$indices['FIRST ATTENDANCE']],
          $student[$indices['FUNDING YEAR LEVEL']],
          $student[$indices['FTE']]
        );
      } 
    }

    ksort($notFundedStudents);

    $handlebarsEngine = new Handlebars;

    $templatePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'notFundedStudentAudit.html';
    $template = file_get_contents($templatePath);
    return $handlebarsEngine->render(
      $template,
      array(
        'notFundedStudents' => $notFundedStudents,
        'totalNotFundedStudents' => count($notFundedStudents)
      )
    );
  }

}
