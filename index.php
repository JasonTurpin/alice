<?php
// Include file
include_once('Alice.php');

// Fetch string
$ch = curl_init();
curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, 'http://www.textfiles.com/etext/FICTION/alice30.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
$str = curl_exec($ch);
curl_close($ch);

// Run logic
new Alice($str);
