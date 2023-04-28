@extends('layouts.master-leftbar')



                            



@section('bodyjs')

@endsection



@section('headscript')

<meta name="csrf-token" content="{{ csrf_token() }}" />

@endsection







@section('body')

@include('flash-message')

<h1>Inspektion Employee Report</h1>







<ul class="nav nav-tabs" id="myTab" role="tablist">

  <li class="nav-item active">

    <a class="nav-link" id="home-tab" data-toggle="tab" href="#thermal" role="tab" aria-controls="home" aria-selected="true">รายงานการเข้าทำงาน</a>

  </li>

  <li class="nav-item">

    <a class="nav-link" id="vibrator-tab" data-toggle="tab" href="#vibrator" role="tab" aria-controls="vibrator" aria-selected="false">การลางาน</a>

  </li>

  <li class="nav-item">

    <a class="nav-link" id="camera-tab" data-toggle="tab" href="#camera" role="tab" aria-controls="camera" aria-selected="false">Progress Report</a>

  </li>

  

</ul>

<div class="tab-content" id="myTabContent">

  <div class="tab-pane fade active in" id="thermal" role="tabpanel" aria-labelledby="home-tab">

    <div class="row">

			<div class="col-md-12">

				

				<div class="panel panel-primary" data-collapsed="0">

				

					<div class="panel-heading">

						<div class="panel-title">

                            บันทึกการเข้างาน

						</div>

						

						<div class="panel-options">

							<a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>

							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>

							<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>

							<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>

						</div>

					</div>

					

					<div class="panel-body">

						

						<form role="form" class="form-horizontal form-groups-bordered" action="{{route('savearriveweb')}}" method="post" enctype="multipart/form-data">

                            @csrf

							{{-- <div class="form-group">

								<div class="col-sm-offset-3 col-sm-5">

									<div class="radio">

										<label>

											<input type="radio" name="usenormal_time" id="optionsRadios1" value="true" onclick="inputTime(false)"  checked>เช็คเข้างานตามเวลาปัจจุบัน

										</label>

									</div>

									<div class="radio">

										<label>

											<input type="radio" name="usenormal_time" id="optionsRadios2" value="false" onclick="inputTime(true)">แก้ไขเวลาเข้างาน(กรณีลืมลงชื่อเข้างาน)

										</label>

									</div>

								</div>

							</div> --}}

							

							

                            {{-- <div class="form-group">

								<label class="col-sm-3 control-label">แก้ไขเวลาเข้างาน</label>

								

								<div class="col-sm-2">

									<div class="input-group">

										<input type="text" name="time_edit" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="09:00 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5"  disabled/>

										

										<div class="input-group-addon">

											<a href="#"><i class="entypo-clock"></i></a>

										</div>

									</div>

								</div>

							</div> --}}

							

							

							<div class="form-group">

								<label for="field-ta" class="col-sm-3 control-label">หมายเหตุ</label>

								

								<div class="col-sm-5">

									<textarea class="form-control autogrow" name="notation" id="field-ta" placeholder="กรอกหมายเหตุ."></textarea>

								</div>

							</div>

							<div class="form-group">

								<label for="field-ta" class="col-sm-3 control-label">Work From Home</label>

								

								<div class="col-sm-5">

									<input type="checkbox" name="wfh" style="width:20px;height:20px;" value="1">

								</div>

							</div>

							

							

							

							

							

							

							

							<div class="form-group">

								<div class="col-sm-offset-3 col-sm-5">

									<button type="submit" class="btn btn-success">บันทึกการเข้างาน</button>

								</div>

							</div>

						</form>

						

					</div>

				

				</div>

			

			</div>

		</div>

  </div>

  <div class="tab-pane fade" id="vibrator" role="tabpanel" aria-labelledby="vibrator-tab">

    <div class="row">

			<div class="col-md-12">

				

				<div class="panel panel-primary" data-collapsed="0">

				

					<div class="panel-heading">

						<div class="panel-title">

							รายงานการลางาน

						</div>

						

						<div class="panel-options">

							<a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>

							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>

							<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>

							<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>

						</div>

					</div>

					

					<div class="panel-body">

								

						<form role="form" class="form-horizontal form-groups-bordered" action="{{route('saveleaveformweb')}}" method="post" enctype="multipart/form-data">

                            @csrf

							<div class="form-group">

								<div class="col-sm-offset-3 col-sm-5">

									<div class="radio">

										<label>

											<input type="radio" name="type" id="optionsRadios1" value="onbusiness" onclick="inputTime(false)"  checked>ลากิจ

										</label>

									</div>

									<div class="radio">

										<label>

											<input type="radio" name="type" id="optionsRadios2" value="sick" onclick="inputTime(true)">ลาป่วย

										</label>

									</div>

                                    <div class="radio">

										<label>

											<input type="radio" name="type" id="optionsRadios2" value="other" onclick="inputTime(true)">ลาอื่นๆ

										</label>

									</div>

                                    

								</div>

							</div>

							<div class="form-group">

								<label for="field-ta" class="col-sm-3 control-label">สาเหตุของการลาหยุด</label>

								

								<div class="col-sm-5">

									<textarea class="form-control autogrow" name="leavecause" id="field-ta" placeholder="กรอกสาเหตุ" required></textarea>

								</div>

							</div>



                            <div class="form-group">

                                <label class="col-sm-3 control-label">ลางานระหว่างวันที่</label>

                                

                                <div class="col-sm-5">

                                    

                                    <input type="text" name="dateleave" class="form-control daterange" data-time-picker="true" data-time-picker-increment="5" data-format="YYYY/MM/DD h:mm A" required>

                                    

                                </div>

                            </div>

                            <div class="form-group">

								<div class="col-sm-offset-3 col-sm-5">

									<div class="radio">

										<label>

											<input type="radio" name="havedoc" id="havedoc" value="true" onclick="inputTime(false)"  checked>มีเอกสาร

										</label>

									</div>

									<div class="radio">

										<label>

											<input type="radio" name="havedoc" id="nodoc" value="false" onclick="inputTime(true)">ไม่มีเอกสาร

										</label>

									</div>

                                    

                                    

								</div>

							</div>



                            <div class="form-group">

								<label class="col-sm-3 control-label">ไฟล์เอกสาร</label>

								

								<div class="col-sm-5">

									

									<input type="file" name="uploadfile" class="form-control file2 inline btn btn-primary" data-label="<i class='glyphicon glyphicon-file'></i> Browse" />

									

								</div>

							</div>



                            <div class="form-group">

								<div class="col-sm-offset-3 col-sm-5">

									<button type="submit" class="btn btn-success">บันทึกการลางาน</button>

								</div>

							</div>



                            



                            

						

						</form>



                        

						

					</div>

				

				</div>

			

			</div>

		</div>

  </div>

  <div class="tab-pane fade" id="camera" role="tabpanel" aria-labelledby="camera-tab">

        <div class="row">

			<div class="col-md-12">

				

				<div class="panel panel-primary" data-collapsed="0">

				

					<div class="panel-heading">

						<div class="panel-title">

							Progress Report

						</div>

						

						<div class="panel-options">

							<a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>

							<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>

							<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>

							<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>

						</div>

					</div>

					

					<div class="panel-body">

								

						<form role="form" class="form-horizontal form-groups-bordered" action="{{route('saveprogressreportweb')}}" method="post" enctype="multipart/form-data">

                            @csrf

							<div class="form-group">

								<div class="col-sm-offset-3 col-sm-5">

									<div class="radio">

										<label>

											<input type="radio" name="remainwork"  value="true" onclick="remainWork(true)"  checked>มีงานค้าง

										</label>

									</div>

									<div class="radio">

										<label>

											<input type="radio" name="remainwork"  value="false" onclick="remainWork(false)">ไม่มีงานค้าง

										</label>

									</div>

								</div>

							</div>



                            <div class="form-group">

								<label for="field-ta" class="col-sm-3 control-label">รายละเอียดงานค้าง</label>

								

								<div class="col-sm-5">

									<textarea class="form-control autogrow remainwork" name="remainwork_detail" id="remainwork_detail" placeholder="กรอกรายละเอียด" required></textarea>

								</div>

							</div>



                            <div class="form-group">

								<label class="col-sm-3 control-label">รูปงานค้าง(ถ้ามี)</label>

								

								<div class="col-sm-5">

									

									<input type="file" name="remainwork_file[]" class="form-control file2 inline btn btn-primary remainwork" data-label="<i class='glyphicon glyphicon-file'></i> Browse" multiple>

									

								</div>

							</div>

							



                            <div class="form-group">

								<label for="field-ta" class="col-sm-3 control-label">งานที่ทำ</label>

								

								<div class="col-sm-5">

									<textarea class="form-control autogrow" name="today_work" id="field-ta" placeholder="กรอกสาเหตุ"></textarea>

								</div>

							</div>



                            <div class="form-group">

								<label class="col-sm-3 control-label">รูปงานที่ทำ(ถ้ามี)</label>

								

								<div class="col-sm-5">

									

									<input type="file" name="todaywork_file[]" class="form-control file2 inline btn btn-primary" data-label="<i class='glyphicon glyphicon-file'></i> Browse" multiple>

									

								</div>

							</div>



                            <div class="form-group">

								<div class="col-sm-offset-3 col-sm-5">

                                    ปัญหาระหว่างการทำงาน

                                    <div class="radio">

										<label>

											<input type="radio" name="haveproblem"  value="true" onclick="haveProblem(true)" checked>มี

										</label>

									</div>

									<div class="radio">

										<label>

											<input type="radio" name="haveproblem"  value="false" onclick="haveProblem(false)" >ไม่มี

										</label>

									</div>

									

								</div>

							</div>



                            <div class="form-group">

								<label for="field-ta" class="col-sm-3 control-label">ปัญหาการทำงานที่พบ</label>

								

								<div class="col-sm-5">

									<textarea class="form-control autogrow problemfield" name="problem_with_solution" id="field-ta" placeholder="ปัญหาที่พบ" required></textarea>

								</div>

							</div>
							<div class="form-group">

								<label class="col-sm-3 control-label">รูปภาพปัญหา</label>

								

								<div class="col-sm-5">

									

									<input type="file" name="problem_file[]" class="form-control file2 inline btn btn-primary" data-label="<i class='glyphicon glyphicon-file'></i> Browse" multiple>

									

								</div>

							</div>

							<div class="form-group">

								<label for="field-ta" class="col-sm-3 control-label">วิธีแก้ปัญหา</label>

								

								<div class="col-sm-5">

									<textarea class="form-control autogrow problemfield" name="solve_solution" id="field-ta" placeholder="วิธีแก้ปัญหา" required></textarea>

								</div>

							</div>
							<div class="form-group">

								<label class="col-sm-3 control-label">รูปภาพแสดงวิธีแก้ปัญหา(ถ้ามี)</label>

								

								<div class="col-sm-5">

									

									<input type="file" name="solve_solution_file[]" class="form-control file2 inline btn btn-primary" data-label="<i class='glyphicon glyphicon-file'></i> Browse" multiple>

									

								</div>

							</div>



                            <div class="form-group">

								<div class="col-sm-offset-3 col-sm-5">

									<button type="submit" class="btn btn-success">บันทึก Progress Report</button>

								</div>

							</div>



                            



                            

						

						</form>



                        

						

					</div>

				

				</div>

			

			</div>

		</div>

  </div>

  <div class="tab-pane fade" id="network" role="tabpanel" aria-labelledby="network-tab">

    None

  </div>

