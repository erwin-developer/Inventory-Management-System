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


      unset($_SESSION['joborder']);


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
      <p style="font-size:8px;"><span style="font-size:13px;"><strong>DOUBLE K COMPUTER RETAIL</strong></span><br />
      #076 The Royal Heritage Bldg.<br />
      Brgy 5 P. Gomez Street, Laoag City<br />
      Mobile: (+63) 123.456.7890 | (+63) 123.456.7890<br />
      Email Address: support@2kpcshop.com<br />
      Website: www.2kpcshop.com</p>
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
                    $qex = "SELECT * FROM services
                      INNER JOIN customer ON customer.cust_id = services.cust_id
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
                 <th data-field="category" data-sortable="true">SERVICES TYPE</th>
                 <th>HD S/N</th>
                 <th class="text-right">COST</th>
                 </tr>
             </thead>

              <?php
              echo "<tbody>";
              $query = "SELECT * FROM services WHERE `transaction` = '$ntrasaction' ";


                  $results = $database->get_results( $query );
                    foreach( $results as $row ){
                        $total_price = $row['cost'];

                      echo "<tr>";
                      echo "<td><strong>FORMAT: ".strtoupper($row['service'])."</strong>";
                      echo "</td>";
                      echo "<td>".$row['hd_serial']."</td>";
                      echo"<td class=\"text-right\">".number_format($row['cost'],2,'.',',')."</td>";

                        ?>


             </tr>


               <?php
                   }
               ?>

             </tbody>
           </table>




                   <div class="col-md-12 text-right" style="padding-top:15px;margin-top:20px;border-top:1px solid #ddd;">
                   <p>Subtotal: <?php echo number_format($total_price,2,'.',',');?></p>

                   <p>VAT: 0%</p>
                   <p><strong>GRAND TOTAL: <?php
                      echo number_format($total_price,2,'.',',');?></strong></p>
                 </div>




             <div class="clearfix"></div>

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
