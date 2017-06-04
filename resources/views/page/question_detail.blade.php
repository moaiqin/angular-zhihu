<div class="item-list" ng-controller="questionDetailController" id="questionDetail">
    <div class="item clearfix">
        <div class="item-content">
            <div class="title" >[:question.question_detail.title:]</div>
            <div class="owner">
                <a href="#" ui-sref="user({id:question.question_detail.user.id})">[:question.question_detail.user.username:]</a>
                <span class="desc" >[:question.question_detail.intro:]</span>
            </div>
            <div class="content-main">
                [:question.question_detail.desc:]
            </div>
            <h4 class="answer_count">
                <span>回答数:[:question.question_detail.get_question_user_answers.length:]</span>
            </h4>
            <hr ng-if="question.question_detail.get_question_user_answers.length">
            <div class="answer_block"
                 ng-if="!question.answer_curent_id || question.answer_curent_id==v.id"
                 ng-repeat="(k,v) in question.question_detail.get_question_user_answers">
                <div class="vote">
                    <span ng-click="question.vote({id:v.id,vote:1})" class="up">赞 [:v.upvote_count:]</span>
                    <span ng-click="question.vote({id:v.id,vote:2})" class="down">踩 [:v.downvote_count:]</span>
                </div>
                <div class="answer-item">
                    <h6 class="answer_username">
                        <a href="#" ui-sref="user({id:[:v.user.id:]})">
                            [:v.user.username:]
                        </a>
                    </h6>
                    <div class="answer_content">
                        [:v.content:]
                        <span ng-if="user_id==v.user.id" class="answer_reset">
                            <span href="#"
                               ng-click="answer.answer_form=v">编辑</span>
                            <span ng-click="answer.delete({id:v.id})">删除</span>
                        </span>
                    </div>
                </div>
                <div class="action_set">
                    <div class="comment" data-toggle="collapse" data-target="#comentCollapse[:k:]">评论</div>
                </div>
                <div class="collapse conmen-block" comment-block answer-id="v.id" id="comentCollapse[:k:]">
                </div>
                <hr>
            </div>
            <form name="answer_form"
                  {{--ng-init="question.answer_form.id=question.question_detail.id"
                  每次提交的时候这和语句才实行，从服务器去慢啦--}}
                  ng-submit="answer.answer_add_update(question.question_detail.id)">
                <div class="form-group">
                    <textarea
                            name="content"
                            ng-minlength="5"
                            ng-maxlength="400"
                            ng-model="answer.answer_form.content"
                            required
                            class="answer_form_text"></textarea>
                </div>
                <div class="form-group">
                    <input ng-disabled="answer_form.$invalid"
                           class="btn btn-default btn-sm"
                           type="submit" value="提交"/>
                </div>
            </form>
        </div>
    </div>
</div>
