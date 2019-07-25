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
  $cat_name = $_POST['cat_name'];

  $check_column = 'cat_name';
  $check_for = array( 'cat_name' => $cat_name );
  $exists = $database->exists( 'category', $check_column,  $check_for );
  if(!$exists )
  {
    $data = array(
        'cat_name' => $cat_name
        );

        $add_query = $database->insert( 'category', $data );
  }







  }
?>
