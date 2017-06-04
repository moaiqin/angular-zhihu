<div class="item-list" id="user_question">
    <div ng-if="user_answers_data.user_answers|answered" class="answer_alert">
        还没有回答任何问题哦，赶快去回答吧
    </div>
    <div class="item clearfix" ng-repeat="(key,value) in user_answers_data.user_answers">
        <div class="vote">
            <span ng-click="user_self_data.user_answer_vote({id:key,vote:1})" class="up">赞 [:value.upvote_count:]</span>
            <span ng-click="user_self_data.user_answer_vote({id:key,vote:2})" class="down">踩 [:value.downvote_count:]</span>
        </div>
        <div class="item-content">
            <div class="title" ui-sref="question.detail({id:value.question.id})">问题标题：<a href="#">[:value.question.title:]</a></div>
            <div class="content-main" ui-sref="question.detail({id:value.question.id,answer_id:value.id})">
                回答内容：
                [:value.content:]
            </div>

            <div class="action_set clearfix">
                <div class="comment pull-left" data-toggle="collapse"
                     data-target="#comentCollapse[:key:]"
                     ng-if="value.question.desc"> 查看问题内容</div>
                <div class="pull-right create_time">发布时间:[:value.time.date:]</div>
            </div>
            <div class="collapse conmen-block" id="comentCollapse[:key:]" ng-if="value.question.desc">
                <hr/>
                <p>[:value.question.desc:]</p>
            </div>
        </div>
    </div>
</div>