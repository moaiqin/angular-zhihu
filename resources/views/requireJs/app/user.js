define(['main'],function (model) {
    model.filter('answered',function(){
        return function(data){
           if (angular.isArray(data)&&!data.length)
               return true;
           return false;
        }
    })
    model.factory('userService',[
        '$http',
        '$state',
        'answerService',
        function ($http,$state,answerService) {
        var userObj={};
        userObj.read=function (userParam) {
            $http({
                method:'post',
                url:'api/user/read',
                data:$.param(userParam),
                headers:{'content-type':'application/x-www-form-urlencoded'}
            }).then(function (success) {
                if(success.status&&success.data.status==3){
                    $state.go('home');
                }
                else if (success.status&&success.data.status)
                    userObj.user_data=success.data.data;
            },function (error) {
                console.log(error);
            })
        };
        //个人主页的点赞模块
        userObj.user_answer_vote=function (conf) {
            userObj.check_vote(conf);
            answerService.vote(conf).then(function (data) {
                if(data)
                    answerService.read({id:conf.id}).then(function (success) {
                        answerService.user_answers[conf.id]=success;
                        console.log(success)
                    },function (error) {
                        console.error('service error')
                    })
            },function () {
                console.error('service error')
            })
        };
        //判断是否取消点赞
        userObj.check_vote=function (conf) {
            var current_answer=answerService.user_answers[conf.id];
            for(var i=0; i<current_answer.users.length; i++){
                if(current_answer.users[i].id == user.id){
                    if(current_answer.users[i].pivot.vote==conf.vote){
                        conf.vote=3;
                    }
                }
            }
        }
        return userObj;
    }]);

    model.controller('userController',[
        '$scope',
        'answerService',
        '$stateParams',
        'questionService',
        'userService',
        function ($scope,
                  answerService,
                  $stateParams,
                  questionService,
                  userService) {
        userService.read($stateParams);
        $scope.user_self_data=userService;
        $scope.user_questions=questionService;
        $scope.user_answers_data=answerService;
        answerService.read({user_id:$stateParams.id})
            .then(function (data) {
                //data不是数组，先转化成数组
                console.log(data)
                var answers=[];
                var index=0;
                for(var attr in data){
                    answers[index]=data[attr];
                    index++;
                }
                answerService.getVote(answers);
                answerService.user_answers=data;
        });
        questionService.read({user_id:$stateParams.id})
            .then(function (data) {
            questionService.user_questions=data;
        })
    }]);
});