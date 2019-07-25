<?php
/**
 * @author Erwin Agpasa
 * @version 1.0
 * @date 25-Feb-2016
 **/
session_start();
include ( 'config.php' );
require_once( 'class.db.php' );

$database = DB::getInstance();

if($_POST) {
  $brand_name = $_POST['brand_name'];
  $cat = $_POST['category'];

/*

  $check_column = 'brand_name';
  $check_for = array( 'brand_name' => $brand_name );
  $exists = $database->exists( 'brand', $check_column,  $check_for );
  if(!$exists )

  {
*/
    $data = array(

        'brand_name' => $brand_name,
        'cat_id' => $cat
        );

        $add_query = $database->insert( 'brand', $data );
  //}







  }
?>
