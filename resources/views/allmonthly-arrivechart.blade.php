@extends('layouts.master-leftbar')

@section('body')
    <script src="{{asset('js/qc_js/Chart.min.js')}}"></script>
    <script src="{{asset('js/qc_js/utils.js')}}"></script>
    <h1>Inspektion Employee Report ของพนักงานทุกคน</h1>
    <button class="btn btn-success" type="button" style="float: right;" onclick="window.location.href='{{url('arriving/overall')}}'">ดูการแสดงผลในรูปแบบตาราง</button>
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
            function filtermonth(){
                month = $('#selectedmonth').val();
                year = $('#selectedyear').val();
                window.location.href= '{{url("allarrivechart?month=")}}'+month+'&year='+year;
            }
        </script>
    </div>
    <div style="width:100%;height:500px;margin-top:200px;">
        <canvas id="canvas_xbar" style="width:99.5%;height:400px;margin-top:-60px;margin-left:-6px;"></canvas>
    </div>
    <script>
        var alluser = [];
    </script>
    @foreach ($arriveuser as $key => $user)
        <script>
            alluser[{{$key}}] = {{$key+1}};
        </script>
        <div class="col-md-2">
            <label class="checkbox-inline" style="color: black;font-size:25px;">
                <input type="checkbox" name="userlist[]" class="userlist" value="{{$key+1}}" style="width:20px;height:20px;" onclick="getcheckedbox()" checked>&nbsp;{{$user->name}}
            </label>
        </div>
    @endforeach
    

    <script>
        function getcheckedbox(){
            var selected = [];
            $('.userlist:checked').each(function() {
                selected.push($(this).attr('value'));
            });

            alluser.forEach(element => {
                
                if($.inArray( element.toString(), selected ) > -1){
                    console.log("show:"+element)
                    myLine.data.datasets[element].hidden = false;
                }else{
                    console.log("hide:"+element)
                    myLine.data.datasets[element].hidden = true;
                }
                myLine.update();
            });
            console.log(selected);
        }    
        
        
		var config = {
			type: 'line',
			data: {
				labels: {!! $monthdate !!},
				datasets: [{
					label: 'TCT',
					backgroundColor: 'rgb(255,0,0)',
					borderColor: 'rgb(255,0,0)',
					data: [
                        9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00,9.00
					],
					fill: false,
				},{!! $arrivetime !!}
                
                
                ]
			},
			options: {
				animation: false,
				responsive: false,
				title: {
					display: false,
					text: 'test'
				},
				legend: {
					display: false
				},
				tooltips: {
					 
					intersect: true,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: false,
							labelString: 'sampling'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: false,
							labelString: 'Value'
						},
                        ticks: {
                            beginAtZero: false,
                            max: 18.00,
                            userCallback: function(label, index, labels) {
                                // when the floored value is the same as the value we have a whole number
                                return label.toFixed(2);

                            },
                        }
					}]
				}
			}
        };
        
        

		window.onload = function() {
			var ctx = document.getElementById('canvas_xbar').getContext('2d');
            
            //aong edit
            
			
            window.myLine = new Chart(ctx, config);
            updatex()
            updatetct()
			


             
		};

        var datefilter = '{{date("Y-m-d")}}';

        function updatetct(){
            var tct = $('#tct').val();
            var arr = [];
            for(i = 0;i<31;i++){
                arr[i] = tct;
            }
            myLine.data.datasets[1].data = arr;
        }
        
    
    </script>
@endsection