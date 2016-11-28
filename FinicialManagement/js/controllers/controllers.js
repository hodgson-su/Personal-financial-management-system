/**
 * Created by Administrator on 2016/2/28 0028.
 */
/**
 * Created by yhg97p on 2016/1/5.
 */
//    测试页面
var test = angular.module('TestCtrl',[]);
test.controller('TestController',['scope',
    function($scope){

    }])

//个人主页,左边固定列
var individual = angular.module('IndividualCtrl',['ngAnimate']);
individual.controller('individualController',
    function($scope,$cookies,$interval,$http){
        //头部效果,左边username的获取，右边退出，将cookies中的村纯数据消除
        //判断cookies中是否存有数据，没有回到登录界面，这里还要注意时间问题
        $scope.individual = {};
        $scope.message_check = $cookies.message_check;
        //退出
        $scope.click_quit = function(){
            $cookies.message_check = false;
            location.href = "index.html#/signin";
        }
        if($cookies.message_check == "false"){
            location.href = "index.html#/signin";
        }else{
            $scope.individual.username = $cookies.username;
            $scope.individual.nickname = $cookies.nickname;
            $scope.individual.month = 0;
        }

        //消息判断mesremind
        //$scope.remind = 1;
        $interval(function(){
            $scope.apply(get_remind());
        },500);

        var get_remind = function(){
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/runRemind.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                $scope.remind = data.i;
            })
        }



        //通过点击拉开收缩列表
        $scope.current_firstpage = true;
        close_all_sidebar($scope);
        close_bills_all($scope);

        //firstpage learn_more()
        $scope.learn_more = function(){
            first_reset_all($scope);
            $scope.hide_form = false;
            $scope.current_form = true;
            open_form01($scope);
            location.href="#/individual/form01";
        }

        //blance_of_account
        $scope.blance_of_account = function(){
            first_reset_all($scope);
            $scope.hide_form = false;
            $scope.current_form = true;
            open_form05($scope);
            location.href="#/individual/form05";
        }

        //首页
        $scope.click_firstpage = function(){
            first_reset_all($scope);
            $scope.current_firstpage = true;
        }
        //记账
        $scope.click_bills = function(){
            first_reset_all($scope);
            $scope.hide_bills = false;
            $scope.current_bills = true;
        }
//????????????????????????????????????????click_bills设定值完成后下面的无法进行修改值????????????????????????
        $scope.click_bills01 = function(){
            open_bills01($scope);
        }
        $scope.click_bills02 = function(){
            open_bills02($scope);
        }
        //报表
        $scope.click_form = function(){
            first_reset_all($scope);
            $scope.hide_form = false;
            $scope.current_form = true;
        }
        $scope.click_form01 = function(){
            open_form01($scope);
        }
        $scope.click_form02 = function(){
            open_form02($scope);
        }
        $scope.click_form03 = function(){
            open_form03($scope);
        }
        $scope.click_form04 = function(){
            open_form04($scope);
        }
        $scope.click_form05 = function(){
            open_form05($scope);
        }
        $scope.click_form06 = function(){
            open_form06($scope);
        }
        $scope.click_form07 = function(){
            open_form07($scope);
        }
        $scope.click_form08 = function(){
            open_form08($scope);
        }
        //账户
        $scope.click_account = function(){
            first_reset_all($scope);
            $scope.hide_account = false;
            $scope.current_account = true;
        }
        $scope.click_account01 = function(){
            open_account01($scope);
        }
        $scope.click_account02 = function(){
            open_account02($scope);
        }
        $scope.click_account03 = function(){
            open_account03($scope);
        }
        $scope.click_account04 = function(){
            open_account04($scope);
        }
        $scope.click_account05 = function(){
            open_account05($scope);
        }
        $scope.click_account06 = function(){
            open_account06($scope);
        }
        //设置
        $scope.click_setting = function(){
            first_reset_all($scope);
            $scope.hide_setting = false;
            $scope.current_setting = true;
        }
        $scope.click_setting01 =function(){
            open_setting01($scope);
        }
        $scope.click_setting02 =function(){
            open_setting02($scope);
        }
        $scope.click_setting03 =function(){
            open_setting03($scope);
        }
        $scope.click_setting04 =function(){
            open_setting04($scope);
        }
        $scope.click_setting05 =function(){
            open_setting05($scope);
        }
        $scope.click_setting06 =function(){
            open_setting06($scope);
        }

        $scope.settings_width_150 = false;
        $scope.settings_width_0 = true;
        $scope.click_settings = function(){
            $scope.settings_width_150 = !$scope.settings_width_150;
            $scope.settings_width_0 = !$scope.settings_width_0;
        }

    })


