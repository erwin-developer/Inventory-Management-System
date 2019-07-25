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

if(!isset($_SESSION['transaction'])){
      header("Location: stock.php");
         exit();
    }

$ses_id = $_SESSION['id'];
$transaction = $_SESSION['transaction'];

    $query = "SELECT * FROM employee WHERE id = $ses_id";
      $results = $database->get_results( $query );
      foreach( $results as $row ){
      $employee = $row['fullname'];
      }

      if (isset($_GET['del']) && ($_GET['del'] == 'true')) {
          $delete = array(
              'cart_id' => $_GET['id']
          );
          $deleted = $database->delete( 'cart', $delete, 1 );


          //Updating availability to 0 and then add into cart table
          $update = array('availability' => 1);
          $where_clause = array('s_id' => $_GET['s']);
          $updated = $database->update( 'stock', $update, $where_clause, 1 );


        }




        if (isset($_POST['formsubmitted'])) {
          $error = array();

                  if (empty($_POST['customer'])) {
                      $error[] = '<span class="text-danger">Please select customer!</span>';
                      } else {
                      $customer = $database->filter($_POST['customer']);
                      }
                    //Debug on vat
                    if(empty($_POST['vat'])){
                      $ins_vat = 0;
                      }else{
                      $ins_vat = $_POST['vat'];
                      }

            if (empty($error)) {
                $ins_grandtotal = $_POST['grandtotal'];
                $ins_subtotal = $_POST['subtotal'];
                //$ins_vat = $_POST['vat'];
                $ins_discount = $_POST['discount'];
                $date = date("Y-m-d H:i:s");

              $data = array(
                  'cust_id' => $customer,
                  'transaction' => $transaction,
                  'discount' => $ins_discount,
                  'vat' => $ins_vat,
                  'date' => $date,
                  'subtotal' => $ins_subtotal,
                  'grand_total' => $ins_grandtotal,
                  'time' => time()
                  );

                  $add_query = $database->insert( 'order_item', $data );
                  if( $add_query ){

                    $update = array('status' => 1);
                    $where_clause = array('transaction' => $_POST['transac_id']);
                    $updated = $database->update( 'cart', $update, $where_clause );



                    header("Location: invoice.php");
                    unset($_SESSION['transaction']);
                       exit();
                  }


              } else {
              foreach ($error as $key => $values) {
              $showMsg = $values;
                  }
              }

      } # End of the main Submit conditional.



