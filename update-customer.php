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
  $cust_id = $_POST['cust_id'];
  $fullname = $_POST['fullname'];
  $contact = $_POST['contact'];
  $address = $_POST['address'];
  $fb = $_POST['fb'];


  $update = array(
    'cust_name' => $fullname,
    'cust_address' => $address,
    'contact' => $contact,
    'fb' => $fb
  );

  $where_clause = array(
    'cust_id' => $cust_id
  );

  $updated = $database->update( 'customer', $update, $where_clause, 1 );
  }
?>
