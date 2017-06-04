define(['main','signupCommonFun'],function(model,signCom){

    model.service('signupService',['$http','$state',function ($http,$state) {
        var me=this;
        me.signup_data={};
        me.signup=function(){
            $http.post('api/signup',me.signup_data).then(function (data) {
                if(data.status&&data.data.status){
                    me.signup_data={};
                    alert('注册成功');
                    $state.go('login');
                }else{
                    alert(data.data.msg);0
                }
            },function (error) {
                console.log(error)
                alert('服务端错误')
            })
        }
        me.usernameexists=false;
        me.phoneexists=false;
        me.emailexists=false;
        me.chech_or_exitsts=signCom.signup_user_phone_email_exists($http,me);
       /* me.username_exists=function () {
            $http.post('api/user/exists',{username:me.signup_data.username})
                .then(function(data){
                var status=parseInt(data.data.status);
                if(status&&data.status)
                    me.userexists=true;
                else
                    me.userexists=false;
            },function(data){
                console.log(data);
            });
        }
        me.phone_exists=function(){
            $http.post('api/user/phone_exists',{phone:me.signup_data.phone})
                .then(function(data){
                if(data.status&&data.data.status)
                    me.phoneexists=true;
                else
                    me.phoneexists=false;
            },function (error) {
                alert('服务器出错');
            })
        }
        me.email_exists=function(){
            $http.post('api/user/email_exists',{email:me.signup_data.email})
                .then(function(data){
                    if(data.status&&data.data.status)
                        me.emailexists=true;
                    else
                        me.emailexists=false;
                },function (error) {
                    alert('服务器出错');
                })
        }*/
    }]);

    model.controller('signupController',['$scope','signupService',function ($scope,signupService) {
        $scope.userSignup=signupService;
        signCom.checkExists($scope,signupService);
        /*
         scope.$watch(function(){
         return service.signup_data.username;
         },function (v,o) {//signup_data.username只有满足条件$valid为true的时候才会有值，但刚刷新的时候为undefine，会触发这个￥watch方法
         if(v)
         service.username_exists();
         },true)
         scope.$watch(function () {
         return service.signup_data.phone;
         },function (v,o) {
         if(v)
         service.phone_exists();
         })
         scope.$watch('userSignup.signup_data.email',function (v,o) {
         if(v)
         service.email_exists();
         })
         */
    }])
    model.directive('compare',[function(){
        var obj={};
        obj.restrict='AE';
        obj.scope={
            orgText:'=compare'
        };
        obj.require='ngModel';
        obj.link=function(scope,elem,attr,con){
            con.$validators.compare=function(v){
                return scope.orgText==v;
            }
            scope.$watch('orgText',function(){
                con.$validate();
            })
        }
        return obj;
    }]);

})
