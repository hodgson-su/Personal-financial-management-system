//账目清单
var bills = angular.module('BillsCtrl',['ngRoute','ui.bootstrap']);
bills.controller('bills01Controller',
    function($scope,$http,$cookies){
        $http.get("http://localhost/Finicial_Management/api/index.php/mes_show/"+$cookies.nickname)
            .success(function(response) {
                $scope.items = response;
            })
        //新增记录
        //支出收入切换
        $scope.individual = {};
        $scope.individual.budget = "支";
        $scope.bills01_expend_hover = true;
        $scope.bills01_expend = false;
        $scope.add_mes = true;
        $scope.change_mes = false;

        selete_budget($scope,$http);

        //支出按钮点击
        $scope.click_bills01_expend = function(){
            $scope.bills01_expend_hover = true;
            $scope.bills01_expend = false;
            $scope.bills01_income_hover = false;
            $scope.bills01_income = true;
            $scope.bills01_change_hover = false;
            $scope.bills01_change = true;
            $scope.individual.budget = "支";

            $scope.add_mes = true;
            $scope.change_mes = false;

            $scope.change_budget = false;
            //添加写入表单是支出还是收入
            selete_budget($scope,$http);
        }
        //收入按钮点击
        $scope.click_bills01_income = function(){
            $scope.bills01_income_hover = true;
            $scope.bills01_income = false;
            $scope.bills01_expend_hover = false;
            $scope.bills01_expend = true;
            $scope.bills01_change_hover = false;
            $scope.bills01_change = true;
            $scope.individual.budget = "收";

            $scope.add_mes = true;
            $scope.change_mes = false;

            $scope.change_budget = false;
            //添加写入表单是支出还是收入
            selete_budget($scope,$http);
        }
        $scope.click_bills01_change = function(){
            alert("请点击对应行的修改按钮进行修改");
        }
        //$scope.accounts = ["现金", "银行卡", "负债","投资","保险"];
        //$scope.budgets = ["支","收"];

        $scope.change_budget =false;
        $scope.individual.nickname =$cookies.nickname;

        $scope.bills01_add = function(){
            if($scope.individual.classify == null || $scope.individual.account == null){
                $scope.dd = "请填写分类和账户";
            }else if($scope.individual.price == null){
                $scope.dd = "请填写金额";
            }else{
                $http({
                    method:'post',
                    url:"http://localhost/Finicial_Management/api/index.php/mes_add/"+$scope.individual.nickname,
                    data:$scope.individual,
                    headers:{
                        "Content-Type": "application/json"
                    },
                }).success(function(data){
                    setTimeout(function () {
                        $scope.$apply(function () {
                            $http.get("http://localhost/Finicial_Management/api/index.php/mes_show/"+$scope.individual.nickname)
                                .success(function(response){
                                    $scope.items = response;
                                    $scope.dd = "填写成功";
                                    $scope.individual.account = null;
                                    $scope.individual.classify = null;
                                    $scope.individual.price = null;
                                    $scope.individual.remark = null;
                                })
                        });
                    }, 200);

                    setTimeout(function(){
                        $scope.$apply(function(){
                            $scope.dd = null;
                        })
                    },2000);
                })
            }
        }

        $scope.bills01_change_mes = function(){
            var deleteUser = confirm('确定修改吗？');
            if (deleteUser) {
                $http({
                    method:'put',
                    url:"http://localhost/Finicial_Management/api/index.php/"+$scope.individual.nickname+"/"+$scope.individual.id,
                    data:$scope.individual,
                    headers:{
                        "Content-Type": "application/json"
                    },
                }).success(function(data){
                    setTimeout(function () {
                        $scope.$apply(function () {
                            $http.get("http://localhost/Finicial_Management/api/index.php/mes_show/"+$scope.individual.nickname)
                                .success(function(response){
                                    $scope.items = response;
                                    $scope.dd = "填写成功";
                                    $scope.individual.account = null;
                                    $scope.individual.classify = null;
                                    $scope.individual.price = null;
                                    $scope.individual.remark = null;
                                })
                        });
                    }, 200);
                    setTimeout(function(){
                        $scope.$apply(function(){
                            $scope.dd = null;
                        })
                    },2000);
                })
            }
        }

        $scope.click_delete = function(event){
            $scope.individual.nickname = $cookies.nickname;
            var id=event.target.getAttribute("class");
            $scope.individual.id = id;
            var deleteUser = confirm('确定删除吗？');
            if (deleteUser) {
                $http.delete("http://localhost/Finicial_Management/api/index.php/mes_delete/"+$scope.individual.nickname+"/"+$scope.individual.id);
                setTimeout(function () {
                    $scope.$apply(function () {
                        $http.get("http://localhost/Finicial_Management/api/index.php/mes_show/"+$scope.individual.nickname)
                            .success(function(response){
                                $scope.items = response;
                            })
                    });
                }, 200);
            }
        }

        $scope.click_change = function(event,item){
            $scope.individual.nickname = $cookies.nickname;
            var id = event.target.getAttribute('class');
            $scope.individual.id = id;
            //$scope.message = $scope.id;
            $scope.bills01_expend_hover = false;
            $scope.bills01_expend = true;
            $scope.bills01_income_hover = false;
            $scope.bills01_income = true;
            $scope.bills01_change_hover = true;
            $scope.bills01_change = false;

            $scope.individual.id = item.id;
            $scope.individual.classify = item.classify;
            $scope.individual.account = item.account;
            $scope.individual.price = item.price;
            $scope.individual.remark = item.remark;
            $scope.individual.budget = item.budget;
            selete_budget($scope,$http);

            $scope.add_mes = false;
            $scope.change_mes = true;

            $scope.change_budget = true;
        }

    //    select
        $scope.bill = {
            dueOn: new Date(),
            payee: "",
            amount: 0
        }
        $scope.open = function ($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened = true;
        }

        $scope.select_all = function(){
            $scope.select = "";
            $scope.account = "";
        }
    })

