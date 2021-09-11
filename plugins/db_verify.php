<?php
if(!defined('ROOT')) exit('No direct script access allowed');

if(_db()) {
  $dbInfo = _db()->get_dbObjects();
  $dbTables = _db()->get_tableList();
  
  $out1 = [];$out2 = [];$out3 = [];$out4 = [];$out5 = [];
  foreach($dbTables as $tbl) {
    $cols = _db()->get_columnList($tbl,false);
    $keys = _db()->get_allkeys($tbl);
    $keyP = _db()->get_primaryKey($tbl);
    
    
    if(count($keyP)<=0) {
      $out1[] = "<div class='row'><div class='col-md-8 text-left'>{$tbl}</div><div class='col-md-4 hinttext'>primary key missing</div></div>";
    }
    
    $colMissing = array_diff([
                "id","guid",
                "groupuid",//"privilegeid",
                "blocked",
                "created_by","created_on","edited_by","edited_on",
              ],array_keys($cols));
    if(count($colMissing)>0) {
      $out2[] = "<div class='row'><div class='col-md-8 text-left'>{$tbl}</div><div class='col-md-4 hinttext'>".implode(", ",$colMissing)."</div></div>";
    }
    
    if(!isset($cols['id'])) {
        $out2[] = "<div class='row'><div class='col-md-8 text-left'>{$tbl}</div><div class='col-md-4 hinttext'>ID missing</div></div>";
    }
    if(isset($cols['id'])) {
        if($cols['id'][3]!="PRI") {
            $out1[] = "<div class='row'><div class='col-md-8 text-left'>{$tbl}</div><div class='col-md-4 hinttext'>ID Is not Primary Column</div></div>";
        }
        if($cols['id'][5]!="auto_increment") {
            $out1[] = "<div class='row'><div class='col-md-8 text-left'>{$tbl}</div><div class='col-md-4 hinttext'>ID Is not Auto Increament</div></div>";
        }
    }
//     printArray([$cols,$keys,$keyP]);
  }
  
  if((count($out1)+count($out2))<=0) {
        echo "No issues found in the configured database";
  } else {
        printResultBlock($out1,"Keys Verification",6);
        printResultBlock($out2,"Missing Columns",6);
        // printResultBlock($out3,"",6);
        // printResultBlock($out4,"",6);
        // printResultBlock($out5,"",6);
  }
} else {
  echo "No database configured for the app";
}
?>
