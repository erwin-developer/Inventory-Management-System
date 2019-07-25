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

    $query = "SELECT * FROM category WHERE cat_id = $id";
    $results = $database->get_results( $query );
    foreach( $results as $row ){

      ?>



<form method="post" name="form">
  <input id="cat_id" name="brand_id" type="hidden" value="<?php echo $row['cat_id'];?>"/>


  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <label class="control-label">CATEGORY NAME</label>
      <input type="text" id="cat_name" name="cat_name" value="<?php echo $row['cat_name'];?>" class="form-control"/>
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
var cat_id = $("#cat_id").val();
var cat_name = $("#cat_name").val();


var dataString =
'cat_id='+ cat_id +
'&cat_name=' + cat_name
;

if(
  cat_id=='' ||
  cat_name==''
){
$('.success').fadeOut(200).hide();
$('.error').fadeOut(200).show();
}
else
{
$.ajax({
type: "POST",
url: "update-category.php",
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
