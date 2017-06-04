<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>

    <script src="/momo/resources/views/requireJs/require.js"></script>
    <script src="/momo/resources\views\requireJs\base.js"></script>
    <link rel="stylesheet" href="\momo\resources\views\requireJs\css\main.css"/>
</head>
<body ng-app="xiaohu" user_id="{{session('user_id')}}" ng-controller="baseController">
<div class="navbar navbar-default" id="headerNav">
    <div class="container">
        <div class="navbar-header">
            <a href="#" class="navbar-brand">小呼</a>
        </div>
        <form class="navbar-form navbar-left" id="navbar-form" ng-controller="questionController"  ng-submit="question.add_question()" >
            <div class="input-group">
                <input type="text" placeholder="输入问题" ng-model="question.question_data.title" class="form-control"/>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">提问</button>
                </span>
            </div>
        </form>
        <ul class="nav navbar-nav navbar-right">
            <li class="nav-active"><a href="#" ui-sref="home">首页</a> </li>
            @if(!session('user_id'))
                <li ng-if="!base.logined" ><a href="#" class="" ui-sref="login">登录</a> </li>
                <li ng-if="!base.logined"><a href="#" ui-sref="signup">注册</a> </li>
            @else
                <li ng-if="base.logined" ng-click="base.logout()"><a href="#">退出</a> </li>
                <li class="date-target-self" ng-if="base.logined"><a href="#"
                                            ui-sref="user({id:'self'})">个人中心</a> </li>
            @endif
        </ul>
    </div>
</div>

<div ui-view></div>

<script>
    require(['bootstrap','signup','login','question','home','user']);
</script>
</body>
</html>

<script type="text/ng-template" id="comment.tpl">
    <hr/>
    <div class="comment-item-set">
        <span class="owncaret"></span>
        <div class="comment_none" ng-if="!comment_data(data)">暂无评论</div>
        <div ng-if="comment_data(data)" class="comment-item" ng-repeat="comment in data">
            <div class="comment-user"><span class="comment_user_id" ui-sref="user({id:comment.user.id})">[:comment.user.username:]</span></div>
            <p class="comment-content">: [:comment.content:]</p>
            <div class="comment_do_list">
                <span ng-click="comment_delete(comment.id)" ng-if="comment.user.id==user_id">删除</span>
            </div>
        </div>
    </div>
    <div>
        <form name="comment_form" ng-submit="comment_action.comment_submit()">
            <div class="input-group">
            <input
                    placeholder="说点什么吧......"
                    type="text"
                    name="content"
                    required
                    ng-model="answer.comment_text.content"
                    class="form-control comment-text">
            <div class="input-group-btn">
                <input
                        type="submit"
                        ng-disabled="!comment_form.$valid"
                        class="btn btn-primary"
                        value="评论">
            </div>
        </form>
    </div>
</script>