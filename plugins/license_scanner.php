<?php
if(!defined('ROOT')) exit('No direct script access allowed');

// echo $appPath;

$licenseFiles = getAllFiles($appPath,"/LICENSE/");

echo "<p>Found ".count($licenseFiles)." License Files</p>";
$count = 0;
foreach($licenseFiles as $lic) {
  $line = trim(fgets(fopen($lic, 'r')));
  if(strpos($line, "ISC ")!==false && strpos($line, " ISC")!==false) {
    $count++;
    echo "<div class='row alert-danger'><div class='col-md-8'>{$lic}</div><div class='col-md-4 text-right'>{$line}</div></div>";
  } else {
    if($debug) {
      echo "<div class='row'><div class='col-md-8'>{$lic}</div><div class='col-md-4 text-right'>{$line}</div></div>";
    } else {
      echo "<div class='row hidden'><div class='col-md-8'>{$lic}</div><div class='col-md-4 text-right'>{$line}</div></div>";
    }
  }
}
if($count>0) {
  echo "<div class='row alert-danger'><div class='col-md-12'>{$count} Licenses need your attention</div></div>";
}
?>