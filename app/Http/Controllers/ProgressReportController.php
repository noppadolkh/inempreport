<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgressReport;
use App\Models\ArriveCheck;
use App\Models\LeaveForm;
use App\Models\User;
use DataTables;

class ProgressReportController extends Controller
{
    public function __construct() {
        date_default_timezone_set("Asia/Bangkok");
    }
    public function ShowProgressReport(){
        return view('progressreport');
    }

    public function SaveProgressReport(Request $request){
        extract($request->all());
        $newReport = new ProgressReport;
        $newReport->user_id = \Auth::user()->id;
        $newReport->today_work = $today_work;
        $newReport->backlog = $remainwork;
        if($remainwork == 'true'){
            $newReport->backlog_detail = $remainwork_detail;
        }
        
        $newReport->haveproblem = $haveproblem;
        if($haveproblem == 'true'){
            $newReport->problem_with_solution = $problem_with_solution;
            $newReport->solve_solution = $solve_solution;
        }

        $storepath = 'storage/app/';
        $remainwork_filepath = [];
        if($request->hasFile('remainwork_file')){
            foreach($remainwork_file as $key => $uploadfile){
                $name = $uploadfile->getClientOriginalName();
                $extension = $uploadfile->extension();
                $backlogpath = $uploadfile->storeAs(
                    'backlogfile', date('Y-m-d').'_'.\Auth::user()->name.'_'.$name
                );
                $remainwork_filepath[] = $storepath.$backlogpath;
            }
            
            $newReport->backlog_file = json_encode((object)$remainwork_filepath);
        }
        

        $todaywork_filepath = [];
        if($request->hasFile('todaywork_file')){
            foreach($todaywork_file as $key => $uploadfile){
                $name = $uploadfile->getClientOriginalName();
                $extension = $uploadfile->extension();
                $dailyfilepath = $uploadfile->storeAs(
                    'dailyworkfile', date('Y-m-d').'_'.\Auth::user()->name.'_'.$name
                );
                $todaywork_filepath[] = $storepath.$dailyfilepath;
            }
            $newReport->todaywork_file = json_encode((object)$todaywork_filepath);
        }

        $problem_filepath = [];
        if($request->hasFile('problem_file')){
            foreach($problem_file as $key => $uploadfile){
                $name = $uploadfile->getClientOriginalName();
                $extension = $uploadfile->extension();
                $problemfilepath = $uploadfile->storeAs(
                    'problemworkfile', date('Y-m-d').'_'.\Auth::user()->name.'_'.$name
                );
                $problem_filepath[] = $storepath.$problemfilepath;
            }
            $newReport->problem_file = json_encode((object)$problem_filepath);
        }
        
        $solve_solution_filepath = [];
        if($request->hasFile('solve_solution_file')){
            foreach($solve_solution_file as $key => $uploadfile){
                $name = $uploadfile->getClientOriginalName();
                $extension = $uploadfile->extension();
                $solvesolutionfilepath = $uploadfile->storeAs(
                    'solvesolutionfile', date('Y-m-d').'_'.\Auth::user()->name.'_'.$name
                );
                $solve_solution_filepath[] = $storepath.$solvesolutionfilepath;
            }
            $newReport->solve_solution_file = json_encode((object)$solve_solution_filepath);
        }
        
        $newReport->save();

        return back()->with('success','บันทึก Progress Report เรียบร้อย');
    }

    public function ShowOverallProgressReport(Request $request){
        $data['allchecklog'] = ProgressReport::all();
        $data['users'] = User::all();
        if(!isset($request->month)){
            $month = date('m');
        }else{
            $month = $request->month;
        }

        if(!isset($request->year)){
            $year = date('Y');
        }else{
            $year = $request->year;
        }

        $data['selectedmonth'] = $month;
        $data['selectedyear'] = $year;
        $data['selecteduser'] = isset($request->user_id) ? $request->user_id : 'all'; 
        $data['users'] = User::all();
        return view('overall-progressreport',$data);
    }

    public function ShowEmployeeProgressReport(){
        $data['users'] = User::all();
        return view('progressreport');
    }

