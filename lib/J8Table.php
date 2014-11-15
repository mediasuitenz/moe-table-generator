<?php

namespace MOETableGenerator;
use DateTimeZone, DateTime;
use Handlebars\Handlebars;

class J8Table {

  public static function generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoff, $students) {

    $nzdt = new DateTimeZone('Pacific/Auckland');
    $indices = MOEIndices::getIndices();

    $cutoffDate = new DateTime($cutoff, $nzdt);

    $now = new DateTime('now', $nzdt);


    //Columns:
    // Attended Kindergarten, 
    // Play centre, Education 
    // and Care or Home Based 
    // Service 
    // EPC Attended Playcentre
    // EKE Attended Kindergarten or Education and Care
    // EHB Attended Home Based Service
    // ECO Attended the Correspondence School - Te Aho o Te Kura Pounamu

    // Attended
    // Kohanga Reo
    // EKR Attended Kohanga Reo 
    
    // Attended Playgroup 
    // or Pacific Islands EC 
    // Group 
    // EPG Attended Playgroup or Pacific Islands EC group
    
    // Attended ECE but 
    // Type Unknown 
    // (including overseas 
    // service)
    // EOS Attended, but only outside New Zealand
    // ETU Attended, but don’t know what type of service
    
    // Did not attend any 
    // type of ECE Centre or 
    // Service
    // ENA Did not attend
    
    // Unable to establish 
    // whether ECE 
    // attended or not
    // EUN Unable to establish if attended or not
    
    // Total

    $eceByEthnicity = array(
      'kind' => Ethnicity::getEthnicityRows(),
      'kohanga' => Ethnicity::getEthnicityRows(),
      'playgroup' => Ethnicity::getEthnicityRows(),
      'typeUnknown' => Ethnicity::getEthnicityRows(),
      'notAttend' => Ethnicity::getEthnicityRows(),
      'unableToEstablish' => Ethnicity::getEthnicityRows(),
      'total' => Ethnicity::getEthnicityRows()
    );

    $studentFilter = function ($cutoffDate, $student) {
      // Student TYPE in [RE, FF, EX]
      // and FIRST ATTENDANCE is <=1 July 2015
      // and LAST ATTENDANCE is Null or >=1 July 2015
      // and FUNDING YEAR LEVEL = Year 1
      $allowedTypes = ['RE', 'FF', 'EX'];
      $indices = MOEIndices::getIndices();
      $type = $student[$indices['TYPE']];
      $yearLevel = $student[$indices['FUNDING YEAR LEVEL']];
      $nzdt = new DateTimeZone('Pacific/Auckland');
      $firstAttendance = new DateTime($student[$indices['FIRST ATTENDANCE']], $nzdt);
      $lastAttendance = empty($student[$indices['LAST ATTENDANCE']]) ? null : new DateTime($student[$indices['LAST ATTENDANCE']], $nzdt);
      return (in_array($type, $allowedTypes) &&
        $yearLevel == '1' &&
        $firstAttendance->getTimestamp() <= $cutoffDate->getTimestamp() &&
        (is_null($lastAttendance) || $lastAttendance->getTimestamp() >= $cutoffDate->getTimestamp())
      );
    };

    foreach ($students as $student) {
      if($studentFilter($cutoffDate, $student)) {
        $ethnicity = Ethnicity::getEthnicity($student);
        $ece = $student[$indices['ECE']];
        $type = $student[$indices['TYPE']];
        if (empty($ece)) {
          //Assuming no ECE specified should result in 'unknown'
          $ece = 'EUN';
        }
        if ($type === 'FF') {
          $ethnicity = 'ff';
        }
        if(in_array($ece, ['EPC', 'EKE', 'EHB', 'ECO'])) {
          $eceByEthnicity['kind'][$ethnicity]++;
          $eceByEthnicity['kind']['total']++;
        } else if ($ece === 'EKR') {
          $eceByEthnicity['kohanga'][$ethnicity]++;
          $eceByEthnicity['kohanga']['total']++;
        } else if ($ece === 'EPG') {
          $eceByEthnicity['playgroup'][$ethnicity]++;
          $eceByEthnicity['playgroup']['total']++;
        } else if (in_array($ece, ['EOS', 'ETU'])) {
          $eceByEthnicity['typeUnknown'][$ethnicity]++;
          $eceByEthnicity['typeUnknown']['total']++;
        } else if ($ece === 'ENA') {
          $eceByEthnicity['notAttend'][$ethnicity]++;
          $eceByEthnicity['notAttend']['total']++;
        } else if ($ece === 'EUN') {
          $eceByEthnicity['unableToEstablish'][$ethnicity]++;
          $eceByEthnicity['unableToEstablish']['total']++;
        }

        $eceByEthnicity['total'][$ethnicity]++;
        $eceByEthnicity['total']['total']++;
      }
    }

    $handlebarsEngine = new Handlebars;
    $template = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' .
      DIRECTORY_SEPARATOR . 'tableJ8.html');
    return $handlebarsEngine->render(
      $template,
      $eceByEthnicity
    );

  }

}
