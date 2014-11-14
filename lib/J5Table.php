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

    $ethnicityArray = array(
      'maori' => 0,
      'tokelauan' => 0,
      'fijian' => 0,
      'niuean' => 0,
      'tongan' => 0,
      'cookIslandsMaori' => 0,
      'samoan' => 0,
      'otherPacific' => 0,
      'southeastAsian' => 0,
      'indian' => 0,
      'chinese' => 0,
      'otherAsian' => 0,
      'middleEastern' => 0,
      'latinAmerican' => 0,
      'african' => 0,
      'other' => 0,
      'otherEuropean' => 0,
      'newZealand' => 0,
      'total' => 0,
      'ff' => 0
    );

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
        'M' => $ethnicityArray,
        'F' => $ethnicityArray
      );
    }

    $schoolRollByEthnicity['22plus'] = array(
      'M' => $ethnicityArray,
      'F' => $ethnicityArray
    );
    $schoolRollByEthnicity['total'] = array(
      'M' => $ethnicityArray,
      'F' => $ethnicityArray,
      'total' => $ethnicityArray
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
          $ethnicity = self::getEthnicity($student);
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

  /**
   * Returns the highest ranked reportable ethnicity from the ethnicity hierachy
   * in 6.4 of specification
   * @param  Array $student
   * @return 
   */
  private static function getEthnicity($student) {
    $rankedEthnicCodes = array(
      '211' => 'maori',
      '351' => 'tokelauan',
      '361' => 'fijian',
      '341' => 'niuean',
      '331' => 'tongan',
      '321' => 'cookIslandsMaori',
      '311' => 'samoan',
      '371' => 'otherPacific',

      '411' => 'southeastAsian', //Filipino
      '412' => 'southeastAsian', //Cambodian
      '413' => 'southeastAsian', //Vietnamese
      '414' => 'southeastAsian', //Other Southeast Asian
      '431' => 'indian',
      '421' => 'chinese',
      '441' => 'otherAsian', //Sri Lankan
      '442' => 'otherAsian', //Japanese
      '443' => 'otherAsian', //Korean
      '444' => 'otherAsian', //Other Asian

      '511' => 'middleEastern',
      '521' => 'latinAmerican',
      '531' => 'african',

      '611' => 'other', //Other Ethnicity
      '999' => 'other', //Not stated

      '128' => 'otherEuropean', //Australian
      '121' => 'otherEuropean', //British/Irish
      '127' => 'otherEuropean', //German
      '122' => 'otherEuropean', //Dutch
      '123' => 'otherEuropean', //Greek
      '124' => 'otherEuropean', //Polish
      '125' => 'otherEuropean', //Southern slav
      '126' => 'otherEuropean', //Italian
      '129' => 'otherEuropean', //Other European
      '111' => 'newZealand'
    );

    $indices = MOEIndices::getIndices();

    $ethnic1 = $student[$indices['ETHNIC1']];
    $ethnic2 = $student[$indices['ETHNIC2']];
    $ethnic3 = $student[$indices['ETHNIC3']];
    //Iterate over ethnic codes and return the first occurrence
    foreach($rankedEthnicCodes as $code => $ethnicity) {
      if ($ethnic1 == $code ||
        $ethnic2 == $code ||
        $ethnic3 == $code) {
        return $ethnicity;
      }
    }
  }

}
