<div class="container-fluid home" ng-controller="homeController">
    <div class="row">
        <div class="col-md-4">
            左边
        </div>
        <div class="col-md-7">
            <h3>最新动态</h3>
            <hr/>
            <div class="item-list">
                <div class="item clearfix" ng-repeat="(k,v) in home.data track by $index">
                    <div ng-if="v.question_id" class="vote">
                        <span ng-click="home.vote({id:v.id,vote:1})" class="up">赞 [:v.upvote_count:]</span>
                        <span ng-click="home.vote({id:v.id,vote:2})" class="down">踩 [:v.downvote_count:]</span>
                    </div>
                    <div class="item-content">
                        <div ng-if="v.question_id" class="content-act">
                            [:v.user.username:]添加了回答
                        </div>
                        <div ng-if="!v.question_id" class="content-act">
                            [:v.user.username:]添加了提问
                        </div>
                        <div class="title" ng-if="!v.question_id"
                             ui-sref="question.detail({id:v.id})">
                            [:v.title:]
                        </div>
                        <div class="title" ng-if="v.question_id"
                             ui-sref="question.detail({id:v.question.id})">
                            [:v.question.title:]
                        </div>
                        <div class="owner"><a href="#" ui-sref="user({id:v.user.id})">[:v.user.username:]</a>
                            <span class="desc" >[:v.user.intro:]</span>
                        </div>
                        <div class="content-main"
                             ui-sref="question.detail({id:v.id})"
                             ng-if="!v.question_id">
                            [:v.desc:]
                        </div>
                        <div class="content-main"
                             ui-sref="question.detail({id:v.question.id,answer_id:v.id})"
                             ng-if="v.question_id">
                            [:v.content:]
                        </div>

                        <div class="action_set">
                            <div class="comment" data-toggle="collapse" data-target="#comentCollapse[:k:]">评论</div>
                        </div>
                        <div class="collapse conmen-block" id="comentCollapse[:k:]">
                            <hr/>
                            <div class="comment-item-set">
                                <span class="owncaret"></span>
                                <div class="comment-item">
                                    <div class="comment-user"><a href="#"> mohaobu:</a></div>
                                    <p class="comment-content">这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾</p>
                                </div>
                                <div class="comment-item">
                                    <div class="comment-user"><a href="#"> mohaobu:</a></div>
                                    <p class="comment-content">这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾</p>
                                </div>
                                <div class="comment-item">
                                    <div class="comment-user"><a href="#"> mohaobu:</a></div>
                                    <p class="comment-content">这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾这个学校很垃圾</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="socur" ng-if="!home.socurces_have">加载更多</div>
                <div class="socur" ng-if="home.socurces_have">没有更多资源啦</div>
            </div>
        </div>
    </div>
</div>