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
  public static function generateTables($moeFilePath) {

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


    $rollReturnTables = '';

    if ($month === 'M') {
      $cutoffDate = '2015-03-01';
      $rollReturnTables .= M3Table::generate($footer);
      $rollReturnTables .= M4Table::generate($cutoffDate, $students);
      // $rollReturnTables .= M6Table::generate($students);
      // $rollReturnTables .= AuditTables::generate($somedata)
    } else if ($month === 'J') {
      //TODO:
      // $rollReturnTables .= J3Table::generate($footer);
      // $rollReturnTables .= J4Table::generate($students);
      // $rollReturnTables .= J5Table::generate($students);
      // $rollReturnTables .= J6Table::generate($students);
      // $rollReturnTables .= J7Table::generate($footer);
      // $rollReturnTables .= J9Table::generate($students);
    }

    return $rollReturnTables;
  }

}
