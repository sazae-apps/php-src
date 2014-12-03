<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>TwitterのtweetをGoogle翻訳</title>
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
TwitterのtweetをGoogle翻訳。
<hr/>
翻訳したいTwitterのユーザ名を入力してください。<br/>
<div class="t2">
※日本語→英語 or 英語→日本語に翻訳されます。<br/>
※上記以外の言語はいまのところ翻訳されません。<br/>
</div>

<div class="info">
<input type="text" maxlength ="30" name="userName" tabindex="1" />
<span class="status-btn">
<input type="submit" value="ほんやくする" tabindex="2" />
</span>
</div>
</form>
</div>
<hr/>

<?php

try
{   
    $getTwitterUserName = htmlspecialchars($_GET["userName"],ENT_QUOTES);

    if(strlen($getTwitterUserName) > 30)
    {
        getUrlAndHtml();
        exit;
    }

    if($getTwitterUserName == "")
    {
        getUrlAndHtml();
        exit;
    }

    include_once 'CallTwitterApi.php';
    include_once 'CallGoogleTranslateApi.php';

    sleep(2);
    $obj1 = new CallTwitterApi();
    $obj2 = new CallGoogleTranslateApi();

    $obj1->setTwitterUserName("$getTwitterUserName");
    $XML = $obj1->getTwitterData();

    if($XML->status == "")
    {
        echo "指定したユーザは見つかりませんでした。";
        echo "<hr/>";
    }
    else
    {
        echo '<a href="http://xxxxxxxxxxxx/">■一覧に戻る</a>';
        echo "<hr/>";
        
        foreach ($XML->status as $status)
        {
            if($status->user->lang == "en")
            {
                switch ($status->user->time_zone)
                {
                    case "Tokyo":
                    case "Osaka":
                    case "Sapporo":
                        $obj2->setTranslateLang("jp_to_en");
                        break;
                    default:
                        $obj2->setTranslateLang("en_to_ja");
                }
            }
            else
            {
                $obj2->setTranslateLang("jp_to_en");
            }

            $obj2->setTranslateString($status->text);
            $translatedString = $obj2->getTranslatedString();

            echo '<div class="status-body">';
            echo '<strong>';
            echo '<a href="http://twitter.com/' .$status->user->screen_name. '" class="tweet-url screen-name">' .$status->user->screen_name. '</a>';
            echo '</strong>';
            echo '</div>';
            echo '<span class="thumb vcard author">';
            echo '<a href="http://twitter.com/' .$status->user->screen_name. '" class="tweet-url profile-pic url">';
            echo '<img alt="' .$status->user->screen_name. '" class="photo fn" align="left" height="48" src="' .$status->user->profile_image_url. '" width="48" />';
            echo '</a>';
            echo '</span>';
            echo '<span class="gt">google 翻訳前：</span>';
            echo '<span class="entry-content">' .$status->text. '</span>';
            echo '<br/>';
            echo '<span class="gt">google 翻訳後：</span>';
            echo '<span class="entry-content">' .$translatedString. '</span>';
            echo '<hr/>';
        }   
    }
    echo '<a href="http://xxxxxxxxxxxxxx/">■一覧に戻る</a>';
    echo "<hr/>";
}
catch (Exception $e)
{
    getUrlAndHtml();
    exit;
}

function getUrlAndHtml()
{
    echo '<div class="t2">';
    echo "◎以下のtweetをGoogle翻訳。<br/>";
    echo "</div>";
    echo "<hr/>";
    echo '<a href="http://xxxxxxxxx/index.php?userName=mrskutcher">●●・●●●</a><br/>';
    echo "<hr/>";
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

<div class="t2">
Powered by <a href="http://apiwiki.twitter.com/" target="_blank">Twitter API</a><br/>
Powered by <a href="http://code.google.com/intl/ja/apis/ajaxlanguage/" target="_blank">Google AJAX Language API</a><br/>
</div>
</body>
</html>
