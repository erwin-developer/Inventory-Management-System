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
  $supplier = $_POST['supplier'];
  $contact = $_POST['contact'];

  $check_column = 'supplier';
  $check_for = array( 'supplier' => $supplier );
  $exists = $database->exists( 'supplier', $check_column,  $check_for );
  if(!$exists )
  {
    $data = array(
        'supplier' => $supplier,
        'contact' => $contact
        );

        $add_query = $database->insert( 'supplier', $data );
  }







  }
?>
