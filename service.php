<?php
if(!defined('ROOT')) exit('No direct script access allowed');

$appPath = ROOT.APPS_FOLDER.CMS_SITENAME."/";
$debug = false;

$_ENV['BASEPATH'] = $appPath;

if(isset($_REQUEST['debug']) && $_REQUEST['debug']=="true") {
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

function getAllFiles($path, $filter = '', $exclude = '') {
//     $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
  
    $files = array(); 
    foreach ($rii as $file) {
        if ($file->isFile()) { 
          $tempPath = $file->getPathname();
          
          if(!empty($exclude) && preg_match($exclude, $tempPath)) {
            continue;
          }
          
          if(empty($filter) || preg_match($filter, $tempPath)) {
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