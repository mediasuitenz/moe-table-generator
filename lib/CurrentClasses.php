<?php

namespace MOETableGenerator;

use DateTime, DateTimeZone;

class CurrentClasses {

  /**
   * Generates zoning status for schools with a valid enrolment scheme
   * @param  String $smsName
   * @param  String $smsVersion
   * @param  String $schoolName
   * @param  String $schoolNumber
   * @param  String $enrolmentSchemeDate (Ymd)
   * @param  String $cutoff - The cutoff date (Y-m-d) for this roll return table
   * @param  Array $students 
   * @return String
   */
  public static function checkClasses( $students, $indices){
    
   

foreach ($students as $student) {

     $ids[] = $student[$indices['STUDENT_ID']];

  
    }

global $wpdb;
  $groups_table = $wpdb->prefix."groups";
  $group_relationships_table = $wpdb->prefix."group_relationships";
    $user_table = $wpdb->prefix."users";
     $usermeta_table = $wpdb->prefix."usermeta";


$classes = $wpdb->get_row($wpdb->prepare("select $group_relationships_table .`person_id`, $groups_table .`group_id` AS `group_id`,$groups_table .`user_id` AS `user_id`, CASE WHEN $usermeta_table.meta_key = 'first_name' then $usermeta_table.meta_value end AS `teacher_first`, CASE WHEN $usermeta_table.meta_key = 'last_name' then $usermeta_table.meta_value end AS `teacher_last`,  $groups_table .`room` AS `room`,$groups_table .`year` AS `year`,$groups_table .`type` AS `type`,$groups_table .`group_name` AS `group_name`,$groups_table .`Team` AS `Team`,$groups_table .`team_order` AS `team_order`,$groups_table .`YearGroup` AS `YearGroup`,$groups_table .`ref_id` AS `ref_id`,$groups_table .`shared` AS `shared` from $groups_table left join $group_relationships_table on $groups_table .`group_id` = $group_relationships_table.group_id 
left join $usermeta_table on $groups_table.`user_id` = $usermeta_table.user_id where ($groups_table .`type` = 'Class') and $group_relationships_table.person_id = 11",'' ));


return $classes; 

  }

}
