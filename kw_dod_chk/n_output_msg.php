<?php

//--デフォルトタイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');
//echo date_default_timezone_get();

function n_output_msg($OutMode, $logOutPath, $phpFileName, $status, $webPageURL, $msg)
{
	//-- ログの内容を格納
	$execDate = date("D d M Y H:i:s O");
	$outputLogData = "[" .$execDate ."]	[" .$phpFileName ."]	[" .$status ."]	[" .$webPageURL ."]	" .$msg ."\n";

	if($OutMode == 0 || $OutMode == 1)
	{
		//-- ログ画面出力処理
		echo $outputLogData;
		flush();
		echo "<BR>\n";
		flush();
	}

	if($OutMode == 0 || $OutMode == 2)
	{
		//-- ログファイルファイル出力処理
		$handle = fopen($logOutPath, 'a');
		if(FALSE == ($write = fwrite($handle, $outputLogData)))
		{
			fclose($handle);
		}
		fclose($handle);
	}
}

?>
