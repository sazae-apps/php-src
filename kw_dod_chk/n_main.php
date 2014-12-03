<?php

//degree of difficulty
//-----------------
// 初期化処理
//-----------------

//-- 日本語エンコード自動判別設定
mb_language('Japanese');

//-- 環境設定ファイル読み込み
require('./conf/n.conf');

//-- ファイル出力処理関数読込
require_once('n_write_file.php');

//-- google検索結果数取得関数読込
require_once('n_get_data.php');

//-- 排他処理解除関数読込 
require_once('n_return_exclusion.php');

//-- 実行ログ出力関数読込
require_once('n_output_msg.php');

//-- デフォルトタイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');
//echo date_default_timezone_get();

//-----------------
// PHP設定関連処理
//-----------------
//-- エラー出力設定ON
//error_reporting(E_ALL);

//-- PHPの実行時間制限を解除する
set_time_limit(0);

//-- 外部への通信待ち時間の設定
ini_set('default_socket_timeout', 60*5);

//-- ブラウザからの接続の切断を無視して実行を継続する
ignore_user_abort(1);

//-- ブラウザからの接続が切断された場合、処理を停止・初期化
//ignore_user_abort(0);

//-- 実行ユーザ制限数
$userLimitNum = 5;

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' ."\n";
echo '<html>'."\n";
echo '<head>'."\n";
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'."\n";
echo '<title>キーワード難易度測定ツール（n）</title>'."\n";
echo '</head>'."\n";
echo '<body>'."\n";

//-- 緊急システム停止処理
$lockFlag = $lockFlagPath .$lockFlagName;
if(file_exists($lockFlag))
{
    echo "※現在「キーワード難易度測定ツール」は利用できません。\n";
    flush();
    echo "<BR>\n";
    flush();

    echo '</body>'."\n";
    echo '</html>'."\n";
    exit;
}

//-- 日時指定後のxlsファイル削除
$rmPath = $kwDifficultyResultFilePath ."*_*";

//-- 2日前
$rmTime = time() - 60*60*24*2;

//-- 3時間前
//$rmTime = time() - 60*60*3;

foreach(glob($rmPath) as $filename)
{
    if(filemtime($filename) < $rmTime)
    {
        //-- 削除
        $result = unlink($filename);
        if($result == FALSE)
        {
            echo "ファイル削除に失敗しました。\n";
            flush();
            echo "<BR>\n";
            flush();
            echo "時間をおいてもう一度実行してください。\n";
            flush();
            echo "<BR>\n";
            flush();
            echo '<a href="index.php">←入力画面に戻る</a>'."\n";
            flush();
            
            echo '</body>'."\n";
            echo '</html>'."\n";
            exit;
        }
    }
}

//-- 共通のファイルIDを格納
$fileId = date("YmdHis", time());

sleep(1);

//-- ユニークな一時ファイル名の作成
$tmpKwDifficultyResultFile = tempnam($kwDifficultyResultFilePath, $fileId."_");
if($tmpKwDifficultyResultFile == FALSE)
{
    echo "ファイル作成に失敗しました。\n";
    flush();
    echo "<BR>\n";
    flush();
    echo "時間をおいてもう一度実行してください。\n";
    flush();
    echo "<BR>\n";
    flush();
    echo '<a href="index.php">←入力画面に戻る</a>'."\n";
    flush();

    echo '</body>'."\n";
    echo '</html>'."\n";
    exit;
}

//-- 実行ユーザ数制限処理
$userLimitCount = 0;
$kwDRFile = $kwDifficultyResultFilePath ."*_*";
foreach(glob($kwDRFile) as $filename)
{
    $tmpKDRFile = basename($filename);
    if(!(preg_match("*_n*", $tmpKDRFile)))
    {
        if($tmpKDRFile != "index.php")
        {
            $userLimitCount++;
        }
    }

    if($userLimitCount > $userLimitNum)
    {
        echo "※実行ユーザ数を超えています。\n";
        echo "<BR>\n";
        flush();
        echo "時間をおいてもう一度実行してください。\n";
        flush();
        echo "<BR>\n";
        flush();
        echo '<a href="index.php">←入力画面に戻る</a>'."\n";
        flush();
        
        echo '</body>'."\n";
        echo '</html>'."\n";
        exit;
    }
}

