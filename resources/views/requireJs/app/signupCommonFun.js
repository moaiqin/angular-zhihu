
define([],function () {
    return {
        checkExists:function(scope,service){
            scope.$watch(function(){
                return service.signup_data.username;
            },function (v,o) {//signup_data.username只有满足条件$valid为true的时候才会有值，但刚刷新的时候为undefine，会触发这个￥watch方法
                if(v)
                    service.chech_or_exitsts('username');
            },true)
            scope.$watch(function () {
                return service.signup_data.phone;
            },function (v,o) {
                if(v)
                    service.chech_or_exitsts('phone');
            })
            scope.$watch('userSignup.signup_data.email',function (v,o) {
                if(v)
                    service.chech_or_exitsts('email');
            })
        },
        signup_user_phone_email_exists:function (http,service) {
            return function (name) {
                var userData=service.signup_data[name];
                var obj={};
                obj[name]=userData;
                /*http.post('api/user/'+name+'_exists',obj)
                    .then(function(data){
                        console.log(data);
                        if(data.data.status&&data.status)
                            service[name+'exists']=true;
                        else
                            service[name+'exists']=false;
                    },function(data){
                    });*///这两个方法都可以
                http({
                    method:'post',
                    url:'api/user/'+name+'_exists',
                    data:$.param(obj),
                    headers:{'content-type':'application/x-www-form-urlencoded'}
                }).then(function(data){
                    console.log(data);
                    if(data.data.status&&data.status)
                        service[name+'exists']=true;
                    else
                        service[name+'exists']=false;
                },function(data){
                });
            }
        }
    }
})