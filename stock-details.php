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

if (isset($_GET['cat']) && isset($_GET['brand'])) {
    $get_cat = $_GET['cat'];
    $get_brand = $_GET['brand'];
    $get_product = $_GET['product'];

  }else{
  header("Location: stock.php");
     exit();
   }

$transaction = $_SESSION['transaction'];
$ses_id = $_SESSION['id'];

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
                         <?php
                         $query = "SELECT * FROM stock
                         INNER JOIN category ON category.cat_id = stock.cat_id
                         INNER JOIN brand ON brand.brand_id = stock.brand_id
                         INNER JOIN product ON product.p_id = stock.p_id
                         WHERE stock.cat_id = $get_cat AND stock.brand_id = $get_brand AND stock.p_id = $get_product LIMIT 1";
                             $results = $database->get_results( $query );
                               foreach( $results as $row ){
                         ?>

                            <div class="row">

                              <div class="col-xs-4 col-sm-4 col-md-4">
                                 <div class="form-group">
                                   <label class="control-label">CATEGORY</label>
                                   <select name="product" class="form-control">
                                       <option value="0"><?php echo $row['cat_name'];?></option>
                                  </select>
                                </div>
                              </div>


                              <div class="col-xs-4 col-sm-4 col-md-4">
                                 <div class="form-group">
                                   <label class="control-label">BRAND</label>
                                   <select name="product" class="form-control">
                                       <option value="0"><?php echo $row['brand_name'];?></option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-xs-4 col-sm-4 col-md-4">
                                 <div class="form-group">
                                   <label class="control-label">PRODUCT NAME</label>
                                   <select name="product" class="form-control">
                                       <option value="0"><?php echo $row['product_name'];?></option>
                                  </select>
                                </div>
                              </div>


                         </div><!-- row -->
                         <?php
                          }
                          ?>

                       </div>
                       <div class="panel-body">



                         <table id="example" class="nowrap dataTable" cellspacing="0" width="100%">
                                 <thead>
                                     <tr>
                                         <th>SERIAL #</th>
                                         <th>PART #</th>
                                         <th>MODEL</th>
                                         <th>SUPPLIER</th>
                                         <th>PRICE</th>
                                         <th>SRP</th>
                                         <th>WNTY</th>
                                         <th></th>
                                     </tr>
                                 </thead>

                                 <tbody>
                                   <?php
                                   $query = "SELECT * FROM stock
                                   INNER JOIN category ON category.cat_id = stock.cat_id
                                   INNER JOIN brand ON brand.brand_id = stock.brand_id
                                   INNER JOIN product ON product.p_id = stock.p_id
                                   INNER JOIN supplier ON supplier.sup_id = stock.sup_id
                                   INNER JOIN warranty ON warranty.wnty_id = stock.wnty_id
                                   WHERE stock.cat_id = $get_cat AND stock.brand_id = $get_brand AND stock.p_id = $get_product";
                                       $results = $database->get_results( $query );
                                         foreach( $results as $row ){
                                   ?>
                                     <tr>
                                    <?php
                                    if ($row['availability'] == 0 ) {
                                    ?>
                                    <td style="background:#efb6b6;color:#333;"><?php echo $row['serial'];?></td>
                                    <td style="background:#efb6b6;color:#333;"><?php echo $row['part'];?></td>
                                    <td style="background:#efb6b6;color:#333;"><?php echo $row['model'];?></td>
                                    <td style="background:#efb6b6;color:#333;"><?php echo $row['supplier'];?></td>
                                    <td style="background:#efb6b6;color:#333;"><?php echo number_format($row['price'],2,'.',',');?></td>
                                    <td style="background:#efb6b6;color:#333;"><?php echo number_format($row['srp'],2,'.',',');?></td>
                                    <td style="background:#efb6b6;color:#333;"><?php echo $row['warranty'];?></td>
                                    <td style="background:#efb6b6;color:#333;"><a href="#cart" data-toggle="modal" data-id="<?php echo $row['s_id'];?>"><i class="fa fa-shopping-basket" aria-hidden="true"></i></a></td>
                                    <?php
                                    } else {
                                    ?>
                                    <td><?php echo $row['serial'];?></td>
                                    <td><?php echo $row['part'];?></td>
                                    <td><?php echo $row['model'];?></td>
                                    <td><?php echo $row['supplier'];?></td>
                                    <td><?php echo number_format($row['price'],2,'.',',');?></td>
                                    <td><?php echo number_format($row['srp'],2,'.',',');?></td>

                                    <td><?php echo $row['warranty'];?></td>
                                    <td>
                                      <a href="#cart" data-toggle="modal" data-id="<?php echo $row['s_id'];?>">
                                        <i class="fa fa-shopping-basket" aria-hidden="true"></i></a>
                                      </td>
                                    <?php
                                    }
                                     ?>




                                     </tr>
                                     <?php } ?>
                                 </tbody>
                             </table>


         </div><!-- panel-body -->
     </div><!-- panel -->
     <div class="col-md-12 text-right">
       <small>LEGEND:</small> <span class="label label-danger">Sold</span>
     </div>
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