//关闭所有列表
var first_reset_all = function($scope){
    close_all_sidebar($scope);
    current_no($scope);
}
var close_all_sidebar = function ($scope) {
    $scope.hide_bills = true;
    $scope.hide_form = true;
    $scope.hide_account = true;
    $scope.hide_setting = true;
}
var current_no = function($scope){
    $scope.current_firstpage = false;
    $scope.current_bills = false;
    $scope.current_form = false;
    $scope.current_account = false;
    $scope.current_setting = false;
}
var open_list_first = function($scope){
    $scope.current_list = "current";
    //$scope.current_bills01 = true;
    //$scope.current_form01 = true;
    //$scope.current_account01 = true;
    //$scope.current_setting01 = true;
}
var close_list_all =function($scope){
    $scope.current_list = null;
    close_bills_all($scope);
    close_form_all($scope);
    close_account_all($scope);
    close_setting_all($scope);
}
var close_bills_all = function($scope){
    $scope.current_bills01 = false;
    $scope.current_bills02 = false;
}
var close_form_all = function($scope){
    $scope.current_form01 = false;
    $scope.current_form02 = false;
    $scope.current_form03 = false;
    $scope.current_form04 = false;
    $scope.current_form05 = false;
    $scope.current_form06 = false;
    $scope.current_form07 = false;
    $scope.current_form08 = false;
}
var close_account_all = function($scope){
    $scope.current_account01 = false;
    $scope.current_account02 = false;
    $scope.current_account03 = false;
    $scope.current_account04 = false;
    $scope.current_account05 = false;
}
var close_setting_all = function($scope){
    $scope.current_setting01 = false;
    $scope.current_setting02 = false;
    $scope.current_setting03 = false;
    $scope.current_setting04 = false;
    $scope.current_setting05 = false;
    $scope.current_setting06 = false;
}
var open_bills01 = function($scope){
    close_list_all($scope);
    $scope.current_bills01 = true;
}
var open_bills02 = function($scope){
    close_list_all($scope);
    $scope.current_bills02 = true;
}
var open_form01 = function($scope){
    close_list_all($scope);
    $scope.current_form01 = true;
}
var open_form02 = function($scope){
    close_list_all($scope);
    $scope.current_form02 = true;
}
var open_form03 = function($scope){
    close_list_all($scope);
    $scope.current_form03 = true;
}
var open_form04 = function($scope){
    close_list_all($scope);
    $scope.current_form04 = true;
}
var open_form05 = function($scope){
    close_list_all($scope);
    $scope.current_form05 = true;
}
var open_form06 = function($scope){
    close_list_all($scope);
    $scope.current_form06 = true;
}
var open_form07 = function($scope){
    close_list_all($scope);
    $scope.current_form07 = true;
}

var open_form08 = function($scope){
    close_list_all($scope);
    $scope.current_form08 = true;
}
var open_account01 = function($scope){
    close_list_all($scope);
    $scope.current_account01 = true;
}
var open_account02 = function($scope){
    close_list_all($scope);
    $scope.current_account02 = true;
}
var open_account03 = function($scope){
    close_list_all($scope);
    $scope.current_account03 = true;
}
var open_account04 = function($scope){
    close_list_all($scope);
    $scope.current_account04 = true;
}
var open_account05 = function($scope){
    close_list_all($scope);
    $scope.current_account05 = true;
}
var open_setting01 = function($scope){
    close_list_all($scope);
    $scope.current_setting01 = true;
}
var open_setting02 = function($scope){
    close_list_all($scope);
    $scope.current_setting02 = true;
}
var open_setting03 = function($scope){
    close_list_all($scope);
    $scope.current_setting03 = true;
}
var open_setting04 = function($scope){
    close_list_all($scope);
    $scope.current_setting04 = true;
}
var open_setting05 = function($scope){
    close_list_all($scope);
    $scope.current_setting05 = true;
}
var open_setting06 = function($scope){
    close_list_all($scope);
    $scope.current_setting06 = true;
}

