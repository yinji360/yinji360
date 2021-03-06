<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


// 如果已经登录则访问以下页面自动跳转
if($_SERVER["REQUEST_URI"] == '/login' || $_SERVER["REQUEST_URI"] == '/' 
    || $_SERVER["REQUEST_URI"] == '/invite_by' || $_SERVER["REQUEST_URI"] == '/register' )
{
    if( Auth::check() )
    {
        header('Location: /column/static');
        exit;
    }
}

// 后台登录认证
Route::filter('adminLogin', function()
{
    if( !Auth::check() || Auth::user()->type != -1 || Auth::user()->status != 1 )
    {
        return Redirect::to('/admin/login');
    }
    elseif(!Session::get('telLogin'))
    {
        // 如果不是手机验证也退出
        return Redirect::to('/admin/login');
    }
});


// 前台登录认证
Route::filter('indexLogin', function()
{
    if( !Auth::check() || (Auth::user()->status != 1 && Auth::user()->status != 2) )
    {
        return Redirect::to('/login');
    }
});


// 后台未登录可以访问页面
/*
Route::get('/admin/login', 'LoginController@admin');
Route::post('/admin/doLogin', 'LoginController@doAdminLogin');
*/
Route::get('/admin/login', 'LoginController@adminFromTel');
Route::post('/admin/doLogin', 'LoginController@doAdminLoginFromTel');
Route::get('/admin/prompt', '\Admin\PromptController@index');

// 后台管理路由组,需要后台登录认证
Route::group(array('prefix' => 'admin', 'before' => 'adminLogin'), function(){
	Route::get('/', '\Admin\IndexController@index');
	// Route::get('/userList', '\Admin\UserController@showList');
	// Route::get('/userEdit/{id}', '\Admin\UserController@showEdit');
	// Route::post('/doUserEdit', '\Admin\UserController@doEdit');
	// Route::post('/doUserDel', '\Admin\UserController@doDel');

    Route::get('/topic', '\Admin\TopicController@index');
    Route::get('/topic/add', '\Admin\TopicController@showAdd');
    Route::post('/topic/doAdd', '\Admin\TopicController@doAdd');
    Route::get('/topic/edit', '\Admin\TopicController@showEdit');
    Route::post('/topic/doEdit', '\Admin\TopicController@doEdit');
    Route::get('/topic/doDel', '\Admin\TopicController@doDel');
    Route::get('/topic/column', '\Admin\TopicController@showColumn');
    Route::get('/topic/exam', '\Admin\TopicController@showExam');

    // 试卷
    Route::get('/examPaper', '\Admin\ExamPaperController@index');
    Route::get('/examPaper/add', '\Admin\ExamPaperController@showAdd');
    Route::post('/examPaper/doAdd', '\Admin\ExamPaperController@doAdd');
    Route::get('/examPaper/edit', '\Admin\ExamPaperController@showEdit');
    Route::post('/examPaper/doEdit', '\Admin\ExamPaperController@doEdit');
    Route::post('/examPaper/editStatus', '\Admin\ExamPaperController@editStatus');
    Route::get('/examPaper/clist', '\Admin\ExamPaperController@showClist');
    Route::get('/examPaper/child', '\Admin\ExamPaperController@showChild');
    Route::get('/examPaper/child/edit', '\Admin\ExamPaperController@editClist');
    Route::get('/examPaper/qlist', '\Admin\ExamPaperController@showQlist');
    Route::post('/examPaper/del', '\Admin\ExamPaperController@doDel');
    Route::get('/examPaper/column', '\Admin\ExamPaperController@showColumn');
    Route::get('/examPaper/addColumn', '\Admin\ExamPaperController@showAddColumn');
    Route::post('/examPaper/doEditQuestion', '\Admin\ExamPaperController@doEditQuestion');

    Route::resource('examSort', 'Admin\ExamSortController');

    Route::get('/teacher', '\Admin\TeacherController@index');
    Route::get('/teacher/add', '\Admin\TeacherController@add');
    Route::post('/teacher/doAdd', '\Admin\TeacherController@doAdd');
    Route::get('/teacher/edit', '\Admin\TeacherController@edit');
    Route::post('/teacher/doEdit', '\Admin\TeacherController@doEdit');
    Route::post('/teacher/doDel', '\Admin\TeacherController@doDel');
    Route::get('/teacher/sync', '\Admin\TeacherController@sync');

    Route::get('/notice', '\Admin\NoticeController@index');
    Route::get('/notice/add', '\Admin\NoticeController@add');
    Route::post('/notice/doAdd', '\Admin\NoticeController@doAdd');
    Route::get('/notice/edit', '\Admin\NoticeController@edit');
    Route::post('/notice/doEdit', '\Admin\NoticeController@doEdit');
    Route::post('/notice/doDel', '\Admin\NoticeController@doDel');

    Route::get('/notice/allcomment', '\Admin\NoticeController@allcomment'); // 管理员查看所有评论
    Route::post('/notice/doCommentDelMany', '\Admin\NoticeController@doCommentDelMany'); // 管理员批量删除评论
    Route::get('/notice/comment', '\Admin\NoticeController@comment'); // 管理员查看评论
    Route::post('/notice/doCommentDel', '\Admin\NoticeController@doCommentDel'); // 管理员删除评论
    Route::get('/notice/reply', '\Admin\NoticeController@reply'); // 管理员回复评论
    Route::post('/notice/doReply', '\Admin\NoticeController@doReply'); // 管理员删除评论

    Route::get('/student', '\Admin\StudentController@index');
    Route::get('/student/add', '\Admin\StudentController@add');
    Route::post('/student/doAdd', '\Admin\StudentController@doAdd');
    Route::get('/student/edit', '\Admin\StudentController@edit');
    Route::post('/student/doEdit', '\Admin\StudentController@doEdit');
    Route::post('/student/doDel', '\Admin\StudentController@doDel');
    Route::get('/student/import', '\Admin\StudentController@import');
    Route::post('/student/doImport', '\Admin\StudentController@doImport');
});

