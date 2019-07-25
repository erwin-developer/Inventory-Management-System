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
$transaction = $_SESSION['transaction'];

    $query = "SELECT * FROM employee WHERE id = $ses_id";
      $results = $database->get_results( $query );
      foreach( $results as $row ){
      $employee = $row['fullname'];
      }






      if (isset($_GET['del']) && ($_GET['del'] == 'true')) {
          $delete = array(
              'p_id' => $_GET['id']
          );
          $deleted = $database->delete( 'product', $delete, 1 );
        }



include ( 'head.inc.php' );
?>
<link type="text/css" rel="stylesheet" href="css/bootstrap-table.css" />
<?php include ( 'menu.inc.php' ); ?>

       <div class="container main">
         <div class="row">
           <div class="main-col">

             <div class="panel panel-default">
                 <div class="panel-heading"><strong>MANAGE STOCKS <a href="select-category.php" title="Add Product"><i class="fa fa-plus-circle" aria-hidden="true"></i></a></strong>

                   <div class="pull-right" style="margin-top:-7px;">
                   <input type="search" class="form-control light-table-filter" data-table="order-table" placeholder="Search" />
                   </div>

                </div>
                 <div class="panel-body">
                   <div class="filterable">
                   <table data-toggle="table" id="mytable" class="order-table table">
                     <thead>
                       <tr>
                         <th data-field="category" data-sortable="true">CATEGORY</th>
                         <th>BRAND</th>
                         <th data-field="type" data-sortable="true">PRODUCT NAME</th>
                         <th data-field="stock" data-sortable="true">IN STOCK</th>
                         <th>PURCHASED</th>
                         <th>ACTION</th>
                       </tr>
                     </thead>

                      <?php
                      echo "<tbody>";

                      $query = "SELECT * FROM stock
                      INNER JOIN category ON category.cat_id = stock.cat_id
                      INNER JOIN brand ON brand.brand_id = stock.brand_id
                      INNER JOIN product ON product.p_id = stock.p_id
                      GROUP BY stock.p_id";
                          $results = $database->get_results( $query );
                            foreach( $results as $row ){
                              echo "<tr>";
                              echo "<td>".$row['cat_name']."</td>";
                              echo "<td>".$row['brand_name']."</td>";


                                echo"<td data-cat=".$row['product_name'].">".$row['product_name']."</td>";


                                $query_c = "SELECT p_id,count(*) as total FROM stock WHERE p_id=".$row['p_id']." AND availability = 1 ";
                                $resultsc = $database->get_results( $query_c );
                                foreach( $resultsc as $rowc )
                                {
                                    echo "<td>".$rowc['total']."</td>";
                                }


                                $query_c = "SELECT p_id,count(*) as total FROM stock WHERE p_id=".$row['p_id']." AND availability = 0 ";
                                $resultsc = $database->get_results( $query_c );
                                foreach( $resultsc as $rowc )
                                {
                                    echo "<td>".$rowc['total']."</td>";
                                }


                                ?>
                                <td class="text-center">
                                <a href="stock-details.php?cat=<?php echo $row['cat_id'];?>&brand=<?php echo $row['brand_id'];?>&product=<?php echo $row['p_id'];?>" style="margin-right:15px;"><i class="fa fa-pencil-square-o"></i></a>
                                <a href="stock.php?id=<?php echo $row['p_id'];?>&amp;del=true" onclick="return checkDelete()"><i class="glyphicon glyphicon-trash"></i></a></td>


                     </tr>


                       <?php
                           }
                       ?>

                     </tbody>
                   </table>
                 </div>



               </div><!-- panel-body -->
           </div><!-- panel -->











             <div class="clearfix"></div>
             </div><!--main-col-->
           </div><!--/row-->
         </div><!--/container-->


      <div class="clearfix"></div>
      <?php include ( 'footer.inc.php' ); ?>



      <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Edit Brand Information</h4>
                  </div>
                  <div class="modal-body">
                      <div class="fetched-data"></div>
                  </div>
              </div>
          </div>
      </div>




      <div class="modal fade" id="addBrand" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Add Brand</h4>
                  </div>
                  <div class="modal-body">
                      <div class="brand-data"></div>
                  </div>
              </div>
          </div>
      </div>

      <!-- Bootstrap core JavaScript -->
      <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/bootstrap.js"></script>
      <script type="text/javascript" src="js/moment.min.js"></script>

      <script type="text/javascript">
      (function(document) {
        'use strict';
        var LightTableFilter = (function(Arr) {
        var _input;
        var _select;

        function _onInputEvent(e) {
          _input = e.target;
          var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
           Arr.forEach.call(tables, function(table) {
             Arr.forEach.call(table.tBodies, function(tbody) {
               Arr.forEach.call(tbody.rows, _filter);
               });
             });
           }

       function _onSelectEvent(e) {
         _select = e.target;
         var tables = document.getElementsByClassName(_select.getAttribute('data-table'));
           Arr.forEach.call(tables, function(table) {
             Arr.forEach.call(table.tBodies, function(tbody) {
               Arr.forEach.call(tbody.rows, _filterSelect);
               });
             });
           }

       function _filter(row) {
         var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
           row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
           }

       function _filterSelect(row) {
         var text_select = row.textContent.toLowerCase(), val_select = _select.options[_select.selectedIndex].value.toLowerCase();
         row.style.display = text_select.indexOf(val_select) === -1 ? 'none' : 'table-row';
       }

       return {
         init: function() {
           var inputs = document.getElementsByClassName('light-table-filter');
           var selects = document.getElementsByClassName('select-table-filter');
             Arr.forEach.call(inputs, function(input) {
               input.oninput = _onInputEvent;
               });

             Arr.forEach.call(selects, function(select) {
               select.onchange  = _onSelectEvent;
               });
             }
           };
         })(Array.prototype);

         document.addEventListener('readystatechange', function() {
           if (document.readyState === 'complete') {
             LightTableFilter.init();
             }
       });
     })(document);



       $(document).ready(function() {
           $('#myModal').on('show.bs.modal', function (e) {
               var rowid = $(e.relatedTarget).data('id');
               $.ajax({
                   type : 'post',
                   url : 'fetch_brand.php',
                   data :  'rowid='+ rowid,
                   success : function(data){
                   $('.fetched-data').html(data);
                   }
               });
            });



            $('#addBrand').on('show.bs.modal', function (e) {
                var rowid = $(e.relatedTarget).data('id');
                $.ajax({
                    type : 'post',
                    url : 'add_brand.php',
                    data :  'rowid='+ rowid,
                    success : function(data){
                    $('.brand-data').html(data);
                    }
                });
             });


       });



       $('#myModal').on('hidden.bs.modal', function () {
         window.location.reload(true);
         })
        $('#addBrand').on('hidden.bs.modal', function () {
           window.location.reload(true);
           })

           function checkDelete(){
             return confirm('Are you sure to delete this data?');
             }
         </script>
         <script type="text/javascript" src="js/bootstrap-table.js"></script>
     </body>
 </html>
