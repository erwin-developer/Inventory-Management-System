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

    $query = "SELECT * FROM customer WHERE cust_id = $id";
    $results = $database->get_results( $query );
    foreach( $results as $row ){
      ?>



<form method="post" name="form">
  <input id="cust_id" name="cust_id" type="hidden" value="<?php echo $row['cust_id'];?>"/>


  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <label class="control-label">FULLNAME</label>
      <input type="text" id="fullname" name="fullname" value="<?php echo $row['cust_name'];?>" class="form-control"/>
    </div>
  </div>

  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <label class="control-label">ADDRESS</label>
      <input type="text" id="address" name="address" value="<?php echo $row['cust_address'];?>" class="form-control"/>
    </div>
  </div>

  <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
      <label class="control-label">CONTACT</label>
      <input type="text" id="contact" name="contact" value="<?php echo $row['contact'];?>" class="form-control"/>
    </div>
  </div>

  <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
      <label class="control-label">FB ADDRESS</label>
      <input type="text" id="fb" name="fb" value="<?php echo $row['fb'];?>" class="form-control"/>
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
var cust_id = $("#cust_id").val();
var fullname = $("#fullname").val();
var contact = $("#contact").val();
var address = $("#address").val();
var fb = $("#fb").val();

var dataString =
'cust_id='+cust_id +
'&fullname='+fullname +
'&contact='+contact +
'&address='+address +
'&fb='+fb
;

if(
  cust_id=='' ||
  fullname=='' ||
  contact=='' ||
  address=='' ||
  fb==''
){
$('.success').fadeOut(200).hide();
$('.error').fadeOut(200).show();
}
else
{
$.ajax({
type: "POST",
url: "update-customer.php",
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
