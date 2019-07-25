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
      <label class="control-label">SUPPLIER NAME</label>
      <input type="text" id="supplier" name="supplier" value="<?php if (isset($_POST['supplier'])) echo $_POST['supplier']; ?>" class="form-control"/>
    </div>
  </div>

  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <label class="control-label">CONTACT</label>
      <input type="text" id="contact" name="contact" value="<?php if (isset($_POST['contact'])) echo $_POST['contact']; ?>" class="form-control"/>
    </div>
  </div>


<div class="clearfix"></div>
<div>
<input type="submit" value="Add Supplier" class="pull-right btn btn-primary submit" style="margin-right:15px;"/>
<span class="pull-left error" style="display:none;margin-left:15px;"> Please Enter Valid Data</span>
<span class="pull-left success" style="display:none;margin-left:15px;"> Data updated!</span>
<div class="clearfix"></div>

</div>
</form>

<script type="text/javascript" >
$(document).ready(function(){

$(function() {
$(".submit").click(function() {
var supplier = $("#supplier").val();
var contact = $("#contact").val();

var dataString =
'supplier=' + supplier +
'&contact=' + contact
;

if(
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
url: "insert-supplier.php",
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
