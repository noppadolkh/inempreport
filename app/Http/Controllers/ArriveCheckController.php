<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArriveCheck;
use App\Models\LeaveForm;
use DataTables;
use App\Models\ProgressReport;
use App\Models\User;

class ArriveCheckController extends Controller
{

    public function __construct() {
        date_default_timezone_set("Asia/Bangkok");
    }

    public function ShowArrivePage(){
        return view('checkarrive');
    }

    public function ShowLeaveForm(){
        return view('leavepage');
    }

    public function SaveEmployeeCheckArrive(Request $request){
        //dd($request->all());
        extract($request->all());

        // if($usenormal_time == "true"){
        //     $timestamp = date('Y-m-d H:i:s');
        // }else{
        //     $timestamp = date("Y-m-d H:i:s", strtotime(date('Y-m-d').' '.$time_edit));
        // }
        $usenormal_time = "true";
        $timestamp = date('Y-m-d H:i:s');
        $arriveCheck = new ArriveCheck;
        // $arriveCheck->user_id = \Auth::user()->id;
        $arriveCheck->user_id = \Auth::user()->id;
        $arriveCheck->arrived = 'true';
        $arriveCheck->arrive_time = $timestamp;
        $arriveCheck->normal_time = $usenormal_time;
        $arriveCheck->notation = $notation;
        if(isset($wfh)){
            $arriveCheck->wfh = 1;
        }else{
            $arriveCheck->wfh = 0;
        }
        $arriveCheck->save();

        return back()->with('success','บันทึกการเข้าทำงานเรียบร้อย');

    }

    public function SaveEmployeeLeaveform(Request $request){
        extract($request->all());

        $arriveCheck = new ArriveCheck;
        // $arriveCheck->user_id = \Auth::user()->id;
        $arriveCheck->user_id = \Auth::user()->id;
        $arriveCheck->arrived = 'false';
        $arriveCheck->normal_time = 'true';
        $arriveCheck->notation = $leavecause;
        $arriveCheck->save();
        
        $leaveForm = new LeaveForm;
        $leaveForm->arrive_report_id = $arriveCheck->id;
        $leaveForm->type = $type;
        $leaveForm->leave_cause = $leavecause;
        $dateext = explode(' - ',$dateleave);
        $datestart = $dateext[0];
        $dateend = $dateext[1];
        $leaveForm->date_start = date("Y-m-d H:i:s", strtotime($dateext[0]));
        $leaveForm->date_end = date("Y-m-d H:i:s", strtotime($dateext[1]));
        $leaveForm->have_doc = $havedoc;
        if($request->hasFile('uploadfile')){
            $name = $request->file('uploadfile')->getClientOriginalName();
            $extension = $request->file('uploadfile')->extension();
            $path = $request->file('uploadfile')->storeAs(
                'leavedoc', date('Y-m-d').'_'.\Auth::user()->name.'_'.$name
            );
            $leaveForm->doc_path = $path;
        }
        
        $leaveForm->save();

        return back()->with('success','บันทึกการลางานเรียบร้อย');

    }

    public function ShowEmployeeDashboard(Request $request){
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
        return view('employee-dashboard',$data);
    }

    public function ShowOverallDashboard(Request $request){
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
        return view('overall-dashboard',$data);
    }
    public function getAllArrive(Request $request)
    {
        if ($request->ajax()) {
            $data = \App\Models\ArriveCheck::leftJoin('users','users.id','arrive_report.user_id')->leftJoin('leave_report','leave_report.arrive_report_id','arrive_report.id')->select('users.name','arrive_report.*','leave_report.type','leave_report.leave_cause','leave_report.date_start','leave_report.date_end','leave_report.have_doc','leave_report.doc_path','arrive_report.created_at')
            ->whereMonth('arrive_report.created_at',$request->month)
            ->whereYear('arrive_report.created_at',$request->year);
            if(isset($request->user_id) && $request->user_id != 'all'){
                $data = $data->where('user_id',$request->user_id);
            }
            $data = $data->latest()->get();
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('arrived', function($row) {
                    if($row->have_doc == 'true'){
                        $actionBtn = '<a href="'.url('storage/app/'.$row->doc_path).'" class="edit btn btn-success btn-sm">ดูเอกสารประกอบการลางาน</a>';
                    }else{
                        $wfh = '';
                        if($row->arrived == 'true'){
                            $wfh = $row->wfh == 1 ? '(work from home)' : '';
                            $actionBtn = '';
                        }else{
                            $actionBtn = '<font style="color:red;">(ไม่มีเอกสารลางาน)</font>';
                        }
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน'.$wfh : 'ไม่มาทำงาน '.$actionBtn;
                })
                ->editColumn('normal_time', function(ArriveCheck $checker) {
                    return $checker->normal_time == 'true' ? '-' : 'มีการแก้ไขเวลา';
                })
                // ->addColumn('action', function($row){
                //     if($row->have_doc == 'true'){
                //         $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">ดูเอกสารประกอบการลางาน</a>';
                //     }else{
                //         if($row->arrived == 'true'){
                //             $actionBtn = '';
                //         }else{
                //             $actionBtn = '<font style="color:red;">ไม่มีเอกสารลางาน</font>';
                //         }
                        
                //     }
                    
                //     return $actionBtn;
                // })
                ->rawColumns(['arrived'])
                ->make(true);
        }
    }

    public function getSelfArrive(Request $request)
    {
        if ($request->ajax()) {
            $data = \App\Models\ArriveCheck::leftJoin('users','users.id','arrive_report.user_id')->leftJoin('leave_report','leave_report.arrive_report_id','arrive_report.id')->select('users.name','arrive_report.*','leave_report.type','leave_report.leave_cause','leave_report.date_start','leave_report.date_end','leave_report.have_doc','leave_report.doc_path')
            ->whereMonth('arrive_report.created_at',$request->month)
            ->whereYear('arrive_report.created_at',$request->year)
            ->where('user_id',\Auth::user()->id)
            ->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('arrived', function($row) {
                    if($row->have_doc == 'true'){
                        $actionBtn = '<a href="'.url('storage/app/'.$row->doc_path).'" class="edit btn btn-success btn-sm">ดูเอกสารประกอบการลางาน</a>';
                    }else{
                        $wfh = '';
                        if($row->arrived == 'true'){
                            $wfh = $row->wfh == 1 ? '(work from home)' : '';
                            $actionBtn = '';
                        }else{
                            $actionBtn = '<font style="color:red;">(ไม่มีเอกสารลางาน)</font>';
                        }
                        
                    }
                    return $row->arrived == 'true' ? 'มาทำงาน'.$wfh : 'ไม่มาทำงาน '.$actionBtn;
                })
                ->editColumn('normal_time', function(ArriveCheck $checker) {
                    return $checker->normal_time == 'true' ? '-' : 'มีการแก้ไขเวลา';
                })
                // ->addColumn('action', function($row){
                //     if($row->have_doc == 'true'){
                //         $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">ดูเอกสารประกอบการลางาน</a>';
                //     }else{
                //         if($row->arrived == 'true'){
                //             $actionBtn = '';
                //         }else{
                //             $actionBtn = '<font style="color:red;">ไม่มีเอกสารลางาน</font>';
                //         }
                        
                //     }
                    
                //     return $actionBtn;
                // })
                ->rawColumns(['arrived'])
                ->make(true);
        }
    }
}
