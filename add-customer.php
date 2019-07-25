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


if(!isset($_GET['cat'])){
    header("Location: select-category.php");
    }else{
    $get_cat = $_GET['cat'];
    }

    $get_brand = $_GET['brand'];

    $query = "SELECT * FROM employee WHERE id = $ses_id";
      $results = $database->get_results( $query );
      foreach( $results as $row ){
      $employee = $row['fullname'];
      }





        if (isset($_POST['formsubmitted'])) {
          $error = array();

          if (empty($_POST['warranty'])) {
            $error[] = '<span class="text-danger">Please enter warranty</span>';
              } else {
              $warranty = $database->filter($_POST['warranty']);
              }


          if (empty($_POST['srp'])) {
            $error[] = '<span class="text-danger">Please enter srp</span>';
              } else {
              $srp = $database->filter($_POST['srp']);
              }

          if (empty($_POST['sup_price'])) {
            $error[] = '<span class="text-danger">Please enter supplier price</span>';
              } else {
              $sup_price = $database->filter($_POST['sup_price']);
              }


          if (empty($_POST['supplier'])) {
            $error[] = '<span class="text-danger">Please enter supplier</span>';
              } else {
              $supplier = $database->filter($_POST['supplier']);
              }

          if (empty($_POST['part'])) {
            $error[] = '<span class="text-danger">Please enter part number</span>';
              } else {
              $part = $database->filter($_POST['part']);
              }

          if (empty($_POST['serial'])) {
            $error[] = '<span class="text-danger">Please enter serial number</span>';
              } else {
              $serial = $database->filter($_POST['serial']);
              }

          if (empty($_POST['model'])) {
            $error[] = '<span class="text-danger">Please enter model number</span>';
              } else {
              $model = $database->filter($_POST['model']);
              }



          if (empty($_POST['product'])) {
            $error[] = '<span class="text-danger">Please enter product name</span>';
              } else {
              $p_id = $database->filter($_POST['product']);
              }


        if (empty($error)) {


          $remarks = $_POST['remarks'];

          $new_supprice = str_replace(',', '', $sup_price);
            if(is_numeric($new_supprice)) {
              $supplier_price = $new_supprice;
              }

          $new_srp = str_replace(',', '', $srp);
            if(is_numeric($new_srp)) {
              $unit_price = $new_srp;
              }


              $data = array(
                  'cat_id' => $get_cat,
                  'brand_id' => $get_brand,
                  'p_id' => $p_id,
                  'model' => $model,
                  'serial' => $serial,
                  'part' => $part,
                  'sup_id' => $supplier,
                  'price' => $supplier_price,
                  'srp' => $unit_price,
                  'wnty_id' => $warranty,
                  'remarks' => $remarks
              );

              $add_query = $database->insert( 'stock', $data );

              if($add_query){
                header("Location: upload.php");
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

       <div class="container main">
         <div class="row">
           <div class="main-col">

             <div class="col-md-8 no-padding">
                   <div class="panel panel-default">
                       <div class="panel-heading">
                         <?php
                         $qex = "SELECT * FROM brand WHERE brand_id = $get_brand";
                         $rex = $database->get_results( $qex );
                         foreach( $rex as $rowex ) {
                           echo "<strong>ADD NEW ".strtoupper($rowex['brand_name'])."</strong> ";
                           }

                         $qex = "SELECT * FROM category WHERE cat_id = $get_cat";
                         $rex = $database->get_results( $qex );
                         foreach( $rex as $rowex ) {
                           echo "<strong>".strtoupper($rowex['cat_name'])."</strong>";
                           }
                         ?>


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

                        <form method="POST" action="<?=$_SERVER['PHP_SELF'];?>?cat=<?php echo $get_cat;?>&amp;brand=<?php echo $get_brand;?>">
                           <div class="row" style="padding:25px;">

                             <div class="col-xs-8 col-sm-8 col-md-8">
                                <div class="form-group">
                                  <label class="control-label">PRODUCT NAME</label>
                                  <select name="product" class="form-control">


                                      <option value="0">Please select product</option>
                                      <?php
                                      $qex = "SELECT * FROM product WHERE cat_id = $get_cat AND brand_id = $get_brand ORDER BY product_name";
                                      $rex = $database->get_results( $qex );
                                      foreach( $rex as $rowex ) {
                                      ?>
                                      <option value="<?php echo $rowex['p_id'];?>"<?php if(isset($_POST['product']) && $_POST['product'] == $rowex['p_id']) echo 'selected';?>><?php echo $rowex['product_name'];?></option>
                                      <?php
                                      }



                                    ?>




                                 </select>
                               </div>
                             </div>




                              <div class="col-xs-4 col-sm-4 col-md-4">
                               <div class="form-group">
                                <label class="control-label">MODEL #</label>
                                <input type="text" name="model" value="<?php if(isset($_POST['model'])) echo $_POST['model'];?>" class="form-control">
                                </div>
                               </div>




                               <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                 <label class="control-label">SERIAL #</label>
                                 <input type="text" name="serial" value="<?php if(isset($_POST['serial'])) echo $_POST['serial'];?>" class="form-control">
                                 </div>
                                </div>


                                <div class="col-xs-6 col-sm-6 col-md-6">
                                 <div class="form-group">
                                  <label class="control-label">PART #</label>
                                  <input type="text" name="part" value="<?php if(isset($_POST['part'])) echo $_POST['part'];?>" class="form-control">
                                  </div>
                                 </div>



                                   <div class="col-xs-12 col-sm-12 col-md-12">
                                      <div class="form-group">
                                        <label class="control-label">SUPPLIER</label>
                                        <select name="supplier" class="form-control">
                                        <option value="0">Please select supplier</option>
                                        <?php
                                        $qex = "SELECT * FROM supplier ORDER BY supplier";
                                        $rex = $database->get_results( $qex );
                                        foreach( $rex as $rowex ) {
                                        ?>
                                        <option value="<?php echo $rowex['sup_id'];?>"<?php if(isset($_POST['supplier']) && $_POST['supplier'] == $rowex['sup_id']) echo 'selected';?>><?php echo $rowex['supplier'];?></option>
                                        <?php
                                        }
                                        ?>
                                       </select>
                                     </div>
                                   </div>



                                   <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                     <label class="control-label">SUPPLIER PRICE</label>
                                     <input type="text" name="sup_price" value="<?php if(isset($_POST['sup_price'])) echo $_POST['sup_price'];?>" class="form-control">
                                     </div>
                                    </div>

                                   <div class="col-xs-4 col-sm-4 col-md-4">
                                    <div class="form-group">
                                     <label class="control-label">UNIT PRICE (SRP)</label>
                                     <input type="text" name="srp" value="<?php if(isset($_POST['srp'])) echo $_POST['srp'];?>" class="form-control">
                                     </div>
                                    </div>


                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                       <div class="form-group">
                                         <label class="control-label">WARRANTY</label>
                                         <select name="warranty" class="form-control">
                                           <?php
                                           $qex = "SELECT * FROM warranty ORDER BY warranty";
                                           $rex = $database->get_results( $qex );
                                           foreach( $rex as $rowex ) {
                                           ?>
                                        <option value="<?php echo $rowex['wnty_id'];?>"<?php if(isset($_POST['warranty']) && $_POST['warranty'] == $rowex['wnty_id']) echo 'selected';?>><?php echo $rowex['warranty'];?></option>
                                        <?php
                                        }
                                        ?>
                                        </select>
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
                                 <button type="submit" style="margin:20px auto 0 auto;width:300px;" class="input-lg btn btn-primary">UPLOAD PHOTO</button>
                               </div>


                     </div><!-- row -->
                     </form>






         </div><!-- panel-body -->
     </div><!-- panel -->
     </div><!-- col-md-7 -->




                 <!--Sidebar-->
                 <div class="col-md-4">
                   <div class="col-md-12 col-sm-12 no-padding">
                     <div class="well">
                       Complete each step now and you can edit your listing once finished
                     </div>

                     <p>QUESTION? GET IN TOUCH:<br />
                     Email us at <a href="#">support@juanproject.org</a></p>



                   </div>

                   </div>
                   <!-- /Sidebar -->



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
    </body>
 </html>
