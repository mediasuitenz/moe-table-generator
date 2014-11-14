<?php

namespace MOETableGenerator;
use DateTimeZone, DateTime;
use Handlebars\Handlebars;

class J4Table {

  public static function generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoff, $students) {

    $nzdt = new DateTimeZone('Pacific/Auckland');
    $indices = MOEIndices::getIndices();

    $cutoffDate = new DateTime($cutoff, $nzdt);

    $now = new DateTime('now', $nzdt);

    $studentCountByAge = array(
      '5' => array(
        'M' => array(
          '1' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '1' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '6' => array(
        'M' => array(
          '1' => 0,
          '2' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '1' => 0,
          '2' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '7' => array(
        'M' => array(
          '1' => 0,
          '2' => 0,
          '3' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '1' => 0,
          '2' => 0,
          '3' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '8' => array(
        'M' => array(
          '2' => 0,
          '3' => 0,
          '4' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '2' => 0,
          '3' => 0,
          '4' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '9' => array(
        'M' => array(
          '3' => 0,
          '4' => 0,
          '5' => 0,
          '7' => 0,
          '8' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '3' => 0,
          '4' => 0,
          '5' => 0,
          '7' => 0,
          '8' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '10' => array(
        'M' => array(
          '4' => 0,
          '5' => 0,
          '6' => 0,
          '7' => 0,
          '8' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '4' => 0,
          '5' => 0,
          '6' => 0,
          '7' => 0,
          '8' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '11' => array(
        'M' => array(
          '5' => 0,
          '6' => 0,
          '7' => 0,
          '8' => 0,
          '9' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '5' => 0,
          '6' => 0,
          '7' => 0,
          '8' => 0,
          '9' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '12' => array(
        'M' => array(
          '6' => 0,
          '7' => 0,
          '8' => 0,
          '9' => 0,
          '10' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '6' => 0,
          '7' => 0,
          '8' => 0,
          '9' => 0,
          '10' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '13' => array(
        'M' => array(
          '7' => 0,
          '8' => 0,
          '9' => 0,
          '10' => 0,
          '11' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '7' => 0,
          '8' => 0,
          '9' => 0,
          '10' => 0,
          '11' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '14' => array(
        'M' => array(
          '7' => 0,
          '8' => 0,
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '7' => 0,
          '8' => 0,
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '15' => array(
        'M' => array(
          '7' => 0,
          '8' => 0,
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '7' => 0,
          '8' => 0,
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '16' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '17' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '18' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '19' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '20' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '21' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '22' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '23' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '24' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '25' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '26' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '27' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '28' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '29' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '30to34' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '35to39' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      '40plus' => array(
        'M' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          '9' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          'total' => 0,
          'partTime' => 0
        )
      ),
      'total' => array(
        'M' => array(
          'total' => 0,
          'partTime' => 0
        ),
        'F' => array(
          'total' => 0,
          'partTime' => 0
        ),
        'total' => array(
          'total' => 0,
          'partTime' => 0
        )
      ),
      'smsName' => $smsName,
      'smsVersion' => $smsVersion,
      'schoolName' => $schoolName,
      'schoolNumber' => $schoolNumber,
      'datePrinted' => $now->format('Y-m-d'),
      'dateTime' => $now->format('Y-m-d H:i')
    );

    for ($i = 1; $i <= 15; $i++) {
      $studentCountByAge['total']['M'][$i] = 0;
      $studentCountByAge['total']['F'][$i] = 0;
      $studentCountByAge['total']['total'][$i] = 0;
    }

    $studentFilter = function ($cutoffDate, $student) {
      // Student TYPE in [FF, EX, AE, RE, RA, AD, TPREOM, TPRAOM]
      //   and FIRST ATTENDANCE is <=1 July 2015
      //   and LAST ATTENDANCE is Null or >=1 July 2015
      $allowedTypes = ['FF', 'EX', 'AE', 'RE', 'RA', 'AD', 'TPREOM', 'TPRAOM'];
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
        $yearLevel = $student[$indices['FUNDING YEAR LEVEL']];

        //Place in part time
        //Count number of Part-time students where FTE < 1.0 and STP in [NULL,98,99]
        $fte = $student[$indices['FTE']];
        $stp = $student[$indices['STP']];
        $partTime = false;
        if ($fte < 1.0 && (empty($stp) || in_array($stp, ['98', '99']))) {
          $partTime = true;
        }

        //Place in age group
        if ($ageAtJul1 >= 40) {
          $studentCountByAge['40plus'][$gender][$yearLevel]++;
          $studentCountByAge['40plus'][$gender]['total']++;
          if ($partTime === true) {
            $studentCountByAge['40plus'][$gender]['partTime']++;
          }
        } else if ($ageAtJul1 >= 35) {
          $studentCountByAge['35to39'][$gender][$yearLevel]++;
          $studentCountByAge['35to39'][$gender]['total']++;
          if ($partTime === true) {
            $studentCountByAge['35to39'][$gender]['partTime']++;
          }
        } else if ($ageAtJul1 >= 30) {
          $studentCountByAge['30to34'][$gender][$yearLevel]++;
          $studentCountByAge['30to34'][$gender]['total']++;
          if ($partTime === true) {
            $studentCountByAge['30to34'][$gender]['partTime']++;
          }
        } else {
          $studentCountByAge[$ageAtJul1][$gender][$yearLevel]++;
          $studentCountByAge[$ageAtJul1][$gender]['total']++;
          if ($partTime === true) {
            $studentCountByAge[$ageAtJul1][$gender]['partTime']++;
          }
        }

        //Place in totals
        $studentCountByAge['total'][$gender][$yearLevel]++;
        $studentCountByAge['total'][$gender]['total']++;
        if ($partTime === true) {
            $studentCountByAge['total'][$gender]['partTime']++;
        }
        $studentCountByAge['total']['total'][$yearLevel]++;
        $studentCountByAge['total']['total']['total']++;
        if ($partTime === true) {
            $studentCountByAge['total']['total']['partTime']++;
        }
      }
    }

    $handlebarsEngine = new Handlebars;
    $template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' .
      DIRECTORY_SEPARATOR . 'tableJ4.html');
    return $handlebarsEngine->render(
      $template,
      $studentCountByAge
    );
  }

}
