<?php

if(mb_ereg("CallGoogleTranslateApi.php", $_SERVER["PHP_SELF"]))
{
    header("Location: index.php");
    exit;
}

class CallGoogleTranslateApi
{
    const GOOGLE_API_URL = "http://ajax.googleapis.com/ajax/services/language/translate";

    private $urlString;
    private $translateLangString;
    private $url;
    private $ch;
    private $body;
    private $tmp;

    function  __construct()
    {
    }

    public function setTranslateString($statusText)
    {
        $this->urlString = $statusText;
    }

    public function setTranslateLang($translateLang)
    {
        $this->translateLangString = $translateLang;
    }

    public function getTranslatedString()
    {
        try
        {
            $this->urlString = urlencode($this->urlString);
            $this->url = self::GOOGLE_API_URL;
            $this->url = $this->url."?v=1.0";
            $this->url = $this->url."&hl=ja";
        
            switch($this->translateLangString)
            {
                case "ja_to_en":
                    $this->url = $this->url."&langpair=ja%7Cen";
                    break;

                case "en_to_ja":
                    $this->url = $this->url."&langpair=en%7Cja";
                    break;

                default:
                    $this->url = $this->url."&langpair=ja%7Cen";
           }
        
           $this->url = $this->url."&q="."$this->urlString";
           $this->ch = curl_init();
           curl_setopt($this->ch, CURLOPT_URL, $this->url);
           curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
           curl_setopt($this->ch, CURLOPT_REFERER, "http://localhost/test1.php");
           $this->body = curl_exec($this->ch);
           curl_close($this->ch);

           $this->tmp = json_decode($this->body, true);
           //var_dump(json_decode($this->body, true));

           return $this->tmp["responseData"]["translatedText"];
        }
        catch (Exception $e)
        {
           return "";
        }
    }
}

?>
