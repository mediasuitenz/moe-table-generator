<?php

namespace MOETableGenerator;

class Ethnicity {

  public static function getEthnicityRows() {
    return array(
      'maori' => 0,
      'tokelauan' => 0,
      'fijian' => 0,
      'niuean' => 0,
      'tongan' => 0,
      'cookIslandsMaori' => 0,
      'samoan' => 0,
      'otherPacific' => 0,
      'southeastAsian' => 0,
      'indian' => 0,
      'chinese' => 0,
      'otherAsian' => 0,
      'middleEastern' => 0,
      'latinAmerican' => 0,
      'african' => 0,
      'other' => 0,
      'otherEuropean' => 0,
      'newZealand' => 0,
      'ff' => 0,
      'total' => 0
    );
  }

  /**
   * Returns the highest ranked reportable ethnicity from the ethnicity hierachy
   * in 6.4 of specification
   * @param  Array $student
   * @return 
   */
  public static function getEthnicity($student) {
    $rankedEthnicCodes = array(
      '211' => 'maori',
      '351' => 'tokelauan',
      '361' => 'fijian',
      '341' => 'niuean',
      '331' => 'tongan',
      '321' => 'cookIslandsMaori',
      '311' => 'samoan',
      '371' => 'otherPacific',

      '411' => 'southeastAsian', //Filipino
      '412' => 'southeastAsian', //Cambodian
      '413' => 'southeastAsian', //Vietnamese
      '414' => 'southeastAsian', //Other Southeast Asian
      '431' => 'indian',
      '421' => 'chinese',
      '441' => 'otherAsian', //Sri Lankan
      '442' => 'otherAsian', //Japanese
      '443' => 'otherAsian', //Korean
      '444' => 'otherAsian', //Other Asian

      '511' => 'middleEastern',
      '521' => 'latinAmerican',
      '531' => 'african',

      '611' => 'other', //Other Ethnicity
      '999' => 'other', //Not stated

      '128' => 'otherEuropean', //Australian
      '121' => 'otherEuropean', //British/Irish
      '127' => 'otherEuropean', //German
      '122' => 'otherEuropean', //Dutch
      '123' => 'otherEuropean', //Greek
      '124' => 'otherEuropean', //Polish
      '125' => 'otherEuropean', //Southern slav
      '126' => 'otherEuropean', //Italian
      '129' => 'otherEuropean', //Other European
      '111' => 'newZealand'
    );

    $indices = MOEIndices::getIndices();

    $ethnic1 = $student[$indices['ETHNIC1']];
    $ethnic2 = $student[$indices['ETHNIC2']];
    $ethnic3 = $student[$indices['ETHNIC3']];
    //Iterate over ethnic codes and return the first occurrence
    foreach($rankedEthnicCodes as $code => $ethnicity) {
      if ($ethnic1 == $code ||
        $ethnic2 == $code ||
        $ethnic3 == $code) {
        return $ethnicity;
      }
    }
  }
 
}
