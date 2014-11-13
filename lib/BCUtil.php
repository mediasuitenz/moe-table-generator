<?php

namespace MOETableGenerator;

class BCUtil {

  /**
   * Given an array of numbers as strings, returns the total at the precision
   * using bcadd function
   * @param  Array $argsArray
   * @return String
   */
  public static function bctotal($argsArray, $precision) {
    $total = '0';
    foreach ($argsArray as $num) {
      $total = bcadd($total, $num, $precision);
    }
    return $total;
  }

}
