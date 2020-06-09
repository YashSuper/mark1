<?php
namespace Drupal\moviedb;
class FormService {

  public function getresult($sid,$webid) {
    $database = \Drupal::database();
    $query = $database->query("SELECT * FROM {webform_submission_data}
      where sid = $sid and webform_id = '$webid'");
    $arr = $query->fetchAll();
    $output=array();
    $i=0;
    foreach($arr as $value){
      $output[$i][$value->name]=$value->name;
      $output[$i]['value']=$value->value;
      $i++;
    }
    $count=0;
    if($output[0]['value']=="530") {
      $count+=1;
    }
    if($output[2]['value']=="17.571") {
      $count+=1;
    }
    if($output[1]['value']=="50") {
      $count+=1;
    }
    if($output[3]['value']=="23") {
      $count+=1;
    }
    if($count>=1) {
      return "You have given ".$count ." correct answers. So you have passed the ".$output[6]['value']." quiz";
    }
    else {
      return "You have given ".$count." correct answers. So you have failed the ".$output[6]['value']." quiz";
    }
  }

}
 ?>
