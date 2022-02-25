<?php
if(!defined('ROOT')) exit('No direct script access allowed');

$rootDir = CMS_APPROOT;
$rii = getAllFiles($rootDir);

$files = []; 
foreach ($rii as $file) {
    if(strpos($file, "reports/")) {
        $files[] = $file;
    }
}
$result = [];

//Report File Analysis
foreach($files as $file) {
    $fData = json_decode(file_get_contents($file), true);
    $stxt = json_encode($fData['source']['where']);
    if(!strpos($stxt, "#SESS_GUID#")) {
    	$err = str_replace($rootDir, "", $file);
      	$result[] = "<div class='row'><div class='col-md-12 text-left'>{$err}</div></div>";
    }
}

printResultBlock($result, "SESS_GUID Missing in Where", 6);
?>