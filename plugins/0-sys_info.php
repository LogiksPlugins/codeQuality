<?php
if(!defined('ROOT')) exit('No direct script access allowed');

//echo $appPath;

$out1 = [];$out2 = [];

//PHP
$phpVersion = phpversion();
$phpBuild = PHP_VERSION_ID;
$out1[] = "<div class='row'><div class='col-md-4 text-left bold'>PHP Build</div><div class='col-md-8'>{$phpBuild}</div></div>";
$out1[] = "<div class='row'><div class='col-md-4 text-left bold'>PHP Version</div><div class='col-md-8'>{$phpVersion}</div></div>";

//Database
if(_db()) {
  $out1[] = "<div class='row'><div class='col-md-4 text-left bold'>DB Driver</div><div class='col-md-8'>"._db()->dbParams("driver")."</div></div>";
  
  $sqlVersion = _db()->_RAW("select @@version as vers")->_GET();
  if(isset($sqlVersion[0]) && isset($sqlVersion[0]['vers'])) {
    $out1[] = "<div class='row'><div class='col-md-4 text-left bold'>MySQL Version</div><div class='col-md-8'>{$sqlVersion[0]['vers']}</div></div>";
  }
}

//_cache

//Logiks Version
$out2[] = "<div class='row'><div class='col-md-4 text-left bold'>Framework Name</div><div class='col-md-8'>Logiks</div></div>";
if(defined("Framework_Version"))
  $out2[] = "<div class='row'><div class='col-md-4 text-left bold'>Framework Version</div><div class='col-md-8'>".Framework_Version."</div></div>";
else
  $out2[] = "<div class='row'><div class='col-md-4 text-left bold'>Framework Version</div><div class='col-md-8'>--</div></div>";
$out2[] = "<div class='row'><div class='col-md-4 text-left bold'>CMS Version</div><div class='col-md-8'>".APPS_VERS."</div></div>";

//$out2[] = "<div class='row'><div class='col-md-4 text-left bold'>App Status</div><div class='col-md-8'>".APPS_STATUS."</div></div>";


printResultBlock($out1,false);
printResultBlock($out2,false);

function phpinfo2array() {
    $entitiesToUtf8 = function($input) {
        // http://php.net/manual/en/function.html-entity-decode.php#104617
        return preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $input);
    };
    $plainText = function($input) use ($entitiesToUtf8) {
        return trim(html_entity_decode($entitiesToUtf8(strip_tags($input))));
    };
    $titlePlainText = function($input) use ($plainText) {
        return '# '.$plainText($input);
    };
    
    ob_start();
    phpinfo(-1);
    
    $phpinfo = array('phpinfo' => array());

    // Strip everything after the <h1>Configuration</h1> tag (other h1's)
    if (!preg_match('#(.*<h1[^>]*>\s*Configuration.*)<h1#s', ob_get_clean(), $matches)) {
        return array();
    }
    
    $input = $matches[1];
    $matches = array();

    if(preg_match_all(
        '#(?:<h2.*?>(?:<a.*?>)?(.*?)(?:<\/a>)?<\/h2>)|'.
        '(?:<tr.*?><t[hd].*?>(.*?)\s*</t[hd]>(?:<t[hd].*?>(.*?)\s*</t[hd]>(?:<t[hd].*?>(.*?)\s*</t[hd]>)?)?</tr>)#s',
        $input, 
        $matches, 
        PREG_SET_ORDER
    )) {
        foreach ($matches as $match) {
            $fn = strpos($match[0], '<th') === false ? $plainText : $titlePlainText;
            if (strlen($match[1])) {
                $phpinfo[$match[1]] = array();
            } elseif (isset($match[3])) {
                $keys1 = array_keys($phpinfo);
                $phpinfo[end($keys1)][$fn($match[2])] = isset($match[4]) ? array($fn($match[3]), $fn($match[4])) : $fn($match[3]);
            } else {
                $keys1 = array_keys($phpinfo);
                $phpinfo[end($keys1)][] = $fn($match[2]);
            }

        }
    }
    
    return $phpinfo;
}
?>