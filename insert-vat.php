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
  $vat = $_POST['vat'];
  $transaction = $_POST['transaction'];


  $check_column = 'transaction';
  $check_for = array( 'transaction' => $transaction );
  $exists = $database->exists( 'vat', $check_column,  $check_for );
  if(!$exists )
  {
    $data = array(
        'vat' => $vat,
        'transaction' => $transaction
        );
        $add_query = $database->insert( 'vat', $data );
  }else{

      $update = array(
        'vat' => $vat
      );

      $where_clause = array(
        'transaction' => $transaction
      );

      $updated = $database->update( 'vat', $update, $where_clause, 1 );

  }







  }
?>