//firstpage
var firstpage = angular.module('FirstPage',[]);
firstpage.controller("firstpageController",function($scope,$http,$cookies){
    $scope.income = {};
    $scope.pay = {};
    $scope.overflus = 12;
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        console.log(data);
        $scope.income.week = data.income.week;
        $scope.income.month =data.income.month;
        $scope.income.year = data.income.year;
        $scope.pay.week = data.pay.week;
        $scope.pay.month = data.pay.year;
        $scope.pay.year = data.pay.year;
        $scope.overflus = $scope.income.month - $scope.pay.month;
        $scope.individual.month = data.month;
    })

})
firstpage.controller("firstpage_PieCtrl", function ($scope,$http,$cookies) {
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        console.log(data);
        $scope.labels = data.classify;
        $scope.series = data.classify_income;
        $scope.data = data.classify_price;
    })
    //$scope.labels = ["衣服饰品", "交流通讯", "人情往来","医疗保健"];
    //$scope.series = ['衣服饰品', '交流通讯'];
    //$scope.data = [300, 500, 100, 0];
});
firstpage.controller("firstpage_LineCtrl",function ($scope, $timeout,$http,$cookies) {
    $scope.labels = ["周一","周二","周三","周四","周五","周六","周日"];
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        console.log(data);
        $scope.data = [data.pay_sum_week_price];
    })
});


//图表
var charts = angular.module("ChartsCtrl", ["chart.js",'ngRoute','ui.bootstrap']);
//日常收支表
charts.controller("BarCtrl_Out", function ($scope,$http,$cookies) {
    //$scope.labels = ['衣食住行', '行车交通', '住宿'];
    //$scope.data = [
    //    [65, 59, 80],
    //];
    //$scope.individual.nickname = $cookies.nickname;
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        console.log(data);
        $scope.labels = data.classify;
        $scope.data = [
            data.classify_price
        ];
    })
});//支出
charts.controller("BarCtrl_In", function ($scope,$http,$cookies) {
    //$scope.labels = ['衣食住行', '行车交通', '住宿'];
    //$scope.data = [
    //    [65, 59, 80],
    //];
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        $scope.labels = data.classify_income;
        $scope.data = [
            data.classify_income_price
        ];
    })
});//收入
//收支趋势表
charts.controller("form02Controller",function($scope,$http,$cookies){
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        $scope.items = [ {time:data.trend_times[0],income:data.pay_month_trend_price[0],pay:data.pay_month_trend_price[0],blance:data.blance_month_trend_price[0]},
                          {time:data.trend_times[1],income:data.pay_month_trend_price[1],pay:data.pay_month_trend_price[1],blance:data.blance_month_trend_price[1]},
                          {time:data.trend_times[2],income:data.pay_month_trend_price[2],pay:data.pay_month_trend_price[2],blance:data.blance_month_trend_price[2]},
                          {time:data.trend_times[3],income:data.pay_month_trend_price[3],pay:data.pay_month_trend_price[3],blance:data.blance_month_trend_price[3]}]
    })
})
charts.controller("LineCtrl_Out_In",  function ($scope, $timeout,$http,$cookies) {
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        console.log(data);
        $scope.labels = data.trend_times;
        $scope.series = ['收入','支出','结余'];
        $scope.data = [
            data.income_month_trend_price,
            data.pay_month_trend_price,
            data.blance_month_trend_price
        ];
    })

});

