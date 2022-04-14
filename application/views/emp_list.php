<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <title>Employee Data</title>
  </head>
  <body>


    <div class="container-fluid">
      <h2 class="text-center">Employees Data</h2>
      
      <div class="row">
        <div class="container">       
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 card" style="padding: 20px;">
              <form class="row g-3">
                <div class="col-md-4">
                  <label for="inputCity" class="form-label">Name</label>
                  <input type="text" class="form-control" id="search_name" name="search_name">
                </div>
                <div class="col-md-4">
                  <label for="inputCity" class="form-label">Email</label>
                  <input type="text" class="form-control" id="search_email" name="search_email">
                </div>
             
              
                <div class="col-md-4">
                  <label for="inputState" class="form-label">Created Date</label>
                  <input type="text" class="form-control" id="daterange" name="daterange" value="" placeholder="Select date" />
                </div>

                  <div class="col-md-12">
                  <label for="inputCity" class="form-label">City</label>
                  <select id="search_city" name="search_city" class="form-control" multiple aria-label="size 3 select example">

                    <option value="Badlapur">Badlapur</option>
                    <option value="Ahmednagar">Ahmednagar</option>
                    <option value="Akalkot">Akalkot</option>
                    <option value="Khopoli">Khopoli</option>
                    <option value="Thane">Thane</option>
                    <option value="Pimpri">Pimpri</option>
                    <option value="Kalyan">Kalyan</option>
                  </select>
                </div>

                <div class="col-md-12">
                    	
                  <button id="search" class="btn btn-primary ">Search</button>
                  <button id="reset" class="btn btn-primary ">Reset</button>
                </div>
              </form>
            </div>
            <div class="col-md-2"></div>
          </div>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="container">       
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
              <table id="example" class="table">
                <thead>
                  <th>Emp No</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Birth date</th>
                  <th>City</th>
                  <th>State</th>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class="col-md-2"></div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>      
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      
    <script type="text/javascript">
      $(document).ready( function () {


       var table = $('#example').DataTable({
          "fnCreatedRow": function(nRow, aData, iDataIndex) {
            $(nRow).attr('id', aData[0]);
          },
          'serverSide': 'true',
          'processing': 'true',
          'paging': 'true',
          'orderCellsTop': 'true',
          'fixedHeader': 'true',
          'searching': 'true',
          'order': [],
          'ajax': {
            'url': '<?php echo base_url(); ?>Employee/getDatatableAjax',
            'type': 'post',
            "data": function (d) {
             d.search_name = $('#search_name').val();
             d.search_email = $('#search_email').val();
             d.search_city = $('#search_city').val();
             d.daterange = $('#daterange').val();             	
           }
         }               
       });


      
       

        $("#search").on("click", function (e) {
          e.preventDefault();        
          var table = $('#example').DataTable();
        //table.search(xh).draw();
        table.draw();
        
      });
        $("#reset").on("click", function (e) {
          e.preventDefault();

          $('#search_name').val('');
          $('#search_email').val('');
          $('#daterange').val(''); 
          //$('#search_city').val('');
          //$('#city2').val(''); 
          //$("#city2").empty();
          $('#search_city').val(null).trigger("change"); 
          var table = $('#example').DataTable();        
          table.draw();

        });

        $('input[name="daterange"]').daterangepicker({
          autoUpdateInput: false,
          opens: 'left',
    //  locale: {
    //   format: 'MMMM D, YYYY'
    // }
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });

        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
          $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
        });
     

        $('#search_city').select2({
          placeholder: 'Select an City',
          maximumInputLength: 20
        });

      } );
    </script>

  </body>
  </html>