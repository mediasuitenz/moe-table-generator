<?php

namespace MOETableGenerator;

use DateTime, DateTimeZone;
use Handlebars\Handlebars;

class MaoriLanguageAuditTable {

  public static function generate($students, $cutoff) {

    $indices = MOEIndices::getIndices();

    $maoriCodeToLevel = function ($code) {
      switch ($code) {
        case ('H'):
          return '1';
          break;
        case ('G'):
          return '1';
          break;
        case ('F'):
          return '2';
          break;
        case ('E'):
          return '3';
          break;
        case ('D'):
          return '4A';
          break;
        case ('C'):
          return '4B';
          break;
        case ('B'):
          return '5';
          break;
        case ('A'):
          return '6';
          break;
      }
    };

    $maoriLanguageStudents = array();
    foreach($students as $student) {
      if (MaoriLanguageTable::studentFilter($student, $cutoff)) {
        //Be sorted in the following order:
        // 1. MĀORI (Language Learning Index levels 1 to 6 not the Māori language codes A - H)
        // 2. FUNDING YEAR LEVEL
        // 3. Alphabetically by surname
        $maoriLevel = $maoriCodeToLevel($student[$indices['MAORI']]);
        if ($student[$indices['ETHNIC1']] === '211' ||
          $student[$indices['ETHNIC2']] === '211' ||
          $student[$indices['ETHNIC3']] === '211') {
          $ethnicity = 'Māori';
        } else {
          $ethnicity = 'Non-Māori';
        }
        $key = $maoriLevel;
        $key .= $student[$indices['FUNDING YEAR LEVEL']];
        $key .= $student[$indices['SURNAME']];
        $key .= $student[$indices['FIRSTNAME']];
        $key .= $student[$indices['STUDENT_ID']];
        $maoriLanguageStudents[$key] = array(
          $student[$indices['SURNAME']] . ' ' . $student[$indices['FIRSTNAME']],
          $student[$indices['ETHNIC1']],
          $student[$indices['ETHNIC2']],
          $student[$indices['ETHNIC3']],
          $ethnicity,
          $maoriLevel,
          $student[$indices['FUNDING YEAR LEVEL']],
          $student[$indices['FTE']],
          $student[$indices['TYPE']],
          $student[$indices['FIRST ATTENDANCE']],
          $student[$indices['LAST ATTENDANCE']]
        );
      }
    }

    ksort($maoriLanguageStudents);

    $maoriLanguageTotals = MaoriLanguageTable::getTotalsData($students, $cutoff);

    $handlebarsEngine = new Handlebars;

    $templatePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'maoriLanguageAudit.html';
    $template = file_get_contents($templatePath);
    return $handlebarsEngine->render(
      $template,
      array(
        'maoriLanguageStudents' => $maoriLanguageStudents,
        'maoriLanguageTotals' => $maoriLanguageTotals
      )
    );

    return '';

  }

}
