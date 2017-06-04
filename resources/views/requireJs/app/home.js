define(['main','answer'],function (model,a) {

    model.service('homeService',['$http','answerService',function($http,answerService){
        var me=this;
        me.data=[];

        me.getData=function (conf) {
            if(me.pending || !me.data_more)
                return;
            me.pending=true;
            conf=conf || {page:me.page};
            $http.post('api/timeline',conf).then(function (success) {
                if(success.status&&success.data.status){
                    me.data=me.data.concat(success.data.data);
                    me.data=answerService.getVote(me.data);
                    me.page++;
                    if(success.data.data.length){
                        me.socurces_have=false;
                    }else{
                        me.socurces_have=true;
                        /*判断是否还有数据*/
                        me.data_more=false;
                    }
                }else{
                    console.log('data error')
                }
            },function (error) {
                console.log('service error');
            }).finally(function () {
                me.pending=false;
            });
        };
        me.vote=function (conf) {
            /*
            * 如果点赞成功就从新读取该回答
            * */
            answerService.vote(conf).then(function (data) {
              if(data)
                  answerService.answer_update(conf.id);
            })
        };
        me.state_reset=function () {
            me.page=1;
            me.pending=false;
            me.data_more=true;
        }

    }])
    model.controller('homeController',
        ['$scope',
            'homeService',
            'answerService',
        function ($scope,homeService, answerService) {
        $scope.home=homeService;
        homeService.state_reset();
        homeService.data=[];//每次刷新上次的数据清除
        homeService.getData();
        /*$(window).on('scroll',function () {
            if($(document).height()-($(window).height()+$(document).scrollTop())<30){
                alert(1);
            }
        })*/

        window.onscroll=function () {
            var scrollTop=document.documentElement.scrollTop||document.body.scrollTop;
            var windowHeight=document.documentElement.clientHeight||
                document.documentElement.body.clientHeight;
            var documentHeight=document.documentElement.offsetHeight||document.body.offsetHeight;
            if(documentHeight-windowHeight-scrollTop<30){
                homeService.getData();
            }
        }

        /*
        * 点赞和回答数据重新改变，替换
        * */
        $scope.$watch(function () {
            return answerService.data;
        },function (new_data,old_data) {
            var question_answer_data=homeService.data;
            for(var attr in new_data){
                for(var i=0; i<question_answer_data.length; i++){
                    //判断点赞后该回答的新数据，代替之前该回答的数据的数据;
                    if(attr==question_answer_data[i].id)
                        question_answer_data[i]=new_data[attr];
                }
            }
            homeService.data=answerService.getVote(question_answer_data);
        },true)
    }])
    model.controller('baseController',
        ['$scope',
            'loginService',
            function ($scope,loginService) {
        $scope.user_id=$('body').attr('user_id');
        $scope.base=loginService;
        if($scope.user_id){
            $scope.base.logined=true;
        }
        $('#headerNav').on('click','li',function () {
            $('#headerNav li').removeClass('nav-active');
            $(this).addClass('nav-active');
        });

    }])
})
