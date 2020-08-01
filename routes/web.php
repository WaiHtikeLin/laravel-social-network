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

Route::redirect('/', '/home');


Auth::routes();

Route::get('/home', 'PostController@index')->name('home');
Route::get('/create/post', 'PostController@create');
Route::post('/store/post', 'PostController@store');
Route::patch('/toggle/like/to/{post}', 'PostController@toggleLike');


Route::get('/users', 'UserController@index');
Route::get('/followers', 'UserController@showFollowers')->name('followers');
Route::get('/user/profile/{user}', 'UserController@show')->name('profile');
Route::get('/myprofile', 'UserController@showProfile');
Route::get('/edit/profile/{field?}', 'UserController@edit');
Route::get('/request/to/{user}', 'UserController@handleFriendship');
Route::get('/follow/to/{user}', 'UserController@handleFollowing');
Route::get('/block/to/{id}', 'UserController@handleBlocking');
Route::post('/accept/to/{id}', 'UserController@acceptFriendRequest');
Route::delete('/reject/to/{id}', 'UserController@rejectFriendRequest');
Route::delete('/unfriend/to/{id}', 'UserController@unfriend');

Route::get('/notify', function(){
  $user=Auth::user();
  $o=App\User::find(2);
  $user->notify(new App\Notifications\friendRequested($o));
  return view('user.test');
});

Route::post('/add/email', 'UserInfoController@addEmail');
Route::post('/add/phone', 'UserInfoController@addPhone');
Route::post('/add/site', 'UserInfoController@addSite');
Route::post('/add/about', 'UserInfoController@addAbout');
Route::post('/add/bio', 'UserInfoController@addBio');
Route::post('/edit/email/{id}', 'UserInfoController@editEmail');
Route::post('/edit/phone/{id}', 'UserInfoController@editPhone');
Route::post('/edit/site/{id}', 'UserInfoController@editSite');

Route::get('/edit/education/{id}', 'UserInfoController@editEdu');
Route::get('/edit/work/{id}', 'UserInfoController@editWork');
Route::get('/edit/address/{id}', 'UserInfoController@editAddress');

Route::post('/update/education/{id?}', 'UserInfoController@updateEdu');
Route::post('/update/work/{id?}', 'UserInfoController@updateWork');
Route::post('/update/address/{id?}', 'UserInfoController@updateAddress');

Route::delete('/delete/{field}/{id?}', 'UserInfoController@deleteInfo');

Route::get('/notifications', 'NotificationController@getNotifications');
Route::get('/unreadnotifications', 'NotificationController@getUnreadNotifications');
Route::patch('/check/noti/{id}', 'NotificationController@checkNoti');
Route::patch('/read/noti', 'NotificationController@readNoti');
Route::get('/count/unreadnoti', 'NotificationController@countUnreadNoti');

Route::resource('posts.comments', 'CommentController')->shallow();

Route::get('/chat/to/{user}', 'ChatController@startChat');
Route::post('/send/message/to/{id}', 'ChatController@sendMessage');
// Route::get('/get/email', function()
// {
//   return Auth::user()->information->emails[0];
// });
