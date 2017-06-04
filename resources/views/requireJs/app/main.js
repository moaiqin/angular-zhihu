/**
 * 主页模块
 */
define(['angular','router'],function () {
    var model=angular.module('xiaohu',['ui.router']);
    model.config(['$interpolateProvider',
        '$stateProvider',
        '$urlRouterProvider',
        '$locationProvider',
        function($interpolateProvider,$stateProvider,$urlRouterProvider,$locationProvider){
        $locationProvider.hashPrefix('');
        $interpolateProvider.startSymbol('[:');
        $interpolateProvider.endSymbol(':]');
        $urlRouterProvider.otherwise('/home');
        $stateProvider.state('home',{
                url:'/home',
                templateUrl:'tpl/page/home'
            })
            .state('login',{
                url:'/login',
                templateUrl:'tpl/page/login'
            })
            .state('signup',{
                url:'/signup',
                templateUrl:'tpl/page/signup'
            })
            .state('question',{
                abstract:true,//这样在地址栏输入的时候不会显示这个模块，把他隐藏
                url:'/question',
                template:"<div ui-view></div>",
                controller:'questionController'
            })
            .state('question.add',{
                url:'/add',
                templateUrl:'tpl/page/question_add',
                //controller:'questionAddController'
            })
            .state('question.detail',{//也可以是/:answer_id但必须要有answer_id，不然会掉到首页
                url:'/detail/:id?answer_id',
                templateUrl:'tpl/page/question_detail',
                //controller:'questionDetailController'
            })
            .state('user',{
                url:'/user/:id',
                templateUrl:'tpl/page/user'
            })
            .state('user.question',{
                url:'/question',
                templateUrl:'tpl/user/question'
            })
            .state('user.answer',{
                url:'/answer',
                templateUrl:'tpl/user/answer'
            })
            .state('user.desc',{
                url:'/desc',
                templateUrl:'tpl/user/desc'
            });
    }]);
    return model;

});