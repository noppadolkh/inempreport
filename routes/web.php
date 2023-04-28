<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/testdata',function(){
    return "testdata";
});
Route::get('/send/email', 'EmailController@dailymail');

Route::middleware(['auth','web'])->group(function () {

    Route::get('/', 'ArriveCheckController@ShowArrivePage');

    Route::get('empreport','ArriveCheckController@ShowArrivePage');

    Route::get('leaveform','ArriveCheckController@ShowLeaveForm');

    Route::get('progressform','ProgressReportController@ShowProgressReport');

    Route::get('arriving/employee','ArriveCheckController@ShowEmployeeDashboard');

    Route::get('arriving/overall','ArriveCheckController@ShowOverallDashboard');

    Route::get('arrive/list', 'ArriveCheckController@getAllArrive')->name('allarrive.list');

    Route::get('selfarrive/list', 'ArriveCheckController@getSelfArrive')->name('selfarrive.list');

    Route::get('progressreport/employee','ProgressReportController@ShowEmployeeProgressReport');

    Route::get('progressreport/overall','ProgressReportController@ShowOverallProgressReport');

    Route::get('progressreport/list', 'ProgressReportController@getAllProgressReport')->name('progressall.list');

    Route::get('selfprogressreport/list', 'ProgressReportController@getSelfProgressReport')->name('selfprogress.list');

    Route::get('arrivechart','ProgressReportController@arriveChart');
    Route::get('allarrivechart','ProgressReportController@arriveChartAll');

    Route::get('getreportimage/{report_id}','ProgressReportController@getReportImage');
});

Route::middleware(['web'])->group(function () {
    Route::post('savearrive','ArriveCheckController@SaveEmployeeCheckArrive')->name('savearriveweb');

    Route::post('saveleaveform','ArriveCheckController@SaveEmployeeLeaveform')->name('saveleaveformweb');

    Route::post('saveprogressreport','ProgressReportController@SaveProgressReport')->name('saveprogressreportweb');

    Route::get('test',function(){
        $data = \App\Models\ArriveCheck::leftJoin('users','users.id','arrive_report.user_id')->leftJoin('leave_report','leave_report.arrive_report_id','arrive_report.id')->select('users.name','arrive_report.*','leave_report.type','leave_report.leave_cause','leave_report.date_start','leave_report.date_end','leave_report.have_doc','leave_report.doc_path')->latest()->get();
        dd($data);
    });
});




Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
