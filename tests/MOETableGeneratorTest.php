<?php

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR .
  '..' . DIRECTORY_SEPARATOR . 
  'MOETableGenerator.php');

class MOEFileGeneratorTest extends PHPUnit_Framework_TestCase {

  private function getClasses() {
    return array(
      (object)array('person_id' => '114801', 'group_name' => 'Room 1', 'group_id' => '1'),
      (object)array('person_id' => '114866', 'group_name' => 'Room 1', 'group_id' => '1'),
      (object)array('person_id' => '114803', 'group_name' => 'Room 1', 'group_id' => '1'),
      (object)array('person_id' => '114804', 'group_name' => 'Room 1', 'group_id' => '1'),
      (object)array('person_id' => '114805', 'group_name' => 'Room 1', 'group_id' => '1'),
      (object)array('person_id' => '114806', 'group_name' => 'Room 1', 'group_id' => '1'),
      (object)array('person_id' => '114807', 'group_name' => 'Room 1', 'group_id' => '1'),
      (object)array('person_id' => '114808', 'group_name' => 'Room 1', 'group_id' => '1'),
      (object)array('person_id' => '114809', 'group_name' => 'Room 1', 'group_id' => '1'),
      (object)array('person_id' => '114810', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114811', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114867', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114883', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114814', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114815', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114816', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114817', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114924', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114819', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114820', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114802', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114822', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114823', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114824', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114812', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114826', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114827', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114828', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114829', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114830', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114831', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114832', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114813', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114834', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114818', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114836', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114837', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114838', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114839', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114840', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114821', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114842', 'group_name' => 'Room 2', 'group_id' => '2'),
      (object)array('person_id' => '114843', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114844', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114845', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114846', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114825', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114848', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114849', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114850', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114833', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114852', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114853', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114854', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114855', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114856', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114857', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114858', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114859', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114860', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114861', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114862', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114863', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114864', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114865', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114835', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114841', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114868', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114869', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114870', 'group_name' => 'Room 3', 'group_id' => '3'),
      (object)array('person_id' => '114871', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114873', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114874', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114876', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114877', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114881', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114882', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114847', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114884', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114851', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114888', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114889', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114890', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114892', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114893', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114921', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114922', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114923', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114887', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114926', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114927', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114928', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114929', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114931', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114932', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114933', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114934', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114937', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114942', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114944', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114945', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114946', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114947', 'group_name' => 'Room 4', 'group_id' => '4'),
      (object)array('person_id' => '114950', 'group_name' => 'Room 5', 'group_id' => '5')
    );
  }

  public function testGenerateMTables() {

    $classes = $this->getClasses();

    $filePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testMOEFiles' .
      DIRECTORY_SEPARATOR . 'M' . DIRECTORY_SEPARATOR . '12345M15.moe';

    $returnTables = MOETableGenerator\MOETableGenerator::generateTables('My School Name', $filePath, $classes);

    file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'output-M-.html', $returnTables);

    $filePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'testMOEFiles' .
      DIRECTORY_SEPARATOR . 'J' . DIRECTORY_SEPARATOR . '12345J15.moe';

    $returnTables = MOETableGenerator\MOETableGenerator::generateTables('My School Name', $filePath, $classes);

    file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'output-J-.html', $returnTables);
  }

}