charts.controller("form03Controller",function($scope,$http,$cookies){
    $scope.select = function(month){
        $scope.individual.month = $scope.month;
        $http({
            method:'post',
            url:"http://localhost/Finicial_Management/api/individual.php",
            data:$scope.individual,
            headers:{
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).success(function(data){
            $scope.labels = data.classify;
            $scope.series = ['实际支出', '预算支出'];
            $scope.data = [
                data.fact_pay_price,
                data.budget_pay_price
            ];

            $scope.labels02 = data.classify_income;
            $scope.series02 = ['实际收入', '预期收入'];
            $scope.data02 = [
                data.fact_income_price,
                data.budget_income_price
            ];
            $scope.message = $scope.data;
        })
    }
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        $scope.individual.month = data.month;
        $scope.month = $scope.individual.month;
        $scope.labels = data.classify;
        $scope.series = ['实际支出', '预算支出'];
        $scope.data = [
            data.fact_pay_price,
            data.budget_pay_price
        ];

        $scope.labels02 = data.classify_income;
        $scope.series02 = ['实际收入', '预期收入'];
        $scope.data02 = [
            data.fact_income_price,
            data.budget_income_price
        ];
        setTimeout(function () {
            $scope.$apply(function () {
                refresh();
            });
        }, 2000);
    })

    //var refresh = function(){

    //};
    ////每隔5秒刷新
    //setInterval(function(){
    //    $scope.$apply(refresh);
    //},10000);

    //setTimeout(function () {
    //    $scope.$apply(function () {
    //        $scope.labels02 = ['a','b']
    //        $scope.series02 = ['实际收入', '预期收入'];
    //        $scope.data02 = [
    //            [2,2],
    //            [2,2]
    //        ];
    //    });
    //}, 2000);

})
charts.controller("form04Controller",function($scope,$http){
    //$scope.labels =["现金", "银行卡", "负债", "投资","保险"];
    //$scope.data = [
    //    [65, 59, 90, 81],
    //    [28, 48, 40, 19]
    //];
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        $scope.labels = data.account;
        $scope.data = [
            data.account_pay_price,//花费
            data.account_income_price
        ];
        $scope.items = [
            {account:data.account[0],pay:data.account_pay_price[0],income:data.account_income_price[0],blance:data.account_blance_price[0]},
            {account:data.account[1],pay:data.account_pay_price[1],income:data.account_income_price[1],blance:data.account_blance_price[1]},
            {account:data.account[2],pay:data.account_pay_price[2],income:data.account_income_price[2],blance:data.account_blance_price[2]},
            {account:data.account[3],pay:data.account_pay_price[3],income:data.account_income_price[3],blance:data.account_blance_price[3]},
        ]
    })
})
charts.controller("form05Controller",function($scope,$http){
    $scope.item = {};
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        $scope.account = data.account[0];
        $scope.item.income = data.account_income_price[0];
        $scope.item.pay = data.account_pay_price[0];
        $scope.item.blance = data.account_blance_price[0];
    })
})