Route::group(array('prefix' => 'admin', 'before' => 'adminLogin'), function(){
    Route::resource('user', 'Admin\UserController');
    // Route::resource('subject', 'Admin\SubjectController');
    // Route::resource('subject_item', 'Admin\SubjectItemController');
    // Route::resource('subject_item_relation', 'Admin\SubjectItemRelationController');
    // Route::resource('item_content', 'Admin\ItemContentController');
    // Route::resource('subject_content', 'Admin\SubjectContentController');
    // Route::resource('content_exam', 'Admin\ContentExamController');

    Route::resource('product', 'Admin\ProductController');
    Route::resource('log', 'Admin\LogController');
    Route::resource('loginlog', 'Admin\LoginlogController');
    Route::resource('textbook_item', 'Admin\TextbookItemController');

    Route::resource('classes', 'Admin\ClassesController');
    Route::resource('classmate', 'Admin\ClassmateController');

    Route::resource('message', 'Admin\MessageController');
    Route::resource('feedback', 'Admin\FeedbackController');
    Route::resource('favorite', 'Admin\FavoriteController');
    Route::resource('uploadbank', 'Admin\UploadbankController');
    Route::resource('training', 'Admin\TrainingController');

    Route::get('/column/question', 'Admin\ColumnController@questionList');
    Route::resource('column', 'Admin\ColumnController');
    Route::resource('sort', 'Admin\SortController');
    Route::resource('questions', 'Admin\QuestionsController');

    Route::get('/{column}.json', '\Admin\JsonController@index');

    Route::get('/select', 'Admin\SelectController@index');
    Route::post('/relation/sort', '\Admin\RelationController@postSort');
    Route::post('/relation/column', '\Admin\RelationController@postColumn');
    Route::post('/relation/do_question', '\Admin\RelationController@postDoQuestion');
    Route::post('/relation/del_question', '\Admin\RelationController@deleteColumn');
    Route::post('/relation/doExam', '\Admin\RelationController@doExam');
    Route::post('/relation/delExam', '\Admin\RelationController@delExam');
    Route::post('/relation/columnExam', '\Admin\RelationController@doColumnExam');
    Route::post('/relation/delColumnExam', '\Admin\RelationController@delColumnExam');
});

