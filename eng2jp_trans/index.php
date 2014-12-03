<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>ちょこっと翻訳</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style type="text/css">
<!--
img { float: left; border-style:none;}
.cl { clear: left }
.cr { clear: right }
a { text-decoration: none; font-size:small;}
a:link { color: #00008B }
.gt {color: #191970; font-size:small;}
.entry-content {font-size:small;}
.t2 {font-size:small;}
.status-btn {}
-->
</style>
</head>
<body>

<div id="t1">
<form action="index.php" method="post">
◎ちょこっと翻訳。
<hr/>
翻訳したい文章を入力してください。<br/>
<div class="info">
<input type="text" size="60" maxlength ="140" name="tranSentence" tabindex="1" />
<br/>
<input type="radio" name="tranLang" value="jp_to_en" checked>日本語から英語へ翻訳<br/>
<input type="radio" name="tranLang" value="en_to_ja" >英語から日本語へ翻訳<br/>
<div class="status-btn">
<input type="submit" value="翻訳する" tabindex="2"/>
</div>
</div>
</form>
</div>
<hr/>

<?php

try
{
    //-- 日本語エンコード自動判別設定
    mb_language('Japanese');

    $tranSentence = htmlspecialchars($_POST["tranSentence"],ENT_QUOTES);
    $tranLang = htmlspecialchars($_POST["tranLang"],ENT_QUOTES);

    //-- 翻訳対象文章の両端の全角スペース、半角スペース除去
    $wkTranSentence = mbereg_replace('^[[[:space:]]　]*(.*?)[[[:space:]]　 ]*$','\\1', mb_convert_encoding($tranSentence, "UTF-8", "AUTO"));

    //-- 半角スペース除去(タブとか改行とかも除去)
    $wkTranSentence = trim($wkTranSentence);

    //-- 翻訳対象文章をUTF-8にエンコード
    $wkCnvTranSentence = mb_convert_encoding($wkTranSentence, "UTF-8", "AUTO");

    //-- 修正版 google 翻訳対象文章の格納
    $cnvTranSentence = $wkCnvTranSentence;

    if($cnvTranSentence == "")
    {
        getUrlAndHtml();
        exit;
    }

    if(mb_strlen($cnvTranSentence, "UTF-8") > 140)
    {
        getUrlAndHtml();
        exit;
    }

    //-- URLエンコード処理
    //$encodeCnvTranSentence = urlencode($cnvTranSentence);

    include_once 'CallGoogleTranslateApi.php';

    sleep(1);
    $obj = new CallGoogleTranslateApi();

    switch($tranLang)
    {
        case "jp_to_en":
            $obj->setTranslateLang("jp_to_en");
            break;
        
        case "en_to_ja":
            $obj->setTranslateLang("en_to_ja");
            break;

        default:
            $obj->setTranslateLang("jp_to_en");
    }

    //$obj->setTranslateLang("ja_to_en");
    $obj->setTranslateString($cnvTranSentence);
    $translatedString = $obj->getTranslatedString();

    echo '<span class="gt">google 翻訳前：</span>';
    echo '<span class="entry-content">' .$cnvTranSentence. '</span>';
    echo '<br/>';
    echo '<span class="gt">google 翻訳後：</span>';
    echo '<span class="entry-content">' .$translatedString. '</span>';
}
catch (Exception $e)
{
    getUrlAndHtml();
    exit;
}

getUrlAndHtml();
exit;

function getUrlAndHtml()
{
    echo "<hr/>";
    echo '<div class="t2">';
    echo 'Powered by <a href="http://code.google.com/intl/ja/apis/ajaxlanguage/" target="_blank">Google AJAX Language API</a><br/>';
    echo "</div>";
    echo "</body>";
    echo "</html>";
    return;
}

?>
