<?php

namespace MOETableGenerator;
use Handlebars\Handlebars;
use DateTime, DateTimeZone;

class MaoriLanguageTable {

  /**
   * Given the footer of a .moe file returns the HMTL
   * for Table M4 or J7: Highest Level of Māori Language Learning
   * @param  String $smsName
   * @param  String $smsVersion
   * @param  String $schoolName
   * @param  String $schoolNumber
   * @param  String $cutoff   Date to cutoff students for this table
   * @param  Array  $students
   * @param  String $month M or J
   * @return String
   */
  public function generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoff, $students, $month) {

    $rows = array();

    $studentFilter = function($cutoff, $student) {
      // Student TYPE in [EX, RA, AD, RE, TPREOM, TPRAOM]
      // and MĀORI=not Null
      // Exclusions Students with STP in (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22)
      $allowedTypes = ['EX', 'RA', 'AD', 'RE', 'TPREOM', 'TPRAOM'];
      $excludedStp = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22];
      
      $indices = MOEIndices::getIndices();

      $nzdt = new DateTimeZone('Pacific/Auckland');
      $collectionDate = new DateTime($cutoff, $nzdt);
      $startDate = new DateTime($student[$indices['FIRST ATTENDANCE']], $nzdt);
      $lastAttendance = empty($student[$indices['LAST ATTENDANCE']]) ? null : new DateTime($student[$indices['LAST ATTENDANCE']], $nzdt);
      return (in_array($student[$indices['TYPE']], $allowedTypes) &&
        !in_array($student[$indices['STP']], $excludedStp) &&
        !empty($student[$indices['MAORI']]) &&
        $startDate->getTimestamp() <= $collectionDate->getTimestamp() &&
        (is_null($lastAttendance) || $lastAttendance->getTimestamp() >= $collectionDate->getTimestamp()));
    };

    $m4Columns = array(
      'MLL1',
      'MLL2',
      'MLL3',
      'MLL4A',
      'MLL4B',
      'MLL5',
      'MLL6'
    );

    $highestLevelMaori = array();

    //Populate highestLevelMaori with 0 values
    foreach($m4Columns as $column) {
      $highestLevelMaori[$column] = array(
        'total' => array('total'=>0),
        'maori' => array('total'=>0)
      );
      for ($i = 0; $i <= 15; $i++) {
        $highestLevelMaori[$column]['total'][$i] = 0;
        $highestLevelMaori[$column]['maori'][$i] = 0;
      }
    }

    $indices = MOEIndices::getIndices();

    foreach($students as $student) {
      if ($studentFilter($cutoff, $student)) {
        $yearLevel = $student[16];
        //Column was being re-used here from last loop
        $column = null;
        switch ($student[$indices['MAORI']]) {
          case ('H'):
            $column = 'MLL1';
            break;
          case ('G'):
            $column = 'MLL1';
            break;
          case ('F'):
            $column = 'MLL2';
            break;
          case ('E'):
            $column = 'MLL3';
            break;
          case ('D'):
            $column = 'MLL4A';
            break;
          case ('C'):
            $column = 'MLL4B';
            break;
          case ('B'):
            $column = 'MLL5';
            break;
          case ('A'):
            $column = 'MLL6';
            break;
        }
        //Maori students are counted separately
        if ($student[$indices['ETHNIC1']] == '211' ||
          $student[$indices['ETHNIC2']] == '211' ||
          $student[$indices['ETHNIC3']] == '211') {
          $highestLevelMaori[$column]['maori'][$yearLevel]++;
          $highestLevelMaori[$column]['maori']['total']++;
        }
        //All students regardless of race 
        $highestLevelMaori[$column]['total'][$yearLevel]++;
        $highestLevelMaori[$column]['total']['total']++;
      }
    }

    $highestLevelMaori['totals'] = array();

    for ($i = 1; $i <= 15; $i++) {
      $highestLevelMaori['totals'][$i] = array_sum(array(
        $highestLevelMaori['MLL1']['total'][$i],
        $highestLevelMaori['MLL2']['total'][$i],
        $highestLevelMaori['MLL3']['total'][$i],
        $highestLevelMaori['MLL4A']['total'][$i],
        $highestLevelMaori['MLL4B']['total'][$i],
        $highestLevelMaori['MLL5']['total'][$i],
        $highestLevelMaori['MLL6']['total'][$i]
      ));
    }

    $highestLevelMaori['totals']['total'] = array_sum($highestLevelMaori['totals']);
    $highestLevelMaori['smsName'] = $smsName;
    $highestLevelMaori['smsVersion'] = $smsVersion;
    $highestLevelMaori['schoolName'] = $schoolName;
    $highestLevelMaori['schoolNumber'] = $schoolNumber;
    $nzdt = new DateTimeZone('Pacific/Auckland');
    $now = new DateTime('now', $nzdt);
    $highestLevelMaori['datePrinted'] = $now->format('Y-m-d');
    $highestLevelMaori['dateTime'] = $now->format('Y-m-d H:i');

    $handlebarsEngine = new Handlebars;

    $templatePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    if ($month === 'M')  {
      $templatePath .= 'tableM4.html';
    } else if ($month === 'J') {
      $templatePath .= 'tableJ7.html';
    }
    $template = file_get_contents($templatePath);
    return $handlebarsEngine->render(
      $template,
      $highestLevelMaori
    );
  }

}