    public function getAllProgressReport(Request $request){
        if ($request->ajax()) {
            $data = \App\Models\ProgressReport::leftJoin('users','users.id','progress_report.user_id')->select('users.name','progress_report.*')
            ->whereMonth('progress_report.created_at',$request->month)
            ->whereYear('progress_report.created_at',$request->year);
            if(isset($request->user_id) && $request->user_id != 'all'){
                $data = $data->where('user_id',$request->user_id);
            }
            $data = $data->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('today_work', function($row) {
                    
                    return nl2br($row->today_work);
                })
                ->editColumn('created_at', function($row) {
                    
                    return date('Y-m-d H:i:s',strtotime($row->created_at));
                })
                ->editColumn('problem_with_solution', function($row) {
                    
                    return nl2br($row->problem_with_solution);
                })
                ->editColumn('solve_solution', function($row) {
                    
                    return nl2br($row->solve_solution);
                })
                ->editColumn('backlog', function($row) {
                    if($row->backlog == 'true'){
                        return 'มีงานค้าง';
                    }else{
                        return 'ไม่มีงานค้าง';
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน '.$actionBtn;
                })
                ->editColumn('todaywork_file', function($row) {
                    if($row->todaywork_file != null){
                        return '<button class="btn btn-success" data-toggle="modal" data-target="#displayModal" onclick="modalAllImageShow(\'todaywork\',\''.$row->id.'\')">ดูภาพงานที่ทำ</button>';
                    }else{
                        return 'ไม่มีภาพประกอบ';
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน '.$actionBtn;
                })
                ->editColumn('backlog_file', function($row) {
                    if($row->backlog_file != null){
                        return '<button class="btn btn-info" data-toggle="modal" data-target="#displayModal" onclick="modalAllImageShow(\'backlog\',\''.$row->id.'\')">ดูภาพงานค้าง</button>';
                    }else{
                        return 'ไม่มีภาพประกอบ';
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน '.$actionBtn;
                })
                ->editColumn('problem_file', function($row) {
                    if($row->problem_file != null){
                        return '<button class="btn btn-info" data-toggle="modal" data-target="#displayModal" onclick="modalAllImageShow(\'problem\',\''.$row->id.'\')">ดูภาพปัญหา</button>';
                    }else{
                        return 'ไม่มีภาพประกอบ';
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน '.$actionBtn;
                })
                ->editColumn('solve_solution_file', function($row) {
                    if($row->solve_solution_file != null){
                        return '<button class="btn btn-info" data-toggle="modal" data-target="#displayModal" onclick="modalAllImageShow(\'solvesolution\',\''.$row->id.'\')">ดูภาพการแก้ปัญหา</button>';
                    }else{
                        return 'ไม่มีภาพประกอบ';
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน '.$actionBtn;
                })
                
                ->rawColumns(['today_work','problem_with_solution','solve_solution','todaywork_file','backlog_file','problem_file','solve_solution_file'])
                ->make(true);
        }
    }

    public function getSelfProgressReport(Request $request){
        if ($request->ajax()) {
            $data = \App\Models\ProgressReport::leftJoin('users','users.id','progress_report.user_id')->select('users.name','progress_report.*')->where('user_id',\Auth::user()->id)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('today_work', function($row) {
                    
                    return nl2br($row->today_work);
                })
                ->editColumn('created_at', function($row) {
                    
                    return date('Y-m-d H:i:s',strtotime($row->created_at));
                })
                ->editColumn('problem_with_solution', function($row) {
                    
                    return nl2br($row->problem_with_solution);
                })
                ->editColumn('solve_solution', function($row) {
                    
                    return nl2br($row->solve_solution);
                })
                ->editColumn('backlog', function($row) {
                    if($row->backlog == 'true'){
                        return 'มีงานค้าง';
                    }else{
                        return 'ไม่มีงานค้าง';
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน '.$actionBtn;
                })
                ->editColumn('todaywork_file', function($row) {
                    if($row->todaywork_file != null){
                        return '<button class="btn btn-success" data-toggle="modal" data-target="#displayModal" onclick="modalAllImageShow(\'todaywork\',\''.$row->id.'\')">ดูภาพงานที่ทำ</button>';
                    }else{
                        return 'ไม่มีภาพประกอบ';
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน '.$actionBtn;
                })
                ->editColumn('backlog_file', function($row) {
                    if($row->backlog_file != null){
                        return '<button class="btn btn-info" data-toggle="modal" data-target="#displayModal" onclick="modalAllImageShow(\'backlog\',\''.$row->id.'\')">ดูภาพงานค้าง</button>';
                    }else{
                        return 'ไม่มีภาพประกอบ';
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน '.$actionBtn;
                })
                ->editColumn('problem_file', function($row) {
                    if($row->problem_file != null){
                        return '<button class="btn btn-info" data-toggle="modal" data-target="#displayModal" onclick="modalAllImageShow(\'problem\',\''.$row->id.'\')">ดูภาพปัญหา</button>';
                    }else{
                        return 'ไม่มีภาพประกอบ';
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน '.$actionBtn;
                })
                ->editColumn('solve_solution_file', function($row) {
                    if($row->solve_solution_file != null){
                        return '<button class="btn btn-info" data-toggle="modal" data-target="#displayModal" onclick="modalAllImageShow(\'solvesolution\',\''.$row->id.'\')">ดูภาพการแก้ปัญหา</button>';
                    }else{
                        return 'ไม่มีภาพประกอบ';
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน '.$actionBtn;
                })
                
                ->rawColumns(['today_work','problem_with_solution','solve_solution','todaywork_file','backlog_file','problem_file','solve_solution_file'])
                ->make(true);
        }
    }

    public function getReportImage($report_id){
        $report = ProgressReport::find($report_id);
        return response()->json(['remainwork_file'=>json_decode($report->backlog_file),'todaywork_file'=>json_decode($report->todaywork_file),'problem_file'=>json_decode($report->problem_file),'solve_solution_file'=>json_decode($report->solve_solution_file)]);
    }

    public function arriveChartAll(Request $request){
        extract($request->all());
        //$request->month
        if(!isset($request->month)){
            $month = date('m');
        }

        if(!isset($request->year)){
            $year = date('Y');
        }

        $alluserthismonth = ArriveCheck::leftJoin('users','arrive_report.user_id','users.id')
        ->whereMonth('arrive_report.created_at',$month)->whereYear('arrive_report.created_at',$year)->groupBy('user_id')->pluck('user_id');
        
        
        
        $arrivetime = [];
        $monthdate = [];
        $userarrivetime = [];
        

        
        $dayinmonth = date('t',strtotime($year.$month));
        for($d=1;$d<=$dayinmonth;$d++){
            $monthdate[] = $d.'-'.$month.'-'.$year;
        }
        // label: 'values',
        // backgroundColor: 'blue',
        // borderColor: 'blue',
        // data: {!! $arrivetime !!},
        // fill: false,
        $userdata = [];
        foreach($alluserthismonth as $user_id){
            $userarrivetime[$user_id] = [];
            $userarrivetime[$user_id]["user"] = \App\Models\User::where('id',$user_id)->first()->name;
            $userdata[] = \App\Models\User::where('id',$user_id)->first();
            $userarrivetime[$user_id]['values'] = [];
            for($d=1;$d<=$dayinmonth;$d++){
                $findarrive = ArriveCheck::whereYear('created_at',$year)->whereMonth('created_at',$month)->whereDay('created_at',$d)->where('user_id',$user_id)->first();
                if($findarrive != null){
                    $userarrivetime[$user_id]['values'][] = (float)str_replace(':','.',date('H:i',strtotime($findarrive->arrive_time)));
                }else{
                    $userarrivetime[$user_id]['values'][] = 0; 
                }
            }
        }

        $color = ["red","green","blue","yellow","black","orange","purple","red","green","blue","yellow","black","orange","purple"];
        $dataarrive = [];
        $datastring = '';
        $round = 1; 
        foreach($userarrivetime as $user => $arrive){
            
            $dataarrive[] = json_encode(["label"=>$arrive["user"],"data"=>$arrive["values"],"backgroundColor"=>$color[$user],"borderColor"=>$color[$user],"fill"=>"false"]);
            $datastring .= json_encode(["label"=>$arrive["user"],"data"=>$arrive["values"],"backgroundColor"=>$color[$user],"borderColor"=>$color[$user],"fill"=>"false"]);
            if($round < count($userarrivetime) ){
                $datastring .= ',';
            }
            $round++;
        }
        //dd($datastring);    
        // dd(json_encode($dataarrive));
        //dd($arrivetime,$monthdate);
        $data['arrivetime'] = $datastring;
        $data['monthdate'] = json_encode($monthdate);
        $data['selectedmonth'] = $month;
        $data['selectedyear'] = $year;
        $data['arriveuser'] = $userdata;

        // foreach($arrivedata as $key => $arrive){
        //     $arrivetime[] = str_replace(':','.',date('H:i',strtotime($arrivedata->created_at)));
        //     $dateofmonth[] =
        // }

        return view("allmonthly-arrivechart",$data);
    }

    public function arriveChart(Request $request){
        extract($request->all());
        //$request->month
        if(!isset($request->month)){
            $month = date('m');
        }

        if(!isset($request->year)){
            $year = date('Y');
        }
        $arrivedata = ArriveCheck::whereMonth('created_at',$month)->where('user_id',\Auth::user()->id)->get();
        $arrivetime = [];
        $monthdate = [];
        
        
        $dayinmonth = date('t',strtotime($year.$month));
        for($d=1;$d<=$dayinmonth;$d++){
            $monthdate[] = $d.'-'.$month.'-'.$year;
            $findarrive = ArriveCheck::whereYear('created_at',$year)->whereMonth('created_at',$month)->whereDay('created_at',$d)->where('user_id',\Auth::user()->id)->first();
            if($findarrive != null){
                $arrivetime[] = (float)str_replace(':','.',date('H:i',strtotime($findarrive->arrive_time)));
            }else{
                $arrivetime[] = 0; 
            }
        }

        //dd($arrivetime,$monthdate);
        $data['arrivetime'] = json_encode($arrivetime);
        $data['monthdate'] = json_encode($monthdate);
        $data['selectedmonth'] = $month;
        $data['selectedyear'] = $year;

        // foreach($arrivedata as $key => $arrive){
        //     $arrivetime[] = str_replace(':','.',date('H:i',strtotime($arrivedata->created_at)));
        //     $dateofmonth[] =
        // }

        return view("monthly-arrivechart",$data);
    }

    
    
}
