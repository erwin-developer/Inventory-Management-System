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
  $product = $_POST['product'];
  $brand = $_POST['brand'];
  $cat = $_POST['category'];



  $check_column = 'product_name';
  $check_for = array( 'product_name' => $product );
  $exists = $database->exists( 'product', $check_column,  $check_for );
  if(!$exists )
  {
    $data = array(
        'cat_id' => $cat,
        'brand_id' => $brand,
        'product_name' => $product
        );

        $add_query = $database->insert( 'product', $data );
  }







  }
?>
