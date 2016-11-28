/**
 * Created by yhg97p on 2016/3/10.
 */
//登陆与注册
var signin = angular.module('SigninCtrl',[]);

signin.controller('SigninController',
    function($scope,$http,$cookies){
        //登录、注册、找回密码界面切换
        login($scope);
        $scope.click_registry = function(){
            registry($scope);
            clear_message($scope);
        }
        $scope.click_forgot = function(){
            forgot($scope);
            clear_message($scope);
        }
        $scope.click_login = function(){
            login($scope);
            clear_message($scope);
        }

        //登录
        $scope.login = {};
        $scope.loginIn = function() {
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/lognin.php",
                data:$scope.login,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                console.log(data);
                $scope.login.message = data.marked;
                $cookies.message_check = data.errors.login;
            //    假如登录成功，页面切换至individual中，并将login.username赋值给individual.username,实验中可以直接用nickname后面再换
                if($cookies.message_check == true){//这里有个问题在于，下面要用字符串，但这里要用boolean的，dont't konw why？？？？？
                    //$cookies.nickname = $scope.login.nickname;不可以这么写
                    $cookies.nickname = data.save.nickname;
                    $cookies.username = data.save.username;
                    location.href = "index.html#/individual";
                }
            })
        };
        if($cookies.message_check == "true"){
            location.href = "index.html#/individual";
        }

        //注册
        $scope.register = {};
        $scope.registerTo = function(){
            $http({
                method:'post',
                url:'http://localhost/Finicial_Management/api/register.php',
                data:$scope.register,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                console.log(data);
                $scope.register.message = data.marked;
            })
        }

        //找回密码
        $scope.forgot = {};
        $scope.forgotBack = function(){
            $http({
                method:'post',
                url:'http://localhost/Finicial_Management/api/forgot.php',
                data:$scope.forgot,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                console.log(data);
                $scope.forgot.message = data.marked;
            })
        }

    });

var login = function($scope){
    $scope.showLogin = true;
    $scope.showRegistry = false;
    $scope.showForgot = false;
}
var registry = function($scope){
    $scope.showLogin = false;
    $scope.showRegistry = true;
    $scope.showForgot = false;
}
var forgot = function($scope){
    $scope.showLogin = false;
    $scope.showRegistry = false;
    $scope.showForgot = true;
}

var clear_message = function($scope){
    $scope.login.message = null;
    //$scope.login.nickname = null;
    $scope.login.password = null;

    $scope.register.message = null;
    //$scope.register.nickname = null;
    //$scope.register.username = null;
    $scope.register.password = null;
    $scope.register.repassword = null;
    //$scope.register.email = null;

    $scope.forgot.message = null;
    $scope.forgot.nickname = null;
    $scope.forgot.email = null;


}