var selete_budget = function($scope,$http){
    $http({
        method:'post',
        url:"http://localhost/Finicial_Management/api/individual.php",
        data:$scope.individual,
        headers:{
            'Content-Type': 'application/x-www-form-urlencoded'
        },
    }).success(function(data){
        if($scope.individual.budget == "支"){
            $scope.classifies = data.classify;
        }else{
            $scope.classifies = data.classify_income;
        }
        $scope.accounts = data.account;
    })
}

bills.controller('bills02Controller',
    function($scope,$http){
        //获取数据
        var date = new Date();
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        $scope.newDate = month+"-"+strDate;

        $scope.get_mes = true;

        $http.get("http://localhost/Finicial_Management/api/remind.php/remind/"+$scope.individual.nickname)
            .success(function(response){
                $scope.items = response;
                $scope.get_mes = response;

            })
        $scope.bill = {
            dueOn: new Date(),
            payee: "",
            amount: 0
        }
        $scope.open = function ($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened = true;
        }

        $scope.individual.date = {};
        $scope.individual.year = {};
        $scope.individual.month = {};
        $scope.individual.remind = null;
        $scope.bills02_add = function(){
            if($scope.bill.dueOn == null || $scope.individual.remind == null){
                $scope.dd = "请填写完整";
            }
            else{
                $scope.individual.date = $scope.bill.dueOn;
                $scope.individual.year = $scope.bill.dueOn.getFullYear();
                $scope.individual.month = $scope.bill.dueOn.getMonth()+1;
                $scope.individual.day = $scope.bill.dueOn.getDate();
                $scope.dd = "";
                $http({
                    method:'post',
                    url:"http://localhost/Finicial_Management/api/remind.php/remind_add/"+$scope.individual.nickname,
                    data:$scope.individual,
                    headers:{
                        "Content-Type": "application/json"
                    },
                }).success(function(data){
                    $scope.individual.remind = null;
                    setTimeout(function () {
                        $scope.$apply(function () {
                            $http.get("http://localhost/Finicial_Management/api/remind.php/remind/"+$scope.individual.nickname)
                                .success(function(response){
                                    $scope.items = response;
                                })
                        });
                    }, 200);

                })
            }
        }

        $scope.click_delete = function(event){
            var id=event.target.getAttribute("class");
            $scope.individual.id = id;
            var deleteUser = confirm('确定删除吗？');
            if (deleteUser) {
                $http.delete("http://localhost/Finicial_Management/api/remind.php/remind_delete/"+$scope.individual.nickname+"/"+$scope.individual.id);
                setTimeout(function () {
                    $scope.$apply(function () {
                        $http.get("http://localhost/Finicial_Management/api/remind.php/remind/"+$scope.individual.nickname)
                            .success(function(response){
                                $scope.items = response;
                            })
                    });
                }, 200);

            }
        }

        $scope.mes_close = function(){
            $scope.get_mes = false;
        }
})