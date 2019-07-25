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
$uniqid = $_SESSION['sesuniqid'];
$path = "images/item/";
$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
$name = $_FILES['photoimg']['name'];
$size = $_FILES['photoimg']['size'];

  if(strlen($name)){
    list($txt, $ext) = explode(".", $name);

  if(in_array($ext,$valid_formats)){
    if($size<(1024*1024)) // Image size max 1 MB
    {

      $actual_image_name = time().$uniqid.".".$ext;
      $tmp = $_FILES['photoimg']['tmp_name'];


      $query = "SELECT `s_id` FROM `stock` ORDER BY s_id DESC LIMIT 1";
              $results = $database->get_results( $query );
              foreach( $results as $row )
              {
                $last = $row['s_id'];
              }



      $qex = "SELECT * FROM photo WHERE stock_id = '$last' ";
      $rex = $database->get_results( $qex );
      foreach( $rex as $rowex ) {
        ?>
            <img src="images/item/<?php echo $rowex['path'];?>" style="width:100px;height:80px;"/>
        <?php
      }

      if(move_uploaded_file($tmp, $path.$actual_image_name)){







$data = array(
    'path' => $actual_image_name,
    'stock_id' => $last
    );

    $add_query = $database->insert( 'photo', $data );
    if( $add_query )
    {
        echo "<img src='images/item/".$actual_image_name."' class='img-responsive' style='margin-top:15px;'>";
    }

?>

<!--
<br /><br /><br />
<div class="clearfix"></div>
<div class="col-md-12 text-center">
<a href="stock.php"><span class="add-tour label label-success">PROCEED</span></a>
</div>
-->
<div class="clearfix"></div>

<?php
}
else
echo "failed";
}
else
echo "Image file size max 1 MB";
}
else
echo "Invalid file format..";
}
else
echo "Please select image..!";
exit;
}
?>
