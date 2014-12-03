<?php

//設定ファイルインクルード
require("kansan_config.php");

//外部変数取得
if(isset($_GET["mode"]))
{
	$input_mode = $_GET["mode"];
} else {
	$input_mode = "kansan_input";
}

//ページの表示
header("Content-Type: text/html; charset=UTF-8");

// 入力画面
print <<<_EOT_
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>バイト換算</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
 
<!--
<link rel="stylesheet" href="">
-->

<!--[if lt IE 9]>
<script src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
 
<!--  
<link rel="shortcut icon" href="">
-->

<!--
<meta name="mobile-web-app-capable" content="yes">
<link rel="icon" sizes="196x196" href="">
<link rel="apple-touch-icon" sizes="152x152" href="">
-->
</head>
<body>
_EOT_;

//ファイルinclude
include($input_mode . ".php");

print <<<_EOT_
</body>
</html>
_EOT_;

?>
