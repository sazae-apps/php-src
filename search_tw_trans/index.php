<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Twitterのキーワード検索結果のtweetをGoogle翻訳</title>
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
-->
</style>
</head>
<body>

<div id="t1">
<form action="index.php" method="get">
◎Twitterのキーワード検索結果のtweetをGoogle翻訳
<hr/>
検索対象のキーワードを入力してください。<br/>
<div class="t2">
※Twitterのキーワード検索結果のtweetが翻訳されます。<br/>
※日本語→英語 or 英語→日本語に翻訳されます。<br/>
※上記以外の言語はいまのところ翻訳されません。<br/>
</div>

<div class="info">
<input type="text" maxlength ="30" name="searchWord" tabindex="1" />
<span class="status-btn">
<input type="submit" value="検索して翻訳する" tabindex="2" />
</span>
</div>
</form>
</div>
<hr/>

<?php

try
{
    //-- 日本語エンコード自動判別設定
    mb_language('Japanese');

    $searchWord = htmlspecialchars($_GET["searchWord"],ENT_QUOTES);

    //-- 検索キーワード両端の全角スペース、半角スペース除去
    $wkSearchWord = mbereg_replace('^[[[:space:]]　]*(.*?)[[[:space:]]　 ]*$','\\1', mb_convert_encoding($searchWord, "UTF-8", "AUTO"));

    //-- 半角スペース除去(タブとか改行とかも除去)
    $wkSearchWord = trim($wkSearchWord);

    //-- 入力検索キーワードをUTF-8にエンコード
    $wkCnvSearchWord = mb_convert_encoding($wkSearchWord, "UTF-8", "AUTO");

    //-- 修正版 google 入力検索キーワード
    $cnvSearchWord = $wkCnvSearchWord;

    if($cnvSearchWord == "")
    {
        getUrlAndHtml();
        exit;
    }

    if(mb_strlen($cnvSearchWord, "UTF-8") > 30)
    {
        getUrlAndHtml();
        exit;
    }

    //-- URLエンコード処理
    $encodeCnvSearchWord = urlencode($cnvSearchWord);

    include_once 'CallTwitterApiJson.php';
    include_once 'CallGoogleTranslateApi.php';

    sleep(2);
    $obj1 = new CallTwitterApiJson();
    $obj2 = new CallGoogleTranslateApi();

    $obj1->setTwitterSearchWord("$encodeCnvSearchWord");
    $JSON = $obj1->getTwitterData();

    //var_dump($JSON);
    //exit;

    if(($JSON == "") or (count($JSON["results"]) == 0))
    {
        echo '"' .$cnvSearchWord. '"に一致する情報は見つかりませんでした。';
        getUrlAndHtml();
        exit;
    }
    else
    {
        echo '"' .$cnvSearchWord. '"の検索結果';
        echo "<hr/>";
 
        foreach ($JSON["results"] as $results)
        {
            if($results["iso_language_code"] == "en")
            {
                $obj2->setTranslateLang("en_to_ja");
            }
            elseif($results["iso_language_code"] == "ja")
            {
                $obj2->setTranslateLang("jp_to_en");
            }
            else
            {
                $obj2->setTranslateLang("en_to_ja");
            }

            $obj2->setTranslateString($results["text"]);
            $translatedString = $obj2->getTranslatedString();

            echo '<div class="status-body">';
            echo '<strong>';
            echo '<a href="http://twitter.com/' .$results["from_user"]. '" class="tweet-url screen-name">' .$results["from_user"]. '</a>';
            echo '</strong>';
            echo '</div>';
            echo '<span class="thumb vcard author">';
            echo '<a href="http://twitter.com/' .$results["from_user"]. '" class="tweet-url profile-pic url">';
            echo '<img alt="' .$results["from_user"]. '" class="photo fn" align="left" height="48" src="' .$results["profile_image_url"]. '" width="48" />';
            echo '</a>';
            echo '</span>';
            echo '<span class="gt">google 翻訳前：</span>';
            echo '<span class="entry-content">' .$results["text"]. '</span>';
            echo '<br/>';
            echo '<span class="gt">google 翻訳後：</span>';
            echo '<span class="entry-content">' .$translatedString. '</span>';
            echo "<hr/>";
        }
    }
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
    echo 'Powered by <a href="http://apiwiki.twitter.com/" target="_blank">Twitter API</a><br/>';
    echo 'Powered by <a href="http://code.google.com/intl/ja/apis/ajaxlanguage/" target="_blank">Google AJAX Language API</a><br/>';
    echo "</div>";
    echo "</body>";
    echo "</html>";
    return;
}

?>
