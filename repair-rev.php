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







include ( 'head.inc.php' );
?>
<link type="text/css" rel="stylesheet" href="css/jquery.dataTables.min.css" />



<?php include ( 'menu.inc.php' ); ?>

       <div class="container main">
         <div class="row">
           <div class="main-col">

             <div class="col-md-12">
                   <div class="panel panel-default" style="width:600px;margin:0 auto;">
                       <div class="panel-heading">
                        <strong>REPAIR REVENUE</strong>
                       </div>
                       <div class="panel-body">
                         <br /><br />


                         <!-- tabs -->
         <div class="tabbed-widget widget">
               <div class="tab-inner">
                 <ul class="nav nav-tabs tab-count-4">
                   <li class="active"><a href="#daily" data-toggle="tab">Daily</a></li>
                   <li><a href="#weekly" data-toggle="tab">Weekly</a></li>
                   <li><a href="#monthly" data-toggle="tab">Monthly</a></li>
                   <li><a href="#all" data-toggle="tab">All</a></li>

                 </ul>

                 <div class="tab-content">
                   <div class="tab-pane active" id="daily">
                     <table data-toggle="table" id="mytable" style="margin-top:15px;" class="order-table table table-bordred table-striped">
                       <thead>
                         <tr>
                           <th>DATE</th>
                           <th>REVENUE</th>
                         </tr>
                       </thead>

                        <?php
                        echo "<tbody>";
                        $query = "SELECT *, SUM(cost) as grandtotal FROM repair GROUP BY date(date) ORDER BY date DESC";
                          $results = $database->get_results( $query );
                              foreach( $results as $row ){
                                echo "<tr>
                                      <td>".$row['date']."</td>
                                      <td>".number_format($row['grandtotal'],2,'.',',')."</td>";



                       ?>

                       </tr>


                         <?php
                             }
                         ?>

                       </tbody>
                     </table>

                   </div><!--/weekly -->

                   <div id="weekly" class="tab-pane">
                     <table data-toggle="table" id="mytable" style="margin-top:15px;" class="order-table table table-bordred table-striped">
                       <thead>
                         <tr>
                           <th>DATE</th>
                           <th>REVENUE</th>
                         </tr>
                       </thead>

                        <?php
                        echo "<tbody>";
                        $query = "SELECT *, SUM(cost) as grandtotal FROM repair GROUP BY week(date) ORDER BY date DESC";
                          $results = $database->get_results( $query );
                              foreach( $results as $row ){
                                echo "<tr>
                                      <td>".$row['date']."</td>
                                      <td>".number_format($row['grandtotal'],2,'.',',')."</td>";



                       ?>

                       </tr>


                         <?php
                             }
                         ?>

                       </tbody>
                     </table>
                   </div><!--/monthly-->

                   <div id="monthly" class="tab-pane">
                     <table data-toggle="table" id="mytable" style="margin-top:15px;" class="order-table table table-bordred table-striped">
                       <thead>
                         <tr>
                           <th>DATE</th>
                           <th>REVENUE</th>
                         </tr>
                       </thead>

                        <?php
                        echo "<tbody>";
                        $query = "SELECT *, SUM(cost) as grandtotal FROM repair GROUP BY month(date) ORDER BY date DESC";
                          $results = $database->get_results( $query );
                              foreach( $results as $row ){
                                echo "<tr>
                                      <td>".$row['date']."</td>
                                      <td>".number_format($row['grandtotal'],2,'.',',')."</td>";



                       ?>

                       </tr>


                         <?php
                             }
                         ?>

                       </tbody>
                     </table>
                   </div><!--/monthly-->



                   <div id="all" class="tab-pane">
                     <table data-toggle="table" id="mytable" style="margin-top:15px;" class="order-table table table-bordred table-striped">
                       <thead>
                         <tr>
                           <th>DATE</th>
                           <th>REVENUE</th>
                         </tr>
                       </thead>

                        <?php
                        echo "<tbody>";
                        $query = "SELECT *, SUM(cost) as grandtotal FROM repair";
                          $results = $database->get_results( $query );
                              foreach( $results as $row ){
                                echo "<tr>
                                      <td>".$row['date']."</td>
                                      <td>".number_format($row['grandtotal'],2,'.',',')."</td>";



                       ?>

                       </tr>


                         <?php
                             }
                         ?>

                       </tbody>
                     </table>
                   </div><!--/all-->


                 </div><!-- /.tab-content -->
               </div><!-- /.tab-inner -->
             </div>
         <!-- /tabs -->




         </div><!-- panel-body -->
     </div><!-- panel -->
     <!--
     <div class="col-md-12 text-right">
       <small>LEGEND:</small> <span class="label label-danger">Sold</span>
     </div>
   -->
     </div><!-- col-md-7 -->






             <div class="clearfix"></div>
             </div><!--main-col-->
           </div><!--/row-->
         </div><!--/container-->


      <div class="clearfix"></div>
      <?php include ( 'footer.inc.php' ); ?>


      <div class="modal fade" id="cart" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">PRODUCT INFORMATION</h4>
                  </div>
                  <div class="modal-body">
                      <div class="cart-data"></div>
                  </div>
              </div>
          </div>
      </div>



      <!-- Bootstrap core JavaScript -->
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/bootstrap.js"></script>

      <script type="text/javascript" src="js/moment.min.js"></script>
      <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>

      <script type="text/javascript">



      $(document).ready(function() {

        $('#cart').on('show.bs.modal', function (e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type : 'post',
                url : 'fetch_cart.php',
                data :  'rowid='+ rowid,
                success : function(data){
                $('.cart-data').html(data);
                }
            });
         });

         $('#cart').on('hidden.bs.modal', function () {
            window.location.reload(true);
            })



          $('#example').DataTable( {
              "scrollY":        "300px",
              "ordering": true,
              "scrollCollapse": true,
              "paging":         false,
              "searching": true
          } );




      });

    </script>
    </body>
 </html>
