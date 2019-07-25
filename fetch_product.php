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

    $query = "SELECT * FROM product WHERE p_id = $id";
    $results = $database->get_results( $query );
    foreach( $results as $row ){
      $cat_id = $row['cat_id'];
      $brand_id = $row['brand_id'];
      ?>



<form method="post" name="form">
  <input id="p_id" name="p_id" type="hidden" value="<?php echo $row['p_id'];?>"/>

  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
            <label class="control-label">CATEGORY</label>
            <select id="category" name="category" class="form-control">
            <?php
            $qex = "SELECT * FROM category";
            $rex = $database->get_results( $qex );
            foreach( $rex as $rowex ) {
            ?>

            <option value="<?php echo $rowex['cat_id']; ?>"<?php
            if ($cat_id == $rowex['cat_id'])
            echo 'selected'; ?>><?php echo $rowex['cat_name'];?></option>
            <?php
            }
            ?>
            </select>
          </div>
          </div>


          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                    <label class="control-label">BRAND</label>
                    <select id="brand" name="brand" class="switchable form-control">
                    <?php
                    $qex = "SELECT * FROM brand";
                    $rex = $database->get_results( $qex );
                    foreach( $rex as $rowex ) {
                    ?>
                    <option value="<?php echo $rowex['brand_id']; ?>"<?php
                    if ($brand_id == $rowex['brand_id'])
                    echo 'selected'; ?> class="brand_<?php echo $rowex['cat_id'];?>"><?php echo $rowex['brand_name'];?></option>
                    <?php
                    }
                    ?>
                    </select>
                  </div>
                  </div>



  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <label class="control-label">BRAND NAME</label>
      <input type="text" id="product" name="product" value="<?php echo $row['product_name'];?>" class="form-control"/>
    </div>
  </div>



<div class="clearfix"></div>
<div>
<input type="submit" value="Update Data" class="pull-right btn btn-primary submit" style="margin-right:15px;"/>
<span class="pull-left error" style="display:none;margin-left:15px;"> Please Enter Valid Data</span>
<span class="pull-left success" style="display:none;margin-left:15px;"> Data updated!</span>
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
var p_id = $("#p_id").val();
var category = $('select[name="category"]').val()
var brand = $('select[name="brand"]').val()
var product = $("#product").val();

var dataString =
'p_id='+ p_id +
'&brand=' + brand +
'&category=' + category +
'&product=' + product
;

if(
  p_id=='' ||
  brand=='' ||
  category=='' ||
  product==''
){
$('.success').fadeOut(200).hide();
$('.error').fadeOut(200).show();
}
else
{
$.ajax({
type: "POST",
url: "update-product.php",
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

  });/** Document Ready Functions END **/
  </script>

<?php  } ?>
