<?php
if(!defined('ROOT')) exit('No direct script access allowed');

$appPath = ROOT.APPS_FOLDER.CMS_SITENAME."/";
$debug = false;

$_ENV['BASEPATH'] = $appPath;

if(isset($_GET['debug']) && $_GET['debug']=="true") {
  $debug = true;
}

set_time_limit(150);

switch ($_REQUEST['action']) {
  case "runplugin":
      if(isset($_REQUEST['plugin']) && file_exists(__DIR__."/plugins/{$_REQUEST['plugin']}.php")) {
        include_once __DIR__."/plugins/{$_REQUEST['plugin']}.php";
      } else {
        echo "CodeQuality Plugin not installed on system";
      }
    break;
  case "reportinfo":
    $dated = date("Y-m-d H:i:s");
    $userid = $_SESSION['SESS_USER_NAME'];
    echo "<h3 style='margin: 0px;margin-bottom: 8px;'>LCAR Summary Report for :: <u>".CMS_SITENAME."</u></h3>";
    echo "<p>Logiks Code Analysis Report for <b>".CMS_SITENAME."</b> generated on {$dated} by {$userid}</p>";
    
    break;
  case "autopluginlist":
    printServiceMsg([]);
    break;
}

function printResultBlock($result, $title, $width=6) {
  if(!$result || $result==null) return;
  if($title && strlen($title)<=0) $title = "Error in analysis";
  
  echo "<div class='col-md-{$width}'>";
  if(is_array($result)) {
    if($title)
      echo "<div class='row alert alert-warning'><div class='col-md-9'>{$title}</div><div class='col-md-3 text-right'>[".count($result)."]</div></div>";
    echo implode("",$result);
  } else {
    if($title)
      echo "<div class='row alert alert-warning'><div class='col-md-9'>{$title}</div><div class='col-md-3 text-right'></div></div>";
    echo $result;
  }
  echo "</div>";
}

function getAllFiles($path, $filter = '', $exclude = '.git|tmp|.temp|logs') {
//     $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
  
    $files = array(); 
    foreach ($rii as $file) {
        if ($file->isFile()) { 
          $tempPath = $file->getPathname();
          
          if(!empty($exclude) && preg_match("/{$exclude}/", $tempPath)) {
            continue;
          }
          
          if(empty($filter) || preg_match("/{$filter}/", $tempPath)) {
            $files[] = $tempPath;
          }
        }
    }
  
//     $files = array_filter(iterator_to_array($rii), function($file) {
//         return $file->isFile();
//     });

    return $files;
}


?>