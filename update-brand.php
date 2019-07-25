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
  $brand_id = $_POST['brand_id'];
  $brand_name = $_POST['brand_name'];
  $cat = $_POST['category'];



  $update = array(
    'brand_name' => $brand_name,
    'cat_id' => $cat
  );

  $where_clause = array(
    'brand_id' => $brand_id
  );

  $updated = $database->update( 'brand', $update, $where_clause, 1 );
  }
?>
