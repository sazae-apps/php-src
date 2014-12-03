<?php

//アクセス制限
if(preg_match("*kansan_exec*", $_SERVER["PHP_SELF"]))
{
	header("Location: /");
	exit();
}

try
{
	//リクエストメソッドチェック
	if(strtoupper($_SERVER["REQUEST_METHOD"]) != "GET")
	{
		throw(new Exception("Bad Access 01"));
	}

	//リクエストデータ取得
	$ks_value = $_GET['ks_value'];
	$bc_unit = $_GET['bc_unit'];
	$af_unit = $_GET['af_unit'];

	//換算用変数初期化
	$bc_value = 1;
	$af_value = 1;
	$kansan_value = 0;
	$tmp_value = 0;
	$tmp_bc_unit = "Byte";
	$tmp_af_unit = "Byte";

	//バリデーション
	//--換算する数値
	if(!isset($ks_value))
	{
		throw(new Exception("換算する数値が入力されていません。"));
	} 
	if(!is_numeric($ks_value))
	{
		throw(new Exception("入力されている値が数値ではありません。"));
	}
	if($ks_value < 1)
	{
		throw(new Exception("1以上の数値を入力してください。"));
	}

	//--換算前の単位
	if(!isset($bc_unit))
	{
		throw(new Exception("無効な単位選択(換算前)がされています。01"));
	} 
	if(!is_numeric($bc_unit))
	{
		throw(new Exception("無効な単位選択(換算前)がされています。02"));
	} 
	elseif($bc_unit < 0 || $bc_unit > 4)
	{
		throw(new Exception("無効な単位選択(換算前)がされています。03"));
	}
	switch ($bc_unit) {
		case 1:
			# KB
			$tmp_bc_unit = "KB";
			$bc_value = 1024;
			break;
		case 2:
			# MB
			$tmp_bc_unit = "MB";
			$bc_value = 1024 * 1024;
			break;
		case 3:
			# GB
			$tmp_bc_unit = "GB";
			$bc_value = 1024 * 1024 * 1024; 
			break;
		case 4:
			# TB
			$tmp_bc_unit = "TB";
			$bc_value = 1024 * 1024 * 1024 * 1024;
			break;
		default:
			# Byte
			$bc_value = 1;
			break;
	}

	//--換算後の単位
	if(!isset($af_unit))
	{
		throw(new Exception("無効な単位選択(換算後)がされています。01"));
	} 
	if(!is_numeric($af_unit))
	{
		throw(new Exception("無効な単位選択(換算後)がされています。02"));
	}
	elseif($af_unit < 0 || $af_unit > 4)
	{
		throw(new Exception("無効な単位選択(換算後)がされています。03"));
	}
	switch ($af_unit) {
		case 1:
			# KB
			$tmp_af_unit = "KB";
			$af_value = 1024;
			break;
		case 2:
			# MB
			$tmp_af_unit = "MB";
			$af_value = 1024 * 1024;
			break;
		case 3:
			# GB
			$tmp_af_unit = "GB";
			$af_value = 1024 * 1024 * 1024; 
			break;
		case 4:
			# TB
			$tmp_af_unit = "TB";
			$af_value = 1024 * 1024 * 1024 * 1024;
			break;
		default:
			# Byte
			$af_value = 1;
			break;
	}

	//換算処理
	$tmp_value = $ks_value * $bc_value;
	$kansan_value = round(($tmp_value / $af_value), 10, PHP_ROUND_HALF_UP);

	//換算後の数値出力
print <<<_EOT_
■換算結果<br>
_EOT_;
	echo "換算前：" . $ks_value . $tmp_bc_unit;
	echo "<br>";
	echo "換算後：" . $kansan_value . $tmp_af_unit;
	echo "<br>";
	echo '<a href="/">入力画面に戻る</a><br>';
}
catch(Exception $e)
{
	$msg = $e->getMessage();
	echo $msg; 
	echo "<br>";
	echo '<a href="/">入力画面に戻る</a><br>';
}

?>
