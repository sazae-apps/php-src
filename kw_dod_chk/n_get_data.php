<?php

//--デフォルトタイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');
//echo date_default_timezone_get();

function n_get_data($searchWord)
{
    try
    {
        //-- 日本語エンコード自動判別設定
        mb_language('Japanese');

        //-- 環境設定ファイル読み込み
        require('./conf/n.conf');

        //-- ファイル出力処理関数読込
        require_once('n_write_file.php');

        //-- 実行ログ出力関数読込
        require_once('n_output_msg.php');

        //-- 全角スペース除去
        $wkSearchWord = mbereg_replace("　", "", mb_convert_encoding($searchWord, "SJIS", "AUTO"));

        //-- 半角スペース除去
        $wkSearchWord = trim(mbereg_replace(" ", "", $wkSearchWord));

        //-- 入力検索キーワードをUTF-8にエンコード
        $wkCnvSearchWord = mb_convert_encoding($wkSearchWord, "UTF-8", "SJIS");

        //-- 変数初期化
        $searchResultData = "";

        $cnvSearchWord = $wkCnvSearchWord;

        //google AJAX Serch API
        //http://code.google.com/intl/ja/apis/ajaxsearch/documentation/reference.html#_intro_fonje
        $url_string = urlencode($cnvSearchWord);
        $url = "http://ajax.googleapis.com/ajax/services/search/web";
        $url = $url."?q="."$url_string";
        $url = $url."&v=1.0";
        $url = $url."&hl=ja";
        //$url = $url."&rsz=small"; //small large
        //$url = $url."&lr=lang_ja";
        //$url = $url."&start=1";
        //$url = $url."&safe=off";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, "http://localhost/n/index.php");
        $body = curl_exec($ch);
        curl_close($ch);

        //-- json_decodeで変換
        $tmp = json_decode($body, true);

        //-- 検索キーワード取得
        $searchQuery1 = $tmp["responseData"]["cursor"]["moreResultsUrl"];

        //-- 通常検索結果数取得
        $estimatedTotalResultsCount1 = $tmp["responseData"]["cursor"]["estimatedResultCount"];

        //-- 実行ログ出力処理（画面出力のみ）
        $msg = "exec n_get_data ( Result Search Count(all) : " .$estimatedTotalResultsCount1 ." )";
        n_output_msg("1", "", "n_get_data.php", "", $searchQuery1, $msg);

        //-- 入力検索キーワードにallintitleを付加
        $cnvSearchWord = "allintitle:" .$wkCnvSearchWord;

        //google
        //http://code.google.com/intl/ja/apis/ajaxsearch/documentation/reference.html#_intro_fonje
        $url_string = urlencode($cnvSearchWord);
        $url = "http://ajax.googleapis.com/ajax/services/search/web";
        $url = $url."?q="."$url_string";
        $url = $url."&v=1.0";
        $url = $url."&hl=ja";
        //$url = $url."&rsz=small"; //small large
        //$url = $url."&lr=lang_ja";
        //$url = $url."&start=1";
        //$url = $url."&safe=off";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, "http://localhost/n/index.php");
        $body = curl_exec($ch);
        curl_close($ch);

        //-- json_decodeで変換
        $tmp = json_decode($body, true);

        //-- 検索キーワード取得
        $searchQuery2 = $tmp["responseData"]["cursor"]["moreResultsUrl"];

        //-- allintitle検索結果数取得
        $estimatedTotalResultsCount2 = $tmp["responseData"]["cursor"]["estimatedResultCount"];

        //-- 実行ログ出力処理（画面出力のみ）
        $msg = "exec n_get_data ( Result Search Count(allintitle) : " .$estimatedTotalResultsCount2 ." )";
        n_output_msg("1", "", "n_get_data.php", "", $searchQuery2, $msg);

        //-- 入力検索キーワードにallinanchorを付加
        $cnvSearchWord = "allinanchor:" .$wkCnvSearchWord;

        $url_string = urlencode($cnvSearchWord);
        $url = "http://ajax.googleapis.com/ajax/services/search/web";
        $url = $url."?q="."$url_string";
        $url = $url."&v=1.0";
        $url = $url."&hl=ja";
        //$url = $url."&rsz=small"; //small large
        //$url = $url."&lr=lang_ja";
        //$url = $url."&start=1";
        //$url = $url."&safe=off";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, "http://localhost/n/index.php");
        $body = curl_exec($ch);
        curl_close($ch);

        //-- json_decodeで変換
        $tmp = json_decode($body, true);
        
        //-- 検索キーワード取得
        $searchQuery3 = $tmp["responseData"]["cursor"]["moreResultsUrl"];

        //-- allinanchor検索結果数取得
        $estimatedTotalResultsCount3 = $tmp["responseData"]["cursor"]["estimatedResultCount"];

        //-- 実行ログ出力処理（画面出力のみ）
        $msg = "exec n_get_data ( Result Search Count(allinanchor) : " .$estimatedTotalResultsCount3 ." )";
        n_output_msg("1", "", "n_get_data.php", "", $searchQuery3, $msg);

        //-- キーワード難易度計算
        if($estimatedTotalResultsCount1 == 0)
        {
            $kwDifficulty = 0;
        }
        else
        {
            $kwDifficulty = ($estimatedTotalResultsCount3 * $estimatedTotalResultsCount2) / $estimatedTotalResultsCount1;
        }
        
        //-- 日付取得
        $today = date("Y/m/d H:i:s");

        //-- 空白削除
        $searchWord = trim($searchWord);

        //-- 連想配列作成
        $searchResultData = compact("today","searchWord","kwDifficulty","estimatedTotalResultsCount1","estimatedTotalResultsCount2","estimatedTotalResultsCount3");

        return $searchResultData;
    }
    catch (Exception $e)
    {
       return "";
    }
}

?>
