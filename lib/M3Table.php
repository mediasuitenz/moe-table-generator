<?php

namespace MOETableGenerator;
use Handlebars\Handlebars;

class M3Table {

  /**
   * Given the footer of a .moe file returns the HMTL
   * for Table M3 School Roll by Type of Student and Funding Year Level
   * @param  Array $moeFooter
   * @return String
   */
  public function generate($moeFooter) {

    //Perform calculations on footer

    //FR students go from years 1 to 15
    $fullRegularMale = array_slice($moeFooter[1], 1, 15);
    $fullRegularMaleTotal = array_sum($fullRegularMale);
    $fullRegularFemale = array_slice($moeFooter[1], 16, 30);
    $fullRegularFemaleTotal = array_sum($fullRegularFemale);

    //PR students go from years 9 to 15
    $partTimeRegularMale = array_slice($moeFooter[2], 1, 7);
    $partTimeRegularMaleTotal = self::bcTotal($partTimeRegularMale, 1);

    $partTimeRegularFemale = array_slice($moeFooter[2], 8, 14);
    $partTimeRegularFemaleTotal = self::bcTotal($partTimeRegularFemale, 1);

    //FA students go from years 9 to 15
    $fullAdultMale = array_slice($moeFooter[3], 1, 7);
    $fullAdultMaleTotal = array_sum($fullAdultMale);
    $fullAdultFemale = array_slice($moeFooter[3], 8, 14);
    $fullAdultFemaleTotal = array_sum($fullAdultFemale);

    //PA students go from years 9 to 15
    $partTimeAdultMale = array_slice($moeFooter[4], 1, 7);
    $partTimeAdultMaleTotal = self::bcTotal($partTimeAdultMale, 1);

    $partTimeAdultFemale = array_slice($moeFooter[4], 8, 14);
    $partTimeAdultFemaleTotal = self::bcTotal($partTimeAdultFemale, 1);

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
      '8' => self::bcTotal([
        $fullRegularMale[8], 
        $partTimeRegularMale[0],
        $fullAdultMale[0],
        $partTimeAdultMale[0]
      ], 1),
      '9' => self::bcTotal([
        $fullRegularMale[9], 
        $partTimeRegularMale[1],
        $fullAdultMale[1],
        $partTimeAdultMale[1]
      ], 1),
      '10' => self::bcTotal([
        $fullRegularMale[10], 
        $partTimeRegularMale[2],
        $fullAdultMale[2],
        $partTimeAdultMale[2]
      ], 1),
      '11' => self::bcTotal([
        $fullRegularMale[11], 
        $partTimeRegularMale[3],
        $fullAdultMale[3],
        $partTimeAdultMale[3]
      ], 1),
      '12' => self::bcTotal([
        $fullRegularMale[12], 
        $partTimeRegularMale[4],
        $fullAdultMale[4],
        $partTimeAdultMale[4]
      ], 1),
      '13' => self::bcTotal([
        $fullRegularMale[13], 
        $partTimeRegularMale[5],
        $fullAdultMale[5],
        $partTimeAdultMale[5]
      ], 1),
      '14' => self::bcTotal([
        $fullRegularMale[14], 
        $partTimeRegularMale[6],
        $fullAdultMale[6],
        $partTimeAdultMale[6]
      ], 1),
    );

    $ministryFundedStudentsMaleTotal = self::bcTotal($ministryFundedStudentsMale, 1);

