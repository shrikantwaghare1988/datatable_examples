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
      <h2 class="text-center">EMI Calculator</h2>
      
      <div class="row">
        <div class="container">       
          <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 card" style="padding: 20px;">
              <form class="row g-3">

                <div class="col-md-3">
                  <label for="inputCity" class="form-label">Loan Amount</label>
                  <input type="text" class="form-control" id="loan_amount" name="loan_amount">
                </div>

                <div class="col-md-3">
                  <label for="inputCity" class="form-label">Interest Rate</label>
                  <input type="text" class="form-control" id="interest_rate" name="interest_rate">
                </div>             
              
                <div class="col-md-3">
                  <label for="inputState" class="form-label">Loan Tenure In Months</label>
                  <input type="text" class="form-control" id="tenure_months" name="tenure_months">
                </div>                

                <div class="col-md-3">
                  <label for="inputState" class="form-label">Start Date</label>
                  <input type="text" class="form-control" id="daterange" name="daterange">
                </div>

                <div class="col-md-12">
                    	
                  <button id="calculate" class="btn btn-primary ">Calculate</button>
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
                <th>SN</th>
                <th>Payment Date</th>
                <th>Monthly EMI</th>
                <th>Interest Paid</th>
                <th>Principal Paid</th>
                <th>Balance</th>               
                </thead>
                <tbody id="table_data">                   
                </tbody>
              </table>
            </div>
            <div class="col-md-2"></div>
          </div>
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

        var table = $('#example').DataTable();

        $("#calculate").click(function(e){

            e.preventDefault();
            var loan_amount = $('#loan_amount').val();
            var interest_rate = $('#interest_rate').val();
            var tenure_months = $('#tenure_months').val();
            var daterange = $('#daterange').val();
            
            $.ajax({
                type: 'POST',
                data: {loan_amount:loan_amount,interest_rate:interest_rate,tenure_months:tenure_months,daterange:daterange},
                url: '<?php echo base_url(); ?>Emi_calculator/test',
                success: function(response) 
                {                  
                   const obj = JSON.parse(response);
                   var arrayLength = obj.length;
                   $('#table_data').html("");
                   for(let i = 0; i < obj.length; i++) 
                   {                   
                    $('#table_data').append('<tr><td>' + obj[i].sr_no + '</td><td>' + obj[i].payment_date + '</td><td>' + obj[i].emi + '</td><td>' + obj[i].interest + '</td><td>' + obj[i].principal +'</td><td>' + obj[i].balance + '</td><td></tr>');     
                   }                    
                }
            });
        });

        $('input[name="daterange"]').daterangepicker({
          singleDatePicker: true,  
          autoUpdateInput: false,
          opens: 'left',
    //  locale: {
    //   format: 'MMMM D, YYYY'
    // }
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
          $(this).val(picker.startDate.format('MM/DD/YYYY'));
        });

        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
        });

      } );
    </script>

  </body>
  </html>