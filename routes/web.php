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

//后台路由
Route::group(['prefix' => 'admin'],function(){
    //后台登陆页面
    Route::get('public/login','Admin\PublicController@login')->name('login');
    //后台退出地址
    Route::get('public/logout','Admin\PublicController@logout');

    //后台登陆处理页面
    Route::post('public/check','Admin\PublicController@check');

    //初始化后台的系统设置
    Route::post('public/inization','Admin\SystemController@inization');

    //初始化管理员信息
    Route::post('manager/initzation','Admin\ManagerController@inization')->middleware('initzation.auth');


    //项目初始化
    // Route::any('public/','Admin\PublicController@index');


});

//需要认证的后台路由
Route::group(['prefix' => 'admin','middleware' => ['admin.auth']],function(){

    //后台首页的路由
    Route::get('index/index','Admin\IndexController@index');
    Route::get('index/welcome','Admin\IndexController@welcome');

    //管理员的管理模块
    Route::get('manager/index','Admin\ManagerController@index');

    //权限的管理模块
    Route::get('auth/index','Admin\AuthController@index');
    Route::any('auth/add','Admin\AuthController@add');

    //角色的管理模块
    Route::get('role/index','Admin\RoleController@index');
    Route::any('role/assign','Admin\RoleController@assign');
    Route::any('role/add','Admin\RoleController@add');

    //会员模块
    Route::get('member/index','Admin\MemberController@index');
    Route::any('member/add','Admin\MemberController@add');
    Route::any('member/adds','Admin\MemberController@adds');
    Route::delete('member/delete','Admin\MemberController@delete');
    //批量删除
    Route::delete('members/delete','Admin\MemberController@deletes');
    Route::any('member/modify/{id}','Admin\MemberController@modify');
    //异步头像上传
    Route::post('uploader/webuploader','Admin\UploaderController@index');
    //异步四级联动数据获取
    // Route::get('member/getAreaById','Admin\UploaderController@getAreaById');
    //tableau模块
    //tableau测试
    Route::get('table/index','Admin\TableController@index');
    //tableau用户的状态修改
    Route::post('table/status','Admin\TableController@status');
    //报表权限分配
    Route::any('table/auth/{id}','Admin\TableController@auth');
    Route::any('table/auths/{id}','Admin\TableController@auths');
    //站内用户映射tableau用户
    Route::any('table/user/{id}','Admin\TableController@user');
    Route::any('table/users/{id}','Admin\TableController@users');
    // 刷新tableau票据
    // Route::get('table/refresh','Admin\TableController@refresh');

    //修改全局配置
    Route::any('system/update','Admin\SystemController@update');


    //excel模板导出
    Route::get('member/excel','Admin\MemberController@excel');

});

//后台二需要认证的后台路由
Route::group(['prefix' => 'admintwo','middleware' => ['admin.auth']],function(){

    //后台首页的路由
    Route::get('index/index','Admintwo\IndexController@index');
    Route::get('index/welcome','Admintwo\IndexController@welcome');

    //管理员的管理模块
    Route::get('manager/index','Admintwo\ManagerController@index');

    //权限的管理模块
    Route::get('auth/index','Admintwo\AuthController@index');
    Route::any('auth/add','Admintwo\AuthController@add');

    //角色的管理模块
    Route::get('role/index','Admintwo\RoleController@index');
    Route::any('role/assign','Admintwo\RoleController@assign');
    Route::any('role/add','Admintwo\RoleController@add');

    //会员模块
    Route::get('member/index','Admintwo\MemberController@index');
    Route::any('member/add','Admintwo\MemberController@add');
    Route::delete('member/delete','Admintwo\MemberController@delete');
    Route::any('member/modify/{id}','Admintwo\MemberController@modify');

    //异步头像上传
    Route::post('uploader/webuploader','Admintwo\UploaderController@index');
    //异步四级联动数据获取
    // Route::get('member/getAreaById','Admin\UploaderController@getAreaById');
    //tableau模块
    //tableau测试
    Route::get('table/index','Admintwo\TableController@index');
    //tableau用户的状态修改
    Route::post('table/status','Admintwo\TableController@status');
    //报表权限分配
    Route::any('table/auth/{id}','Admintwo\TableController@auth');
    // 刷新tableau票据
    // Route::get('table/refresh','Admin\TableController@refresh');

    //修改全局配置
    Route::any('system/update','Admintwo\SystemController@update');

});

//后台三需要认证的后台路由
Route::group(['prefix' => 'adminthree','middleware' => ['admin.auth']],function(){

    //后台首页的路由
    Route::get('index/index','Adminthree\IndexController@index');
    Route::get('index/welcome','Adminthree\IndexController@welcome');

    //管理员的管理模块
    Route::get('manager/index','Adminthree\ManagerController@index');

    //权限的管理模块
    Route::get('auth/index','Adminthree\AuthController@index');
    Route::any('auth/add','Adminthree\AuthController@add');

    //角色的管理模块
    Route::get('role/index','Adminthree\RoleController@index');
    Route::any('role/assign','Adminthree\RoleController@assign');
    Route::any('role/add','Adminthree\RoleController@add');

    //会员模块
    Route::get('member/index','Adminthree\MemberController@index');
    Route::any('member/add','Adminthree\MemberController@add');
    Route::any('member/adds','Adminthree\MemberController@adds');
    Route::delete('member/delete','Adminthree\MemberController@delete');
    Route::any('member/modify/{id}','Adminthree\MemberController@modify');
    //批量删除
    Route::delete('members/delete','Adminthree\MemberController@deletes');
    //异步头像上传
    Route::post('uploader/webuploader','Adminthree\UploaderController@index');
    //异步四级联动数据获取
    // Route::get('member/getAreaById','Admin\UploaderController@getAreaById');
    //tableau模块
    //tableau测试
    Route::get('table/index','Adminthree\TableController@index');
    //tableau用户的状态修改
    Route::post('table/status','Adminthree\TableController@status');

    //站内用户映射tableau用户
    Route::any('table/user/{id}','Adminthree\TableController@user');
    Route::any('table/users/{id}','Adminthree\TableController@users');

    //站内用户项目组的映射
    Route::any('report/user/{id}','Adminthree\MemberController@report');

    //报表权限分配
    Route::any('table/auth/{id}','Adminthree\TableController@auth');
    Route::any('table/auths/{id}','Adminthree\TableController@auths');
    // 刷新tableau票据
    // Route::get('table/refresh','Admin\TableController@refresh');

    //修改全局配置
    Route::any('system/update','Adminthree\SystemController@update');
    //excel模板导出
    Route::get('member/excel','Admin\MemberController@excel');

    //报表操作
    Route::get('report/index','Adminthree\ReportController@index');
    Route::any('report/{id}','Adminthree\ReportController@relation');
    Route::any('report/groups/{id}','Adminthree\ReportController@relations');

});




