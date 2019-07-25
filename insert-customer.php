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
  $fullname = $_POST['fullname'];
  $address = $_POST['address'];
  $contact = $_POST['contact'];
  $fb = $_POST['fb'];


  $check_column = 'cust_name';
  $check_for = array('cust_name' => $fullname);
  $exists = $database->exists( 'customer', $check_column,  $check_for );
  if(!$exists )
  {
    $data = array(
      'cust_name' => $fullname,
      'cust_address' => $address,
      'contact' => $contact,
      'fb' => $fb
        );

        $add_query = $database->insert( 'customer', $data );
  }







  }
?>
