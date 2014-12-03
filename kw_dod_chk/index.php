<?php

//-- 環境設定ファイル読み込み
require('./conf/n.conf');

//-- 緊急システム停止処理
$lockFlag = $lockFlagPath .$lockFlagName;
if(file_exists($lockFlag))
{
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' ."\n";
    echo '<html>'."\n";
    echo '<head>'."\n";
    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'."\n";
    echo '<title>キーワード難易度測定ツール</title>'."\n";
    echo '</head>'."\n";
    echo '<body>'."\n";

    echo "※現在「キーワード難易度測定ツール」は利用できません。\n";
    flush();
    echo "<BR>\n";
    flush();

    echo '</body>'."\n";
    echo '</html>'."\n";

    exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>キーワード難易度測定ツール</title>
<!-- <link rel="shortcut icon" href="img/favicon.ico"> -->
</head>
<body>
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="705" height="24"><table width="705" height="24" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td width="10" height="24"></td>
    <td width="680" height="24" class="text01"></td>
    <td width="15" height="24"></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td width="705"><table width="705" height="480" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="700" height="60" ></td>
        <td width="5" height="60" ></td>
      </tr>
	  <tr>
        <td width="700" height="300" align="center" valign="middle" bgcolor="#FFFFFF"><br />
	<form action="n_main.php" method="post">
	<textarea name="searchWords" cols="60" rows="20"></textarea>
	<br/>
	<input type="submit" value="測定開始" />
	</form>	</td>
        <td width="5" height="300" >
        </td>
        </tr>
	  <tr>
        <td height="120" align="center" valign="" bgcolor="#FFFFFF">
        <a href="./output/index.php">▼キーワード難易度測定結果xlsファイル一覧の表示</a>
        </td>
        <td height="120">
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="705" height="25"><table width="705" height="25" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="10" height="25"></td>
        <td width="680" height="25" align="right" class="text01"></td>
        <td width="15" height="25"></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
