<?php

namespace MOETableGenerator;
use DateTimeZone, DateTime;
use Handlebars\Handlebars;

class J5Table {

  public static function generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoff, $students) {

    $nzdt = new DateTimeZone('Pacific/Auckland');
    $indices = MOEIndices::getIndices();

    $cutoffDate = new DateTime($cutoff, $nzdt);

    $now = new DateTime('now', $nzdt);

    $schoolRollByEthnicity = array(
      'smsName' => $smsName,
      'smsVersion' => $smsVersion,
      'schoolName' => $schoolName,
      'schoolNumber' => $schoolNumber,
      'datePrinted' => $now->format('Y-m-d'),
      'dateTime' => $now->format('Y-m-d H:i')
    );

    for ($i = 5; $i <= 21; $i++) {
      $schoolRollByEthnicity[$i] = array(
        'M' => Ethnicity::getEthnicityRows(),
        'F' => Ethnicity::getEthnicityRows()
      );
    }

    $schoolRollByEthnicity['22plus'] = array(
      'M' => Ethnicity::getEthnicityRows(),
      'F' => Ethnicity::getEthnicityRows()
    );
    $schoolRollByEthnicity['total'] = array(
      'M' => Ethnicity::getEthnicityRows(),
      'F' => Ethnicity::getEthnicityRows(),
      'total' => Ethnicity::getEthnicityRows()
    );

    $studentFilter = function($cutoffDate, $student) {
      // Student TYPE in [FF, EX, AE, RA, AD, RE, TPREOM, TPRAOM]
      //   and FIRST ATTENDANCE is <=1 July 2015
      //   and LAST ATTENDANCE is Null or >=1 July 2015
      $allowedTypes = ['FF', 'EX', 'AE', 'RA', 'AD', 'RE', 'TPREOM', 'TPRAOM'];
      $indices = MOEIndices::getIndices();
      $nzdt = new DateTimeZone('Pacific/Auckland');
      $firstAttendance = new DateTime($student[$indices['FIRST ATTENDANCE']], $nzdt);
      $lastAttendance = empty($student[$indices['LAST ATTENDANCE']]) ? null : new DateTime($student[$indices['LAST ATTENDANCE']], $nzdt);
      return (in_array($student[$indices['TYPE']], $allowedTypes) &&
        $firstAttendance->getTimestamp() <= $cutoffDate->getTimestamp() &&
        (is_null($lastAttendance) || $lastAttendance->getTimestamp() >= $cutoffDate->getTimestamp())
      );
    };

    $july1 = new DateTime($cutoffDate->format('Y') . '-07-01', $nzdt);
    foreach($students as $student) {
      if ($studentFilter($cutoffDate, $student)) {
        $dob = new DateTime($student[$indices['DOB']], $nzdt);
        $ageAtJul1 = $dob->diff($july1)->y;
        $gender = $student[$indices['GENDER']];
        $type = $student[$indices['TYPE']];

        if ($type === 'FF') {
          if ($ageAtJul1 >= 22) {
            $schoolRollByEthnicity['22plus'][$gender]['ff']++;
            $schoolRollByEthnicity['22plus'][$gender]['total']++;
          } else {
            $schoolRollByEthnicity[$ageAtJul1][$gender]['ff']++;
            $schoolRollByEthnicity[$ageAtJul1][$gender]['total']++;
          }
          //Totals
          $schoolRollByEthnicity['total'][$gender]['ff']++;
          $schoolRollByEthnicity['total']['total']['ff']++;
        } else {
    
          //Reporting of Ethnicity must follow the hierarchy as detailed in Section 6.4. 
          $ethnicity = Ethnicity::getEthnicity($student);
          if ($ageAtJul1 >= 22) {
            $schoolRollByEthnicity['22plus'][$gender][$ethnicity]++;
            $schoolRollByEthnicity['22plus'][$gender]['total']++;
          } else {
            $schoolRollByEthnicity[$ageAtJul1][$gender][$ethnicity]++;
            $schoolRollByEthnicity[$ageAtJul1][$gender]['total']++;
          }
          $schoolRollByEthnicity['total'][$gender][$ethnicity]++;
          $schoolRollByEthnicity['total']['total'][$ethnicity]++;
        }
        
        //Totals
        $schoolRollByEthnicity['total'][$gender]['total']++;
        $schoolRollByEthnicity['total']['total']['total']++;
      }
    }

    $handlebarsEngine = new Handlebars;
    $template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' .
      DIRECTORY_SEPARATOR . 'tableJ5.html');
    return $handlebarsEngine->render(
      $template,
      $schoolRollByEthnicity
    );
  }

  

}
