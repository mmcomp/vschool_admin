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

Route::middleware('auth')->group(function () {
    Route::get('/', 'ChapterController@index')->name('home');
    Route::get('/forcelogout/{id}', 'Controller@forceLogout');
    Route::post('/changepass', 'Controller@changePass');

    Route::any('/test', 'Controller@test');

    Route::prefix('/level')->group(function () {
        Route::any('/', 'LevelController@index');
        Route::get('/delete/{id}', 'LevelController@delete');
        Route::any('/create', 'LevelController@create');
        Route::any('/edit/{id}', 'LevelController@edit');
    });

    Route::prefix('/school')->group(function () {
        Route::any('/', 'SchoolController@index');
        Route::get('/delete/{id}', 'SchoolController@delete');
        Route::any('/create', 'SchoolController@create');
        Route::any('/edit/{id}', 'SchoolController@edit');
    });

    Route::prefix('/user')->group(function () {
        Route::any('/', 'UserController@index');
        Route::get('/delete/{id}', 'UserController@delete');
        Route::any('/create', 'UserController@create');
        Route::any('/edit/{id}', 'UserController@edit');
        Route::any('/course/{id}', 'UserController@course');
        Route::get('/course_delete/{id}/{course_id}', 'UserController@courseDelete');
        Route::any('/course_create/{id}', 'UserController@courseCreate');
        Route::any('/question/{id}', 'UserController@question');
        Route::get('/question_delete/{id}/{course_id}', 'UserController@questionDelete');
        Route::any('/question_create/{id}', 'UserController@questionCreate');
    });

    Route::prefix('/course')->group(function () {
        Route::any('/', 'CourseController@index');
        Route::get('/delete/{id}', 'CourseController@delete');
        Route::any('/create', 'CourseController@create');
        Route::any('/edit/{id}', 'CourseController@edit');
    });

    Route::prefix('/course_select')->group(function () {
        Route::any('/', 'CourseController@indexSelect');
    });
    
    Route::prefix('/question')->group(function () {
        Route::any('/{id}', 'QuestionController@theIndex');
        Route::get('/delete/{id}', 'QuestionController@delete');
        Route::any('/create/{id}', 'QuestionController@create');
        Route::any('/edit/{id}', 'QuestionController@edit');
    });

    Route::prefix('/lesson')->group(function () {
        Route::any('/', 'LessonController@index');
        Route::get('/delete/{id}', 'LessonController@delete');
        Route::any('/create', 'LessonController@create');
        Route::any('/edit/{id}', 'LessonController@edit');
        Route::any('/page/{id}', 'LessonController@page');
        Route::any('/page_create/{id}', 'LessonController@pageCreate');
        Route::any('/page_edit/{id}', 'LessonController@pageEdit');
        Route::any('/page_delete/{id}', 'LessonController@pageDelete');
    });

    Route::prefix('/chapter')->group(function () {
        Route::any('/', 'ChapterController@index');
        Route::get('/delete/{id}', 'ChapterController@delete');
        Route::any('/create', 'ChapterController@create');
        Route::any('/edit/{id}', 'ChapterController@edit');
    });

    Route::prefix('/protocols')->group(function () {
        Route::any('/', 'ProtocolController@index');
        Route::get('/delete/{id}', 'ProtocolController@delete');
        Route::any('/create', 'ProtocolController@create');
        Route::any('/edit/{id}', 'ProtocolController@edit');
        Route::get('/company/select/{company_id}', 'CompanyController@select');
        Route::get('/company/edit/{company_id}', 'CompanyController@edit');
        Route::post('/company', 'CompanyController@create');
    });
    
    Route::prefix('/protocoldoc')->group(function () {
        Route::get('/{id}', 'ProtocolDocController@theIndex');
        Route::get('/delete/{id}', 'ProtocolDocController@delete');
        Route::any('/create/{id}', 'ProtocolDocController@create');
        Route::any('/edit/{id}', 'ProtocolDocController@edit');
    });

    Route::prefix('/statistics_protocol_type')->group(function () {
        Route::get('/', 'ProtocolTypeController@index');
        Route::get('/delete/{id}', 'ProtocolTypeController@delete');
        Route::any('/create', 'ProtocolTypeController@create');
        Route::any('/edit/{id}', 'ProtocolTypeController@edit');
    });
    Route::prefix('/statistics_certificate_type')->group(function () {
        Route::get('/', 'CertificateTypeController@index');
        Route::get('/delete/{id}', 'CertificateTypeController@delete');
        Route::any('/create', 'CertificateTypeController@create');
        Route::any('/edit/{id}', 'CertificateTypeController@edit');
    });
    /*
    Route::get('/help', 'RequestController@helpMain');
    Route::get('/help/{page}', 'RequestController@helpPage');
    Route::post('/editprofile', 'RequestController@editProfile');
    //---------Resident--------------
    Route::get('/resident', 'ResidentController@index');
    Route::get('/resident_signs', 'ResidentSignController@index');
    Route::get('/resident_coin_trans', 'ResidentCoinTransactionController@index');
    Route::prefix('/resident_catagory')->group(function () {
        Route::get('/', 'ResidentController@catIndex');
        Route::get('/delete/{id}', 'ResidentController@catDelete');
        Route::any('/create', 'ResidentController@catCreate');
        Route::any('/edit/{id}', 'ResidentController@catEdit');
    });
    Route::prefix('/resident_leaderboard')->group(function () {
        Route::get('/', 'ResidentController@leaderBoard');
        Route::get('/accept_privecy', 'ResidentController@acceptPrivecy');
        // Route::any('/create', 'ResidentController@catCreate');
        // Route::any('/edit/{id}', 'ResidentController@catEdit');
    });
    Route::post('/resident_image', 'ResidentController@updateImage');
    Route::get('/resident_activity', 'ResidentController@activityIndex');
    Route::get('/resident_activity/join/{id}', 'ResidentController@activityJoin');    
    Route::get('/resident_tournament', 'ResidentController@tournamentIndex');
    Route::get('/resident_tournament/join/{id}', 'ResidentController@tournamentJoin');
    Route::get('/resident_tournament/leader/{id}', 'ResidentController@tournamentLeader');
    Route::get('/resident_battle', 'ResidentController@battleIndex');
    Route::get('/resident_battle/join/{id}', 'ResidentController@battleJoin');
    //\--------Resident--------------
    //---------WebService--------------
    Route::prefix('/userlevels')->group(function () {
        Route::get('/', 'UserLevelController@index');
        Route::get('/delete/{id}', 'UserLevelController@delete');
        Route::any('/create', 'UserLevelController@create');
        Route::any('/edit/{id}', 'UserLevelController@edit');
    });
    Route::prefix('/user_medalians')->group(function () {
        Route::get('/', 'UserMedalianController@index');
        Route::get('/delete/{id}', 'UserMedalianController@delete');
        Route::any('/create', 'UserMedalianController@create');
        Route::any('/edit/{id}', 'UserMedalianController@edit');
    });
    // Route::prefix('/user_token')->group(function () {
    //     Route::get('/', 'UserTokenController@index');
    // });
    //\--------WebService--------------
    Route::prefix('/req')->group(function () {
        Route::get('/', 'RequestController@index');
        Route::get('/delete/{id}', 'RequestController@requestDelete');
        Route::any('/edit/{id}', 'RequestController@requestEdit');
        Route::any('/permissions/{id}', 'RequestController@permissions');
        Route::any('/permissions_create/{user_id}', 'RequestController@permissionsCreate');
        Route::any('/permissions_edit/{user_id}/{id}', 'RequestController@permissionsEdit');
        Route::get('/permissions_delete/{user_id}/{id}', 'RequestController@permissionsDelete');
        Route::any('/permissions_time/{user_id}/{id}', 'RequestController@permissionsTime');
        Route::any('/permissions_time_edit/{user_propety_times_id}/{user_properties_id}', 'RequestController@permissionsTimeEdit');
        Route::any('/permissions_time_delete/{user_propety_times_id}/{user_properties_id}', 'RequestController@permissionsTimeDelete');
        Route::any('/permissions_time_create/{user_properties_id}', 'RequestController@permissionsTimeCreate');
        Route::any('/catagory/{id}', 'RequestController@catagory');
        Route::any('/catagory_create/{user_id}', 'RequestController@catagoryCreate');
        Route::any('/catagory_edit/{user_id}/{id}', 'RequestController@catagoryEdit');
        Route::get('/catagory_delete/{user_id}/{id}', 'RequestController@catagoryDelete');
    });
    Route::any('/statistics_coin', 'StatisticsController@coins');
    Route::any('/statistics_usage', 'StatisticsController@usages');
    Route::prefix('/activity_groups')->group(function () {
        Route::get('/', 'ActivityController@index');
        Route::get('/delete/{id}', 'ActivityController@delete');
        Route::any('/create', 'ActivityController@create');
        Route::any('/edit/{id}', 'ActivityController@edit');
        Route::get('/reward/{id}', 'ActivityController@reward');
    });
    Route::prefix('/tournamets')->group(function () {
        Route::get('/', 'TournamentController@index');
        Route::get('/delete/{id}', 'TournamentController@delete');
        Route::any('/create', 'TournamentController@create');
        Route::any('/edit/{id}', 'TournamentController@edit');
    });
    Route::prefix('/battle')->group(function () {
        Route::get('/', 'BattleController@index');
        Route::get('/delete/{id}', 'BattleController@delete');
        Route::any('/create', 'BattleController@create');
        Route::any('/edit/{id}', 'BattleController@edit');
    });
    Route::prefix('/fields')->group(function () {
        Route::get('/', 'FieldController@index');
        Route::get('/delete/{id}', 'FieldController@delete');
        Route::any('/create', 'FieldController@create');
        Route::any('/edit/{id}', 'FieldController@edit');
    });
    Route::prefix('/resident_catagories')->group(function () {
        Route::get('/', 'ResidentCatagoryController@index');
        Route::get('/delete/{id}', 'ResidentCatagoryController@delete');
        Route::any('/create', 'ResidentCatagoryController@create');
        Route::any('/edit/{id}', 'ResidentCatagoryController@edit');
    });
    Route::prefix('/levels')->group(function () {
        Route::get('/', 'LevelController@index');
        Route::get('/delete/{id}', 'LevelController@delete');
        Route::any('/create', 'LevelController@create');
        Route::any('/edit/{id}', 'LevelController@edit');
    });
    Route::prefix('/nicknames')->group(function () {
        Route::get('/', 'ResidentNicknameController@index');
        Route::get('/delete/{id}', 'ResidentNicknameController@delete');
        Route::any('/create', 'ResidentNicknameController@create');
        Route::any('/edit/{id}', 'ResidentNicknameController@edit');
    });
    Route::prefix('/sequences')->group(function () {
        Route::get('/', 'SequenceController@index');
        Route::get('/delete/{id}', 'SequenceController@delete');
        Route::any('/create', 'SequenceController@create');
        Route::any('/edit/{id}', 'SequenceController@edit');
        Route::any('/details/{id}', 'SequenceController@detail');
    });
    Route::prefix('/sign')->group(function () {
        Route::get('/', 'SignController@index');
        Route::get('/delete/{id}', 'SignController@delete');
        Route::any('/create', 'SignController@create');
        Route::any('/edit/{id}', 'SignController@edit');
    });
    Route::prefix('/sequence_details')->group(function () {
        Route::get('/delete/{id}/{sequence_id}', 'SequenceController@detailDelete');
        Route::any('/create/{sequence_id}', 'SequenceController@detailCreate');
        Route::any('/edit/{id}/{sequence_id}', 'SequenceController@detailEdit');
    });
    Route::any('/under', 'Controller@under');
    */
});
Route::any('/login', 'Controller@login')->name('login');
// Route::any('/register', 'Controller@register');
// Route::any('/resident_login', 'Controller@rLogin')->name('rlogin');
