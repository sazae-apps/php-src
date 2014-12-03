<?php

function n_return_exclusion($filename)
{
    //-- 一時ファイル削除
    if(file_exists($filename))
    {
        $result = unlink($filename);
        if($result == FALSE)
        {
            return 1;
        }
    }
    
    return 0;
}

?>
