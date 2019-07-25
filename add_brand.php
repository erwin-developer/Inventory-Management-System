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
            <option value="<?php echo $rowex['cat_id']; ?>"><?php echo $rowex['cat_name'];?></option>
            <?php
            }
            ?>
            </select>
          </div>
          </div>

  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <label class="control-label">BRAND NAME</label>
      <input type="text" id="brand_name" name="brand_name" value="<?php if (isset($_POST['brand_name'])) echo $_POST['brand_name']; ?>" class="form-control"/>
    </div>
  </div>



<div class="clearfix"></div>
<div>
<input type="submit" value="Add Brand" class="pull-right btn btn-primary submit" style="margin-right:15px;"/>
<span class="pull-left error" style="display:none;margin-left:15px;"> Please Enter Valid Data</span>
<span class="pull-left success" style="display:none;margin-left:15px;"> Data updated!</span>
<div class="clearfix"></div>

</div>
</form>

<script type="text/javascript" >
$(document).ready(function(){

$(function() {
$(".submit").click(function() {
var brand_name = $("#brand_name").val();
var category = $('select[name="category"]').val();


var dataString =
'&brand_name=' + brand_name +
'&category=' + category
;

if(
  brand_name=='' ||
  category==''
){
$('.success').fadeOut(200).hide();
$('.error').fadeOut(200).show();
}
else
{
$.ajax({
type: "POST",
url: "insert-brand.php",
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
