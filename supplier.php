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
              'sup_id' => $_GET['id']
          );
          $deleted = $database->delete( 'supplier', $delete, 1 );
        }



include ( 'head.inc.php' );
?>
<link type="text/css" rel="stylesheet" href="css/bootstrap-table.css" />
<?php include ( 'menu.inc.php' ); ?>

       <div class="container main">
         <div class="row">
           <div class="main-col">

             <div class="panel panel-default" style="max-width:700px;margin:auto;">
                 <div class="panel-heading"><strong>MANAGE SUPPLIER <a href="#addSupplier" title="Add supplier" id="0" data-toggle="modal" data-id=""><i class="fa fa-plus-circle" aria-hidden="true"></i></a></strong>

                   <div class="pull-right" style="margin-top:-7px;">
                   <input type="search" class="form-control light-table-filter" data-table="order-table" placeholder="Search" />
                   </div>

                </div>
                 <div class="panel-body">
                   <div class="filterable">
                   <table data-toggle="table" id="mytable" class="order-table table table-bordred table-striped">
                     <thead>
                       <tr>

                         <th data-field="type" data-sortable="true">SUPPLIER NAME</th>
                         <th></th>
                         <th></th>
                       </tr>
                     </thead>

                      <?php
                      echo "<tbody>";
                        $query = "SELECT * FROM supplier ORDER BY supplier";
                          $results = $database->get_results( $query );
                            foreach( $results as $row ){
                              echo "<tr>
                                    <td data-cat=".$row['supplier'].">".$row['supplier']."</td>";

                                    echo'<td class="text-center"><a href="#myModal" title="Edit" id="" data-toggle="modal" data-id="'.$row['sup_id'].'">
                                    <i class="fa fa-pencil-square-o"></i></a></td>';


                     ?>
                                    <td class="text-center"><a href="supplier.php?id=<?php echo $row['sup_id'];?>&amp;del=true" onclick="return checkDelete()"><i class="glyphicon glyphicon-trash"></i></a></td>


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
                      <h4 class="modal-title">Edit category information</h4>
                  </div>
                  <div class="modal-body">
                      <div class="fetched-data"></div>
                  </div>
              </div>
          </div>
      </div>




      <div class="modal fade" id="addSupplier" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Add category</h4>
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
                   url : 'fetch_supplier.php',
                   data :  'rowid='+ rowid,
                   success : function(data){
                   $('.fetched-data').html(data);
                   }
               });
            });



            $('#addSupplier').on('show.bs.modal', function (e) {
                var rowid = $(e.relatedTarget).data('id');
                $.ajax({
                    type : 'post',
                    url : 'add_supplier.php',
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
        $('#addSupplier').on('hidden.bs.modal', function () {
           window.location.reload(true);
           })

           function checkDelete(){
             return confirm('Are you sure to delete this data?');
             }
         </script>
         <script type="text/javascript" src="js/bootstrap-table.js"></script>
     </body>
 </html>
