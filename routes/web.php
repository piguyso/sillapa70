<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});
use App\Event;
use App\Http\Controllers\CopyDataController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('event-selector', [
        'events' => Event::orderByDesc('event_number')->get(),
    ]);
})->name('event.select');

Route::get('/cpn1', function () {
    return view('event-selector', [
        'events' => Event::orderByDesc('event_number')->get(),
    ]);
});

$registerRoutes = function (bool $isEventGroup = true) {
Auth::routes();
// area route
Route::get('/area/{id}','area\AreaController@index')->where('id', '[0-9]+');

Route::get('/area/areasubjectgroup/{id}','area\areaSubjectgroupController@index')->middleware('schoolgroup.context');
Route::get('/area/areasubjectgroup/activity/{id}','area\areaSubjectgroupController@showactivity')->middleware('schoolgroup.context');
Route::get('area/addaddschool/{id}','area\areaAddschoolController@addaddschool')->middleware(['schoolgroup.context', 'registration.network']);
Route::get('area/addaddschool/getschool/{id}','area\areaAddschoolController@getschool')->middleware(['schoolgroup.context', 'registration.network']);


Route::get('area/addaddstudent/{id}','area\areaAddstudentController@addaddstudent')->middleware(['schoolgroup.context', 'registration.network']);
Route::get('area/jowviewactivity/{id}','area\areaJowController@jowviewactivity')->middleware(['schoolgroup.context', 'operator.network']);
route::get('area/addjow/{id}','area\areaJowController@addjow')->middleware(['schoolgroup.context', 'operator.network']);
route::get('area/addjow2','area\areaJowController@addjow2')->middleware(['schoolgroup.context', 'operator.network']);
route::get('area/areajow/{id}/edit','area\areaJowController@edit')->middleware(['schoolgroup.context', 'operator.network']);
route::get('area/areajow2/{id}/edit','area\areaJowController@edit2')->middleware(['schoolgroup.context', 'operator.network']);

//บันทึกผลการแข่ง
route::get('area/centersillapa','area\areaCentersillapaController@index');
route::get('area/centerviewactivity/{id}','area\areaCentersillapaController@centerviewactivity');
route::get('area/centerviewactivityschool/{id}','area\areaCentersillapaController@centerviewactivityschool')->middleware('schoolgroup.context');
route::get('area/centereditactiviy/{id}','area\areaCentersillapaController@centereditactiviy');
route::get('area/centereditstudent/{id}','area\areaCentersillapaController@centereditstudent');
route::get('area/centereditstudentname/{id}','area\areaCentersillapaController@centereditstudentname');

// ตั้งค่าระบบ
route::get('area/areaconfig','area\areaconfigController@index')->middleware('auth');



//พิมพ์เอกสารและเกียรติบัตร
Route::get('area/printpointdoc/{id}','area\areaPrintdocController@printpointdoc');
Route::get('area/studentregisterform/{id}','area\areaPrintdocController@studentregisterform')->middleware('auth');
Route::get('area/teacherregisterform/{id}','area\areaPrintdocController@teacherregisterform')->middleware('auth');
Route::get('area/jowregisterform/{id}','area\areaPrintdocController@jowregisterform')->middleware('auth');

Route::get('area/report/{id}','ReportController@areareport');
route::get('area/reportviewactivity/{id}','ReportController@areajowviewactivity');
route::get('area/reportviewjow/{id}','ReportController@areareportviewjow');

Route::get('area/jow2registerform/{id}','newpdfController@areaexportjow2');

Route::post('dataempty','area\DatatranferController@dataempty')->middleware(['auth', 'systemadmin']);



Route::resource('areaaddstudent','area\areaAddstudentController')->middleware(['schoolgroup.context', 'registration.network']);
Route::resource('areaaddschool','area\areaAddschoolController')->middleware(['schoolgroup.context', 'registration.network']);
Route::resource('areajow','area\areaJowController')->middleware(['schoolgroup.context', 'operator.network']);
Route::resource('areacentersillapa','area\areaCentersillapaController');
Route::resource('areaconfig','area\areaConfigController')->middleware(['auth', 'areamiddleware']);

Route::resource('shoolPrintCard','shoolPrintCardController')->middleware('auth');
Route::resource('shoolPrintCardArea','shoolPrintCardAreaController')->middleware('auth');
// ประกาศของเครือข่าย

Route::get('area/notice_detail/{id}','NoticeController@areanotice_detail');

//แสดงข้อมูล 
Route::get('area/showschooldetail/{id}/{school_group}','area\AreaController@showschooldetail');
Route::get('area/viewactivitydetail/{id}','area\AreaController@viewactivitydetail');
Route::get('area/areaviewactivitydetail/{area}/{id}','area\AreaController@areaviewactivitydetail');
Route::get('area/viewactivity/{id}','area\AreaController@viewactivity');
Route::get('area/showschool/{id}','area\AreaController@showschool');
Route::get('area/showschooldetail/{id}/{school_group}','area\AreaController@showschooldetail');

Route::get('area/showall_print/{id}','area\AreaController@showall_print');
Route::get('area/showall_print2/{id}','area\AreaController@showall_print2');
Route::get('area/showall_print3/{id}','area\AreaController@showall_print3');
Route::get('area/showall_print4/{id}','area\AreaController@showall_print4'); // จำนวนทีมที่มากกว่า 3
Route::get('area/showall_print5/{id}','area\AreaController@showall_print5');
Route::get('area/showall_print6/{id}','area\AreaController@showall_print6');
Route::get('area/showall_print7/{id}','area\AreaController@showall_print7');
Route::get('area/showall_print8/{id}','area\AreaController@showall_print8');

//จัดการผู้ใช้งาน
Route::get('area/areauser','MyUserController@areauser')->middleware(['auth', 'networkadmin', 'schoolgroup.context']);
Route::get('area/myuser/{id}/edit','MyUserController@areauseredit')->middleware(['auth', 'networkadmin', 'schoolgroup.context']);
Route::get('center/myuser/{id}/edit','MyUserController@centeruseredit')->middleware('auth');
Route::put('updateuser','MyUserController@updateuser')->middleware('auth');
Route::put('myuserareaupdate','MyUserController@myuserareaupdate')->middleware(['auth', 'networkadmin', 'schoolgroup.context']);

// เกียรติบัตร
Route::get('area/printtest/{id}','PdfController@areaprinttest');
Route::post('area/printPDF','PdfController@areaprintPDF');
Route::post('area/printPDF2','PdfController@areaprintPDF2');
Route::get('area/printjow/{id}','PdfController@areaprintjowcerf');
Route::get('area/printjow_showbg/{id}','PdfController@areaprintjowcerf_showbg');
Route::get('area/printreportjow/{id}','PdfController@areaprintreportjowcerf');
Route::get('area/printreportjow_showbg/{id}','PdfController@areaprintreportjowcerf_showbg');

Route::get('area/printjow2/{id}','PdfController@areaprintjow2doccerf');
Route::get('area/printjow2_showbg/{id}','PdfController@areaprintjow2doccerf_showbg');
// end area route

// เพิ่ม route สำหรับเช็ค progress
Route::get('datatranfer/progress', 'area\DatatranferController@checkProgress')->middleware(['auth', 'systemadmin'])->name('datatranfer.progress');
Route::get('databackup/progress', 'area\DatatranferController@checkBackupProgress')->middleware(['auth', 'systemadmin'])->name('databackup.progress');
Route::resource('datatranfer','area\DatatranferController')->only(['index', 'store'])->middleware(['auth', 'systemadmin']);
Route::post('databackup', 'area\DatatranferController@databackup')->middleware(['auth', 'systemadmin'])->name('databackup');

Route::resource('myuser','MyUserController')->middleware(['auth', 'systemadmin']);
Route::post('myuser/password-reset', 'PasswordResetManagementController@reset')
    ->middleware(['auth', 'systemadmin'])
    ->name('myuser.password-reset');
Route::get('password/change-required', 'RequiredPasswordChangeController@edit')
    ->middleware('auth')
    ->name('password.change-required.edit');
Route::put('password/change-required', 'RequiredPasswordChangeController@update')
    ->middleware('auth')
    ->name('password.change-required.update');
Route::resource('branch','BranchController')->middleware(['auth', 'systemadmin']);
Route::get('school-import/template', 'SchoolImportController@template')->middleware(['auth', 'systemadmin'])->name('school.import.template');
Route::post('school-import/inspect', 'SchoolImportController@inspect')->middleware(['auth', 'systemadmin'])->name('school.import.inspect');
Route::post('school-import/store', 'SchoolImportController@store')->middleware(['auth', 'systemadmin'])->name('school.import.store');
Route::resource('school','SchoolController')->middleware(['auth', 'systemadmin']);
Route::resource('schoolgroup','SchoolgroupController')->middleware(['auth', 'systemadmin']);
Route::resource('activity','ActivityController');
Route::resource('subjectgroup','SubjectgroupController');
Route::resource('class','ClassController')->middleware(['auth', 'systemadmin']);
Route::resource('addstudent','AddstudentController');
Route::resource('addschool','AddschoolController');
Route::resource('dayofsillapa','DayofsillapaController')->middleware(['auth', 'systemadmin']);
Route::resource('centersillapa','CentersillapaController');
Route::get('systemactive','SystemactiveController@index')->middleware(['auth', 'systemadmin'])->name('systemactive.index');
Route::put('systemactive/rule','SystemactiveController@updateRule')->middleware(['auth', 'systemadmin'])->name('systemactive.rule.update');
Route::put('systemactive/network-school','SystemactiveController@networkSchoolUpdate')->middleware(['auth', 'networkadmin'])->name('systemactive.network-school.update');
Route::resource('title','titleController')->middleware(['auth', 'systemadmin']);
Route::resource('jow','jowController');
Route::resource('report','ReportController');
Route::resource('Notice','NoticeController');

Route::get('secure-assets/{year}/{type}/{file}', 'FileDownloadController@download')
    ->where('year', '[0-9]{4}')
    ->where('type', 'picture|upload')
    ->where('file', '[^/]+')
    ->name('secure.download');

//Route::post('noticeupload',[NoticeController::class,'noticeupload'])->name('noticeupload');
Route::post('noticeupload','NoticeController@noticeupload')->middleware('auth')->name('noticeupload');
Route::get('area/shownotice','NoticeController@shownoticearea')->middleware(['auth', 'schoolgroup.context']);


Route::post('config/years', 'ConfigController@addYear')->name('config.addYear')->middleware(['auth', 'centermiddleware']);
Route::delete('config/years/{id}', 'ConfigController@deleteYear')->name('config.deleteYear')->middleware(['auth', 'centermiddleware']);
Route::post('config/years/active/{id}', 'ConfigController@setActiveYear')->name('config.setActiveYear')->middleware(['auth', 'centermiddleware']);
Route::post('config/years/active-value/{year}', 'ConfigController@setActiveYearByValue')->name('config.setActiveYearByValue')->middleware(['auth', 'centermiddleware']);
Route::post('config/activities/copy', 'ConfigController@copyActivities')->name('config.copyActivities')->middleware(['auth', 'centermiddleware']);
Route::post('config/events', 'ConfigController@storeEvent')->name('config.events.store')->middleware(['auth', 'systemadmin']);
Route::put('config/events/{event}', 'ConfigController@updateEvent')->name('config.events.update')->middleware(['auth', 'systemadmin']);
Route::post('config/events/{event}/activate', 'ConfigController@activateEvent')->name('config.events.activate')->middleware(['auth', 'systemadmin']);
Route::delete('config/events/{event}', 'ConfigController@destroyEvent')->name('config.events.destroy')->middleware(['auth', 'systemadmin']);
Route::resource('config','ConfigController')->middleware(['auth', 'centermiddleware']);

Route::get('myreg','Auth\RegisterController@myreg')->name('myreg.form');
Route::post('myreg','Auth\RegisterController@storeMyreg')->name('myreg.store');
Route::get('get-schools','Auth\RegisterController@getSchoolsByGroup')->name('myreg.getSchools');
// แสดงฟอร์มลงทะเบียน
//Route::get('/register/myreg', [MyRegController::class, 'myreg'])->name('myreg.form');

// รับ submit ลงทะเบียน
//Route::post('/register/myreg', [MyRegController::class, 'store'])->name('myreg.store');

// ajax ดึง school ตาม schoolgroup
//Route::get('/register/get-schools', [MyRegController::class, 'getSchoolsByGroup'])->name('myreg.getSchools');



Route::get('shownotice','NoticeController@shownotice')->middleware('auth');
Route::get('notice_detail/{id}','NoticeController@notice_detail');

Route::get('/addactivity/{id}','ActivityController@addactivity');
Route::get('/addaddschool/{id}','AddschoolController@addaddschool');
Route::get('/viewaddschool/{id}','AddschoolController@viewaddschool');
Route::get('/viewactivity/{id}','homeController@viewactivity');


Route::get('/viewbranch/{id}','homeController@viewbranch');
Route::get('/view/{id}','homeController@viewactivity');
Route::get('/viewall','homeController@viewall');
Route::get('/showall1','homeController@showall1');
Route::get('/showall_print','homeController@showall_print');
Route::get('/showall_print2','homeController@showall_print2');
Route::get('/showall_print3','homeController@showall_print3');
Route::get('/showall_print4','homeController@showall_print4'); // จำนวนทีมที่มากกว่า 3
Route::get('/showall_print5','homeController@showall_print5');
Route::get('/showall_print6','homeController@showall_print6');
Route::get('/showall_print7','homeController@showall_print7');
Route::get('/showall_print8','homeController@showall_print8');
Route::get('/printrandomz','homeController@printrandomz');
Route::get('/printpointdoc','homeController@printpointdoc');

Route::get('/viewactivitydetail/{id}','homeController@viewactivitydetail');

route::get('/centerviewactivity/{id}','CentersillapaController@centerviewactivity');
route::get('/centerviewactivityschool/{id}','CentersillapaController@centerviewactivityschool');
route::get('/centereditactiviy/{id}','CentersillapaController@centereditactiviy');
route::get('/centereditstudent/{id}','CentersillapaController@centereditstudent');
route::get('/centereditstudentname/{id}','CentersillapaController@centereditstudentname');

route::get('/jowviewactivity/{id}','jowController@jowviewactivity');
route::get('/reportviewactivity/{id}','ReportController@jowviewactivity');
route::get('/reportviewjow/{id}','ReportController@reportviewjow');
route::get('/addjow/{id}','jowController@addjow');
route::get('/addjow2','jowController@addjow2');
route::get('/jow2/{id}/edit','jowController@editjow2')->middleware(['auth', 'systemadmin']);


//route::get('/reportviewjow2','ReportController@reportviewjow2');




Route::post('/updatescore','CentersillapaController@updatescore');
Route::post('/updatestudentname/{id}','CentersillapaController@updatestudentname');
Route::post('/printPDF','PdfController@printPDF');
Route::post('/printPDF2','PdfController2@printPDF');

Route::get('/printstudent/{id}','PdfController2@printstudent');

Route::get('/addaddstudent/{id}','AddstudentController@addaddstudent');

//getschoolname js script
Route::get('/addaddschool/getschool/{id}','AddschoolController@getschool');

if ($isEventGroup) {
    Route::get('/','HomeController@index');
}
Route::get('/home','HomeController@index');


Route::get('/adduser', 'HomeController@adduser')->middleware(['auth', 'centermiddleware']);
Route::post('/adduser_prc','HomeController@adduser_prc')->middleware(['auth', 'centermiddleware']);
Route::post('/userlogin','UserauthController@authenticate')->middleware('throttle:5,1');
Route::post('/switch-year','HomeController@switchYear')->name('switch-year');
Route::get('/showschool/{id}','HomeController@showschool');
Route::get('/showschooldetail/{id}/{school_group}','HomeController@showschooldetail');




//Route::put('itemlibPdf','ItemlibPdfController@listitemlib');
Route::get('printactivity/{id}/{score}/{inx}','PdfController@printactivitycerf');
Route::get('printjow/{id}','PdfController@printjowcerf');
Route::get('printjow_showbg/{id}','PdfController@printjowcerf_showbg');
Route::get('printreportjow/{id}','PdfController@printreportjowcerf');
Route::get('printreportjow_showbg/{id}','PdfController@printreportjowcerf_showbg');
Route::get('printcenteractivity/{id}','PdfController@printcenteractivity');
Route::get('printjowdoc/{id}','PdfController@printjowdoccerf');
Route::get('printjow2/{id}','PdfController@printjow2doccerf');
Route::get('printjow2_showbg/{id}','PdfController@printjow2doccerf_showbg');

Route::get('printtest','PdfController@printtest');







Route::get('exportexcel','ExcelController@exportexcel');


Route::get('studentregisterform/{id}','newpdfController@exportstudent');
Route::get('teacherregisterform/{id}','newpdfController@exportteacher');
Route::get('jowregisterform/{id}','newpdfController@exportjow');
Route::get('jow2registerform/{id}','newpdfController@exportjow2');
Route::get('/showprintrandomz/{id}','newpdfController@showprintrandomz'); // print ทีมที่จะจับฉลาก
Route::get('/showprintpointdoc/{id}','newpdfController@showprintpointdoc'); // print ทีมที่ห้คะแนน
Route::get('/printtop/{id}','newpdfController@printtop');


Route::get('/printspeedmath/{id}','newpdfController@printspeedmath'); // print ทีมที่ห้คะแนน



Route::get('/copy-data', [CopyDataController::class, 'index'])->middleware(['auth', 'systemadmin'])->name('copydata.index');
Route::post('/copy-data/process', [CopyDataController::class, 'copyData'])->middleware(['auth', 'systemadmin'])->name('copydata.process');
};

Route::prefix('{eventNumber}')
    ->where(['eventNumber' => '[0-9]+'])
    ->middleware('strip.event.parameter')
    ->namespace('App\\Http\\Controllers')
    ->group(function () use ($registerRoutes) {
        $registerRoutes(true);
    });
