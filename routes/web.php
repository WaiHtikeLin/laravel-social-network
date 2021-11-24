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

Route::get('/guest', 'Auth\LoginController@guest');

Route::get('/privacy', 'RuleController@privacy');
Route::get('/terms', 'RuleController@terms');
Route::get('/terms_of_service', 'RuleController@terms_of_service');
Route::get('/cookies', 'RuleController@cookies');
Route::get('/about', 'RuleController@about');
Route::get('/help', 'RuleController@help');
Route::post('/add/question', 'RuleController@addQuestion');

Route::get('/search/mobile', 'SearchController@searchMobile');


Route::get('/home', 'PostController@index')->name('home');
Route::get('/notifications', 'NotificationController@index');
Route::get('/messages', 'ChatController@index');
Route::get('/requests', 'UserController@showRequests');
Route::get('/sentrequests', 'UserController@showSentRequests');
Route::get('/options', 'UserController@options');
Route::post('/search', 'SearchController@index');
Route::get('/search/posts/{value}', 'SearchController@getSearchedPosts');


Route::get('/settings', 'SettingController@index');
Route::get('/blocked', 'UserController@showBlocked');
Route::get('/unblock/to/{id}', 'UserController@unblock');


Route::patch('/update/settings', 'SettingController@update');

Route::get('/posts/{type}', 'PostController@index');

Route::get('/create/post', 'PostController@create');
Route::post('/store/post', 'PostController@store');
Route::get('/show/post/{post}', 'PostController@show');
Route::get('/edit/post/{post}', 'PostController@edit');
Route::patch('/update/post/{post}', 'PostController@update');
Route::delete('/posts/{post}', 'PostController@destroy');
Route::get('/get/feeds/{page}', 'PostController@getFeeds')->name('feeds');
Route::get('/get/privates/{page}', 'PostController@getPrivates')->name('privates');
Route::get('/get/favorites/{page}', 'PostController@getFavorites')->name('favorites');

Route::get('/is/shared/or/not/{id}', 'PostController@isPostSharedByAuthUser');


Route::get('/get/{user}/posts/{page}', 'UserController@getViewablePosts')->name('posts');

Route::get('/add/profile_pic', 'UserPhotosController@addProfilePic');

Route::patch('/change/profile_pic/{place?}', 'UserPhotosController@changeProfilePic');
Route::patch('/change/name', 'UserController@changeName');



Route::middleware('can:view,post')->group(function ()
{

  Route::get('/get/post/{post}', 'PostController@getPost');

  Route::patch('/add/{reaction}/to/{post}', 'PostController@addReaction');
  Route::patch('/add/{reaction}/to/{post}/from/{form}', 'PostController@addReaction');
  Route::delete('/remove/reaction/from/{post}', 'PostController@removeReaction');


  Route::patch('/toggle/favorite/to/{post}', 'PostController@toggleFavorite');

  Route::post('/comment/to/{post}', 'CommentController@store');
  Route::get('/comments/from/{post}/{page}', 'CommentController@index');

  Route::get('/likes/from/{post}', 'PostController@getLikes');
  Route::get('/reactions/from/{post}/{page}', 'PostController@getReactions');


  Route::get('/shares/from/{post}/{page}', 'PostController@getShares');
  Route::post('/store/share/of/{post}', 'PostController@storeSharedPost');
  Route::post('/store/copy/of/{post}', 'PostController@storeCopiedPost');


  Route::patch('/posts/{post}/comments/{comment}/replies/{reply}/toggle/like','ReplyController@toggleLike');
  Route::resource('posts.comments.replies', 'ReplyController');


});



Route::get('/users', 'UserController@index');
Route::get('/myprofile', 'UserController@showProfile');
Route::get('/users/{user}/friends', 'UserController@showFriends');
Route::get('/users/{user}/followers', 'UserController@showFollowers');
Route::get('/users/{user}/following', 'UserController@showFollowing');

