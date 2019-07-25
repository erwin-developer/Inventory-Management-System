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

if($_POST['rowid']) {
    $id = $_POST['rowid']; //escape string



        $query = "SELECT * FROM stock
        INNER JOIN category ON category.cat_id = stock.cat_id
        INNER JOIN brand ON brand.brand_id = stock.brand_id
        INNER JOIN product ON product.p_id = stock.p_id
        INNER JOIN supplier ON supplier.sup_id = stock.sup_id
        INNER JOIN warranty ON warranty.wnty_id = stock.wnty_id
        WHERE s_id = $id";
            $results = $database->get_results( $query );
              foreach( $results as $row ){


                $s_id = $row['s_id'];

      ?>



<form method="post" name="form">
  <input id="stock" name="stock" type="hidden" value="<?php echo $row['s_id'];?>"/>
  <input id="product_id" name="product_id" type="hidden" value="<?php echo $row['p_id'];?>"/>
  <input id="serial" name="serial" type="hidden" value="<?php echo $row['serial'];?>"/>

  <div class="row" style="padding:0 20px 0 20px;">
    <div class="col-xs-12 col-sm-12 col-md-12" style="margin-bottom:20px;">
      <?php
      $qex = "SELECT * FROM photo WHERE stock_id = ".$row['s_id']." ";
      $rex = $database->get_results( $qex );
      foreach( $rex as $rowex ) {
        ?>
            <a target="_blank" href="images/item/<?php echo $rowex['path'];?>"><img src="images/item/<?php echo $rowex['path'];?>" style="width:100px;height:80px;"/></a>
        <?php
      }
      ?>



  </div>

    <div class="col-xs-8 col-sm-8 col-md-8">
       <div class="form-group">
         <label class="control-label">PRODUCT NAME</label>
             <p><?php echo $row['product_name'];?></p>
      </div>
    </div>




     <div class="col-xs-4 col-sm-4 col-md-4">
      <div class="form-group">
       <label class="control-label">MODEL #</label>
       <p><?php echo $row['model'];?></p>
       </div>
      </div>




      <div class="col-xs-4 col-sm-4 col-md-4">
       <div class="form-group">
        <label class="control-label">SERIAL #</label>
        <p><?php echo $row['serial'];?></p>
        </div>
       </div>




       <div class="col-xs-4 col-sm-4 col-md-4">
        <div class="form-group">
         <label class="control-label">PART #</label>
         <p><?php echo $row['part'];?></p>
         </div>
        </div>



          <div class="col-xs-4 col-sm-4 col-md-4">
             <div class="form-group">
               <label class="control-label">SUPPLIER</label>
               <p><?php echo $row['supplier'];?></p>
            </div>
          </div>



                        <div class="col-xs-4 col-sm-4 col-md-4">
                           <div class="form-group">
                             <label class="control-label">WARRANTY</label>
                            <p><?php echo $row['warranty'];?></p>
                          </div>
                        </div>



          <div class="col-xs-4 col-sm-4 col-md-4">
           <div class="form-group">
            <label class="control-label">PRICE</label>
               <p>Php<?php echo number_format($row['price'],2,'.',',');?></p>
            </div>
           </div>

          <div class="col-xs-4 col-sm-4 col-md-4">
           <div class="form-group">
            <label class="control-label">SRP</label>
               <p>Php<?php echo number_format($row['srp'],2,'.',',');?></p>
            </div>
           </div>











        <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
          <label for="comment">REMARKS:</label>
          <textarea class="form-control" rows="2" name="remarks"><?php echo $row['remarks'];?></textarea>
        </div>
      </div>



<div class="clearfix"></div>
<div>


<?php
  if(!isset($_SESSION['transaction'])){
  echo '';
  }else{
      if ($row['availability'] == 0 ) {
      ?>
        <a href="#" class="pull-right btn btn-danger" style="margin-right:15px;">Item Sold</a>
        <?php
        }else{
        $check_column = $s_id;
        $check_for = array( 'stock_id' => $s_id );
        $exists = $database->exists( 'cart', $check_column,  $check_for );
        if(!$exists ){
        ?>
        <input type="submit" value="Add to cart" class="pull-right btn btn-primary submit" style="margin-right:15px;"/>
        <?php }else{ ?>
        <input type="submit" value="Already added to cart" class="pull-right btn btn-default" style="margin-right:15px;"/>
        <?php
        }
      }
    }
?>





<span class="pull-left error" style="display:none;margin-left:15px;"> Please Enter Valid Data</span>
<span class="pull-left success" style="display:none;margin-left:15px;"> Item added to cart!</span>
<div class="clearfix"></div>

</div>
</form>
<?php
}
?>

<script type="text/javascript" >
$(document).ready(function(){

$(function() {
$(".submit").click(function() {
var stock = $("#stock").val();
var product_id = $("#product_id").val();
var serial = $("#serial").val();

var dataString =
'stock='+ stock +
'&product_id=' + product_id +
'&serial=' + serial
;

if(
  stock=='' ||
  product_id=='' ||
  serial==''
){
$('.success').fadeOut(200).hide();
$('.error').fadeOut(200).show();
}
else
{
$.ajax({
type: "POST",
url: "insert-cart.php",
data: dataString,
success: function(){
$('.success').fadeIn(200).show();
$('.error').fadeOut(200).hide();
}
});
}
return false;
});
});




  });/** Document Ready Functions END **/
  </script>

<?php  } ?>
