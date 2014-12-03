<?php

//-- 環境設定ファイル読み込み
require('./conf/n.conf');

$lockFlag = $lockFlagPath .$lockFlagName;
if(file_exists($lockFlag))
{
    $result = @mkdir($lockFlag, 0777);
}
exit;

?>
