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
unset($_SESSION['joborder']);

    $query = "SELECT * FROM employee WHERE id = $ses_id";
      $results = $database->get_results( $query );
      foreach( $results as $row ){
      $employee = $row['fullname'];
      }





        if (isset($_POST['formsubmitted'])) {
          $error = array();

          if (empty($_POST['brand'])) {
            $error[] = 'Please select brand';
              } else {
              $brand = $database->filter($_POST['brand']);
              }

          if (empty($_POST['category'])) {
            $error[] = 'Please select category';
              } else {
              $category = $database->filter($_POST['category']);
              }


        if (empty($error)) {

                header("Location: add-product.php?cat=$category&brand=$brand");
                exit();



          } else {
          foreach ($error as $key => $values) {
            $showMsg = $values;
            }
          }
      } # End of the main Submit conditional.




include ( 'head.inc.php' );
?>
<link type="text/css" rel="stylesheet" href="css/jquery.dataTables.min.css" />



<?php include ( 'menu.inc.php' ); ?>

       <div class="container main">
         <div class="row">
           <div class="main-col">

             <div class="col-md-12">
                   <div class="panel panel-default">
                       <div class="panel-heading">
                        <strong>SERVICES TRANSACTION</strong>
                       </div>
                       <div class="panel-body">



                         <table id="example" class="nowrap dataTable" cellspacing="0" width="100%">
                                 <thead>
                                     <tr>
                                         <th>TRANSACTION #</th>
                                         <th>CUSTOMER</th>
                                         <th>SYSTEM UNIT</th>
                                         <th>SERVICES TYPE</th>
                                         <th class="text-center">SERVICES COST</th>
                                         <th></th>
                                     </tr>
                                 </thead>

                                 <tbody>
                                   <?php
                                   $query = "SELECT * FROM services
                                   INNER JOIN customer ON customer.cust_id = services.cust_id
                                   ";
                                       $results = $database->get_results( $query );
                                         foreach( $results as $row ){
                                   ?>
                                     <tr>
                                    <td><?php echo $row['transaction'];?></td>
                                    <td><?php echo $row['cust_name'];?></td>
                                    <td><?php echo $row['sys_unit'];?></td>
                                    <td><?php echo $row['service'];?></td>
                                    <td class="text-center"><?php echo number_format($row['cost'],2,'.',',');?></td>

                                    <td>
                                      <a href="services-invoice.php?id=<?php echo $row['transaction']; ?>">
                                        <i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                                      </td>




                                     </tr>
                                     <?php } ?>
                                 </tbody>
                             </table>


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
