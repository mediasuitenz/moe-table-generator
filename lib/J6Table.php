<?php

namespace MOETableGenerator;
use DateTimeZone, DateTime;
use Handlebars\Handlebars;

class J6Table {

  /**
   * Returns the table for roll by yearlevel and ethnicity
   * @param  String $smsName
   * @param  String $smsVersion
   * @param  String $schoolName
   * @param  String $schoolNumber
   * @param  String $cutoff (Y-m-d)
   * @param  Array $students
   * @return String
   */
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

    for ($i = 1; $i <= 15; $i++) {
      $schoolRollByEthnicity[$i] = array(
        'M' => Ethnicity::getEthnicityRows(),
        'F' => Ethnicity::getEthnicityRows()
      );
    }

    $schoolRollByEthnicity['1to8total'] = array(
      'M' => Ethnicity::getEthnicityRows(),
      'F' => Ethnicity::getEthnicityRows(),
      'total' => Ethnicity::getEthnicityRows()
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

    foreach($students as $student) {
      if ($studentFilter($cutoffDate, $student)) {

        $gender = $student[$indices['GENDER']];
        $type = $student[$indices['TYPE']];
        $yearLevel = $student[$indices['FUNDING YEAR LEVEL']];
        if ($type === 'FF') {
          $ethnicity = 'ff';
        } else {
          $ethnicity = Ethnicity::getEthnicity($student);
        }

        $schoolRollByEthnicity[$yearLevel][$gender][$ethnicity]++;
        $schoolRollByEthnicity[$yearLevel][$gender]['total']++;

        if ($yearLevel <= 8) {
          $schoolRollByEthnicity['1to8total'][$gender][$ethnicity]++;
          $schoolRollByEthnicity['1to8total'][$gender]['total']++;
          $schoolRollByEthnicity['1to8total']['total'][$ethnicity]++;
          $schoolRollByEthnicity['1to8total']['total']['total']++;
        }

        $schoolRollByEthnicity['total'][$gender][$ethnicity]++;
        $schoolRollByEthnicity['total'][$gender]['total']++;
        $schoolRollByEthnicity['total']['total'][$ethnicity]++;
        $schoolRollByEthnicity['total']['total']['total']++;

      }
    }

    $handlebarsEngine = new Handlebars;
    $template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' .
      DIRECTORY_SEPARATOR . 'tableJ6.html');
    return $handlebarsEngine->render(
      $template,
      $schoolRollByEthnicity
    );

  }

}
