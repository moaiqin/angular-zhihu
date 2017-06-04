define(['main'],function(model){

    model.service('questionService',[
        '$state',
        '$http',
        'answerService',
        function($state,$http,answerService){
        var me=this;
        me.question_data={};
        me.add_question=function () {
            $state.go('question.add');
        };

        /*读取用户所有的问题或者读取某个问题*/
        me.read=function (param) {
            return $http.post('api/question/read',param).then(function (suc) {
                if(suc.status&&suc.data.status) {
                    /*me.question_detail为问题详情页*/
                    me.question_detail=suc.data.data;
                    /*获取所有点赞或踩的数据*/
                    me.it_answers=suc.data.data.get_question_user_answers||[];
                    me.it_answers=answerService.getVote(me.it_answers);
                    return suc.data.data;
                }
                return false;
            },function (error) {
                console.log('service error')
            })
        }

        /*
        * 用户详情页的回答点赞功能
        * conf({id:,vote:1})
        * id为回答id
        * */
        me.vote=function (conf) {
            //判断用户是否是取消点赞
            me.check_invote(conf);
            answerService.vote(conf).then(function (vote_success) {
                if(vote_success){
                    /*获取该回答的所有内容和点赞书进行比较,赋值改变点赞的值*/
                    answerService.read(conf).then(function (data) {
                        for(var i=0; i < me.it_answers.length; i++){
                            if(conf.id==me.it_answers[i].id){
                                me.it_answers[i]=data;
                            }
                        }
                    },function () {
                        console.log('service error')
                    })
                }
            },function () {
                console.log('service error')
            })

        }
        /*
        *  判断用户是否是取消点赞
        * */
        me.check_invote=function (conf) {
            for(var i=0; i < me.it_answers.length; i++){
                if(conf.id==me.it_answers[i].id){
                    var current_answer=me.it_answers[i];
                    break;
                }
            }
            for(var i=0; i<current_answer.users.length; i++){
                if (current_answer.users[i].id==user.id){
                    if(current_answer.users[i].pivot.vote==conf.vote){
                        conf.vote=3;
                    }
                }
            }
        }

        /*问题提交*/
        me.question_submit=function () {
            $http.post('api/question/add',me.question_data).then(function (data) {
                console.log(data);
                if(data.status&&data.data.status==2){
                    alert('请先登录');
                    $state.go('login');
                }else if(data.status&&data.data.status==1){
                    $state.go('home');
                    me.question_data={};
                }
            },function (error) {})
        };

    }]);
    model.controller('questionController',[
        '$scope',
        'questionService',
        'answerService',
        function($scope,questionService,answerService){
            $scope.question=questionService;
            $scope.answer=answerService;
        }]);

    model.controller('questionAddController',[
        '$scope',
        'questionService',
        function($scope,questionService){
    }])

    model.controller('questionDetailController',[
        '$scope',
        '$stateParams',
        'questionService',
        'answerService',
        function ($scope,$stateParams,questionService,answerService) {
        answerService.answer_form={};
        if($stateParams.answer_id)
            questionService.answer_curent_id=$stateParams.answer_id;
        else
            questionService.answer_curent_id=null;
        questionService.read($stateParams).then(function (data) {

        })
    }])
    //回答评论.里面的scope只是控制该域
    model.directive('commentBlock',[
        '$http',
        'answerService',
        function ($http,answerService) {
        var obj={};
        obj.restrict='AE';
        obj.templateUrl="comment.tpl";
        obj.scope={
            'answer_id':'=answerId'
        }
        obj.link=function (scope,elem,attr,con) {
            scope.answer=answerService;
            scope.comment_action={};
            scope.data={};
            function get_comment_data() {
                $http.post('api/comment/read',{answer_id:scope.answer_id}).then(function (success) {
                    if(success.data.status)
                        scope.data=angular.merge({},scope.data,success.data.data);
                },function () {
                    console.error('service error')
                });
            }
            if(scope.answer_id)
                get_comment_data();

            scope.comment_action.comment_submit=function () {
                answerService.comment_text.answer_id=scope.answer_id;
                answerService.comment_submit().then(function (status) {
                    if(status) {
                        answerService.comment_text = {};
                        get_comment_data();
                    }
                },function (err) {
                    console.log(err);
                })
            }

            //判断data是否为空
            scope.comment_data=function(data) {
                var comment_data_arr=Object.keys(data);
                return comment_data_arr.length;
            }
            scope.user_id=user.id;
            scope.comment_delete=function (comment_id) {
                $http.post('api/comment/remove',{id:comment_id}).then(function (success) {
                    if(success.data.status){
                        get_comment_data();
                    }
                },function (err) {
                    console.error('service error')
                })
            }
        };
        return obj;
    }]);

});