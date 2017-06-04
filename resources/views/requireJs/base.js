/**
 * Created by moshaobu on 2017/4/12.
 */
require.config({
    baseUrl:'../resources/views/requireJs/app',
    paths:{
        'jquery':'../lib/jquery-3.1.0',
        'angular':'../lib/angular.min',
        'bootstrap':'../lib/bootstrap.min',
        'css':'../lib/css.min',
        'router':'../lib/angular-ui-router.min',
    },
    shim:{
        'bootstrap':{
            'deps':['jquery','css!../css/bootstrap.min.css']
        },

    }
});