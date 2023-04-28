@extends('layouts.master-leftbar')



                            



@section('bodyjs')

<script src="{{ asset('assets/js/datatables/datatables.js')}}"></script>



@endsection



@section('headscript')

<meta name="csrf-token" content="{{ csrf_token() }}" />

{{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/> --}}

<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">

{{-- <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}

@endsection







@section('body')

@include('flash-message')

<h1>Inspektion Employee Report ของพนักงานทั้งหมด</h1>
<div class="col-md-12" style="margin-top:50px;">
    <div class="col-md-12" style="float: right;margin-bottom:20px;">
        
        <div class="col-md-6" >
            <select class="form-control" style="width:100%;" id="selecteduser" onchange="filterselect()">
                <option value="all">ทุกคน</option>
                @foreach($users as $user)
                    <option value="{{$user->id}}" {{$selecteduser == $user->id ? 'selected' : ''}}>{{$user->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3" >
            <select class="form-control" style="width:100%;" id="selectedmonth" onchange="filterselect()">
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
        <div class="col-md-3" >
            <select class="form-control" style="width:100%;" id="selectedyear" onchange="filterselect()">
                <option value="{{date('Y')}}" {{$selectedyear == date('Y') ? 'selected' : ''}}>{{date('Y')}}</option>
                <option value="{{date('Y')-1}}" {{$selectedyear == date('Y')-1 ? 'selected' : ''}}>{{date('Y')-1}}</option>
            </select>
        </div>
    </div>
    {{-- <div class="col-md-6" style="float: right;">
        
        
    </div> --}}
    <script>
        function filterselect(){
            month = $('#selectedmonth').val();
            year = $('#selectedyear').val();
            user_id = $('#selecteduser').val();
            window.location.href= '{{url("progressreport/overall?month=")}}'+month+'&year='+year+'&user_id='+user_id;
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

                <th style="min-width:300px;">งานที่ทำ</th>

                <th>ภาพงานที่ทำ</th>

                <th>งานค้าง</th>

                <th>รายละเอียดงานค้าง</th>

                <th>ภาพงานค้าง</th>

                <th>ปัญหาที่พบ</th>

                <th>ภาพปัญหา</th>

                <th>วิธีแก้ปัญหา</th>

                <th>ภาพการแก้ปัญหา</th>

                

            </tr>

        </thead>

        <tbody>

        </tbody>

    </table>

    <!-- Modal -->

    <div class="modal fade " id="displayModal" tabindex="-1" role="dialog" aria-labelledby="displayModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl" role="document">

            <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="displayModalLabel" id="modaltextheader">ภาพประกอบ</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body" id="modalimagebody" style="text-align:center;">

                ...

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                

            </div>

            </div>

        </div>

    </div>

</div>

   

</body>



{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>   --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

{{-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> --}}

{{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> --}}

{{-- <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script> --}}



<script type="text/javascript">



    function modalImageShow(displaytype,imagepath){

        if(displaytype == 'todaywork'){

            $('#modaltextheader').html('ภาพงานที่ทำ');

        }else{

            $('#modaltextheader').html('ภาพงานค้าง');

        }

        image = '<img src="'+imagepath+'" style="width:100%;">';

        $('#modalimagebody').html(image);

        

        

    }

    var baseUrl = '{{url("/")}}';
    function modalAllImageShow(displaytype,report_id){
        

        $.get('{{url("getreportimage")}}'+'/'+report_id,function(data){
            console.log(data.remainwork_file);
            if(displaytype == 'todaywork'){
                $('#modaltextheader').html('ภาพงานที่ทำ');
                objectimage = data.todaywork_file;
            }else if(displaytype == 'backlog'){
                $('#modaltextheader').html('ภาพงานค้าง');
                objectimage = data.remainwork_file;
            }else if(displaytype == 'problem'){
                $('#modaltextheader').html('ภาพปัญหา');
                objectimage = data.problem_file;
            }else{
                $('#modaltextheader').html('ภาพการแก้ปัญหา');
                objectimage = data.solve_solution_file;
            }
            var image = '';
            for (const [key, imagepath] of Object.entries(objectimage)) {
                console.log(imagepath);
                image += '<img src="'+baseUrl+'/'+imagepath+'" style="width:100%;margin-bottom:10px;"><br>';

                    
            }
            $('#modalimagebody').html(image);
        });
    }

    $(function () {

        

        var table = $('.yajra-datatable').DataTable({

            processing: true,

            serverSide: true,

            ajax: {
                url: "{{ route('progressall.list') }}",
                type: 'GET',
                data: function (d) {
                    // read start date from the element
                    d.month = $('#selectedmonth').val();
                    d.year = $('#selectedyear').val();
                    d.user_id = $('#selecteduser').val();
                    
                }
            },
            order: [[ 0, "desc" ]],
            // ajax: "{{ route('progressall.list') }}",

            columns: [

                {data: 'created_at', name: 'created_at'},

                {data: 'name', name: 'name'},

                {data: 'today_work', name: 'today_work'}, 

                {data: 'todaywork_file', name: 'todaywork_file'},

                {data: 'backlog', name: 'backlog'},

                {data: 'backlog_detail', name: 'backlog_detail'},

                {data: 'backlog_file', name: 'backlog_file'},

                {data: 'problem_with_solution', name: 'problem_with_solution'},

                {data: 'problem_file', name: 'problem_file'},

                {data: 'solve_solution', name: 'solve_solution'},

                {data: 'solve_solution_file', name: 'solve_solution_file'},

                

            ]

        });

        

    });

</script>









@endsection