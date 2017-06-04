define(['main'],function(model){
    model.factory('loginService',['$http','$state',function ($http,$state) {
        var obj={};
        obj.login_data={};
        obj.logined=false;
        obj.login=function(){
            $http.post('api/login',{
                username:obj.login_data.username,
                password:obj.login_data.password
            }).then(function (data) {
                if(data.status&&data.data.status) {
                    obj.logined=true;
                    $state.go('home');
                    obj.login_data={};
                }
                else {
                    alert('登录失败');
                }
            },function (error) {
                alert('服务器错误')
            })
        };
        obj.logout=function () {
            $http.post('api/logout').then(function (data) {
                if(data.data.status){
                    obj.logined=false;
                    $state.go('login');
                }
            },function (err) {
                console.error('service error')
            })
        }
        return obj;
    }]);
    model.controller('loginController',['$scope','loginService',function ($scope,loginService) {
        $scope.userLogin=loginService;
    }]);
});