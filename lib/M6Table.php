<?php

namespace MOETableGenerator;
use Handlebars\Handlebars;
use DateTime, DateTimeZone;

class M6Table {

  public static function generate($smsName, $smsVersion, $schoolName, $schoolNumber, $enrolmentSchemeDate, $cutoff, $students) {
    
    $nzdt = new DateTimeZone('Pacific/Auckland');
    $now = new DateTime('now', $nzdt);

    $enrolmentSchemeDate = new DateTime($enrolmentSchemeDate, $nzdt);
    $cutoffDate = new DateTime($cutoff, $nzdt);

    $studentsByZoningStatus = array(
      'smsName' => $smsName,
      'smsVersion' => $smsVersion,
      'schoolName' => $schoolName,
      'schoolNumber' => $schoolNumber,
      'enrolmentSchemeDate' => $enrolmentSchemeDate->format('Y-m-d'),
      'datePrinted' => $now->format('Y-m-d'),
      'dateTime' => $now->format('Y-m-d H:i'),
      'INZN' => array(),
      'OUTZ' => array(),
      'NAPP' => array(),
      'total' => array()
    );

    for ($i = 0; $i <= 15; $i++) {
      $studentsByZoningStatus['INZN'][$i] = 0;
      $studentsByZoningStatus['OUTZ'][$i] = 0;
      $studentsByZoningStatus['NAPP'][$i] = 0;
      $studentsByZoningStatus['total'][$i] = 0;
    }


    $studentFilter = function($cutoffDate, $student) {
      // Student TYPE in [RE, RA, EX, AD, TPREOM, TPRAOM ]
      //   and (FTE = 1.0)
      //   and FIRST ATTENDANCE is <=1 March Year 2015
      //   and LAST ATTENDANCE is NULL OR >= 1 March 2015
      //   and STP in [NULL, 98, 99]
      $allowedTypes = ['RE', 'RA', 'EX', 'AD', 'TPREOM', 'TPRAOM'];
      $allowedSTP = ['98', '99'];
      $nzdt = new DateTimeZone('Pacific/Auckland');
      $indices = MOEIndices::getIndices();
      $firstAttendance = new DateTime($student[$indices['FIRST ATTENDANCE']], $nzdt);
      $lastAttendance = empty($student[$indices['LAST ATTENDANCE']]) ? null : new DateTime($student[$indices['LAST ATTENDANCE']], $nzdt);
      return (in_array($student[$indices['TYPE']], $allowedTypes) &&
        (empty($student[$indices['STP']]) || in_array($student[$indices['STP']], $allowedSTP)) &&
        $firstAttendance->getTimestamp() <= $cutoffDate->getTimestamp() &&
        ($lastAttendance === null || $lastAttendance->getTimestamp() >= $cutoffDate->getTimestamp()));
    };

    foreach ($students as $student) {

      if ($studentFilter($cutoffDate, $student)) {
        
        $indices = MOEIndices::getIndices();
        $enrolmentDate = new DateTime($student[$indices['FIRST ATTENDANCE']], $nzdt);

        $january1 = new DateTime($cutoffDate->format('Y') . '-01-01', $nzdt);
        $dob = new DateTime($student[$indices['DOB']], $nzdt);
        $ageAtJan1 = $dob->diff($january1)->y;

        if ($enrolmentDate->getTimestamp() < $enrolmentSchemeDate->getTimestamp()) {
          // Students enrolled before the Effective Date of the Enrolment Scheme must be reported as NAPP [Not Applicable] 
          $studentsByZoningStatus['NAPP'][$student[$indices['FUNDING YEAR LEVEL']]]++;
        } else if (in_array($student[$indices['TYPE']], ['RA', 'AD', 'TPRAOM']) ||
          ($student[$indices['TYPE'] === 'EX'] && $ageAtJan1 >= 19)) {
          // Adult Students (RA, AD, TPRAOM) enrolled >= enrolment scheme effective date must be reported as OUTZ [Out of Zone] else they are NAPP
          // EX students aged >=19 at 1 January 2015 count as Adult students (RA)
          if ($enrolmentDate->getTimestamp() >= $enrolmentSchemeDate->getTimestamp()) {
            if ($student[$indices['ZONING STATUS']] === 'OUTZ') {
              $studentsByZoningStatus['OUTZ'][$student[$indices['FUNDING YEAR LEVEL']]]++;
            } else {
              $studentsByZoningStatus['NAPP'][$student[$indices['FUNDING YEAR LEVEL']]]++;
            }
          } else {
            $studentsByZoningStatus['NAPP'][$student[$indices['FUNDING YEAR LEVEL']]]++;
          }
        } else {
          $studentsByZoningStatus[$student[$indices['ZONING STATUS']]][$student[$indices['FUNDING YEAR LEVEL']]]++;
        }

        $studentsByZoningStatus['total'][$student[$indices['FUNDING YEAR LEVEL']]]++;

      }
    }

    var_dump($studentsByZoningStatus);

    $studentsByZoningStatus['INZN']['total'] = array_sum($studentsByZoningStatus['INZN']);
    $studentsByZoningStatus['OUTZ']['total'] = array_sum($studentsByZoningStatus['OUTZ']);
    $studentsByZoningStatus['NAPP']['total'] = array_sum($studentsByZoningStatus['NAPP']);
    $studentsByZoningStatus['total']['total'] = array_sum($studentsByZoningStatus['total']);

    $handlebarsEngine = new Handlebars;
    $template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' .
      DIRECTORY_SEPARATOR . 'tableM6.html');
    return $handlebarsEngine->render(
      $template,
      $studentsByZoningStatus
    );
  }

}
