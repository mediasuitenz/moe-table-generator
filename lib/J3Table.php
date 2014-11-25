<?php

namespace MOETableGenerator;
use Handlebars\Handlebars;
use DateTimeZone, DateTime;

class J3Table {

  /**
   * Given the footer of a .moe file returns the HMTL
   * for Table J3 School Roll by Type of Student and Funding Year Level
   * @param  Array $moeFooter
   * @return String
   */
  public static function generate($schoolName, $schoolNumber, $moeFooter, $smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students, $moeDir, $month, $classes) {

    //Perform calculations on footer

    //FR students go from years 1 to 15
    $fullRegularMale = array_slice($moeFooter[1], 1, 15);
    $fullRegularMaleTotal = array_sum($fullRegularMale);
    $fullRegularFemale = array_slice($moeFooter[1], 16, 30);
    $fullRegularFemaleTotal = array_sum($fullRegularFemale);

    //PR students go from years 9 to 15
    $partTimeRegularMale = array_slice($moeFooter[2], 1, 7);
    $partTimeRegularMaleTotal = array_sum($partTimeRegularMale);

    $partTimeRegularFemale = array_slice($moeFooter[2], 8, 14);
    $partTimeRegularFemaleTotal = array_sum($partTimeRegularFemale);

    //FA students go from years 9 to 15
    $fullAdultMale = array_slice($moeFooter[3], 1, 7);
    $fullAdultMaleTotal = array_sum($fullAdultMale);
    $fullAdultFemale = array_slice($moeFooter[3], 8, 14);
    $fullAdultFemaleTotal = array_sum($fullAdultFemale);

    //PA students go from years 9 to 15
    $partTimeAdultMale = array_slice($moeFooter[4], 1, 7);
    $partTimeAdultMaleTotal = array_sum($partTimeAdultMale);

    $partTimeAdultFemale = array_slice($moeFooter[4], 8, 14);
    $partTimeAdultFemaleTotal = array_sum($partTimeAdultFemale);

    //Calculate total across rows 1 to 4
    $ministryFundedStudentsMale = array(
      '0' => $fullRegularMale[0],
      '1' => $fullRegularMale[1],
      '2' => $fullRegularMale[2],
      '3' => $fullRegularMale[3],
      '4' => $fullRegularMale[4],
      '5' => $fullRegularMale[5],
      '6' => $fullRegularMale[6],
      '7' => $fullRegularMale[7],
      '8' => array_sum([
        $fullRegularMale[8], 
        $partTimeRegularMale[0],
        $fullAdultMale[0],
        $partTimeAdultMale[0]
      ]),
      '9' => array_sum([
        $fullRegularMale[9], 
        $partTimeRegularMale[1],
        $fullAdultMale[1],
        $partTimeAdultMale[1]
      ]),
      '10' => array_sum([
        $fullRegularMale[10], 
        $partTimeRegularMale[2],
        $fullAdultMale[2],
        $partTimeAdultMale[2]
      ]),
      '11' => array_sum([
        $fullRegularMale[11], 
        $partTimeRegularMale[3],
        $fullAdultMale[3],
        $partTimeAdultMale[3]
      ]),
      '12' => array_sum([
        $fullRegularMale[12], 
        $partTimeRegularMale[4],
        $fullAdultMale[4],
        $partTimeAdultMale[4]
      ]),
      '13' => array_sum([
        $fullRegularMale[13], 
        $partTimeRegularMale[5],
        $fullAdultMale[5],
        $partTimeAdultMale[5]
      ]),
      '14' => array_sum([
        $fullRegularMale[14], 
        $partTimeRegularMale[6],
        $fullAdultMale[6],
        $partTimeAdultMale[6]
      ]),
    );

    $ministryFundedStudentsMaleTotal = array_sum($ministryFundedStudentsMale);

    $ministryFundedStudentsFemale = array(
      '0' => $fullRegularFemale[0],
      '1' => $fullRegularFemale[1],
      '2' => $fullRegularFemale[2],
      '3' => $fullRegularFemale[3],
      '4' => $fullRegularFemale[4],
      '5' => $fullRegularFemale[5],
      '6' => $fullRegularFemale[6],
      '7' => $fullRegularFemale[7],
      '8' => array_sum([
        $fullRegularFemale[8], 
        $partTimeRegularFemale[0],
        $fullAdultFemale[0],
        $partTimeAdultFemale[0]
      ]),
      '9' => array_sum([
        $fullRegularFemale[9], 
        $partTimeRegularFemale[1],
        $fullAdultFemale[1],
        $partTimeAdultFemale[1]
      ]),
      '10' => array_sum([
        $fullRegularFemale[10], 
        $partTimeRegularFemale[2],
        $fullAdultFemale[2],
        $partTimeAdultFemale[2]
      ]),
      '11' => array_sum([
        $fullRegularFemale[11], 
        $partTimeRegularFemale[3],
        $fullAdultFemale[3],
        $partTimeAdultFemale[3]
      ]),
      '12' => array_sum([
        $fullRegularFemale[12], 
        $partTimeRegularFemale[4],
        $fullAdultFemale[4],
        $partTimeAdultFemale[4]
      ]),
      '13' => array_sum([
        $fullRegularFemale[13], 
        $partTimeRegularFemale[5],
        $fullAdultFemale[5],
        $partTimeAdultFemale[5]
      ]),
      '14' => array_sum([
        $fullRegularFemale[14], 
        $partTimeRegularFemale[6],
        $fullAdultFemale[6],
        $partTimeAdultFemale[6]
      ]),
    );

    $ministryFundedStudentsFemaleTotal = array_sum($ministryFundedStudentsFemale);

    //ST students go from years 9 to 15
    $stMale = array_slice($moeFooter[5], 1, 7);
    $stMaleTotal = array_sum($stMale);

    $stFemale = array_slice($moeFooter[5], 8, 14);
    $stFemaleTotal = array_sum($stFemale);

    //AE students fo from years 9 to 15
    $alternativeEducationMale = array_slice($moeFooter[6], 1, 7);
    $alternativeEducationMaleTotal = array_sum($alternativeEducationMale);

    $alternativeEducationFemale = array_slice($moeFooter[6], 8, 14);
    $alternativeEducationFemaleTotal = array_sum($alternativeEducationFemale);

    ///FF students go from years 1 to 15
    $fullFeeMale = array_slice($moeFooter[7], 1, 15);
    $fullFeeMaleTotal = array_sum($fullFeeMale);

    $fullFeeFemale = array_slice($moeFooter[7], 16, 30);
    $fullFeeFemaleTotal = array_sum($fullFeeFemale);

    //Calculate totals across columns 5 to 8
    $totalNumberMale = array(
      '0' => $ministryFundedStudentsMale[0] + $fullFeeMale[0],
      '1' => $ministryFundedStudentsMale[1] + $fullFeeMale[1],
      '2' => $ministryFundedStudentsMale[2] + $fullFeeMale[2],
      '3' => $ministryFundedStudentsMale[3] + $fullFeeMale[3],
      '4' => $ministryFundedStudentsMale[4] + $fullFeeMale[4],
      '5' => $ministryFundedStudentsMale[5] + $fullFeeMale[5],
      '6' => $ministryFundedStudentsMale[6] + $fullFeeMale[6],
      '7' => $ministryFundedStudentsMale[7] + $fullFeeMale[7],
      '8' => array_sum([
        $ministryFundedStudentsMale[8], 
        $stMale[0],
        $alternativeEducationMale[0],
        $fullFeeMale[8]
      ]),
      '9' => array_sum([
        $ministryFundedStudentsMale[9], 
        $stMale[1],
        $alternativeEducationMale[1],
        $fullFeeMale[9]
      ]),
      '10' => array_sum([
        $ministryFundedStudentsMale[10], 
        $stMale[2],
        $alternativeEducationMale[2],
        $fullFeeMale[10]
      ]),
      '11' => array_sum([
        $ministryFundedStudentsMale[11], 
        $stMale[3],
        $alternativeEducationMale[3],
        $fullFeeMale[11]
      ]),
      '12' => array_sum([
        $ministryFundedStudentsMale[12], 
        $stMale[4],
        $alternativeEducationMale[4],
        $fullFeeMale[12]
      ]),
      '13' => array_sum([
        $ministryFundedStudentsMale[13], 
        $stMale[5],
        $alternativeEducationMale[5],
        $fullFeeMale[13]
      ]),
      '14' => array_sum([
        $ministryFundedStudentsMale[14], 
        $stMale[6],
        $alternativeEducationMale[6],
        $fullFeeMale[14]
      ]),
    );

    $totalNumberMaleTotal = array_sum($totalNumberMale);

    //Calculate totals across columns 5 to 8
    $totalNumberFemale = array(
      '0' => $ministryFundedStudentsFemale[0] + $fullFeeFemale[0],
      '1' => $ministryFundedStudentsFemale[1] + $fullFeeFemale[1],
      '2' => $ministryFundedStudentsFemale[2] + $fullFeeFemale[2],
      '3' => $ministryFundedStudentsFemale[3] + $fullFeeFemale[3],
      '4' => $ministryFundedStudentsFemale[4] + $fullFeeFemale[4],
      '5' => $ministryFundedStudentsFemale[5] + $fullFeeFemale[5],
      '6' => $ministryFundedStudentsFemale[6] + $fullFeeFemale[6],
      '7' => $ministryFundedStudentsFemale[7] + $fullFeeFemale[7],
      '8' => array_sum([
        $ministryFundedStudentsFemale[8], 
        $stFemale[0],
        $alternativeEducationFemale[0],
        $fullFeeFemale[8]
      ]),
      '9' => array_sum([
        $ministryFundedStudentsFemale[9], 
        $stFemale[1],
        $alternativeEducationFemale[1],
        $fullFeeFemale[9]
      ]),
      '10' => array_sum([
        $ministryFundedStudentsFemale[10], 
        $stFemale[2],
        $alternativeEducationFemale[2],
        $fullFeeFemale[10]
      ]),
      '11' => array_sum([
        $ministryFundedStudentsFemale[11], 
        $stFemale[3],
        $alternativeEducationFemale[3],
        $fullFeeFemale[11]
      ]),
      '12' => array_sum([
        $ministryFundedStudentsFemale[12], 
        $stFemale[4],
        $alternativeEducationFemale[4],
        $fullFeeFemale[12]
      ]),
      '13' => array_sum([
        $ministryFundedStudentsFemale[13], 
        $stFemale[5],
        $alternativeEducationFemale[5],
        $fullFeeFemale[13]
      ]),
      '14' => array_sum([
        $ministryFundedStudentsFemale[14], 
        $stFemale[6],
        $alternativeEducationFemale[6],
        $fullFeeFemale[14]
      ]),
    );

    $totalNumberFemaleTotal = array_sum($totalNumberFemale);

    $totalNumber = array();
    for($i = 0; $i < 15; $i++) {
      $totalNumber[$i] = $totalNumberMale[$i] + $totalNumberFemale[$i];
    }

    $totalNumberTotal = array_sum($totalNumber);

    $nzdt = new DateTimeZone('Pacific/Auckland');
    $now = new DateTime('now', $nzdt);

    $handlebarsEngine = new Handlebars;
    $template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' .
      DIRECTORY_SEPARATOR . 'tableJ3.html');
    
