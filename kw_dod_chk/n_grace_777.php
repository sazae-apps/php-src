<?php

//-- 環境設定ファイル読み込み
require('./conf/n.conf');

//-- ロックファイルの削除
$lockFlag = $lockFlagPath .$lockFlagName;
if(file_exists($lockFlag))
{
    $result = rmdir($lockFlag);
}
exit;

?>
