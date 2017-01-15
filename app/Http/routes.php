<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('pages.dashboard');
});

Route::get('/sites', ['as' => 'all_sites', 'uses' => 'BackupController@allSites']);

Route::any('/sites/add', ['as' => 'add_site', 'uses' => 'BackupController@addSite']);
Route::any('/sites/{id}/edit', ['as' => 'edit_site', 'uses' => 'BackupController@addSite']);  // View, Add, Edit
// Route::any('/sites/{id}', ['as' => 'view_site', 'uses' => 'BackupController@viewSite']);  // View, Add, Edit


/**
 * REST APIs
 */
Route::post('/api/test/ssh', ['as' => 'api.test_ssh', 'uses' => 'BackupController@testSSH']);
Route::post('/api/test/mysql', ['as' => 'api.test_mysql', 'uses' => 'BackupController@testMysql']);
Route::post('/api/backup', ['as' => 'api.backup_site', 'uses' => 'BackupController@backupApi']);
