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

if(!isset($_SESSION['id'])){
     header("Location: signin.php");
        exit();
    }


$ses_id = $_SESSION['id'];
$query = "SELECT * FROM employee WHERE id = $ses_id";
  $results = $database->get_results( $query );
  foreach( $results as $row ){
  $employee = $row['fullname'];
  }
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="PHP/MySQL | INVENTORY MANAGEMENT SYSTEM">
  <meta name="author" content="Erwin Agpasa">
    <link rel="icon" href="favicon.ico">
    <title>PHP/MySQL | INVENTORY MANAGEMENT SYSTEM</title>
    <!-- Bootstrap core CSS -->
    <link href="assets/css/xcharts.min.css" rel="stylesheet">
		<link href="assets/css/style.css" rel="stylesheet">
		<!-- Include bootstrap css -->
		<link href="assets/css/daterangepicker.css" rel="stylesheet">

    <link type="text/css" rel="stylesheet" href="css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="css/font-awesome.css" />
    <link href="assets/css/daterangepicker.css" rel="stylesheet">
  	<link type="text/css" rel="stylesheet" href="css/global.css" />

	  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
    <body>
    <?php include ( 'menu.inc.php' ); ?>


              <div class="container main">
                <div class="row">
                  <div class="main-col">
                       <div class="col-xs-12 col-sm-3 col-md-3">
                         <div class="row">
                                   <div class="col-sm-12" style="margin-top:15px;">
                                     <div class="col-sm-3">
                                     <div class="round round-lg hollow blue">
                                     <span class="glyphicon glyphicon-user"></span>
                                     </div>
                                   </div>
                                   <div class="col-sm-9">
                                     <?php
                                     $query_c = "SELECT count(*) as total
                                     FROM `order_item`";
                                     $resultsc = $database->get_results( $query_c );
                                     foreach( $resultsc as $rowc )
                                     {
                                         echo "<p><strong>".$rowc['total']."</strong><br /><small>Sales Transaction</small></p>";


                                     }


                                     ?>
                                   </div>
                                    </div>




                                   <div class="col-sm-12" style="margin-top:25px;">
                                     <div class="col-sm-3">
                                      <div class="round round-lg hollow orange">
                                        <span class="glyphicon glyphicon-briefcase"></span>
                                      </div>
                                    </div>
                                    <div class="col-sm-9">
                                     <p><strong>Php <?php

                                       $queryx = "SELECT SUM(cost) as costx FROM services";
                                       $resultsx = $database->get_results( $queryx );
                                       foreach( $resultsx as $rowx )
                                       {
                                          $services_cost = $rowx['costx'];
                                       }

                                       $queryx = "SELECT SUM(cost) as costx FROM repair";
                                       $resultsx = $database->get_results( $queryx );
                                       foreach( $resultsx as $rowx )
                                       {
                                          $repair_cost = $rowx['costx'];
                                       }


                                        $queryx = "SELECT SUM(grand_total) as revenue FROM order_item";
                                        $resultsx = $database->get_results( $queryx );
                                        foreach( $resultsx as $rowx )
                                        {
                                           $sale_item = $rowx['revenue'];
                                        }
                                              $final_cost = $services_cost + $sale_item + $repair_cost;

                                           echo number_format($final_cost,2,'.',',');

                                     ?></strong><br /><small>Revenue</small></p>
                                    </div>
                                   </div>



                                 <div class="col-sm-12" style="margin-top:25px;">
                                  <div class="col-sm-3">
                                   <div class="round round-lg hollow green">
                                     <span class="glyphicon glyphicon-headphones"></span>
                                   </div>
                                 </div>
                                 <div class="col-sm-9">
                                  <p><strong>0915-000-0000</strong><br /><small>Support Center</small></p>
                                 </div>
                                </div>
                         </div><!-- row-->
                       </div><!-- col-xs-3 col-sm-3 col-md-3 -->
                <div class="col-xs-12 col-sm-9 col-md-9">
                  <form class="form-horizontal">
		                <div class="input-prepend">
                    <div class='input-group date' style="margin-bottom:15px;width:280px;">
		                <input type="text" name="range" id="range" class="form-control input-lg" style="width:260px;" />
                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span></div>
		                </div>
                  </form>

            			<div id="placeholder">
            				<figure id="chart"></figure>
            			</div>
              </div><!-- col-xs-12 col-sm-9 col-md-9 -->

              <div class="clearfix"></div>

              <div class="panel panel-default" style="margin-top:50px;">
                  <div class="panel-heading"><strong>TODAY'S SALES TRANSACTION</strong>

                    <div class="pull-right" style="margin-top:0px;">
                      <?php //echo $server; ?>
                    </div>

                 </div>
                  <div class="panel-body">

                    <div class="filterable">
                    <div class="table-responsive">
                    <table id="mytable" class="order-table table table-bordred table-striped">
                      <thead>
                        <tr>
                        <th>TRANSACTION</th>
                        <th>CUSTOMER</th>
                        <th>TOTAL COST</th>
                        <th></th>
                      </tr>
                      </thead>

                      <?php

                      echo "<tbody>";

                      $query = "SELECT * FROM order_item
                      INNER JOIN customer ON customer.cust_id = order_item.cust_id
                      WHERE `date` = '$server'";
                      $results = $database->get_results( $query );
                      foreach( $results as $row ){


                      echo "<tr>
                            <td>".$row['transaction']."</td>
                            <td>".$row['cust_name']."</td>
                            <td>Php ".number_format($row['grand_total'],2,'.',',')."</td>";
                            echo'<td><a href="view-invoice.php?id='.$row['transaction'].'">
                            <i class="fa fa-pencil-square-o"></i></a></td>';

                      ?>
                      </tr>

                      <?php
                          }
                      ?>
                    </tbody>
                   </table>
                 </div>
              </div>

                </div><!-- panel-body -->
             </div><!-- panel -->




  <div class="clearfix"></div>
  </div><!--main-col-->
