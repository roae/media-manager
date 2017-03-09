<?php
// Media Manager Routes
Route::get('/admin/browser/index', '\Roae\MediaManager\Http\Controllers\MediaController@ls');

Route::post('admin/browser/file', '\Roae\MediaManager\Http\Controllers\MediaController@uploadFiles');
Route::delete('/admin/browser/delete', '\Roae\MediaManager\Http\Controllers\MediaController@deleteFile');
Route::post('/admin/browser/folder', '\Roae\MediaManager\Http\Controllers\MediaController@createFolder');
Route::delete('/admin/browser/folder', '\Roae\MediaManager\Http\Controllers\MediaController@deleteFolder');

Route::post('/admin/browser/rename', '\Roae\MediaManager\Http\Controllers\MediaController@rename');
Route::get('/admin/browser/directories', '\Roae\MediaManager\Http\Controllers\MediaController@allDirectories');
Route::post('/admin/browser/move', '\Roae\MediaManager\Http\Controllers\MediaController@move');
