<?php

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR .
  '..' . DIRECTORY_SEPARATOR . 
  'MOETableGenerator.php');

class MOEFileGeneratorTest extends PHPUnit_Framework_TestCase {

  public function testGenerateMTables() {
    $filePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testMOEFiles' .
      DIRECTORY_SEPARATOR . 'M' . DIRECTORY_SEPARATOR . '12345M15.moe';

    $returnTables = MOETableGenerator\MOETableGenerator::generateTables('My School Name', $filePath);

    file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'output.html', $returnTables);
  }

}
