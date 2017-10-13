<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use api\assets\AppAsset;

$this->registerJsFile("http://libs.baidu.com/jquery/2.0.0/jquery.min.js");
$this->registerJsFile("https://cdn.bootcss.com/bootstrap/4.0.0-beta/js/bootstrap.js");
$this->registerCssFile("https://cdn.bootcss.com/bootstrap/4.0.0-beta/css/bootstrap.css");

AppAsset::register($this);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>用户信息</title>
</head>
<body>
<form action="" method="post">
    <div class="mycenter">
        <div class="mysign">
            <div class="col-lg-11 text-center text-info">
                <h2>请填写完整信息</h2>
            </div>
            <div class="col-lg-10">
                <input type="text" class="form-control" name="username" placeholder="请输入手机号码" required autofocus/>
            </div>
            <div class="col-lg-10"></div>
            <div class="col-lg-10">
                <input type="text" class="form-control" name="realname" placeholder="真实姓名" required autofocus/>
            </div>
            <div class="col-lg-10"></div>

            <div class="col-lg-10"></div>
            <div class="col-lg-10">
                <button type="button" id="btn";class="btn btn-success col-lg-12">提交</button>
            </div>
        </div>
    </div>
</form>
</body>
</html>
<script>

    $(function(){

      $('#btn').click(function(){
         alert('dasdfasd');
         var url =' http://api.dz.com/v1/eval/1?token=3bOd34hIab3mbr7CBu3CJwQuMyuAqwFR_1507875652';
      });
    });
</script>