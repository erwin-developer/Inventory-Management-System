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
  $p_id = $_POST['p_id'];
  $brand = $_POST['brand'];
  $category = $_POST['category'];
  $product = $_POST['product'];


  $update = array(
    'product_name' => $product,
    'brand_id' => $brand,
    'cat_id' => $cat
  );

  $where_clause = array(
    'p_id' => $p_id
  );

  $updated = $database->update( 'product', $update, $where_clause, 1 );
  }
?>