    $ministryFundedStudentsFemale = array(
      '0' => $fullRegularFemale[0],
      '1' => $fullRegularFemale[1],
      '2' => $fullRegularFemale[2],
      '3' => $fullRegularFemale[3],
      '4' => $fullRegularFemale[4],
      '5' => $fullRegularFemale[5],
      '6' => $fullRegularFemale[6],
      '7' => $fullRegularFemale[7],
      '8' => self::bcTotal([
        $fullRegularFemale[8], 
        $partTimeRegularFemale[0],
        $fullAdultFemale[0],
        $partTimeAdultFemale[0]
      ], 1),
      '9' => self::bcTotal([
        $fullRegularFemale[9], 
        $partTimeRegularFemale[1],
        $fullAdultFemale[1],
        $partTimeAdultFemale[1]
      ], 1),
      '10' => self::bcTotal([
        $fullRegularFemale[10], 
        $partTimeRegularFemale[2],
        $fullAdultFemale[2],
        $partTimeAdultFemale[2]
      ], 1),
      '11' => self::bcTotal([
        $fullRegularFemale[11], 
        $partTimeRegularFemale[3],
        $fullAdultFemale[3],
        $partTimeAdultFemale[3]
      ], 1),
      '12' => self::bcTotal([
        $fullRegularFemale[12], 
        $partTimeRegularFemale[4],
        $fullAdultFemale[4],
        $partTimeAdultFemale[4]
      ], 1),
      '13' => self::bcTotal([
        $fullRegularFemale[13], 
        $partTimeRegularFemale[5],
        $fullAdultFemale[5],
        $partTimeAdultFemale[5]
      ], 1),
      '14' => self::bcTotal([
        $fullRegularFemale[14], 
        $partTimeRegularFemale[6],
        $fullAdultFemale[6],
        $partTimeAdultFemale[6]
      ], 1),
    );

    $ministryFundedStudentsFemaleTotal = self::bcTotal($ministryFundedStudentsFemale, 1);

    //ST students go from years 9 to 15
    $stMale = array_slice($moeFooter[5], 1, 7);
    $stMaleTotal = self::bcTotal($stMale, 1);

    $stFemale = array_slice($moeFooter[5], 8, 14);
    $stFemaleTotal = self::bcTotal($stFemale, 1);

    //AE students fo from years 9 to 15
    $alternativeEducationMale = array_slice($moeFooter[6], 1, 7);
    $alternativeEducationMaleTotal = self::bcTotal($alternativeEducationMale, 1);

    $alternativeEducationFemale = array_slice($moeFooter[6], 8, 14);
    $alternativeEducationFemaleTotal = self::bcTotal($alternativeEducationFemale, 1);

    ///FF students go from years 1 to 15
    $fullFeeMale = array_slice($moeFooter[7], 1, 15);
    $fullFeeMaleTotal = self::bcTotal($fullFeeMale, 1);

    $fullFeeFemale = array_slice($moeFooter[7], 16, 30);
    $fullFeeFemaleTotal = self::bcTotal($fullFeeFemale, 1);

    //Calculate totals across columns 5 to 8
    $totalFTEMale = array(
      '0' => bcadd($ministryFundedStudentsMale[0], $fullFeeMale[0], 1),
      '1' => bcadd($ministryFundedStudentsMale[1], $fullFeeMale[1], 1),
      '2' => bcadd($ministryFundedStudentsMale[2], $fullFeeMale[2], 1),
      '3' => bcadd($ministryFundedStudentsMale[3], $fullFeeMale[3], 1),
      '4' => bcadd($ministryFundedStudentsMale[4], $fullFeeMale[4], 1),
      '5' => bcadd($ministryFundedStudentsMale[5], $fullFeeMale[5], 1),
      '6' => bcadd($ministryFundedStudentsMale[6], $fullFeeMale[6], 1),
      '7' => bcadd($ministryFundedStudentsMale[7], $fullFeeMale[7], 1),
      '8' => self::bcTotal([
        $ministryFundedStudentsMale[8], 
        $stMale[0],
        $alternativeEducationMale[0],
        $fullFeeMale[8]
      ], 1),
      '9' => self::bcTotal([
        $ministryFundedStudentsMale[9], 
        $stMale[1],
        $alternativeEducationMale[1],
        $fullFeeMale[9]
      ], 1),
      '10' => self::bcTotal([
        $ministryFundedStudentsMale[10], 
        $stMale[2],
        $alternativeEducationMale[2],
        $fullFeeMale[10]
      ], 1),
      '11' => self::bcTotal([
        $ministryFundedStudentsMale[11], 
        $stMale[3],
        $alternativeEducationMale[3],
        $fullFeeMale[11]
      ], 1),
      '12' => self::bcTotal([
        $ministryFundedStudentsMale[12], 
        $stMale[4],
        $alternativeEducationMale[4],
        $fullFeeMale[12]
      ], 1),
      '13' => self::bcTotal([
        $ministryFundedStudentsMale[13], 
        $stMale[5],
        $alternativeEducationMale[5],
        $fullFeeMale[13]
      ], 1),
      '14' => self::bcTotal([
        $ministryFundedStudentsMale[14], 
        $stMale[6],
        $alternativeEducationMale[6],
        $fullFeeMale[14]
      ], 1),
    );

