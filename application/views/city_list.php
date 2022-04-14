<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <title>Employee Data</title>
  </head>
  <body>


    <div class="container-fluid">
      <h2 class="text-center">City Data</h2> 

      <br>
      <div class="row">
        <div class="container">       
          <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
              <table id="example" class="table">
                <thead>
                  <th>ID</th>
                  <th>City</th>
                  <th>State</th>                  
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class="col-md-1"></div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" ></script>

    <script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
     
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>      
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      
    <script type="text/javascript">
      $(document).ready( function () {


       var table = $('#example').DataTable({         
              
             dom: 'Blfrtip',
             buttons: [
                          {
                             extend: 'copy',
                             text: "<span class='glyphicon glyphicon-print'></span> Copy",
                             title: 'Copy',                               
                             action: newexportaction
                          },
                          {
                             "extend": 'excel',
                             text: "<span class='glyphicon glyphicon-print'></span> Excel",
                             "title": 'Excel',                               
                             "action": newexportaction
                          },
                          {
                             "extend": 'csv',
                             text: "<span class='glyphicon glyphicon-print'></span> CSV",
                             "title": 'CSV',                               
                             "action": newexportaction
                          },
                          {
                             "extend": 'pdf',
                             text: "<span class='glyphicon glyphicon-print'></span> PDF",
                             "title": 'PDF',                               
                             "action": newexportaction
                          },
                          {
                             "extend": 'print',
                             text: "<span class='glyphicon glyphicon-print'></span> Print",
                             "title": 'Print',                                
                             "action": newexportaction
                          }                
                
             ],             
          'serverSide': 'true',
          'lengthMenu': [[10,25, 100, -1], [10,25, 100, "All"]],
          'pageLength': 10,
          'processing': 'true',
          'paging': 'true',
          //'orderCellsTop': 'true',
          //'fixedHeader': 'true',
          'searching': 'true',
          'order': [],
          'ajax': {
            'url': '<?php echo base_url(); ?>Employee/getCityListAjax',
            'type': 'post',
            "data": function (d) {
             //d.search_name = $('#search_name').val();                     	
           }
         },               
       });



    function newexportaction(e, dt, button, config) {
          var self = this;
          var oldStart = dt.settings()[0]._iDisplayStart;
          dt.one('preXhr', function (e, s, data) {
              // Just this once, load all data from the server...
              data.start = 0;
              data.length = 2147483647;
              dt.one('preDraw', function (e, settings) {
                  // Call the original action function
                  if (button[0].className.indexOf('buttons-copy') >= 0) {
                      $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                  } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                      $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                          $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                          $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                  } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                      $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                          $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                          $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                  } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                      $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                          $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                          $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                  } else if (button[0].className.indexOf('buttons-print') >= 0) {
                      $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                  }
                  dt.one('preXhr', function (e, s, data) {
                      // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                      // Set the property to what it was before exporting.
                      settings._iDisplayStart = oldStart;
                      data.start = oldStart;
                  });
                  // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                  setTimeout(dt.ajax.reload, 0);
                  // Prevent rendering of the full data to the DOM
                  return false;
              });
          });
          // Requery the server with the new one-time export settings
          dt.ajax.reload();
}

      } );
    </script>

  </body>
  </html>