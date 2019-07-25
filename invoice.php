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




      $query = "SELECT `id`, `sales_order`, `date` FROM `chart_sales` WHERE `date` = '$serverDate'";
      if( $database->num_rows( $query ) > 0 ){

        list( $id, $sales_order, $date ) = $database->get_row( $query );
          $new_sales = $sales_order + 1;
          $update = array(
            'sales_order' => $new_sales
          );
          $where_clause = array(
              'date' => $serverDate
          );
          $updated = $database->update( 'chart_sales', $update, $where_clause, 1 );


      }else{

          $names = array(
              'date' => $serverDate,
              'sales_order' => 1
          );
          $add_query = $database->insert( 'chart_sales', $names );

      }




include ( 'head.inc.php' );
?>
<link type="text/css" rel="stylesheet" href="css/bootstrap-table.css" />
<?php include ( 'menu.inc.php' ); ?>

    <form method="POST" action="<?=$_SERVER['PHP_SELF'];?>">
       <div class="container main">
         <div class="row">
           <div class="main-col">

                <div class="row">
                  <div class="col-xs-9 col-sm-9 col-md-9">
                    <?php
                    $qex = "SELECT * FROM order_item
                      INNER JOIN customer ON customer.cust_id = order_item.cust_id
                      ORDER BY order_id DESC LIMIT 1";
                    $rex = $database->get_results( $qex );
                    foreach( $rex as $rowex ) {
                    $ntrasaction = $rowex['transaction'];
                    $ntime = $rowex['time'];
                    $ndate = $rowex['date'];

                      echo "<strong>Bill To:</strong><br />
                      ".$rowex['cust_name']."<br />
                      ".$rowex['cust_address']."<br />
                      Contact: ".$rowex['contact']."<br />
                      ";
                    }
                    ?>
                  </div>




                  <div class="col-xs-3 col-sm-3 col-md-3">
                    Transaction #: <strong><?php echo $ntrasaction; ?></strong><br />
                     Date: <?php echo date('Y-m-d', $ntime); ?><br />
                     Time: <?php echo date('h:i:s a', $ntime); ?>
                  </div>

           </div><!-- row -->






                   <table class="table borderless" style="margin-top:30px;">
                     <thead style="border-bottom:1px solid #ddd;">
                       <tr style="border:0px;">
                         <th data-field="category" data-sortable="true">ITEM DESCRIPTION</th>
                         <th>QTY</th>
                         <th>SRP</th>
                         <th>EXT PRICE</th>
                         <th class="text-center">WARRANTY</th>
                       </tr>
                     </thead>

                      <?php
                      echo "<tbody>";
                      $query = "SELECT *, GROUP_CONCAT(p_serial) as grouped_name FROM cart
                      INNER JOIN product ON product.p_id = cart.product_id
                      INNER JOIN stock ON stock.s_id = cart.stock_id
                      INNER JOIN warranty ON warranty.wnty_id = stock.wnty_id
                      WHERE `transaction` = '$ntrasaction' GROUP BY product_id
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
                              FROM cart WHERE transaction = '$ntrasaction' AND product_id=".$row['product_id']."";
                              $resultsc = $database->get_results( $query_c );
                              foreach( $resultsc as $rowc )
                              {
                                  echo "<td>".$rowc['total']."</td>";
                                  $ext_price = $rowc['total'] * $row['srp'];


                                  $total_price += $rowc['total'] * $row['srp'];
                              }
                                echo"<td>".number_format($row['srp'],2,'.',',')."</td>";
                                echo "<td>".number_format($ext_price,2,'.',',')."</td>";
                                echo"<td class='text-center'>".$row['warranty']."</td>";


                                ?>


                     </tr>


                       <?php
                           }
                       ?>

                     </tbody>
                   </table>



                   <div class="col-md-12 text-right" style="padding-top:15px;margin-top:20px;border-top:1px solid #ddd;">
                   <p>Subtotal: <?php echo number_format($total_price,2,'.',',');?></p>

                   <p>VAT:
                     <?php
                     $check_column = 'transaction';
                     $check_for = array( 'transaction' => $ntrasaction );
                     $exists = $database->exists( 'vat', $check_column,  $check_for );
                     if( $exists )
                     {
                       $query_c = "SELECT *
                       FROM `vat` WHERE `transaction` = '$ntrasaction' ";
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
                     ?></p>
                   <p>Discount:
                     <?php
                     $check_column = 'transaction';
                     $check_for = array( 'transaction' => $ntrasaction );
                     $exists = $database->exists( 'discount', $check_column,  $check_for );
                     if( $exists )
                     {
                       $query_c = "SELECT *
                       FROM `discount` WHERE `transaction` = '$ntrasaction' ";
                       $resultsc = $database->get_results( $query_c );
                       foreach( $resultsc as $rowc )
                       {
                         $new_discount = $rowc['discount'];
                         echo $rowc['discount'];
                       }

                     }else{
                      echo "0.00";
                     }


                     ?></p>

                   <p><strong>GRAND TOTAL: <?php

                      //$vatRate = 20;        // This must be the percentage VAT rate. e.g.: 20, 17.5.
                      $vatComponent = ($total_price / 100) * $new_vat;
                      $endPrice = $total_price + $vatComponent;
                      $totalprice = $endPrice - $new_discount;
                      echo number_format($totalprice,2,'.',',');?></strong></p>
                 </div>




             <div class="clearfix"></div>
             <p class="text-center">
             <a href="print.php?id=<?php echo $ntrasaction;?>" target="print"><i class="fa fa-print" aria-hidden="true"></i> Print</a></p>
             </div><!--main-col-->
           </div><!--/row-->
         </div><!--/container-->
       </form>


      <div class="clearfix"></div>
      <?php include ( 'footer.inc.php' ); ?>




      <!-- Bootstrap core JavaScript -->
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/bootstrap.js"></script>
      <script type="text/javascript" src="js/moment.min.js"></script>

      <script type="text/javascript">
      $(document).ready(function () {
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

     </body>
 </html>
