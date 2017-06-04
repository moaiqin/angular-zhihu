
<div ng-controller="userController" class="clearfix" id="user">
    <div class="header">
        <div class="header-left">
            <h2>[:user_self_data.user_data.username:]</h2>
            <p>[:user_self_data.user_data.intro||'主人很懒,还没有个人签名哦':]</p>
        </div>
        <div class="header-right">
            <ul>
                <li>
                    <a href="#">2</a>
                    <span>关注</span>
                </li>
                <li>
                    <a href="#">100</a>
                    <span>粉丝</span>
                </li>
            </ul>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-3">
            <ul class="nav nav-static user_nav">
                <li class="active"><a href="#">主页</a></li>
                <li><a href="#" ui-sref="user.question"><i class=""></i>提问</a></li>
                <li><a href="#" ui-sref="user.answer">回答</a></li>
                <li><a href="#" ui-sref="user.desc">个人资料</a></li>
            </ul>
        </div>
        <div class="col-sm-9">
            <div ui-view></div>
        </div>
    </div>
</div>