    $totalFTEMaleTotal = self::bcTotal($totalFTEMale, 1);

    //Calculate totals across columns 5 to 8
    $totalFTEFemale = array(
      '0' => bcadd($ministryFundedStudentsFemale[0], $fullFeeFemale[0], 1),
      '1' => bcadd($ministryFundedStudentsFemale[1], $fullFeeFemale[1], 1),
      '2' => bcadd($ministryFundedStudentsFemale[2], $fullFeeFemale[2], 1),
      '3' => bcadd($ministryFundedStudentsFemale[3], $fullFeeFemale[3], 1),
      '4' => bcadd($ministryFundedStudentsFemale[4], $fullFeeFemale[4], 1),
      '5' => bcadd($ministryFundedStudentsFemale[5], $fullFeeFemale[5], 1),
      '6' => bcadd($ministryFundedStudentsFemale[6], $fullFeeFemale[6], 1),
      '7' => bcadd($ministryFundedStudentsFemale[7], $fullFeeFemale[7], 1),
      '8' => self::bcTotal([
        $ministryFundedStudentsFemale[8], 
        $stFemale[0],
        $alternativeEducationFemale[0],
        $fullFeeFemale[8]
      ], 1),
      '9' => self::bcTotal([
        $ministryFundedStudentsFemale[9], 
        $stFemale[1],
        $alternativeEducationFemale[1],
        $fullFeeFemale[9]
      ], 1),
      '10' => self::bcTotal([
        $ministryFundedStudentsFemale[10], 
        $stFemale[2],
        $alternativeEducationFemale[2],
        $fullFeeFemale[10]
      ], 1),
      '11' => self::bcTotal([
        $ministryFundedStudentsFemale[11], 
        $stFemale[3],
        $alternativeEducationFemale[3],
        $fullFeeFemale[11]
      ], 1),
      '12' => self::bcTotal([
        $ministryFundedStudentsFemale[12], 
        $stFemale[4],
        $alternativeEducationFemale[4],
        $fullFeeFemale[12]
      ], 1),
      '13' => self::bcTotal([
        $ministryFundedStudentsFemale[13], 
        $stFemale[5],
        $alternativeEducationFemale[5],
        $fullFeeFemale[13]
      ], 1),
      '14' => self::bcTotal([
        $ministryFundedStudentsFemale[14], 
        $stFemale[6],
        $alternativeEducationFemale[6],
        $fullFeeFemale[14]
      ], 1),
    );

    $totalFTEFemaleTotal = self::bcTotal($totalFTEFemale, 1);

    $totalFTE = array();
    for($i = 0; $i < 15; $i++) {
      $totalFTE[$i] = bcadd($totalFTEMale[$i], $totalFTEFemale[$i], 1);
    }

    $totalFTETotal = self::bcTotal($totalFTE, 1);

    $handlebarsEngine = new Handlebars;
    $template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' .
      DIRECTORY_SEPARATOR . 'tableM3.html');
    return $handlebarsEngine->render(
      $template,
      array(
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
        'totalFTEMale' => $totalFTEMale,
        'totalFTEFemale' => $totalFTEFemale,
        'totalFTE' => $totalFTE,

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
        'totalFTEMaleTotal' => $totalFTEMaleTotal,
        'totalFTEFemaleTotal' => $totalFTEFemaleTotal,
        'totalFTETotal' => $totalFTETotal,
      )
    );
  }

  /**
   * Given an array of numbers as strings, returns the total at the precision
   * using bcadd function
   * @param  Array $argsArray
   * @return String
   */
  private static function bcTotal($argsArray, $precision) {
    $total = '0';
    foreach ($argsArray as $num) {
      $total = bcadd($total, $num, $precision);
    }
    return $total;
  }


}