//账户选择account
var accounts = angular.module('FinicialCtrl',[]);
accounts.controller('account01Controller',function($scope,$http,$cookies){
    $scope.individual.account = '现金';
    $scope.message = $scope.individual;
    $http.get("http://localhost/Finicial_Management/api/index.php/"+$cookies.nickname+"/"+$scope.individual.account)
        .success(function(response){
            $scope.items = response;
        })
})
//account02
accounts.controller('account02Controller',function($scope,$http,$cookies){
    $scope.individual.account = '银行卡';
    $scope.individual.bank = {};
    $scope.individual.limit = {};
    $scope.individual.account_day = {};
    $scope.individual.repayment_day = {};
    $scope.add_credit = false;

    $http.get("http://localhost/Finicial_Management/api/setting.php/classify_show/"+$cookies.nickname+"/1")
        .success(function(response){
            $scope.items = response.data_message;
            $scope.credit_cards = response.data_bank;
        })

    $scope.add_credit_account = function(){
        $scope.individual.bank = "";
        $scope.individual.limit = "5000";
        $scope.individual.account_day = "1";
        $scope.individual.repayment_day = "1";
        $scope.add_credit = true;
    }
    $scope.credit_confirm =function(){
        $http({
            method:'post',
            url:"http://localhost/Finicial_Management/api/individual.php",
            data:$scope.individual,
            headers:{
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).success(function(data){
            $scope.add_credit = false;
            $http.get("http://localhost/Finicial_Management/api/setting.php/classify_show/"+$cookies.nickname+"/1")
                .success(function(response){
                    $scope.items = response.data_message;
                    $scope.credit_cards = response.data_bank;
                })
        })
    }
    $scope.credit_cancel = function(){
        $scope.add_credit = false;
    }

})
accounts.controller('account03Controller',function($scope,$http,$cookies){
    $scope.individual.account = '负债';
    $http.get("http://localhost/Finicial_Management/api/index.php/"+$cookies.nickname+"/"+$scope.individual.account)
        .success(function(response){
            $scope.items = response;
        })
})
accounts.controller('account04Controller',function($scope,$http,$cookies){
    $scope.individual.account = '投资';
    $http.get("http://localhost/Finicial_Management/api/index.php/"+$cookies.nickname+"/"+$scope.individual.account)
        .success(function(response){
            $scope.items = response;
        })
})
accounts.controller('account05Controller',function($scope,$http,$cookies){
    $scope.individual.account = '保险';
    $http.get("http://localhost/Finicial_Management/api/index.php/"+$cookies.nickname+"/"+$scope.individual.account)
        .success(function(response){
            $scope.items = response;
        })
})

var accounts_delete = function($scope,$http,event){
    var id=event.target.getAttribute("class");
    $scope.individual.id = id;
    var deleteUser = confirm('确定删除吗？');
    if (deleteUser) {
        $http.delete("http://localhost/Finicial_Management/api/index.php/delete/"+$scope.individual.nickname+"/"+$scope.individual.id);
        setTimeout(function () {
            $scope.$apply(function () {
                $http.get("http://localhost/Finicial_Management/api/index.php/"+$scope.individual.nickname+"/"+$scope.individual.account)
                    .success(function(response){
                        $scope.items = response;
                    })
            });
        }, 200);
    }
}


//setting
//setting01
var settings = angular.module("SettingCtrl", ['ngRoute']);
settings.controller('setting01Controller',
    function ($scope,$http,fileReader) {
        //$scope.imageSrc = "img/imgSrc/1.jpg";
        $scope.individual.email = {};
        $scope.individual.image = {};
        $http({
            method:'post',
            url:"http://localhost/Finicial_Management/api/individual.php",
            data:$scope.individual,
            headers:{
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).success(function(data){
            $scope.imageSrc = data.change.image;
            $scope.email = data.change.email;
            $scope.individual.email = $scope.email;
        })
        $scope.change_eamil = false;
        $scope.setting01_change = function(){
            $scope.change_eamil = true;
        }

        $scope.email_change = function(){
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){

            })
            $scope.change_eamil = false;
        }
        $scope.cancel_change = function(){
            $scope.change_eamil = false;
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                $scope.email = data.change.email;
                $scope.individual.email = $scope.email;
            })
        }

        $scope.getFile = function () {
            fileReader.readAsDataUrl($scope.file, $scope)
                .then(function(result) {
                    $scope.imageSrc = result;
                    $scope.individual.image = $scope.imageSrc;
                    //$http({
                    //    method:'post',
                    //    url:"http://localhost/Finicial_Management/api/individual.php",
                    //    data:$scope.individual,
                    //    headers:{
                    //        'Content-Type': 'application/x-www-form-urlencoded'
                    //    },
                    //}).success(function(data){
                    //
                    //})
                });
        };
})

//setting02
settings.controller('setting02Controller',
    function ($scope,$http) {

})

//setting03
settings.controller('setting03Controller',
    function ($scope,$http) {
        $scope.mmbh_warn = "safe-warn-green";
        $scope.safe_remind = "已开通";

        $scope.individual.oldpassword = {};
        $scope.individual.newpassword = {};
        $scope.individual.repassword = {};

        $scope.individual.email = {};

        $scope.individual.phone = {};

        $scope.mm_change = false;
        $scope.email_change = false;
//        修改密码
        $scope.mm_start_change = function(){
            $scope.mm_change = true;
        }
        $scope.mm_confirm = function(){
            if($scope.individual.newpassword != $scope.individual.repassword){
                $scope.message = "请确保输入密码一致！";
            } else{
                $scope.message = null;
                $http({
                    method:'post',
                    url:"http://localhost/Finicial_Management/api/individual.php",
                    data:$scope.individual,
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                }).success(function(data){
                    $scope.remark = data.passwrod_remark;
                    if($scope.remark == true){
                        $scope.message = "修改密码成功";
                        setTimeout(function(){
                            $scope.$apply(function(){
                                $scope.mm_change = false;
                                $scope.message = null;
                            })
                        },2000);
                    }else {
                        $scope.message = "原始密码输入错误，请检查。"
                    }
                })
            }
            $scope.change_eamil = false;
        }
        $scope.mm_cancel = function(){
            $scope.mm_change = false;
            $scope.individual.oldpassword = {};
            $scope.individual.newpassword = {};
            $scope.individual.repassword = {};

        }
//        修改邮箱
        $scope.email_start_change = function(){
            $scope.email_change = true;
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                $scope.individual.email = data.change.email;
            })
        }
        $scope.email_confirm = function(){
            $scope.message = null;
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                $scope.email_change = false;
            })
        }
        $scope.email_cancel = function(){
            $scope.email_change = false;
        }
