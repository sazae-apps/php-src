<?php

//アクセス制限
if(preg_match("*kansan_input*", $_SERVER["PHP_SELF"]))
{
	header("Location: /");
	exit();
}

// 入力画面
print <<<_EOT_
<form action="/" method="get">
<p><label>換算する数値を入力：<input type="text" name="ks_value"></label></p>
<p>
<label for="before_conv_unit">換算前の単位を選択：</label>
<select id="bc_unit" name="bc_unit">
<option value="0" selected>Byte</option>
<option value="1">KB</option>
<option value="2">MB</option>
<option value="3">GB</option>
<option value="4">TB</option>
</select>
</p>
<p>
<label for="after_conv_unit">換算後の単位を選択：</label>
<select id="af_unit" name="af_unit">
<option value="0">Byte</option>
<option value="1" selected>KB</option>
<option value="2">MB</option>
<option value="3">GB</option>
<option value="4">TB</option>
</select>
</p>
<p><input type="submit" value="換算する"></p>
<input type="hidden" name="mode" value="kansan_exec">
</form>
_EOT_;

?>