include ( 'head.inc.php' );
?>
<link type="text/css" rel="stylesheet" href="css/bootstrap-table.css" />
<?php include ( 'menu.inc.php' ); ?>

    <form method="POST" action="<?=$_SERVER['PHP_SELF'];?>">
       <div class="container main">
         <div class="row">
           <div class="main-col">

                <div class="row">
                  <div class="col-xs-8 col-sm-8 col-md-8">
                     <div class="form-group">
                       <select id="trigger" name="customer" class="form-control">
                       <option value="0">Please select customer</option>
                       <?php
                       $qex = "SELECT * FROM customer ORDER BY cust_name";
                       $rex = $database->get_results( $qex );
                       foreach( $rex as $rowex ) {
                       ?>
                       <option value="<?php echo $rowex['cust_id'];?>"<?php if(isset($_POST['customer']) && $_POST['customer'] == $rowex['cust_id']) echo 'selected';?>><?php echo $rowex['cust_name'];?></option>
                       <?php
                       }
                       ?>
                      </select>
                    </div>
                  </div>




                  <div class="col-xs-4 col-sm-4 col-md-4">
                     <div class="form-group">
                     <select name="contact" id="" class="switchable form-control primary-input">
                       <option value="" class="contact_0">Contact Number</option>
                       <option value="" class="contact_1">Contact Number</option>

                       <?php
                       $qex = "SELECT * FROM customer ORDER BY cust_name";
                       $rex = $database->get_results( $qex );
                       foreach( $rex as $rowex ) {
                       ?>
                       <option value="<?php echo $rowex['cust_id'];?>"<?php if(isset($_POST['contact']) && $_POST['contact'] == $rowex['cust_id']) echo 'selected';?> class="contact_<?php echo $rowex['cust_id'];?>"><?php echo $rowex['contact'];?></option>
                       <?php
                       }
                       ?>


                     </select>

                   </div>
                  </div>


           </div><!-- row -->






             <div class="panel panel-default">
                 <div class="panel-heading"><strong>MANAGE CART</strong>

                </div>
                 <div class="panel-body">
                   <div class="filterable">
                   <table data-toggle="table" id="mytable" class="order-table table">
                     <thead>
                       <tr>
                         <th data-field="category" data-sortable="true">ITEM DESCRIPTION</th>
                         <th>QTY</th>
                         <th>SRP</th>
                         <th>EXT PRICE</th>
                         <th>WARRANTY</th>
                         <th></th>
                       </tr>
                     </thead>

                      <?php
                      echo "<tbody>";
                      $query = "SELECT *, GROUP_CONCAT(p_serial) as grouped_name FROM cart
                      INNER JOIN product ON product.p_id = cart.product_id
                      INNER JOIN stock ON stock.s_id = cart.stock_id
                      INNER JOIN warranty ON warranty.wnty_id = stock.wnty_id
                      WHERE `transaction` = '$transaction' GROUP BY product_id
                      ";

                      $total_price = 0;

                          $results = $database->get_results( $query );
                            foreach( $results as $row ){
                              $output  = str_replace(',', '<br />S/N: ', $row['grouped_name']);
                              echo "<tr>";
                              echo "<td><strong>".$row['product_name']."</strong>
                              <br />";
                              echo "S/N: ".$output;
                              echo "</td>";

                              $query_c = "SELECT product_id,count(*) as total
                              FROM `cart` WHERE `transaction` = '$transaction' AND `product_id` = ".$row['product_id']." ";
                              $resultsc = $database->get_results( $query_c );
                              foreach( $resultsc as $rowc )
                              {
                                  echo "<td>".$rowc['total']."</td>";
                                  $ext_price = $rowc['total'] * $row['srp'];


                                  $total_price += $rowc['total'] * $row['srp'];
                              }
                                echo"<td>".number_format($row['srp'],2,'.',',')."</td>";
                                echo "<td>".number_format($ext_price,2,'.',',')."</td>";
                                echo"<td>".$row['warranty']."</td>";


                                ?>
                                <td class="text-center">
                                <a href="cart.php?id=<?php echo $row['cart_id'];?>&amp;s=<?php echo $row['stock_id'];?>&amp;del=true" onclick="return checkDelete()"><i class="glyphicon glyphicon-trash"></i></a></td>


                     </tr>


                       <?php
                           }
                       ?>

                     </tbody>
                   </table>
                 </div>




               </div><!-- panel-body -->
           </div><!-- panel -->

           <div class="col-md-12 text-right">
           <p>Subtotal: <?php echo number_format($total_price,2,'.',',');?></p>

           <p>VAT: <a href="#addVat" title="" id="<?php echo $transaction; ?>" data-toggle="modal" data-id="<?php echo $transaction; ?>">

             <?php
             $check_column = 'transaction';
             $check_for = array( 'transaction' => $transaction );
             $exists = $database->exists( 'vat', $check_column,  $check_for );
             if( $exists )
             {
               $query_c = "SELECT *
               FROM `vat` WHERE `transaction` = '$transaction' ";
               $resultsc = $database->get_results( $query_c );
               foreach( $resultsc as $rowc )
               {
                 $new_vat= $rowc['vat'];
                 switch ($rowc['vat']) {
                     case "3.00":
                         echo "3%";
                         break;
                     case "6.00":
                         echo "6%";
                         break;
                     case "12.00":
                         echo "12%";
                         break;
                     default:
                         echo "0%";
                 }
               }

             }else{
              echo "0%";
             }
             ?>

           </a></p>
           <p>Discount: <a href="#addDiscount" title="" id="<?php echo $transaction; ?>" data-toggle="modal" data-id="<?php echo $transaction; ?>">
             <?php
             $check_column = 'transaction';
             $check_for = array( 'transaction' => $transaction );
             $exists = $database->exists( 'discount', $check_column,  $check_for );
             if( $exists )
             {
               $query_c = "SELECT *
               FROM `discount` WHERE `transaction` = '$transaction' ";
               $resultsc = $database->get_results( $query_c );
               foreach( $resultsc as $rowc )
               {
                 $new_discount = $rowc['discount'];
                 echo $rowc['discount'];
               }

             }else{
              echo "0.00";
             }


             ?>
           </a></p>

           <p><strong>GRAND TOTAL: <?php

              //$vatRate = 20;        // This must be the percentage VAT rate. e.g.: 20, 17.5.
              $vatComponent = ($total_price / 100) * $new_vat;
              $endPrice = $total_price + $vatComponent;
              $totalprice = $endPrice - $new_discount;
              echo number_format($totalprice,2,'.',',');?></strong></p>
         </div>

           <div class="col-md-12 text-center" style="margin-top:30px;">
             <?php
             #Display error message
             if (!isset($showMsg)) $showMsg = '';
                     if (isset($_POST['formsubmitted'])) {
                     echo "<div class='alert'>
                           <a href='#'' class='close' data-dismiss='alert'>&times;</a>
                           ". $showMsg. "</div>";
                     }
                     ?>
            <input type="hidden" name="grandtotal" value="<?php echo $totalprice; ?>" class="form-control"/>
            <input type="hidden" name="subtotal" value="<?php echo $total_price; ?>" class="form-control"/>
            <input type="hidden" name="vat" value="<?php echo $new_vat; ?>" class="form-control"/>
            <input type="hidden" name="discount" value="<?php echo $new_discount; ?>" class="form-control"/>
            <input type="hidden" name="transac_id" value="<?php echo $transaction; ?>" class="form-control"/>
            <input type="hidden" name="formsubmitted" value="TRUE" />
            <button type="submit" style="margin:20px 5px 0 0;width:20%;" class="input-lg btn btn-warning">PLACE ORDER</button>
           </div>

             <div class="clearfix"></div>
             </div><!--main-col-->
           </div><!--/row-->
         </div><!--/container-->
       </form>


      <div class="clearfix"></div>
      <?php include ( 'footer.inc.php' ); ?>



      <div class="modal fade" id="addDiscount" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Add Discount</h4>
                  </div>
                  <div class="modal-body">
                      <div class="discount-data"></div>
                  </div>
              </div>
          </div>
      </div>

      <div class="modal fade" id="addVat" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Add Vat</h4>
                  </div>
                  <div class="modal-body">
                      <div class="vat-data"></div>
                  </div>
              </div>
          </div>
      </div>


      <!-- Bootstrap core JavaScript -->
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/bootstrap.js"></script>
      <script type="text/javascript" src="js/moment.min.js"></script>

      <script type="text/javascript">
      $(document).ready(function () {

        $('#addDiscount').on('show.bs.modal', function (e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type : 'post',
                url : 'add_discount.php',
                data :  'rowid='+ rowid,
                success : function(data){
                $('.discount-data').html(data);
                }
            });
         });

         $('#addVat').on('show.bs.modal', function (e) {
             var rowid = $(e.relatedTarget).data('id');
             $.ajax({
                 type : 'post',
                 url : 'add_vat.php',
                 data :  'rowid='+ rowid,
                 success : function(data){
                 $('.vat-data').html(data);
                 }
             });
          });


         $('#addDiscount').on('hidden.bs.modal', function () {
            window.location.reload(true);
            })

            $('#addVat').on('hidden.bs.modal', function () {
               window.location.reload(true);
               })

      $("#trigger").change(function () {
          if ($(this).data('options') == undefined) {
              $(this).data('options', $('select.switchable option').clone());
          }
          var id = $(this).val();
          var that = this;
          $("select.switchable").each(function () {
              var thisname = $(this).attr('name');
              var theseoptions = $(that).data('options').filter('.' + thisname + '_' + id);
              $(this).html(theseoptions);
          });
      });
      //then fire it off once to display the correct elements
      $('#trigger').trigger('change');
  });





      function checkDelete(){
      return confirm('Are you sure to delete this item?');
      }
      </script>
         <script type="text/javascript" src="js/bootstrap-table.js"></script>
     </body>
 </html>