//        手机号绑定
        $http({
            method:'post',
            url:"http://localhost/Finicial_Management/api/individual.php",
            data:$scope.individual,
            headers:{
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).success(function(data){
            $scope.individual.phone = data.change.myphone;
            if(data.change.myphone == ""){
                $scope.sjbh_warn = "safe-warn-red";
                $scope.safe_phone_remind = "未开通";
                $scope.phone_remark = "开启手机保护";
            }else{
                $scope.sjbh_warn = "safe-warn-green";
                $scope.safe_phone_remind = "已开通";
                $scope.phone_remark = "修改手机号码";
            }
        })
        $scope.phone_start_change = function(){
            $scope.phone_change = true;
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                $scope.individual.phone = data.change.myphone;
            })
        }
        $scope.phone_confirm = function(){
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                $scope.phone_change = false;
                $http({
                    method:'post',
                    url:"http://localhost/Finicial_Management/api/individual.php",
                    data:$scope.individual,
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                }).success(function(data){
                    $scope.individual.phone = data.change.myphone;
                    if(data.change.myphone == ""){
                        $scope.sjbh_warn = "safe-warn-red";
                        $scope.safe_phone_remind = "未开通";
                        $scope.phone_remark = "开启手机保护";
                    }else{
                        $scope.sjbh_warn = "safe-warn-green";
                        $scope.safe_phone_remind = "已开通";
                        $scope.phone_remark = "修改手机号码";
                    }
                })
            })
        }
        $scope.phone_cancel = function(){
            $scope.phone_change = false;
        }

})


//setting04
settings.controller('setting04Controller',
    function ($scope,$http,$interval) {
        $scope.individual.save = {};
        $scope.individual.takeover = {};
        $scope.show_circle = false;
        $scope.message = {};
        $http({
            method:'post',
            url:"http://localhost/Finicial_Management/api/individual.php",
            data:$scope.individual,
            headers:{
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        }).success(function(data){
            $scope.individual.takeover = data.change.takeover;
            if($scope.individual.takeover == "1"){
                $scope.individual.save = "false";
                $scope.show_circle = false;
            }else if($scope.individual.takeover == "2"){
                $scope.individual.save = "true";
                $scope.show_circle = true;
                $scope.individual.circle = "cycle2";
            }else{
                $scope.individual.save = "true";
                $scope.show_circle = true;
                $scope.individual.circle = "cycle1";
            }
        })
        $scope.individual.save = "false";
        $scope.message = $scope.individual.save;
        $interval(function(){
            $scope.apply(refresh());
        },500);
        //refresh();

        var refresh = function(){
            if($scope.individual.save != "false"){
                $scope.show_circle = true;
                if($scope.individual.circle == null){
                    $scope.individual.circle = "cycle2";
                    $scope.individual.takeover = "2";
                }else if($scope.individual.circle == "cycle1"){
                    $scope.individual.takeover = "3";
                }else{
                    $scope.individual.takeover = "2";
                }
            }else{
                $scope.show_circle = false;
                $scope.individual.takeover = "1";
            }

        }

//        保存设置或重置
        $scope.takeover_keep = function(){
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){

            })
        }
        $scope.takeover_reset = function(){
            $scope.individual.takeover = "1";
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                if($scope.individual.takeover == "1"){
                    $scope.individual.save = "false";
                    $scope.show_circle = false;
                }else if($scope.individual.takeover == "2"){
                    $scope.individual.save = "true";
                    $scope.show_circle = true;
                    $scope.individual.circle = "cycle2";
                }else{
                    $scope.individual.save = "true";
                    $scope.show_circle = true;
                    $scope.individual.circle = "cycle1";
                }
            })
        }
})


