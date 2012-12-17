#!/usr/bin/php
<?php
#Twitter status update bot by http://360percents.com
#Author: Luka Pusic <pusic93@gmail.com>
#Translated to PHP by Dawt Maytrikx

#REQUIRED PARAMS
$username = urlencode("username");
$password = urlencode("password");

#EXTRA OPTIONS
$uagent = "Mozilla/5.0"; #user agent (fake a browser)
$sleeptime = 0; #add pause between requests
$file = $_SERVER['DOCUMENT_ROOT']."/cookie.txt"; #needs to be writable

if (isset($argv[1])) {
    $tweet = urlencode($argv[1]); #must be less than 140 chars
    if (strlen($tweet) > 140) {
        echo "[FAIL] Tweet must not be longer than 140 chars!\r\n";
        exit(1);
    }
} else {
    echo "[FAIL] Nothing to tweet. Enter your text as argument.\r\n";
    exit(1);
}

$host = fopen($file, "w"); #create a temp. cookie file
$ch = curl_init();
curl_setopt_array($ch, array(//CURLOPT_MUTE => TRUE, //-s
                             CURLOPT_COOKIE => $file, //-b
                             CURLOPT_COOKIEJAR => $file, //-c
                             CURLOPT_FOLLOWLOCATION => TRUE, //-L
                             CURLOPT_SSLVERSION => 3, //--sslv3
                             CURLOPT_USERAGENT => $uagent, //-A
                             CURLOPT_RETURNTRANSFER => TRUE
                             ));

#GRAB LOGIN TOKENS
echo "[+] Fetching twitter.com...\r\n";
sleep($sleeptime);

curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/session/new"
                             ));
$initpage = curl_exec($ch);

preg_match("/<input.*authenticity_token.*value=\"(.*?)\".*\/>/i", $initpage, $matches);
$token = $matches[1];


#LOGIN
echo "[+] Submitting the login form...\r\n";
sleep($sleeptime);

curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/session",
                             CURLOPT_POSTFIELDS => "authenticity_token=$token&username=$username&password=$password",
                             CURLOPT_POST => TRUE
                             ));
$loginpage = curl_exec($ch);


#GRAB COMPOSE TWEET TOKENS
echo "[+] Getting compose tweet page...\r\n";
sleep($sleeptime);

curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/compose/tweet",
                             CURLOPT_POSTFIELDS => "",
                             CURLOPT_POST => FALSE
                             ));
$composepage = curl_exec($ch);

preg_match("/<input.*authenticity_token.*value=\"(.*?)\".*\/>/i", $composepage, $matches);
$tweettoken = $matches[1];

#TWEET
echo "[+] Posting a new tweet: $tweet...\r\n";
sleep($sleeptime);

curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/",
                             CURLOPT_POSTFIELDS => "authenticity_token=$tweettoken&tweet[text]=$tweet&tweet[display_coordinates]=false",
                             CURLOPT_POST => TRUE
                             ));
$update = curl_exec($ch);


#GRAB LOGOUT TOKENS
curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/account",
                             CURLOPT_POSTFIELDS => "",
                             CURLOPT_POST => FALSE
                             ));
$logoutpage = curl_exec($ch);

preg_match("/<input.*authenticity_token.*value=\"(.*?)\".*\/>/i", $logoutpage, $matches);
$logouttoken = $matches[1];

#LOGOUT
echo "[+] Logging out...\r\n";
sleep($sleeptime);

curl_setopt_array($ch, array(CURLOPT_URL => "https://mobile.twitter.com/session/destroy",
                             CURLOPT_POSTFIELDS => "authenticity_token=$logouttoken",
                             CURLOPT_POST => TRUE
                             ));
$logout = curl_exec($ch);


fclose($host);
unlink($file);
curl_close($ch);
exit(0);
?>
