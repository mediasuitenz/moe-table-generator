<?php

namespace MOETableGenerator;

class MOETableGenerator {

  /**
   * Given the path to a .moe file returns HTML tables for printing
   * Generates tables for school types 20 and 21
   * Additionally generates audit tables
   * Tables are stored on disk as well as returned
   * @param  String $schoolName Name of the school the .moe file belongs to
   * @param  String $moeFilePath Path to a .moe file 
   * @return String Html
   */
  public static function generateTables($schoolName, $moeFilePath, $classes) {

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

      $m4TableHtml = MaoriLanguageTable::generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students, $month);
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
    } else if ($month === 'J') {
      $cutoffDate = '2015-07-01';
      $j3Table = J3Table::generate($schoolName, $schoolNumber, $footer);
      $rollReturnTables .= $j3Table;
      file_put_contents($moeDir . DIRECTORY_SEPARATOR . 'j3Table.html', $j3Table);
      $j4Table = J4Table::generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students);
      $rollReturnTables .= $j4Table;
      file_put_contents($moeDir . DIRECTORY_SEPARATOR . 'j4Table.html', $j4Table);
      $j5Table = J5Table::generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students);
      $rollReturnTables .= $j5Table;
      file_put_contents($moeDir . DIRECTORY_SEPARATOR . 'j5Table.html', $j5Table);
      $j6Table = J6Table::generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students);
      $rollReturnTables .= $j6Table;
      file_put_contents($moeDir . DIRECTORY_SEPARATOR . 'j6Table.html', $j6Table);
      $j7Table = MaoriLanguageTable::generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students, $month);
      $rollReturnTables .= $j7Table;
      file_put_contents($moeDir . DIRECTORY_SEPARATOR . 'j7Table.html', $j7Table);
      $j8Table = J8Table::generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students);
      $rollReturnTables .= $j8Table;
      file_put_contents($moeDir . DIRECTORY_SEPARATOR . 'j8Table.html', $j8Table);
      $j9Table = J9Table::generate($smsName, $smsVersion, $schoolName, $schoolNumber, $cutoffDate, $students);
      $rollReturnTables .= $j9Table;
      file_put_contents($moeDir . DIRECTORY_SEPARATOR . 'j9Table.html', $j9Table);
    }

    $fullSchoolAuditData = FullSchoolAuditTable::generateData($students, $classes, $cutoffDate);
    $fullSchoolAuditHtml = FullSchoolAuditTable::generateHtml($fullSchoolAuditData, $schoolName, $schoolNumber, $cutoffDate);

    $rollReturnTables .= $fullSchoolAuditHtml;

    //Write CSV
    $fullAuditFileName = $moeDir . DIRECTORY_SEPARATOR . 'FullSchoolAuditTable.csv';
    FullSchoolAuditTable::writeCsv($fullSchoolAuditData, $schoolName, $schoolNumber, $cutoffDate, $fullAuditFileName);

    return $rollReturnTables;
  }

}