</div>



<!-- Imported styles on this page -->

<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">

<link rel="stylesheet" href="assets/js/select2/select2.css">

<link rel="stylesheet" href="assets/js/selectboxit/jquery.selectBoxIt.css">

<link rel="stylesheet" href="assets/js/daterangepicker/daterangepicker-bs3.css">

<link rel="stylesheet" href="assets/js/icheck/skins/minimal/_all.css">

<link rel="stylesheet" href="assets/js/icheck/skins/square/_all.css">

<link rel="stylesheet" href="assets/js/icheck/skins/flat/_all.css">

<link rel="stylesheet" href="assets/js/icheck/skins/futurico/futurico.css">

<link rel="stylesheet" href="assets/js/icheck/skins/polaris/polaris.css">

<script type="text/javascript">

    function inputTime(status){

        if(status == true){

            $('.timepicker').attr('disabled',false)

        }else{

            $('.timepicker').attr('disabled',true)

        }

    }



    function remainWork(status){

        if(status == true){

            $('.remainwork').attr('disabled',false)

        }else{

            $('.remainwork').attr('disabled',true)

        }

    }



    function haveProblem(status){

        if(status == true){

            $('.problemfield').attr('disabled',false)

        }else{

            $('.problemfield').attr('disabled',true)

        }

    }

</script>

@endsection