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

$transaction = $_SESSION['transaction'];

if($_POST) {
  $s_id = $_POST['stock'];
  $product_id = $_POST['product_id'];
  $serial = $_POST['serial'];


  $check_column = 'stock_id';
  $check_for = array( 'stock_id' => $s_id );
  $exists = $database->exists( 'cart', $check_column,  $check_for );
  if(!$exists )
  {
    $data = array(
        'transaction' => $transaction,
        'stock_id' => $s_id,
        'product_id' => $product_id,
        'p_serial' => $serial
        );

        $add_query = $database->insert( 'cart', $data );

        //Updating availability to 0 and then add into cart table
        $update = array('availability' => 0);
        $where_clause = array('s_id' => $_POST['stock']);
        $updated = $database->update( 'stock', $update, $where_clause, 1 );





  }







  }
?>
