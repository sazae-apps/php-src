<?php

if(mb_ereg("CallTwitterApi.php", $_SERVER["PHP_SELF"]))
{
    header("Location: index.php");
    exit;
}

include_once 'CONSUMER.php';
include_once 'ACCESS.php';

require_once("twitteroauth.php");

class CallTwitterApi
{    
    //const TWITTER_URL = "http://twitter.com/statuses/replies.xml";    //返信一覧
    //const TWITTER_URL = "http://twitter.com/statuses/friends_timeline.xml";   //フレンドの発言一覧
    //const TWITTER_URL = "http://api.twitter.com/1/statuses/friends_timeline.xml";
    //const TWITTER_PUBLIC_TIMELINE_URL = "http://twitter.com/statuses/public_timeline.xml";      //公開つぶやき一覧
    //const TWITTER_USERS_SHOW_URL = 'users/show';

    const TWITTER_USERS_SHOW_URL = "http://api.twitter.com/1/users/show.xml";
    const TWITTER_USER_TIMELINE_URL = "http://api.twitter.com/1/statuses/user_timeline.xml";      //ユーザーの発言一覧
    
    private $twitterUrl;
    private $result;
    private $userName;
    private $XML;

    private $consumer_key;
    private $consumer_secret;
    private $access_token;
    private $access_token_secret;
    
    private $twitterOAuth;

    function  __construct()
    {
    }

    Public function setTwitterUserName($userName)
    {
        $this->userName = $userName;
    }
    
    Public function getTwitterData()
    {
        try
        {
            $obj1 = new CONSUMER();
            $obj2 = new ACCESS();

            $this->consumer_key = $obj1->getKEY();
            $this->consumer_secret = $obj1->getSECRET();
            $this->access_token = $obj2->getTOKEN();
            $this->access_token_secret = $obj2->getTOKENSECRET();

            $this->twitterOAuth = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

            if($this->userName =="")
            {
                return "";
            }

            $this->twitterUrl = self::TWITTER_USERS_SHOW_URL;

            $this->result = $this->twitterOAuth->get($this->twitterUrl, array('screen_name' => "$this->userName"));

            $this->XML = simplexml_load_string($this->result);

            //var_dump($this->XML);

            if($this->XML->error == "Not found")
            {
                return "";
            }

            $this->twitterUrl = self::TWITTER_USER_TIMELINE_URL;

            $this->twitterOAuth = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
            
            $this->result = $this->twitterOAuth->get($this->twitterUrl, array('screen_name' => $this->userName));

            //$this->XML = simplexml_load_string($this->result);
            //var_dump($this->XML);

            return simplexml_load_string($this->result);
        }
        catch (Exception $e)
        {
            return "";
        }
    }
}

?>