//-- キーワード難易度測定結果xlsファイル名作成
$kwDifficultyResultFile = $tmpKwDifficultyResultFile .$kwDifficultyResultFileName;

//-- 検索キーワードをフォームより取得
$searchWordAry = explode("\n",htmlspecialchars($_POST['searchWords'],ENT_QUOTES));

//-- 検索キーワード数
$searchWordCnt = 0;

$inputWordCnt = 0;

//-- フォーム入力値チェック
foreach ($searchWordAry as $searchWordChk)
{
    //-- 全角スペース除去
    $searchWordChk = mbereg_replace("　", "", mb_convert_encoding($searchWordChk, "UTF-8", "AUTO"));

    //-- 半角スペース除去
    $searchWordChk = trim(mbereg_replace(" ", "", $searchWordChk));

    if($searchWordChk != "")
    {
        $inputWordCnt = $inputWordCnt + 1;
    }
}

//--　フォームへ検索キーワードが入力されていなかった場合
if ($inputWordCnt == 0)
{
    //-- 処理スピードの調節
    sleep(1);

    //-- 排他解除処理
    $result = n_return_exclusion($tmpKwDifficultyResultFile);
    if($result == 1)
    {
        //-- 実行ログ出力処理（画面出力のみ）
        $msg = "n_return_exclusion error 1";
	n_output_msg("1", "", "n_main.php", "ERROR", "", $msg);
    }
   
    echo "※フォームに検索キーワードを入力して、実行してください。\n";
    flush();
    echo "<BR>\n";
    flush();
    echo '<a href="index.php">←入力画面に戻る</a>'."\n";
    flush();
    echo "<BR>\n";
    flush();

    echo '</body>'."\n";
    echo '</html>'."\n";
    exit;
}

//-- 実行ログ出力処理（画面出力のみ）
$msg = "call n_main";
n_output_msg("1", "", "n_main.php", "START", "", $msg);

//-- キーワード難易度測定結果ファイルのヘッダ作成
$today = "日付";
$searchWord = "検索キーワード";
$kwDifficulty = "難易度";
$estimatedTotalResultsCount1 = "通常検索結果数";
$estimatedTotalResultsCount2 = "allintitle検索結果数";
$estimatedTotalResultsCount3 = "allinanchor検索結果数";

//-- キーワード難易度測定結果ファイルのヘッダを連想配列化
$searchResultData = compact("today","searchWord","kwDifficulty","estimatedTotalResultsCount1","estimatedTotalResultsCount2","estimatedTotalResultsCount3");

//-- ブラウザとの接続切断を検知
if(connection_aborted() == 1)
{
    //-- 一時ファイル削除
    if(file_exists($tmpKwDifficultyResultFile))
    {
        unlink($tmpKwDifficultyResultFile);
    }
    exit;
}

//-- 入力検索キーワード数分ループ
foreach ($searchWordAry as $searchWordLine)
{
    $searchWordLine = trim($searchWordLine);

    // 入力検索キーワードが存在する場合
    if(($searchWordLine != "") and ($searchWordLine != "　"))
    {
        //-- 検索キーワードの処理カウント
        $searchWordCnt = $searchWordCnt + 1;

        //-- 実行ログ出力処理（画面出力のみ）
	$msg = "call n_get_data ( " .$searchWordCnt ." / " .$inputWordCnt ." )";
	n_output_msg("1", "", "n_main.php", "START", $searchWordLine, $msg);

        //-- 処理スピードの調節
        sleep(1);

	//-- Google AJAX Search API でキーワード検索結果数取得処理の呼び出し
	$result = n_get_data($searchWordLine);
        if($result == "")
        {
            //-- 実行ログ出力処理（画面出力のみ）
            $msg = "n_get_data error";
            n_output_msg("1", "", "n_main.php", "ERROR", "", $msg);

            //-- 排他解除処理
            $result = n_return_exclusion($tmpKwDifficultyResultFile);
            if($result == 1)
            {
                //-- 実行ログ出力処理（画面出力のみ）
                $msg = "n_return_exclusion error";
		n_output_msg("1", "", "n_main.php", "ERROR", "", $msg);
            }

            echo '<a href="index.php">←入力画面に戻る</a>'."\n";
            flush();
            exit;
        }

        //-- キーワード難易度測定結果を連想配列化
        $searchResultData = array_merge_recursive($searchResultData,$result);                

	//-- 実行ログ出力処理（画面出力のみ）
	$msg = "return n_get_data ( " .$searchWordCnt ." / " .$inputWordCnt ." )";
	n_output_msg("1", "", "n_main.php", "END", $searchWordLine, $msg);
    }

    //-- ブラウザとの接続切断を検知
    if(connection_aborted() == 1)
    {
        //-- 一時ファイル削除
        if(file_exists($tmpKwDifficultyResultFile))
        {
            unlink($tmpKwDifficultyResultFile);
        }

        //-- キーワード難易度測定結果xlsファイル削除
        if(file_exists($kwDifficultyResultFile))
        {
            unlink($kwDifficultyResultFile);
        }
        exit;
    }
}

