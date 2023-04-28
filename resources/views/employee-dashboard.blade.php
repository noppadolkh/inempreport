@extends('layouts.master-leftbar')



                            



@section('bodyjs')

<script src="{{ asset('assets/js/datatables/datatables.js')}}"></script>

<script type="text/javascript">
    
    jQuery( window ).load( function() {

        var $table2 = jQuery( "#table-2" );

        

        // Initialize DataTable

        $table2.DataTable( {

            "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],

            "bStateSave": true,

        });

        

        // Initalize Select Dropdown after DataTables is created

        $table2.closest( '.dataTables_wrapper' ).find( 'select' ).select2( {

            minimumResultsForSearch: -1

        });

        

        // Highlighted rows

        $table2.find( "tbody input[type=checkbox]" ).each(function(i, el) {

            var $this = $(el),

                $p = $this.closest('tr');

            

            $( el ).on( 'change', function() {

                var is_checked = $this.is(':checked');

                

                $p[is_checked ? 'addClass' : 'removeClass']( 'highlight' );

            } );

        } );

        

        // Replace Checboxes

        $table2.find( ".pagination a" ).click( function( ev ) {

            replaceCheckboxes();

        } );

        

        console.log($('#table-2').attr('id'));

    } );

        

    // Sample Function to add new row

    var olddata = [];

    var giCount = 1;

    

    

    



    

</script>

@endsection



@section('headscript')

<meta name="csrf-token" content="{{ csrf_token() }}" />

{{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/> --}}

<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">

{{-- <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}

@endsection







@section('body')

@include('flash-message')

    <h1>Inspektion Employee Report ของพนักงาน {{Auth::user()->name}}</h1>
    <button class="btn btn-info" type="button" style="float: right;" onclick="window.location.href='{{url('arrivechart')}}'">ดูการแสดงผลในรูปแบบกราฟรายเดือน</button>
    <div class="col-md-12" style="margin-top:50px;">
        <div class="col-md-6" style="float: right;">
            <div class="col-md-6" >
                <select class="form-control" style="width:100%;" id="selectedmonth" onchange="filtermonth()">
                    <option value="01" {{$selectedmonth == "01" ? 'selected' : ''}}>มกราคม</option>
                    <option value="02" {{$selectedmonth == "02" ? 'selected' : ''}}>กุมภาพันธ์</option>
                    <option value="03" {{$selectedmonth == "03" ? 'selected' : ''}}>มีนาคม</option>
                    <option value="04" {{$selectedmonth == "04" ? 'selected' : ''}}>เมษายน</option>
                    <option value="05" {{$selectedmonth == "05" ? 'selected' : ''}}>พฤษภาคม</option>
                    <option value="06" {{$selectedmonth == "06" ? 'selected' : ''}}>มิถุนายน</option>
                    <option value="07" {{$selectedmonth == "07" ? 'selected' : ''}}>กรกฎาคม</option>
                    <option value="08" {{$selectedmonth == "08" ? 'selected' : ''}}>สิงหาคม</option>
                    <option value="09" {{$selectedmonth == "09" ? 'selected' : ''}}>กันยายน</option>
                    <option value="10" {{$selectedmonth == "10" ? 'selected' : ''}}>ตุลาคม</option>
                    <option value="11" {{$selectedmonth == "11" ? 'selected' : ''}}>พฤศจิกายน</option>
                    <option value="12" {{$selectedmonth == "12" ? 'selected' : ''}}>ธันวาคม</option>
                </select>
            </div>
            <div class="col-md-6" >
                <select class="form-control" style="width:100%;" id="selectedyear" onchange="filtermonth()">
                    <option value="{{date('Y')}}" {{$selectedyear == date('Y') ? 'selected' : ''}}>{{date('Y')}}</option>
                    <option value="{{date('Y')-1}}" {{$selectedyear == date('Y')-1 ? 'selected' : ''}}>{{date('Y')-1}}</option>
                </select>
            </div>
        </div>
        <script>
            function filtermonth(month){
                month = $('#selectedmonth').val();
                year = $('#selectedyear').val();
                
                window.location.href= '{{url("arriving/employee?month=")}}'+month+'&year='+year
            }
        </script>
    </div>
    <body>

    

<div class="">

    

    <table class="table table-bordered yajra-datatable">

        <thead>

            <tr>

                <th>เวลา</th>

                <th>พนักงาน</th>

                <th>มาทำงาน</th>

                <th>มีการแก้ไขเวลา</th>

                <th>หมายเหตุ</th>

                

            </tr>

        </thead>

        <tbody>

        </tbody>

    </table>

</div>

   

</body>



{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>   --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

{{-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> --}}

{{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> --}}

{{-- <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script> --}}



<script type="text/javascript">

  $(function () {

    

    var table = $('.yajra-datatable').DataTable({

        processing: true,

        serverSide: true,

        aLengthMenu: [[-1, 10, 25, 100], ["All", 10, 25, 100]],

        iDisplayLength: -1,

        order: [[ 0, "desc" ]],

        // ajax: "{{ route('selfarrive.list') }}",
        ajax: {
            url: "{{ route('selfarrive.list') }}",
            type: 'GET',
            data: function (d) {
                // read start date from the element
                d.month = $('#selectedmonth').val();
                d.year = $('#selectedyear').val();
                
            }
        },

        columns: [

            {data: 'arrive_time', name: 'arrive_time'},

            {data: 'name', name: 'name'},

            {data: 'arrived', name: 'arrived'}, 

            {data: 'normal_time', name: 'normal_time'},

            {data: 'notation', name: 'notation'},

            

        ]

    });

    

  });

</script>









@endsection