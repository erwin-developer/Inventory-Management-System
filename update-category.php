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
  $cat_id = $_POST['cat_id'];
  $cat_name = $_POST['cat_name'];


  $update = array(
    'cat_name' => $cat_name
  );

  $where_clause = array(
    'cat_id' => $cat_id
  );

  $updated = $database->update( 'category', $update, $where_clause, 1 );
  }
?>
