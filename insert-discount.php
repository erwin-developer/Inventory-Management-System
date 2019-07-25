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
  $discount = $_POST['discount'];
  $transaction = $_POST['transaction'];


  $check_column = 'transaction';
  $check_for = array( 'transaction' => $transaction );
  $exists = $database->exists( 'discount', $check_column,  $check_for );
  if(!$exists )
  {
    $data = array(
        'discount' => $discount,
        'transaction' => $transaction
        );
        $add_query = $database->insert( 'discount', $data );
  }else{

      $update = array(
        'discount' => $discount
      );

      $where_clause = array(
        'transaction' => $transaction
      );

      $updated = $database->update( 'discount', $update, $where_clause, 1 );

  }







  }
?>