//setting05
settings.controller('setting05Controller',
    function ($scope,$http) {
        $scope.pay_classify = false;
        $scope.income_classify = false;

        $scope.individual.pay_classify = {};

        //$scope.pay_classifies =[{"id":"1","classify":"衣服饰品"},{"id":"2","classify":"交通通讯"},{"id":"3","classify":"人情往来"},{"id":"5","classify":"医疗保健"}]
        $http.get("http://localhost/Finicial_Management/api/setting.php/classify_show/"+$scope.individual.nickname+"/1")
            .success(function(response) {
                $scope.pay_classifies = response.pay_classify;
                $scope.income_classifies = response.income_classify;
        })
//添加支出分类
        $scope.add_pay = function(){
            $scope.pay_classify = true;
            $scope.individual.pay_classify = "";
        }
        $scope.pay_confirm = function(){
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                $http.get("http://localhost/Finicial_Management/api/setting.php/classify_show/"+$scope.individual.nickname+"/1")
                    .success(function(response) {
                        $scope.pay_classifies = response.pay_classify;
                        $scope.pay_classify = false;
                    })
                $scope.individual.pay_classify = null;
            })
        }
        $scope.pay_cancel = function(){
            $scope.pay_classify = false;
            $scope.individual.pay_classify = null;
        }
//        添加收入分类
        $scope.add_income = function(){
            $scope.income_classify = true;
            $scope.individual.income_classify = "";
        }
        $scope.income_confirm = function(){
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                $http.get("http://localhost/Finicial_Management/api/setting.php/classify_show/"+$scope.individual.nickname+"/1")
                    .success(function(response) {
                        $scope.income_classifies = response.income_classify;
                        $scope.income_classify = false;
                    })
                $scope.individual.income_classify = null;
            })
        }
        $scope.income_cancel = function(){
            $scope.income_classify = false;
            $scope.individual.income_classify = null;
        }

        $scope.pay_delete = function(event){
            var id=event.target.getAttribute("class");
            $scope.individual.pay_id = id;
            var deleteUser = confirm('确定删除吗？');
            if (deleteUser) {
                $http({
                    method:'post',
                    url:"http://localhost/Finicial_Management/api/individual.php",
                    data:$scope.individual,
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                }).success(function(data){
                    setTimeout(function () {
                        $scope.$apply(function () {
                            $http.get("http://localhost/Finicial_Management/api/setting.php/classify_show/"+$scope.individual.nickname+"/1")
                                .success(function(response) {
                                    $scope.pay_classifies = response.pay_classify;
                                })
                        });
                    }, 200);
                })
            }
        }

        $scope.income_delete = function(event){
            var id=event.target.getAttribute("class");
            $scope.individual.income_id = id;
            var deleteUser = confirm('确定删除吗？');
            if (deleteUser) {
                $http({
                    method:'post',
                    url:"http://localhost/Finicial_Management/api/individual.php",
                    data:$scope.individual,
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                }).success(function(data){
                    setTimeout(function () {
                        $scope.$apply(function () {
                            $http.get("http://localhost/Finicial_Management/api/setting.php/classify_show/"+$scope.individual.nickname)
                                .success(function(response) {
                                    $scope.income_classifies = response.income_classify;
                                })
                        });
                    }, 200);
                })
            }
        }
})

//setting06
settings.controller('setting06Controller',
    function ($scope,$http,$interval) {
        //$scope.items =[
        //    {classify:'ji',account:'ji'},
        //    {classify:'ji',account:'ji'}
        //]
        $scope.month = 1;
        $scope.individual.month = $scope.month;
        $http.get("http://localhost/Finicial_Management/api/setting.php/classify_show/"+$scope.individual.nickname+"/"+$scope.month)
            .success(function(response) {
                $scope.items = response.data_budget;
                $scope.pay_select = "支";
                $scope.account_select = "收";

            })
        $interval(function(){
            $scope.apply(get_budget());
        },500);
        var get_budget = function(){
            $http.get("http://localhost/Finicial_Management/api/setting.php/classify_show/"+$scope.individual.nickname+"/"+$scope.month)
                .success(function(response) {
                    $scope.items = response.data_budget;
                    $scope.pay_select = "支";
                    $scope.account_select = "收";
                    $scope.individual.month = $scope.month;
                })
        }

        $scope.price_input = false;

//        bianji
        $scope.price_change = function(event,item){
            var id = event.target.getAttribute('class');
            $scope.individual.id = id;
            $scope.individual.price = "";
            $scope.price_input = true;
        }

        $scope.price_confirm = function(){
            $http({
                method:'post',
                url:"http://localhost/Finicial_Management/api/individual.php",
                data:$scope.individual,
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).success(function(data){
                $scope.individual.id = "";
                $scope.individual.price = "";
                $scope.price_input = false;
            })
        }

        $scope.price_cancel = function(){
            $scope.price_input = false;
        }

})