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

    $query = "SELECT * FROM supplier WHERE sup_id = $id";
    $results = $database->get_results( $query );
    foreach( $results as $row ){

      ?>



<form method="post" name="form">
  <input id="sup_id" name="sup_id" type="hidden" value="<?php echo $row['sup_id'];?>"/>


  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <label class="control-label">SUPPLIER NAME</label>
      <input type="text" id="supplier" name="supplier" value="<?php echo $row['supplier'];?>" class="form-control"/>
    </div>
  </div>

  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <label class="control-label">CONTACT</label>
      <input type="text" id="contact" name="contact" value="<?php echo $row['contact'];?>" class="form-control"/>
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
var sup_id = $("#sup_id").val();
var supplier = $("#supplier").val();
var contact = $("#contact").val();

var dataString =
'sup_id='+ sup_id +
'&supplier=' + supplier +
'&contact=' + contact
;

if(
  sup_id=='' ||
  supplier=='' ||
  contact==''
){
$('.success').fadeOut(200).hide();
$('.error').fadeOut(200).show();
}
else
{
$.ajax({
type: "POST",
url: "update-supplier.php",
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
