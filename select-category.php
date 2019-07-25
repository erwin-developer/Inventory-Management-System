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
<link type="text/css" rel="stylesheet" href="css/bootstrap-table.css" />
<?php include ( 'menu.inc.php' ); ?>

       <div class="container main">
         <div class="row">
           <div class="main-col">

             <div class="col-md-6 col-md-offset-3">
                   <div class="panel panel-default">
                       <div class="panel-heading"><strong>ADD NEW PRODUCT</strong></div>
                       <div class="panel-body" style="padding:25px;">

                         <?php
                         #Display error message
                         if (!isset($showMsg)) $showMsg = '';
                                 if (isset($_POST['formsubmitted'])) {
                                 echo "<div class='alert alert-warning'>
                                       <a href='#'' class='close' data-dismiss='alert'>&times;</a>
                                       ". $showMsg. "</div>";
                                 }
                          ?>

                        <form method="POST" action="<?=$_SERVER['PHP_SELF'];?>">
                           <div class="row">

                             <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                  <select id="trigger" name="category" class="form-control">
                                  <option value="0">Please select category</option>
                                  <?php
                                  $qex = "SELECT * FROM category ORDER BY cat_name";
                                  $rex = $database->get_results( $qex );
                                  foreach( $rex as $rowex ) {
                                  ?>
                                  <option value="<?php echo $rowex['cat_id'];?>"<?php if(isset($_POST['category']) && $_POST['category'] == $rowex['cat_id']) echo 'selected';?>><?php echo $rowex['cat_name'];?></option>
                                  <?php
                                  }
                                  ?>
                                 </select>





                                <br />

                                <select name="brand" id="" class="switchable form-control primary-input">
                                  <option value="" class="brand_0">Select Brand</option>
                                  <option value="" class="brand_1">Select Brand</option>

                                  <?php
                                  $qex = "SELECT * FROM brand ORDER BY brand_name";
                                  $rex = $database->get_results( $qex );
                                  foreach( $rex as $rowex ) {
                                  ?>
                                  <option value="<?php echo $rowex['brand_id'];?>"<?php if(isset($_POST['brand']) && $_POST['brand'] == $rowex['brand_id']) echo 'selected';?> class="brand_<?php echo $rowex['cat_id'];?>"><?php echo $rowex['brand_name'];?></option>
                                  <?php
                                  }
                                  ?>


                                </select>

                               </div>
                             </div>







                               <div class="col-md-12 text-center">
                                 <input type="hidden" name="formsubmitted" value="TRUE" />
                                 <button type="submit" style="margin:20px auto 0 auto;width:300px;" class="input-lg btn btn-primary">PROCEED</button>
                               </div>


                     </div><!-- row -->
                     </form>






         </div><!-- panel-body -->
     </div><!-- panel -->
     </div><!-- col-md-7 -->






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
                      <h4 class="modal-title">Edit category information</h4>
                  </div>
                  <div class="modal-body">
                      <div class="fetched-data"></div>
                  </div>
              </div>
          </div>
      </div>




      <div class="modal fade" id="addBrand" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Add category</h4>
                  </div>
                  <div class="modal-body">
                      <div class="brand-data"></div>
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
    </script>
    </body>
 </html>
