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


      ?>



<form method="post" name="form">

    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="form-group">
            <label class="control-label">CATEGORY</label>
            <select id="category" name="category" class="form-control">
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
          </div>
          </div>



          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                  <label class="control-label">BRAND</label>
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


            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="form-group">
                <label class="control-label">PRODUCT NAME</label>
                <input type="text" id="product" name="product" value="<?php if (isset($_POST['product'])) echo $_POST['product']; ?>" class="form-control"/>
              </div>
            </div>



<div class="clearfix"></div>
<div>
<input type="submit" value="Add Product" class="pull-right btn btn-primary submit" style="margin-right:15px;"/>
<span class="pull-left error" style="display:none;margin-left:15px;"> Please Enter Valid Data</span>
<span class="pull-left success" style="display:none;margin-left:15px;"> Data updated!</span>
<div class="clearfix"></div>

</div>
</form>

<script type="text/javascript" >
$(document).ready(function(){
  $("#category").change(function () {
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
  $('#category').trigger('change');

$(function() {
$(".submit").click(function() {
var category = $('select[name="category"]').val();
var brand = $('select[name="brand"]').val();
var product = $("#product").val();

var dataString =
'&category=' + category +
'&brand=' + brand +
'&product=' + product
;

if(
  category=='' ||
  brand=='' ||
  product==''
){
$('.success').fadeOut(200).hide();
$('.error').fadeOut(200).show();
}
else
{
$.ajax({
type: "POST",
url: "insert-product.php",
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
