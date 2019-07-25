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
    $get_id = $_GET['id'];

    $query = "SELECT * FROM employee WHERE id = $ses_id";
      $results = $database->get_results( $query );
      foreach( $results as $row ){
      $employee = $row['fullname'];
      }

      unset($_SESSION['joborder']);

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
                    $qex = "SELECT * FROM repair
                      INNER JOIN customer ON customer.cust_id = repair.cust_id
                      WHERE `transaction` = '$get_id' ";
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
                    Transaction #: <strong><?php echo $ntrasaction; ?></strong><br /><br />
                     Date: <?php echo date('Y-m-d', $ntime); ?><br />
                     Time: <?php echo date('h:i:s a', $ntime); ?>
                  </div>

           </div><!-- row -->






                   <table class="table borderless" style="margin-top:30px;">
                     <thead style="border-bottom:1px solid #ddd;">
                       <tr style="border:0px;">
                         <th data-field="category" data-sortable="true">SERVICES TYPE</th>
                         <th>SYSTEM UNIT</th>
                         <th>WARRANTY CODE</th>
                         <th>REMARKS</th>
                         <th class="text-right">COST</th>
                         </tr>
                     </thead>

                      <?php
                      echo "<tbody>";
                      $query = "SELECT * FROM repair WHERE `transaction` = '$get_id' ";


                          $results = $database->get_results( $query );
                            foreach( $results as $row ){
                                $total_price = $row['cost'];

                              echo "<tr>";
                              echo "<td>".strtoupper($row['service'])."";
                              echo "</td>";
                              echo "<td>".$row['sys_unit']."</td>";
                              echo "<td>".$row['warranty']."</td>";
                              echo "<td>".$row['remarks']."</td>";
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
             <p class="text-center"><a href="repair-print.php?id=<?php echo $ntrasaction;?>" target="print"><i class="fa fa-print" aria-hidden="true"></i> Print</a></p>
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
