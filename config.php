<?php
/**
 * @author Erwin Agpasa
 * @version 1.0
 * @date 25-Feb-2016
 **/

define( 'DB_HOST', 'localhost' );
define( 'DB_USER', 'root' );
define( 'DB_PASS', '' );
define( 'DB_NAME', 'inventory' );
define( 'SEND_ERRORS_TO', 'email@gmail.com' );
define( 'DISPLAY_DEBUG', true );

	date_default_timezone_set('Asia/Manila');

	$dt = new DateTime();
	$serverDate = $dt->format('Y-m-d');
	$server = $dt->format('Y/m/d');
?>
