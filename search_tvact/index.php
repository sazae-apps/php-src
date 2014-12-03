<?php

try
{
    //-- 日本語エンコード自動判別設定
    mb_language('Japanese');

    $performerName = htmlspecialchars($_GET["performerName"],ENT_QUOTES);
    $area = htmlspecialchars($_GET["area"],ENT_QUOTES);

    //-- 入力値の両端の全角スペース、半角スペース除去
    $wkPerformerName = mbereg_replace('^[[[:space:]]　]*(.*?)[[[:space:]]　 ]*$','\\1', mb_convert_encoding($performerName, "EUC-JP", "AUTO"));

    //-- 半角スペース除去(タブとか改行とかも除去)
    $wkPerformerName = trim($wkPerformerName);

    //-- 入力値をEUC-JPに文字コード変換
    $wkCnvPerformerName = mb_convert_encoding($wkPerformerName, "EUC-JP", "AUTO");

    //-- 文字コード変換した入力値を別変数に格納
    $cnvPerformerName = $wkCnvPerformerName;

    if($cnvPerformerName == "")
    {
        getHtmlHeaderNoPfr();
        getHtmlForm();
        getHtmlfooter();
        exit;
    }

    if(!preg_match("/^[0][0-4][0-9]$/",$area))
    {
        getHtmlHeaderNoPfr();
        getHtmlForm();
        getHtmlfooter();
        exit;
    }
    else if(preg_match("/^[0][4][8-9]$/",$area))
    {
        getHtmlHeaderNoPfr();
        getHtmlForm();
        getHtmlfooter();
        exit;
    }

    if(mb_strlen($cnvPerformerName, "EUC-JP") > 30)
    {
        getHtmlHeaderNoPfr();
        getHtmlForm();
        getHtmlfooter();
        exit;
    }

    $Prefecture = getPrefecture($area);
    
    //-- 入力値をUTF-8に文字コード変換
    $cnvPerformerNameUTF8 = mb_convert_encoding($wkPerformerName, "UTF-8", "AUTO");

    getHtmlHeaderInPfr($cnvPerformerNameUTF8, $Prefecture);
    getHtmlForm();

    //-- URLエンコード処理
    $encodeCnvPerformerName = urlencode($cnvPerformerName);

    $tvInfoseekUrl = "http://tv.infoseek.co.jp/rss/keyword.rdf";
    $tvInfoseekUrl .= "?area=" .$area;
    $tvInfoseekUrl .= "&qt=" .$encodeCnvPerformerName;

    //echo $tvInfoseekUrl;
    //echo "<br>";
    //
    //cURLセッションを初期化する
    $ch = curl_init();

    //オプションを設定する
    curl_setopt($ch, CURLOPT_URL, $tvInfoseekUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

    //cURLセッションを実行する
    $curlResult = curl_exec($ch);

    //cURLリソースを閉じ、システムリソースを解放する
    curl_close($ch);

    $RSS = simplexml_load_string($curlResult);

    //var_dump($RSS);
    
    echo '<div id="channel-title">'."\n";
    echo '<a href="' .$RSS->channel->link .'">' .$RSS->channel->title ." (" .$Prefecture .")" ."</a>\n";
    echo '</div>' ."\n";

    echo '<div id="channel-description">' ."\n";
    echo "※" .$RSS->channel->description ."\n";
    echo '</div>' ."\n";

    foreach ($RSS->channel->item as $results)
    {
       echo '<div id="channel-item-title">' ."\n";
       echo '<a href="' .$results->link .'">' .$results->title ."</a>\n";
       echo '</div>' ."\n";
    }
    getHtmlfooter();
    exit;
}
catch (Exception $e)
{
    getHtmlHeaderNoPfr();
    getHtmlForm();
    getHtmlfooter();
    exit;
}

function getHtmlHeaderNoPfr()
{
print <<<_EOT_
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>TV番組を出演者で検索</title>
</head>
<body>
_EOT_;
return;
}

function getHtmlHeaderInPfr($pfrName, $pfc)
{
print <<<_EOT1_
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
_EOT1_;

echo "<title>[" .$pfrName ."]出演TV番組一覧(" .$pfc .")</title>";

print <<<_EOT2_
</head>
<body>
_EOT2_;
return;
}

function getHtmlForm()
{
print <<<_EOT_
<div id="form1">
<form action="index.php" method="get">
<div class="sentence1">
◎検索対象のTV出演者名を入力してください。
</div>
<div class="input-text1">
<input type="text" size="30" maxlength ="30" name="performerName" tabindex="1" />
</div>

<div class="form1-select">
<select name="area">
<option value="001">北海道</option>
<option value="002">青森県</option>
<option value="003">秋田県</option>
<option value="004">岩手県</option>
<option value="005">山形県</option>
<option value="006">宮城県</option>
<option value="007">福島県</option>
<option value="008" selected="selected">東京都</option>
<option value="009">神奈川県</option>
<option value="010">埼玉県</option>
<option value="011">千葉県</option>
<option value="012">群馬県</option>
<option value="013">栃木県</option>
<option value="014">茨城県</option>
<option value="015">新潟県</option>
<option value="016">長野県</option>

<option value="017">山梨県</option>
<option value="018">静岡県</option>
<option value="019">愛知県</option>
<option value="020">岐阜県</option>
<option value="021">三重県</option>
<option value="022">富山県</option>
<option value="023">石川県</option>
<option value="024">福井県</option>
<option value="025">大阪府</option>

<option value="026">京都府</option>
<option value="027">兵庫県</option>
<option value="028">奈良県</option>
<option value="029">和歌山県</option>
<option value="030">滋賀県</option>
<option value="031">岡山県</option>
<option value="032">広島県</option>
<option value="033">鳥取県</option>
<option value="034">島根県</option>

<option value="035">山口県</option>
<option value="036">香川県</option>
<option value="037">徳島県</option>
<option value="038">愛媛県</option>
<option value="039">高知県</option>
<option value="040">福岡県</option>
<option value="041">佐賀県</option>
<option value="042">長崎県</option>
<option value="043">熊本県</option>

<option value="044">大分県</option>
<option value="045">宮崎県</option>
<option value="046">鹿児島県</option>
<option value="047">沖縄県</option>
</select>
</div>

<div class="input-submit1">
    <input type="submit" value="検索する" tabindex="2"/>
</div>
</form>
</div>
<hr/>
_EOT_;

return;
}

function getHtmlfooter()
{
print <<<_EOT_
<hr/>
<div class="t2">
<br/>
Powered by <a href="http://tv.infoseek.co.jp" target="_blank">Rakuten, Inc.</a><br/>
</div>
</body>
</html>
_EOT_;

return;
}

function getPrefecture($PrefectureNum)
{
switch($PrefectureNum)
{
    case "001":
        return "北海道";
        break;

    case "002":
        return "青森県";
        break;

    case "003":
        return "秋田県";
        break;

    case "004":
        return "岩手県";
        break;

    case "005":
        return "山形県";
        break;

    case "006":
        return "宮城県";
        break;

    case "007":
        return "福島県";
        break;

    case "008":
        return "東京都";
        break;

    case "009":
        return "神奈川県";
        break;

    case "010":
        return "埼玉県";
        break;

    case "011":
        return "千葉県";
        break;

    case "012":
        return "群馬県";
        break;

    case "013":
        return "栃木県";
        break;

    case "014":
        return "茨城県";
        break;

    case "015":
        return "新潟県";
        break;

    case "016":
        return "長野県";
        break;

    case "017":
        return "山梨県";
        break;

    case "018":
        return "静岡県";
        break;

    case "019":
        return "愛知県";
        break;

    case "020":
        return "岐阜県";
        break;

    case "021":
        return "三重県";
        break;

    case "022":
        return "富山県";
        break;

    case "023":
        return "石川県";
        break;

    case "024":
        return "福井県";
        break;

    case "025":
        return "大阪府";
        break;

    case "026":
        return "京都府";
        break;

    case "027":
        return "兵庫県";
        break;

    case "028":
        return "奈良県";
        break;

    case "029":
        return "和歌山県";
        break;

    case "030":
        return "滋賀県";
        break;

    case "031":
        return "岡山県";
        break;

    case "032":
        return "広島県";
        break;

    case "033":
        return "鳥取県";
        break;

    case "034":
        return "島根県";
        break;

    case "035":
        return "山口県";
        break;

    case "036":
        return "香川県";
        break;

    case "037":
        return "徳島県";
        break;

    case "038":
        return "愛媛県";
        break;

    case "039":
        return "高知県";
        break;

    case "040":
        return "福岡県";
        break;

    case "041":
        return "佐賀県";
        break;

    case "042":
        return "長崎県";
        break;

    case "043":
        return "熊本県";
        break;

    case "044":
        return "大分県";
        break;

    case "045":
        return "宮崎県";
        break;

    case "046":
        return "鹿児島県";
        break;

    case "047":
        return "沖縄県";
        break;

    default:
        return;
}

}

?>
