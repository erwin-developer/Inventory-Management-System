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

if(!isset($_SESSION['id'])){
     header("Location: signin.php");
        exit();
    }


    $ses_id = $_SESSION['id'];
    $query = "SELECT * FROM employee WHERE id = $ses_id";
      $results = $database->get_results( $query );
      foreach( $results as $row ){
      $employee = $row['fullname'];
      }






include ( 'head.inc.php' );
?>
<?php include ( 'menu.inc.php' ); ?>

       <div class="container main" style="max-width:700px;">
         <div class="row">
           <div class="main-col">

             <div class="panel panel-default">
                 <div class="panel-heading"><strong>UPLOAD PHOTO</strong> <a href="stock.php" class="pull-right">SKIP</a>
                </div>
                 <div class="panel-body" style="padding:20px;">

                   <form id="imageform" method="post" enctype="multipart/form-data" action='ajax-upload.php'>
                   Upload image <em>(1200px by 700px)</em> <input type="file" name="photoimg" id="photoimg" />
                   </form>
                   <div id='preview'></div>


               </div><!-- panel-body -->
           </div><!-- panel -->

             <div class="clearfix"></div>
             </div><!--main-col-->
           </div><!--/row-->
         </div><!--/container-->

      <div class="clearfix"></div>
      <?php include ( 'footer.inc.php' ); ?>



       <!-- Bootstrap core JavaScript -->
       <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
       <script type="text/javascript" src="js/bootstrap.js"></script>


       <script type="text/javascript" src="js/jquery-1.4.3.js"></script>
       <script type="text/javascript" src="js/jquery.form.js"></script>
       <script type="text/javascript" src="js/jquery.validate.js"></script>

       <script type="text/javascript" src="js/bootstrap-filestyle.min.js"></script>
       <script type="text/javascript" >
         $(document).ready(function()
         {
         $('#photoimg').live('change', function()
         {
         $("#preview").html('');
         $("#preview").html('<div class="col-md-12 text-center"><img src="images/preloader.gif" alt="Uploading...."/></div><div class="clearfix"></div>');
         $("#imageform").ajaxForm(
         {
         target: '#preview'
         }).submit();
         });
         });

       $(":file").filestyle({icon: false});
       </script>


         </body>
</html>
