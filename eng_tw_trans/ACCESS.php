<?php

if(mb_ereg("ACCESS.php", $_SERVER["PHP_SELF"]))
{
    header("Location: index.php");
    exit;
}

class ACCESS
{
    const TOKEN = 'xxxxxxxx';
    const TOKENSECRET = 'zzzzzzzzzzz';

    Public function getTOKEN()
    {
        return self::TOKEN;
    }

    Public function getTOKENSECRET()
    {
        return self::TOKENSECRET;
    }
}

?>
