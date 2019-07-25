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

  if (isset($_POST['formsubmitted'])) {

    $error = array();

            if (empty($_POST['password'])) {
                $error[] = '<span class="text-danger">Please enter your password</span>';
                } else {
                $pwd = $database->filter($_POST['password']);
                }

            if (empty($_POST['username'])) {
                $error[] = '<span class="text-danger">Please enter your username</span>';
                } else {
                $username = $database->filter($_POST['username']);
                }




      if (empty($error)) {
          $pwd_secure = base64_encode($pwd);

            $query = " SELECT id, username, password, level FROM `employee` WHERE `username` = '$username' AND `password` = '$pwd_secure' ";
            if( $database->num_rows( $query ) > 0 ) {
              list( $id, $username, $password, $level ) = $database->get_row( $query );
              $_SESSION["id"]=$id;
              $_SESSION["level"]=$level;
              header("Location: index.php");
              exit();

            } else {
              $showMsg = '<span class="text-danger">
              Invalid username or password</span>';
            }

        } else {
        foreach ($error as $key => $values) {
        $showMsg = $values;
            }
        }

} # End of the main Submit conditional.
include ( 'head.inc.php' );
?>

<body class="login">
<div class="container">
  <div class="row">
    <div class="wrap">


    <div class="col-xs-12 col-sm-12 col-md-12">
      <div class="col-md-12 text-center">
      <img src="images/logo.jpg" id="logo" />
      </div>

      <form method="post" action="<?=$_SERVER['PHP_SELF'];?>">
      <?php
      #Display error message
      if (!isset($showMsg)) $showMsg = '';
              if (isset($_POST['formsubmitted'])) {
              echo "<div class='alert'>
                    <a href='#'' class='close' data-dismiss='alert'>&times;</a>
                    ". $showMsg. "</div>";
              }
              ?>




      <div class="form-group">
        <label class="control-label">USERNAME</label>
        <!--<input type="text"  class="form-control input-lg" name="username" value="<?php
                  if (isset($_POST['username'])) echo $_POST['username']; ?>">-->
        <input type="text"  class="form-control input-lg" name="username" value="root">
      </div>

      <br />
      <div class="form-group">
        <label class="control-label">PASSWORD</label>
        <!--<input type="password"  class="form-control input-lg" name="password" value="<?php
                  if (isset($_POST['password'])) echo $_POST['password']; ?>">-->
        <input type="password"  class="form-control input-lg" name="password" value="demo">
      </div>



        <input type="hidden" name="formsubmitted" value="TRUE" />
        <button type="submit" style="margin:20px 5px 0 0;width:100%;" class="input-lg btn btn-primary">SIGN IN</button>

        <div class="col-md-12 text-center" style="padding:20px 0;">
        <!--<a href="#">Forgot password?</a>-->
        </div>
        <div class="clearfix"></div>
    </form>
  </div>


      <div class="clearfix"></div>




</div><!-- wrap-event -->


</div><!-- row -->
</div><!-- container -->



  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>

   </body>
</html>
