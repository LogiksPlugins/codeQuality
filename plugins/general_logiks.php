<?php
if(!defined('ROOT')) exit('No direct script access allowed');

//echo $appPath;

//Pages with UI extension
$fs0 = getAllFiles($appPath."pages/defn/","(ui_)+");
if(count($fs0)>0) {
  echo "<div class='col-md-6'>";
  echo "<div class='row alert alert-warning'><div class='col-md-8'>Unused UI pages</div><div class='col-md-4 text-right'>".count($fs0)."</div></div>";
  foreach($fs0 as $f) {
    $f = str_replace($appPath,"",$f);
    echo "<div class='row'><div class='col-md-2'></div><div class='col-md-10 text-left'>{$f}</div></div>";
  }
  echo "</div>";
}

//Bad module folders
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($appPath."plugins/modules/"), RecursiveIteratorIterator::SELF_FIRST);
$files = array(); 
echo "<div class='col-md-6'>";
echo "<div class='row alert alert-warning'><div class='col-md-8'>Bad Module Folders</div><div class='col-md-4 text-right'></div></div>";
foreach ($rii as $file) {
  if($file->isDir()) {
    
    $tempPath = $file->getPathname();
    
    if(preg_match("/(.git|tmp|.temp|logs)/", $tempPath)) continue;
    elseif(substr($tempPath, strlen($tempPath)-2)=="." || substr($tempPath, strlen($tempPath)-1)==".") continue;
      
    if(!file_exists("{$tempPath}/index.php")) {
      $f = str_replace($appPath,"",$tempPath);
      echo "<div class='row'><div class='col-md-2'></div><div class='col-md-10 text-left'>{$f}</div></div>";
    }
  }
}
echo "</div>";

//
?>