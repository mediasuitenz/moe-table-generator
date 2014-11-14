<?php

namespace MOETableGenerator;

class MOETableGenerator {

  /**
   * Given the path to a .moe file returns HTML tables for printing
   * Generates tables for school types 20 and 21
   * Additionally generates audit tables
   * Tables are stored on disk as well as returned
   * @param  String $moeFilePath Path to a .moe file 
   * @return String Html
   */
  public static function generateTables($schoolName, $moeFilePath) {

    assert(is_file($moeFilePath), 'generateTables called with invalid file path');

    $moeFile = fopen($moeFilePath, 'r');

    $header = fgetcsv($moeFile);

    $month = $header[2];

    assert(in_array($month, ['M','J']), 'This library only produces tables for school types 20 and 21.');

    $students = array();
    $line = fgetcsv($moeFile);
    while ($line !== false && $line[0] !== 'Footer') {
      $students []= $line;
      $line = fgetcsv($moeFile);
    }

    $footer = array($line);
    $line = fgetcsv($moeFile);
    while ($line !== false) {
      $footer []= $line;
      $line = fgetcsv($moeFile);
    }


    fclose($moeFile);

    $smsName = $header[0];
    $smsVersion = $header[1];
    $schoolNumber = $header[4];
    $enrolmentScheme = $header[6];
    $enrolmentSchemeDate = $header[7];

    $rollReturnTables = '';
    $moeDir = dirname($moeFilePath);

    if ($month === 'M') {
      $cutoffDate = '2015-03-01';
      $m3TableHtml = M3Table::generate($schoolName, $schoolNumber, $footer);
      $rollReturnTables .= $m3TableHtml;
      //Store on disk
      file_put_contents($moeDir . DIRECTORY_SEPARATOR . 'm3Table.html', $m3TableHtml);

      $m4TableHtml = M4Table::generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students);
      $rollReturnTables .= $m4TableHtml;
      //Store on disk
      file_put_contents($moeDir . DIRECTORY_SEPARATOR . 'm4Table.html', $m4TableHtml);

      //When a school does not have a Ministry approved Enrolment scheme students should be recorded as NAPP and it is not necessary for the table (M6) to be produced.
      if ($enrolmentScheme === 'Y') {
        $m6TableHtml = M6Table::generate($smsName, $smsVersion, $schoolName, $schoolNumber, $enrolmentSchemeDate, $cutoffDate, $students);
        $rollReturnTables .= $m6TableHtml;
        //Store on disk
        file_put_contents($moeDir . DIRECTORY_SEPARATOR . 'm6Table.html', $m6TableHtml);
        
      }
      //TODO:
      // $auditTables = AuditTables::generate($cutoffDate, $students);
      // $rollReturnTables .= $auditTables;
      // file_put_contents($moeDir. DIRECTORY_SEPARATOR . 'auditTables.html', $auditTables);

    } else if ($month === 'J') {
      $cutoffDate = '2015-07-01';
      $rollReturnTables .= J3Table::generate($schoolName, $schoolNumber, $footer);
      $rollReturnTables .= J4Table::generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students);
      //TODO:
      // $rollReturnTables .= J5Table::generate($students);
      // $rollReturnTables .= J6Table::generate($students);
      // $rollReturnTables .= J7Table::generate($footer);
      // $rollReturnTables .= J9Table::generate($students);
    }

    return $rollReturnTables;
  }

}