</div><!--/row-->
</div><!--/container-->



    <div class="clearfix"></div>
    <?php include ( 'footer.inc.php' ); ?>





          <div class="modal fade" id="myModal" role="dialog">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Edit Booking Information</h4>
                      </div>
                      <div class="modal-body">


                          <div class="fetched-data"></div>


                      </div>
                  </div>
              </div>
          </div>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- The daterange picker bootstrap plugin -->
		<script src="assets/js/sugar.min.js"></script>
		<script src="assets/js/daterangepicker.js"></script>



	   <!-- xcharts includes -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/d3/2.10.0/d3.v2.js"></script>
		<script src="assets/js/xcharts.min.js"></script>
		<!-- Our main script file -->
		<script src="assets/js/script.js"></script>


    <?php

    // Set up the ORM library
    require_once('setup.php');
/*
    try{
    	// Insert records for the last 30 days for demo purposes.
    	// Delete this block if you want to disable this functionality.

    	for($i = 0; $i < 30; $i++){
    		$sales = ORM::for_table('chart_sales')->create();
    		$sales->date = date('Y-m-d', time() - $i*24*60*60);
    		$sales->sales_order = rand(0, 100);
    		$sales->save();
    	}

    }
    catch(PDOException $e){}
*/
    ?>


    <script type="text/javascript" src="js/moment.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>

    <script type="text/javascript">


     $(document).ready(function() {
         $('#myModal').on('show.bs.modal', function (e) {
             var rowid = $(e.relatedTarget).data('id');
             $.ajax({
                 type : 'post',
                 url : 'fetch_book.php',
                 data :  'rowid='+ rowid,
                 success : function(data){
                 $('.fetched-data').html(data);
                 }
             });
          });
     });

     $('#myModal').on('hidden.bs.modal', function () {
       window.location.reload(true);
       })
       </script>
    </body>
</html>
