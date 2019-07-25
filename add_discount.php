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
}

      ?>



<form method="post" name="form">


  <input type="hidden" id="transaction" name="transaction" value="<?php echo $id; ?>" />



  <div class="col-xs-12 col-sm-12 col-md-12 text-center">
    <div class="form-group">
      <label class="control-label">ENTER DISCOUNT</label>
      <input type="text" id="discount" name="discount" value="<?php if (isset($_POST['discount'])) echo $_POST['discount']; ?>" style="width:200px;margin:0 auto;" class="form-control"/>
        <input type="submit" value="Submit" class="btn btn-primary submit" style="margin:15px auto;"/>
    </div>
  </div>



<div class="clearfix"></div>
<div>

<p class="error" style="display:none;text-align:center;"> Please Enter Valid Data</p>
<p class="text-center success" style="display:none;text-align:center;"> Data updated!</p>
<div class="clearfix"></div>

</div>
</form>

<script type="text/javascript" >
$(document).ready(function(){

$(function() {
$(".submit").click(function() {
var discount = $("#discount").val();
var transaction = $("#transaction").val();

var dataString =
'discount=' + discount +
'&transaction=' + transaction
;

if(
  discount=='' ||
  transaction==''
){
$('.success').fadeOut(200).hide();
$('.error').fadeOut(200).show();
}
else
{
$.ajax({
type: "POST",
url: "insert-discount.php",
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
