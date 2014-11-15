<?php

namespace MOETableGenerator;
use DateTimeZone, DateTime;
use Handlebars\Handlebars;

class J9Table {

  public static function generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoff, $students) {

    $nzdt = new DateTimeZone('Pacific/Auckland');
    $indices = MOEIndices::getIndices();

    $cutoffDate = new DateTime($cutoff, $nzdt);

    $now = new DateTime('now', $nzdt);

    //Lists of students studying in a pacficic medium language
    $pacificMediumLanguageStudents = array(
      'CIM' => array(),
      'FIJ' => array(),
      'NIU' => array(),
      'SAO' => array(),
      'TOK' => array(),
      'TON' => array(),
      'PIL' => array()
    );

    $pacificMediumLanguageCodes = array(
      'CIM' =>  'Cook Island Maori',
      'FIJ' =>  'Fijian',
      'NIU' =>  'Niuean',
      'SAO' =>  'Samoan',
      'TOK' =>  'Tokelauan',
      'TON' =>  'Tongan',
      'PIL' =>  'Other Pacific Languages'
    );

    foreach ($students as $student) {
      $pacificMediumLanguage = $student[$indices['PACIFIC MEDIUM - LANGUAGE']];
      if (!empty($pacificMediumLanguage)) {
        $pacificMediumLanguageStudents[$pacificMediumLanguage] []= $student;
      }
    }

    $table = '';

    //Construct a table for each non-empty list of students
    foreach($pacificMediumLanguageStudents as $pacificMedium => $students) {
      if (count($students) > 0) {
        $studentsInMedium = array(
          'language' => $pacificMediumLanguageCodes[$pacificMedium],
          'smsName' => $smsName,
          'smsVersion' => $smsVersion,
          'schoolName' => $schoolName,
          'schoolNumber' => $schoolNumber,
          'datePrinted' => $now->format('Y-m-d'),
          'dateTime' => $now->format('Y-m-d H:i')
        );

        $column = array(
          'level1Total' => 0,
          'level1Pacific' => 0,
          'level2Total' => 0,
          'level2Pacific' => 0,
          'level3Total' => 0,
          'level3Pacific' => 0,
          'level4Total' => 0,
          'level4Pacific' => 0,
          'total' => 0
        );

        for ($i = 1; $i <= 15; $i++) {
          $studentsInMedium['year'.$i] = $column;
        }
        $studentsInMedium['total'] = $column;

        $studentFilter = function($cutoffDate, $student) {
          // Student TYPE in [FF, EX, RA, AD, RE, TPREOM, TPRAOM]
          //   and PACIFIC MEDIUM - LANGUAGE not null
          //   and PACIFIC MEDIUM - LEVEL in [1,2,3,4]
          //   and LAST ATTENDANCE is Null or >=1 July 2015
          //   and FIRST ATTENDANCE is <=1 July 2015
          $allowedTypes = ['FF', 'EX', 'RA', 'AD', 'RE', 'TPREOM', 'TPRAOM'];
          $indices = MOEIndices::getIndices();
          $nzdt = new DateTimeZone('Pacific/Auckland');
          $firstAtttendance = new DateTime($student[$indices['FIRST ATTENDANCE']], $nzdt);
          $lastAttendance = empty($student[$indices['LAST ATTENDANCE']]) ? null : new DateTime($student[$indices['LAST ATTENDANCE']], $nzdt);
          return (in_array($student[$indices['TYPE']], $allowedTypes) &&
            !empty($student[$indices['PACIFIC MEDIUM - LANGUAGE']]) &&
            in_array($student[$indices['PACIFIC MEDIUM - LEVEL']], ['1','2','3','4']) &&
            $firstAtttendance->getTimestamp() <= $cutoffDate->getTimestamp() &&
            (is_null($lastAttendance) || $lastAttendance->getTimestamp() >= $cutoffDate->getTimestamp()));
        };

        //Students with pacific ethnicity are reported separately
        $pacificEthnicityCodes = ['311', '321', '331', '341', '351', '361', '371'];

        foreach ($students as $student) {
          if ($studentFilter($cutoffDate, $student)) {
            $yearLevel = $student[$indices['FUNDING YEAR LEVEL']];
            $mediumLevel = $student[$indices['PACIFIC MEDIUM - LEVEL']];
            
            $ethnic1 = $student[$indices['ETHNIC1']];
            $ethnic2 = $student[$indices['ETHNIC2']];
            $ethnic3 = $student[$indices['ETHNIC3']];

            $studentsInMedium['year' . $yearLevel]['level'.$mediumLevel.'Total']++;
            $studentsInMedium['total']['level'.$mediumLevel.'Total']++;
            if (in_array($ethnic1, $pacificEthnicityCodes) ||
              in_array($ethnic2, $pacificEthnicityCodes) ||
              in_array($ethnic3, $pacificEthnicityCodes)
              ) {
              $studentsInMedium['year'.$yearLevel]['level'.$mediumLevel.'Pacific']++;
              $studentsInMedium['total']['level'.$mediumLevel.'Pacific']++;
            }
            $studentsInMedium['year'.$yearLevel]['total']++;
            $studentsInMedium['total']['total']++;
          }
        }

        $handlebarsEngine = new Handlebars;
        $template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' .
          DIRECTORY_SEPARATOR . 'tableJ9.html');
        $table .= $handlebarsEngine->render(
          $template,
          $studentsInMedium
        );
      }
    }

    return $table;
  }
}
