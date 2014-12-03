<?php

if(mb_ereg("CONSUMER.php", $_SERVER["PHP_SELF"]))
{
    header("Location: index.php");
    exit;
}

class CONSUMER
{
    const KEY = 'xxxxx';
    const SECRET = 'zzzzzz';

    Public function getKEY()
    {
        return self::KEY;
    }
    
    Public function getSECRE5T()
    {
        return self::SECRET;
    }
}

?>
