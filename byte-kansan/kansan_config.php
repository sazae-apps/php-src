<?php

//アクセス制限
if(preg_match("*kansan_config*", $_SERVER["PHP_SELF"]))
{
	header("Location: /");
	exit();
}

//マルチバイト文字列関連
mb_language("Japanese");
mb_detect_order("auto");

ini_set("mbstring.http_input", "auto");
mb_http_output("auto");
mb_internal_encoding("UTF-8");
mb_substitute_character("none");

?>