Route::get('/user/profile/{user}', 'UserController@show')->name('profile');
Route::get('/edit/profile/{field?}', 'UserController@edit');
Route::post('/request/to/{user}', 'UserController@handleFriendship');
Route::post('/follow/to/{user}', 'UserController@handleFollowing');
Route::get('/block/to/{id}', 'UserController@handleBlocking');
Route::post('/accept/to/{user}', 'UserController@acceptFriendRequest');
Route::delete('/reject/to/{user}', 'UserController@rejectFriendRequest');
Route::delete('/unfriend/to/{friend}', 'UserController@unfriend');
Route::delete('/cancel/request/to/{id}', 'UserController@cancelMyRequest');
Route::delete('/cancel/follow/to/{id}', 'UserController@cancelMyFollow');

Route::get('/count/unreadrequests', 'UserController@countUncheckedRequests');
Route::get('/get/requests/{page?}', 'UserController@getRequests');
Route::get('/get/unreadrequests', 'UserController@getUnreadRequests');


Route::get('/get/messages', 'ChatController@getLatestMessages');
Route::get('/get/messages/all/{page}', 'ChatController@getAllMessages');
Route::get('/count/unseenmessages', 'ChatController@countUnseenMessages');
Route::delete('/rooms/{room_id}', 'ChatController@removeChat');

Route::middleware('can:chat,recipient')->group(function ()
{
Route::get('/chat/to/{recipient}', 'ChatController@startChat');
Route::post('/send/message/to/{recipient}', 'ChatController@sendMessage');
Route::get('/get/messages/{recipient}/{index}', 'ChatController@getMessages');
});

Route::patch('/update/message/{id}', 'ChatController@updateMessage');
Route::patch('/seen/to/{id}', 'ChatController@seeMessage');
Route::delete('/delete/message/{id}', 'ChatController@deleteMessage');


Route::get('/notify', function(){
  $user=Auth::user();
  $o=App\User::find(2);
  $user->notify(new App\Notifications\friendRequested($o));
  return view('user.test');
});

Route::post('/update/email/{id?}', 'UserInfoController@updateEmail');
Route::post('/update/phone/{id?}', 'UserInfoController@updatePhone');
Route::post('/update/site/{id?}', 'UserInfoController@updateSite');
Route::post('/update/about', 'UserInfoController@updateAbout');
Route::post('/update/bio', 'UserInfoController@updateBio');
// Route::post('/edit/email/{id}', 'UserInfoController@editEmail');
// Route::post('/edit/phone/{id}', 'UserInfoController@editPhone');
// Route::post('/edit/site/{id}', 'UserInfoController@editSite');

// Route::get('/edit/education/{id}', 'UserInfoController@editEdu');
// Route::get('/edit/work/{id}', 'UserInfoController@editWork');
// Route::get('/edit/address/{id}', 'UserInfoController@editAddress');

Route::post('/update/education/{id?}', 'UserInfoController@updateEdu');
Route::post('/update/work/{id?}', 'UserInfoController@updateWork');
Route::post('/update/address/{id?}', 'UserInfoController@updateAddress');

Route::delete('/delete/{field}/{id?}', 'UserInfoController@deleteInfo');

Route::get('/get/notifications/{page?}', 'NotificationController@getNotifications');
Route::get('/get/unreadnotifications', 'NotificationController@getUnreadNotifications');
Route::patch('/check/noti/{id}', 'NotificationController@checkNoti');
Route::patch('/read/noti', 'NotificationController@readNoti');
Route::delete('/notifications/{id}', 'NotificationController@remove');
Route::get('/count/unreadnoti', 'NotificationController@countUnreadNoti');


Route::patch('/toggle/like/comment/{comment}', 'CommentController@toggleLike');
Route::get('/comments/get/{comment}','CommentController@get');
Route::resource('comments', 'CommentController')->except(['store','index']);

Route::patch('/replies/{reply}', 'ReplyController@update');
Route::delete('/replies/{reply}', 'ReplyController@destroy');
Route::get('/replies/get/{reply}','ReplyController@get');




// Route::get('/get/email', function()
// {
//   return Auth::user()->information->emails[0];
// });
