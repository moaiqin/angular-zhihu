<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
*/

Route::get('/',function(){
   return view('index');
});

/*user路由*/

function paginate($page,$limit=null){
    $limit=$limit?:15;
    $skip=($page? $page-1:0)*$limit;
    return [$limit,$skip];
}

function rq($key=null,$default=null){
    if(!$key) return Request::all();
    return Request::get($key,$default);
}
function user_ins(){
    return new App\User();
}
Route::any('api/signup',function (){
    return user_ins()->signup();
});

Route::any('api/login',function(){
    return user_ins()->login();
});

Route::any('api/logout',function(){
    return user_ins()->logout();
});

/*获取用户信息
 * */
Route::any('api/user/read',function(){
    return user_ins()->read();
});


/*
 * 查看用户是否存在
 * */
Route::any('api/user/username_exists',function (){
    return user_ins()->userExists();
});
Route::any('api/user/phone_exists',function (){
    return user_ins()->phone_exists();
});
Route::any('api/user/email_exists',function (){
    return user_ins()->email_exists();
});
/*
 * 修改密码
 * */
Route::any('api/user/change_password',function(){
    return user_ins()->change_password();
});

/*
 *
 * 找回密码
 *
 *(1)发验证码
 *（10验证）
 *  */
Route::any('api/user/reset_password',function (){
    return user_ins()->reset_password();
});
Route::any('api/user/validate_reset_password',function(){
   return user_ins()->validate_reset_password();
});


/*问题路由*/
function question_ins(){
    return new App\Question();
}

Route::any('api/question/add',function(){
    return question_ins()->add();
});

Route::any('api/question/update',function() {
    return question_ins()->change();
});

Route::any('api/question/read',function(){
    return question_ins()->read();
});

Route::any('api/question/remove',function(){
    return question_ins()->remove();
});

/*问题回答路由*/
function answer_ins(){
    return new App\Answer();
}
Route::any('api/answer/add',function(){
    return answer_ins()->add();
});

Route::any('api/answer/update',function(){
    return answer_ins()->change();
});

Route::any('api/answer/read',function(){
    return answer_ins()->read();
});
Route::any('api/answer/remove',function(){
    return answer_ins()->remove();
});





/*评论路由*/

function comment_ins(){
    return new App\Comment();
}
Route::any('api/comment/add',function(){
    return comment_ins()->add();
});

Route::any('api/comment/read',function(){
    return comment_ins()->read();
});

Route::any('api/comment/remove',function(){
    return comment_ins()->remove();
});

/*
 * 点赞路有
 * */

Route::any('api/answer/vote',function(){
    return answer_ins()->vote();
});

Route::any('api/timeline','CommonController@timeline');

//前端路由
Route::any('tpl/page/home',function (){
   return view('page.home');
});
Route::any('tpl/page/signup',function (){
   return view('page.signup');
});
Route::any('tpl/page/question_add',function (){
   return view('page.question_add');
});
Route::any('tpl/page/login',function (){
   return view('page.login');
});
Route::any('tpl/page/user',function (){
   return view('page.user');
});
Route::any('tpl/user/question',function (){
   return view('page.user_question');
});
Route::any('tpl/user/answer',function (){
   return view('page.user_answer');
});
Route::any('tpl/user/desc',function (){
   return view('page.user_desc');
});
Route::any('tpl/page/question_detail',function (){
   return view('page.question_detail');
});