//★★★
// キーワード難易度測定結果をxlsファイルに書き込み
$result = n_write_file($searchResultData, $kwDifficultyResultFile);
if($result == 1)
{
    //-- 実行ログ出力処理（画面出力のみ）
    $msg = "n_write_file error";
    n_output_msg("1", "", "n_main.php", "ERROR", "", $msg);
    
    //--  一時ファイル削除
    $result = n_return_exclusion($tmpKwDifficultyResultFile);
    if($result == 1)
    {
        //-- 実行ログ出力処理（画面出力のみ）
        $msg = "n_return_exclusion error(tmp)";
        n_output_msg("1", "", "n_main.php", "ERROR", "", $msg);
    }

    //--  キーワード難易度測定結果xlsファイル削除
    $result = n_return_exclusion($kwDifficultyResultFile);
    if($result == 1)
    {
        //-- 実行ログ出力処理（画面出力のみ）
        $msg = "n_return_exclusion error(xls)";
        n_output_msg("1", "", "n_main.php", "ERROR", "", $msg);
    }

    echo '<a href="index.php">←入力画面に戻る</a>'."\n";
    flush();
    
    echo '</body>'."\n";
    echo '</html>'."\n";
    exit;
}
//★★★

//-- 一時ファイル削除処理
$result = n_return_exclusion($tmpKwDifficultyResultFile);
if($result == 1)
{
    //-- 実行ログ出力処理（画面出力のみ）
    $msg = "n_return_exclusion error(fin tmp)";
    n_output_msg("1", "", "n_main.php", "ERROR", "", $msg);
	
    echo '<a href="index.php">←入力画面に戻る</a>'."\n";
    flush;
    
    echo '</body>'."\n";
    echo '</html>'."\n";
    exit;
}

//-- ブラウザとの接続切断を検知
if(connection_aborted() == 1)
{
    //-- キーワード難易度測定結果xlsファイル削除
    if(file_exists($kwDifficultyResultFile))
    {
        unlink($kwDifficultyResultFile);
    }
    exit;
}

//-- 実行ログ出力処理（画面出力のみ）
$msg = "return n_main";
n_output_msg("1", "", "n_main.php", "END", "", $msg);

$DLkwDifficultyResultFile = basename($kwDifficultyResultFile);

//-- ファイルダウンロード処理
echo "<BR>\n";
echo "●キーワード難易度測定結果xlsファイル名";
echo "<BR>\n";
echo $DLkwDifficultyResultFile;
echo "<BR>\n";
echo '<a href="output/' .$DLkwDifficultyResultFile .'">★キーワード難易度測定結果xlsファイルのダウンロード</a>' . "\n";
echo "<BR>\n";
echo "<BR>\n";
echo '<a href="./output/index.php" target="_blank">▼キーワード難易度測定結果xlsファイル一覧の表示(新しくページを開きます)</a>';
echo "<BR>\n";
echo "<BR>\n";
echo '<a href="index.php" target="_blank">←入力画面に戻る(新しくページを開きます)</a>' . "\n";
echo "<BR>\n";
echo "<BR>\n";
echo '</body>'."\n";
echo '</html>'."\n";
flush();

exit;

?>