// 前台路由
Route::get('/register', 'LoginController@register');
Route::post('/doRegister', 'LoginController@doRegister');
Route::get('/login', 'LoginController@index');
Route::post('/doLogin', 'LoginController@doLogin');
Route::get('/login/ajax', 'LoginController@ajax');
Route::get('/logout', 'LoginController@logout');
Route::get('/', 'IndexController@index');
Route::get('/testmsg', 'MessageController@mobileMsg');
Route::get('/prompt', 'PromptController@index');
Route::get('/prompt/test', 'TestController@test');
Route::get('/forgot', 'LoginController@forgot');
Route::post('/doForgot', 'LoginController@doForgot');
Route::get('/invite_by', 'LoginController@inviteby');
Route::post('/do_invite_by', 'LoginController@doinviteby');
Route::get('/notice/list', 'NoticeController@showList');
Route::get('/notice/show', 'NoticeController@show');


Route::group(array('before' => 'indexLogin'), function(){
    //
    Route::get('/indexColumn', 'IndexController@column');
	// flash录音
	Route::get('/recorder', 'RecorderController@index');
	Route::post('/recorder/upload', 'RecorderController@upload');

    //我的班级
	Route::get('/students/getmate', 'StudentsController@getmate');
	Route::get('/students/mates', 'StudentsController@mates');
    Route::get('/classes/mates', 'ClassesController@mates');
	Route::get('/stu/mates', 'StuController@mates');
	Route::get('/classes/changeteacher', 'ClassesController@changeteacher');
	Route::get('/classes/receiveclass', 'ClassesController@receiveclass');
    Route::get('/classes/manage', 'ClassesController@manage'); // 班级管理
    Route::get('/classes/manage/doDel', 'ClassesController@manageDel'); // 班级管理删除
    Route::post('/classes/manage/doEdit', 'ClassesController@manageEdit'); // 班级管理保存
    Route::resource('classes', 'ClassesController');
    
    // 我的班级－班级消息

    Route::get('/classes_notice/showList', 'ClassesNoticeController@showList'); // 班级消息列表
    Route::get('/classes_notice/create', 'ClassesNoticeController@create'); // 发布班级消息页面
    Route::get('/classes_notice/edit', 'ClassesNoticeController@edit'); // 修改班级消息页面
    Route::post('/classes_notice/doEdit', 'ClassesNoticeController@doEdit'); // 发布/修改班级消息页面
    Route::get('/classes_notice/doDel', 'ClassesNoticeController@doDel'); // 班级消息删除
    Route::get('/classes_notice/show', 'ClassesNoticeController@show'); // 班级消息查看页面
    Route::post('/classes_notice/doComment', 'ClassesNoticeController@doComment'); // 对班级消息发表评论
    Route::get('/classes_notice/doCommentDel', 'ClassesNoticeController@doCommentDel'); // 删除班级消息的评论
    Route::get('/classes_notice/batchmsg', 'ClassesNoticeController@batchmsg'); // 跳转到班级消息群发页面
    Route::post('/classes_notice/dobatchmsg', 'ClassesNoticeController@dobatchmsg'); // 执行班级消息群发
    
    //训练集
    Route::resource('training', 'TrainingController');
    //班级同学对应
    Route::resource('classmate', 'ClassmateController');
    Route::post('/classmate/postDelete', 'ClassmateController@postDelete');
	Route::post('/classmate/postUpdate', 'ClassmateController@postUpdate');
    Route::any('/classm/add_class', 'ClassmateController@addClass');
    Route::any('/classm/doAddClass', 'ClassmateController@doaddClass');
    Route::get('/training_result', 'TrainingResultController@index');
    //消息
    Route::get('/message/talk', 'MessageController@talk');
    Route::get('/message/delete_all', 'MessageController@deleteAll');
    Route::resource('message', 'MessageController');
    //老师上传题库
    Route::resource('uploadbank', 'UploadBankController');

    // 答题页面
    Route::get('/topic', 'TopicController@index');
    Route::post('/topic/post', 'TopicController@post');
    Route::get('/topic/result', 'TopicController@result');
    Route::post('/topic/postRecorder', 'TopicController@postRecorder');


    // 收藏页面
    Route::get('/favorite', 'FavoriteController@index');
    Route::get('/favorite/del', 'FavoriteController@doDel');
    Route::get('/favorite/ajax', 'FavoriteController@ajax');
    Route::get('/favorite/choose', 'FavoriteController@choose');
    Route::post('/favorite/doChoose', 'FavoriteController@dochoose');
    Route::get('/favorite/sort', 'FavoriteController@sort');
    Route::post('/favorite/sort/doAdd', 'FavoriteController@sortDoAdd');
    Route::post('/favorite/sort/doEdit', 'FavoriteController@sortDoEdit');
    Route::get('/favorite/sort/doDel', 'FavoriteController@sortDoDel');
    Route::get('/favorite/move', 'FavoriteController@move');
    Route::get('/favorite/ajaxSort', 'FavoriteController@ajaxSort'); // 收藏夹管理ajax

    //初级
    Route::get('/column', 'ColumnController@index');
    Route::get('/column/static', 'ColumnController@tmpShow');

    //产品商店
    Route::get('/products', 'ProductsController@index');
    //课件
    Route::get('/courseware', 'CoursewareController@index');
    Route::get('/courseware/show', 'CoursewareController@show');

    Route::get('/games', 'GamesController@index');
    Route::get('/games/show', 'GamesController@show');

    //flash播放
    Route::get('/view_flv', 'ViewController@flv');

    Route::get('/indexSchool', 'IndexController@indexschool');
    Route::get('/about', 'IndexController@about');
    // Route::get('/feedback', 'IndexController@feedback');
    Route::get('/help', 'IndexController@help');
    Route::get('/app', 'IndexController@app');
    Route::get('/interestTest', 'IndexController@interestTest');
    Route::get('/link', 'IndexController@link');
    Route::get('/follow', 'IndexController@follow');

    // 个人中心
    Route::get('/profile', 'ProfileController@index');
    Route::post('/doProfile', 'ProfileController@doProfile');
    Route::get('/profile/passwd', 'ProfileController@showPasswd');
    Route::post('/profile/doPasswd', 'ProfileController@doPasswd');
    Route::get('/profile/up', 'ProfileController@up');
    Route::post('/profile/doUp', 'ProfileController@doUp');
    Route::get('/profile/verify', 'ProfileController@verify');
    Route::post('/profile/doVerify', 'ProfileController@doVerify');

    // 错题记录
    Route::get('/failTopic', 'FailTopicController@index');
    Route::get('/failTopic/del', 'FailTopicController@doDel');

    // 问题反馈
    Route::get('/feedback', 'FeedbackController@index');
    Route::post('/feedback/dopost', 'FeedbackController@doPost');

    // 公告评论
    Route::post('/notice/doComment', 'NoticeController@doComment');
});

// 数据导入
Route::get('/api/import', '\Api\ImportController@index');
Route::get('/api/teacherCheck', '\Api\TeacherCheckController@index');

Route::get('/api/courseware/column', '\Api\CoursewareController@getColumn');
Route::get('/api/courseware/list', '\Api\CoursewareController@getList');
Route::get('/api/courseware/getzip', '\Api\CoursewareController@getZip');