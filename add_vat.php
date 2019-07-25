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



  <div class="col-xs-10 col-sm-10 col-md-10 text-center">
    <div class="form-group">
      <select id="vat" name="vat" class="form-control">
        <option value="0">Please select vat</option>
        <option value="0">0%</option>
        <option value="3">3%</option>
        <option value="6">6%</option>
        <option value="12">12%</option>
      </select>
    </div>
  </div>

  <div class="col-xs-2 col-sm-2 col-md-2 text-center">
    <div class="form-group">

        <input type="submit" value="Submit" class="btn btn-primary submit" style="margin:0px auto;"/>
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
var vat = $("#vat").val();
var transaction = $("#transaction").val();

var dataString =
'vat=' + vat +
'&transaction=' + transaction
;

if(
  vat=='' ||
  transaction==''
){
$('.success').fadeOut(200).hide();
$('.error').fadeOut(200).show();
}
else
{
$.ajax({
type: "POST",
url: "insert-vat.php",
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
