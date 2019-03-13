<?php
if(!defined('ROOT')) exit('No direct script access allowed');

//echo $appPath;

//Pages with UI extension
$fs0 = getAllFiles($appPath."pages/defn/","(ui_)+");
$out = [];
if(count($fs0)>0) {
  foreach($fs0 as $f) {
    $f = str_replace($appPath,"",$f);
    $out[] = "<div class='row'><div class='col-md-10 text-left'>{$f}</div><div class='col-md-2'></div></div>";
  }
}
printResultBlock($out,"Page Verification");

//Bad module folders
if(file_exists($appPath."plugins/modules/") && is_dir($appPath."plugins/modules/")) {
  $fs0 = scandir($appPath."plugins/modules/");
  array_shift($fs0);array_shift($fs0);
  foreach($fs0 as $a=>$b) {
    $fs0[$a] = $appPath."plugins/modules/{$b}/";
  }
} else $fs0 = [];
if(file_exists($appPath."pluginsDev/modules/") && is_dir($appPath."pluginsDev/modules/")) {
  $fs1 = scandir($appPath."pluginsDev/modules/");
  array_shift($fs1);array_shift($fs1);
  foreach($fs1 as $a=>$b) {
    $fs1[$a] = $appPath."pluginsDev/modules/{$b}/";
  }
} else $fs1 = [];
$fs = array_merge($fs0,$fs1);
$out = [];
foreach($fs as $f) {
  if(!file_exists("{$f}/index.php")) {
    $out[] = "<div class='row'><div class='col-md-8 text-left'>".str_replace($appPath,"",$f)."</div><div class='col-md-4 hinttext'>index.php missing</div></div>";
  } elseif(!file_exists("{$f}/logiks.json") && basename(dirname(dirname($f)))!="plugins") {
    $out[] = "<div class='row'><div class='col-md-8 text-left'>".str_replace($appPath,"",$f)."</div><div class='col-md-4 hinttext'>logiks.json missing</div></div>";
  }
}
printResultBlock($out, "Module Analysis (Bad Modules)");




//Bad module folders
// $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($appPath."plugins/modules/"), RecursiveIteratorIterator::SELF_FIRST);
// $files = array(); 
// echo "<div class='col-md-6'>";
// echo "<div class='row alert alert-warning'><div class='col-md-8'>Bad Module Folders</div><div class='col-md-4 text-right'></div></div>";
// foreach ($rii as $file) {
//   if($file->isDir()) {
//     $tempPath = $file->getPathname();
    
//     if(preg_match("/(.git|tmp|.temp|logs)/", $tempPath)) continue;
//     elseif(substr($tempPath, strlen($tempPath)-2)=="." || substr($tempPath, strlen($tempPath)-1)==".") continue;
      
//     if(!file_exists("{$tempPath}/index.php")) {
//       $f = str_replace($appPath,"",$tempPath);
//       echo "<div class='row'><div class='col-md-2'></div><div class='col-md-10 text-left'>{$f}</div></div>";
//     }
//   }
// }
// echo "</div>";

?>