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
      <label class="control-label">FULLNAME</label>
      <input type="text" id="fullname" name="fullname" value="<?php if (isset($_POST['fullname'])) echo $_POST['fullname']; ?>" class="form-control"/>
    </div>
  </div>


  <div class="col-xs-12 col-sm-12 col-md-12">
    <div class="form-group">
      <label class="control-label">ADDRESS</label>
      <input type="text" id="address" name="address" value="<?php if (isset($_POST['address'])) echo $_POST['address']; ?>" class="form-control"/>
    </div>
  </div>


  <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
      <label class="control-label">CONTACT</label>
      <input type="text" id="contact" name="contact" value="<?php if (isset($_POST['contact'])) echo $_POST['contact']; ?>" class="form-control"/>
    </div>
  </div>


  <div class="col-xs-6 col-sm-6 col-md-6">
    <div class="form-group">
      <label class="control-label">FB ADDRESS</label>
      <input type="text" id="fb" name="fb" value="<?php if (isset($_POST['fb'])) echo $_POST['fb']; ?>" class="form-control"/>
    </div>
  </div>



<div class="clearfix"></div>
<div>
<input type="submit" value="Add this customer" class="pull-right btn btn-primary submit" style="margin-right:15px;"/>
<span class="pull-left error" style="display:none;margin-left:15px;"> Please Enter Valid Data</span>
<span class="pull-left success" style="display:none;margin-left:15px;"> Data updated!</span>
<div class="clearfix"></div>

</div>
</form>

<script type="text/javascript" >
$(document).ready(function(){

$(function() {
$(".submit").click(function() {
var fullname = $("#fullname").val();
var address = $("#address").val();
var contact = $("#contact").val();
var fb = $("#fb").val();

var dataString =
'&fullname=' + fullname +
'&address=' + address +
'&contact=' + contact +
'&fb=' + fb
;

if(
  fullname=='' ||
  address=='' ||
  contact=='' ||
  fb==''
){
$('.success').fadeOut(200).hide();
$('.error').fadeOut(200).show();
}
else
{
$.ajax({
type: "POST",
url: "insert-customer.php",
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
