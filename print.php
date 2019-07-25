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
<link type="text/css" rel="stylesheet" href="css/bootstrap-table.css" />
<style>
body{
  font-size: 10px!important;
  margin: 0!important;
}
</style>

  <div style="width:340px;margin:0 auto;">
    <div style="float:left;width:80px;"><img src="images/logo.jpg" style="width:80px;"/></div>
    <div style="float:right;width:250px;padding-top:0px;">
      <p style="font-size:8px;"><span style="font-size:13px;"><strong>PHP/MySQL | INVENTORY MANAGEMENT SYSTEM</strong></span><br />
      #076 The Royal Heritage Bldg.<br />
      Brgy 5 P. Gomez Street, Laoag City<br />
      Mobile: (+63) 123.456.7890 | (+63) 123.456.7890<br />
      Email Address: istran.net@gmail.com<br />
      Website: www.juanproject.org</p>
    </div>
  </div>
</div>


<div class="clearfix"></div>

    <form method="POST" action="<?=$_SERVER['PHP_SELF'];?>">
       <div class="container main">
         <div class="row">
           <div class="main-col">

                <div class="row">
                  <div class="col-xs-8 col-sm-8 col-md-8">
                    <?php
                    $qex = "SELECT * FROM order_item
                      INNER JOIN customer ON customer.cust_id = order_item.cust_id
                      WHERE `transaction` = '".$_GET['id']."' ";
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




                  <div class="col-xs-4 col-sm-4 col-md-4">
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
                         echo number_format($rowc['discount'],2,'.',',');
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
             <p>WARRANTY AGREEMENT</p>
             <p>To avail our products warranty, please bring in your items in its original box
             as well as your proof of purchase. Pir warranty protects againts manufacturing defects
           and workmanship from the date of original purchase. For outright replacement, please
         bring your item within the seven (7) days of original purchase. Item outside seven (7) day
      period will be brought to warranty service for evaluation and processing. Any physical damage, defects
    and/or damages caused by improper use.</p>

        <p class="text-center" style="margin-top:20px;">www.facebook.com/istran.net<br /><small>Please like Us on Facebook</small></p>

        <div class="col-md-6"><p>Prepared by: <u>John Smith</u></p></div>
        <div class="col-md-6 text-right"><p>Released by: _______________________________</p></div>

        <div class="col-md-6"><p>Validated by: <u>Erwin Agpasa</u></p></div>
        <div class="col-md-6 text-right"><p>Received by: _______________________________</p></div>


             </div><!--main-col-->
           </div><!--/row-->
         </div><!--/container-->
       </form>


      <div class="clearfix"></div>




      <!-- Bootstrap core JavaScript -->
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/bootstrap.js"></script>
      <script type="text/javascript" src="js/moment.min.js"></script>

      <script type="text/javascript">



  $(document).ready(function(){
    window.print();
    //setTimeout(window.close, 500);
    });/** Document Ready Functions END **/

      </script>

     </body>
 </html>
