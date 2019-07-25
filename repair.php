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


    if(!isset($_SESSION['repairorder'])){
         header("Location: creating-repair.php");
            exit();
        }


$ses_id = $_SESSION['id'];
$transaction = $_SESSION['transaction'];
$repairorder = $_SESSION['repairorder'];



    $query = "SELECT * FROM employee WHERE id = $ses_id";
      $results = $database->get_results( $query );
      foreach( $results as $row ){
      $employee = $row['fullname'];
      }






        if (isset($_POST['formsubmitted'])) {
          $error = array();

            if (empty($_POST['customer'])) {
              $error[] = '<span class="text-danger">Please select customer!</span>';
              } else {
              $customer = $database->filter($_POST['customer']);
              }

            if (empty($_POST['sys_unit'])) {
                $error[] = '<span class="text-danger">Please select system unit!</span>';
                } else {
                $sys_unit = $database->filter($_POST['sys_unit']);
                }

            if (empty($_POST['warranty'])) {
              $error[] = '<span class="text-danger">Please enter warranty code/sricker!</span>';
              } else {
              $warranty = $database->filter($_POST['warranty']);
              }


              if (empty($_POST['service'])) {
                $error[] = '<span class="text-danger">Please select service type!</span>';
                } else {
                $service = $database->filter($_POST['service']);
                }

              if (empty($_POST['cost'])) {
                  $error[] = '<span class="text-danger">Please enter cost!</span>';
                  } else {
                  $cost = $database->filter($_POST['cost']);
                  }

            if (empty($error)) {
                $remarks = $_POST['remarks'];
                $date = date("Y-m-d H:i:s");

              $data = array(
                  'transaction' => $repairorder,
                  'cust_id' => $customer,
                  'sys_unit' => $sys_unit,
                  'warranty' => $warranty,
                  'service' => $service,
                  'cost' => $cost,
                  'remarks' => $remarks,
                  'date' => $date,
                  'time' => time()
                  );

                  $add_query = $database->insert( 'repair', $data );
                  if( $add_query ){

                    header("Location: repair-invoice.php?id=$repairorder");
                    //unset($_SESSION['joborder']);
                    //   exit();
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
             <div class="panel panel-default" style="width:700px;margin:0 auto;">
                 <div class="panel-heading"><strong>REPAIR SERVICE</strong>
                </div>
                 <div class="panel-body">
                   <?php
                   #Display error message
                   if (!isset($showMsg)) $showMsg = '';
                           if (isset($_POST['formsubmitted'])) {
                           echo "<div class='alert'>
                                 <a href='#'' class='close' data-dismiss='alert'>&times;</a>
                                 ". $showMsg. "</div>";
                           }
                           ?>


                           <form method="POST" action="<?=$_SERVER['PHP_SELF'];?>">

                           <div class="row" style="padding:25px 25px 0 25px;">
                             <div class="col-xs-8 col-sm-8 col-md-8">
                                <div class="form-group">
                                  <label class="control-label">CUSTOMER</label>
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
                            <label class="control-label">CONTACT</label>
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
                           <hr />



                     <div class="row" style="padding:10px 25px 25px 25px;">
                       <div class="col-xs-6 col-sm-6 col-md-6">
                          <div class="form-group">
                            <label class="control-label">SYSTEM UNIT</label>
                            <select name="sys_unit" class="form-control">
                           <option value="Desktop"<?php if(isset($_POST['sys_unit']) && $_POST['sys_unit'] == "Desktop") echo 'selected';?>>Desktop</option>
                           <option value="Laptop"<?php if(isset($_POST['sys_unit']) && $_POST['sys_unit'] == "Laptop") echo 'selected';?>>Laptop</option>
                           <option value="HD"<?php if(isset($_POST['sys_unit']) && $_POST['sys_unit'] == "HD") echo 'selected';?>>HD</option>
                           <option value="Video Card"<?php if(isset($_POST['sys_unit']) && $_POST['sys_unit'] == "Video Card") echo 'selected';?>>Video Card</option>
                           <option value="Motherboard"<?php if(isset($_POST['sys_unit']) && $_POST['sys_unit'] == "Motherboard") echo 'selected';?>>Motherboard</option>
                           </select>
                         </div>
                       </div>


                         <div class="col-xs-6 col-sm-6 col-md-6">
                          <div class="form-group">
                           <label class="control-label">WARRANTY STICKER/CODE</label>
                           <input type="text" name="warranty" value="<?php if(isset($_POST['warranty'])) echo $_POST['warranty'];?>" class="form-control">
                           </div>
                          </div>


                          <div class="col-xs-6 col-sm-6 col-md-6">
                             <div class="form-group">
                               <label class="control-label">SERVICE TYPE</label>
                               <select name="service" class="form-control">
                              <option value="repair"<?php if(isset($_POST['service']) && $_POST['service'] == "repair") echo 'selected';?>>Repair</option>
                              <option value="replacement"<?php if(isset($_POST['service']) && $_POST['service'] == "replacement") echo 'selected';?>>Replacement</option>
                              </select>
                            </div>
                          </div>


                          <div class="col-xs-6 col-sm-6 col-md-6">
                           <div class="form-group">
                            <label class="control-label">SERVICE COST</label>
                            <input type="text" name="cost" style="width:100px;" value="<?php if(isset($_POST['cost'])) echo $_POST['cost'];?>" class="form-control">
                            </div>
                           </div>




                           <div class="col-xs-12 col-sm-12 col-md-12">
                           <div class="form-group">
                             <label for="comment">REMARKS:</label>
                             <textarea class="form-control" rows="5" name="remarks"><?php if(isset($_POST['remarks'])) echo $_POST['remarks'];?></textarea>
                           </div>
                         </div>







                         <div class="col-md-12 text-center">
                           <input type="hidden" name="formsubmitted" value="TRUE" />
                           <button type="submit" style="margin:20px auto 0 auto;width:200px;" class="input-lg btn btn-primary">SUBMIT</button>
                         </div>


                  </div><!-- row -->
                  </form>






               </div><!-- panel-body -->
           </div><!-- panel -->



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
