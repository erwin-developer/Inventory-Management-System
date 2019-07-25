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

      if (isset($_GET['del']) && ($_GET['del'] == 'true')) {
          $delete = array(
              'cust_id' => $_GET['id']
          );
          $deleted = $database->delete( 'customer', $delete, 1 );
        }



include ( 'head.inc.php' );
?>
<link type="text/css" rel="stylesheet" href="css/bootstrap-table.css" />
<?php include ( 'menu.inc.php' ); ?>


       <div class="container main">
         <div class="row">
           <div class="main-col">

             <div class="panel panel-default">
                 <div class="panel-heading"><strong>MANAGE CUSTOMER <a href="#addData" title="Add Customer" id="0" data-toggle="modal" data-id=""><i class="fa fa-plus-circle" aria-hidden="true"></i></a></strong>

                   <div class="pull-right" style="margin-top:-7px;">
                   <input type="search" class="form-control light-table-filter" data-table="order-table" placeholder="Search" />
                   </div>

                </div>
                 <div class="panel-body">
                   <div class="filterable">
                   <table data-toggle="table" id="mytable" class="order-table table table-bordred table-striped">
                     <thead>
                       <tr>
                         <th data-field="type" data-sortable="true">FULLNAME</th>
                         <th>ADDRESS</th>
                         <th>CONTACT</th>
                         <th></th>
                       </tr>
                     </thead>

                      <?php
                      echo "<tbody>";
                      $query = "SELECT * FROM customer ORDER BY cust_name";

                        $results = $database->get_results( $query );
                            foreach( $results as $row ){
                              echo "<tr>
                                    <td data-cat=".$row['cust_name'].">".$row['cust_name']."</td>
                                    <td>".$row['cust_address']."</td>
                                    <td>".$row['contact']."</td>";


                     ?>
                                    <td class="text-center">
                                      <a style="margin-right:30px;" href="<?php echo $row['fb'];?>" target="_blank">
                                      <i class="fa fa-facebook"></i></a>

                                      <a style="margin-right:30px;" href="#editData" title="Edit" id="" data-toggle="modal" data-id="<?php echo $row['cust_id'];?>">
                                      <i class="fa fa-pencil-square-o"></i></a>

                                      <a href="customer.php?id=<?php echo $row['cust_id'];?>&amp;del=true" onclick="return checkDelete()"><i class="glyphicon glyphicon-trash"></i></a></td>


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



      <div class="modal fade" id="editData" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Edit Customer Information</h4>
                  </div>
                  <div class="modal-body">
                      <div class="edit-data"></div>
                  </div>
              </div>
          </div>
      </div>




      <div class="modal fade" id="addData" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Add New Customer</h4>
                  </div>
                  <div class="modal-body">
                      <div class="add-data"></div>
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
           $('#editData').on('show.bs.modal', function (e) {
               var rowid = $(e.relatedTarget).data('id');
               $.ajax({
                   type : 'post',
                   url : 'fetch_customer.php',
                   data :  'rowid='+ rowid,
                   success : function(data){
                   $('.edit-data').html(data);
                   }
               });
            });



            $('#addData').on('show.bs.modal', function (e) {
                var rowid = $(e.relatedTarget).data('id');
                $.ajax({
                    type : 'post',
                    url : 'add_customer.php',
                    data :  'rowid='+ rowid,
                    success : function(data){
                    $('.add-data').html(data);
                    }
                });
             });


       });



       $('#editData').on('hidden.bs.modal', function () {
         window.location.reload(true);
         })
        $('#addData').on('hidden.bs.modal', function () {
           window.location.reload(true);
           })

           function checkDelete(){
             return confirm('Are you sure to delete this data?');
             }
         </script>
         <script type="text/javascript" src="js/bootstrap-table.js"></script>
     </body>
 </html>
