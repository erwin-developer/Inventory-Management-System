<nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar-main">
   <div class="container">
       <div class="navbar-header">
           <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navCollapse">
               <span class="sr-only">Toggle Navigation</span>
               <span class="fa fa-chevron-down"></span>
           </button>
           <a class="navbar-brand" href="#"><img src="images/hlogo.png" id="hlogo" /></a>
       </div>

       <div class="collapse navbar-collapse" id="navCollapse">

           <ul class="nav navbar-nav navbar-right">

              <li><a href="index.php">HOME</a></li>


               <li><a href="#" data-toggle="dropdown" class="dropdown-toggle">REPORTS <b class="caret"></b></a>
                 <ul class="dropdown-menu multi-level">
                   <li><a href="transaction.php">Sales Transaction</a></li>
                   <li><a href="repair-transaction.php">Repair Transaction</a></li>
                   <li><a href="services-transaction.php">Reformat Transaction</a></li>
                   <li class="divider"></li>

                   <li class="dropdown-submenu">
                       <a href="#" class="dropdown-toggle" data-toggle="dropdown">Revenue</a>
                       <ul class="dropdown-menu">
                           <li><a href="sales-rev.php">Sales</a></li>
                           <li><a href="repair-rev.php">Repair</a></li>
                           <li><a href="reformat-rev.php">Reformat</a></li>
                       </ul>
                   </li>
                 </ul>
               </li>


               <li><a href="#" data-toggle="dropdown" class="dropdown-toggle">SERVICES <b class="caret"></b></a>
                 <ul class="dropdown-menu multi-level">
                   <li><a href="creating-joborder.php">Reformat</a></li>
                    <li><a href="creating-repair.php">Repair</a></li>
                 </ul>
               </li>




               <li><a href="#" data-toggle="dropdown" class="dropdown-toggle">SETTINGS <b class="caret"></b></a>
                 <ul class="dropdown-menu multi-level">
                   <li><a href="stock.php">Stocks</a></li>
                   <li class="divider"></li>

                    <li><a href="brand.php">Brand</a></li>
                    <li><a href="category.php">Category</a></li>
                    <li><a href="customer.php">Customer</a></li>
                    <li><a href="product.php">Product</a></li>
                    <li><a href="supplier.php">Supplier</a></li>
                    <li class="divider"></li>
                    <li><a href="logout.php">Logout</a></li>


                 </ul>
               </li>



               <li><a href="stock.php"><i class="fa fa-search" aria-hidden="true"></i> SEARCH</a></li>



              <?php
              if(!isset($_SESSION['transaction'])){
              ?>
               <li><a href="creating-transaction.php"><span class="add-tour label label-success">NEW ORDER

               </span></a>
              <?php
              }else{
                ?>
              <li><a href="cart.php"><span class="add-tour label label-warning">CHECKOUT
              <i style="margin-left:20px;" class="fa fa-shopping-basket" aria-hidden="true"></i>
                <?php
                $query = "SELECT count(*) as tcart FROM cart WHERE `transaction` = '$transaction' ";
                $results = $database->get_results( $query );
                foreach( $results as $row )
                {
                    echo $row['tcart'];
                }
                ?>

</span></a>
              <?php
            }
              ?>
              </li>


           </ul>
       </div>

     </div>
 </nav>
 <!--end nav bar-->