    $auditTables = FullSchoolAuditTable::generate( $smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students, $moeDir, $month, $totalNumberTotal, $classes);
    return $handlebarsEngine->render(
      $template,
      array(
        'schoolName' => $schoolName,
        'schoolNumber' => $schoolNumber,
        'datePrinted' => $now->format('Y-m-d'),

        'fullRegularMale' => $fullRegularMale,
        'fullRegularFemale' => $fullRegularFemale,
        'partTimeRegularMale' => $partTimeRegularMale,
        'partTimeRegularFemale' => $partTimeRegularFemale,
        'fullAdultMale' => $fullAdultMale,
        'fullAdultFemale' => $fullAdultFemale,
        'partTimeAdultMale' => $partTimeAdultMale,
        'partTimeAdultFemale' => $partTimeAdultFemale,
        'ministryFundedStudentsMale' => $ministryFundedStudentsMale,
        'ministryFundedStudentsFemale' => $ministryFundedStudentsFemale,
        'stMale' => $stMale,
        'stFemale' => $stFemale,
        'alternativeEducationMale' => $alternativeEducationMale,
        'alternativeEducationFemale' => $alternativeEducationFemale,
        'fullFeeMale' => $fullFeeMale,
        'fullFeeFemale' => $fullFeeFemale,
        'totalNumberMale' => $totalNumberMale,
        'totalNumberFemale' => $totalNumberFemale,
        'totalNumber' => $totalNumber,

        'fullRegularMaleTotal' => $fullRegularMaleTotal,
        'fullRegularFemaleTotal' => $fullRegularFemaleTotal,
        'partTimeRegularMaleTotal' => $partTimeRegularMaleTotal,
        'partTimeRegularFemaleTotal' => $partTimeRegularFemaleTotal,
        'fullAdultMaleTotal' => $fullAdultMaleTotal,
        'fullAdultFemaleTotal' => $fullAdultFemaleTotal,
        'partTimeAdultMaleTotal' => $partTimeAdultMaleTotal,
        'partTimeAdultFemaleTotal' => $partTimeAdultFemaleTotal,
        'ministryFundedStudentsMaleTotal' => $ministryFundedStudentsMaleTotal,
        'ministryFundedStudentsFemaleTotal' => $ministryFundedStudentsFemaleTotal,
        'stMaleTotal' => $stMaleTotal,
        'stFemaleTotal' => $stFemaleTotal,
        'alternativeEducationMaleTotal' => $alternativeEducationMaleTotal,
        'alternativeEducationFemaleTotal' => $alternativeEducationFemaleTotal,
        'fullFeeMaleTotal' => $fullFeeMaleTotal,
        'fullFeeFemaleTotal' => $fullFeeFemaleTotal,
        'totalNumberMaleTotal' => $totalNumberMaleTotal,
        'totalNumberFemaleTotal' => $totalNumberFemaleTotal,
        'totalNumberTotal' => $totalNumberTotal,
      )
    );
  }

}
