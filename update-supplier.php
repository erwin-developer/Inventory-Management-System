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
  $sup_id = $_POST['sup_id'];
  $supplier = $_POST['supplier'];
  $contact = $_POST['contact'];

  $update = array(
    'supplier' => $supplier,
    'contact' => $contact
  );

  $where_clause = array(
    'sup_id' => $sup_id
  );

  $updated = $database->update( 'supplier', $update, $where_clause, 1 );
  }
?>
