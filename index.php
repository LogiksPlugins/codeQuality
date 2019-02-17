<?php
if(!defined('ROOT')) exit('No direct script access allowed');
loadModule("pages");

function pageContentArea() {
    return "<div><div id='contentArea' class='container'><h3 align=center>Run Code Analysis</h3></div></div>";
}
function pageSidebar() {
    $fs = scandir(__DIR__."/plugins/");
    $fs = array_splice($fs,2);
    $html = [];
    foreach($fs as $f) {
      $ext = explode(".",$f);
      $ext = end($ext);
      if($ext != "php") continue;
      
      $v = (str_replace(".php","",$f));
      $t = toTitle(str_replace(".php","",$f));
      $html[] = "<li class='list-group-item'><input type='checkbox' name='plugins' class='pull-left' value='{$v}' /> &nbsp;&nbsp;{$t}</li>";
    }
    $html = implode("",$html);
    return "<div id='sidebarArea'><ul class='list-group'>{$html}</ul></div>";
}

_css(["codeQuality"]);

printPageComponent(false,[
		"toolbar"=>[
 			"reloadPluginList"=>["icon"=>"<i class='fa fa-refresh'></i>"],
			"runQA"=>["title"=>"Run","icon"=>"<i class='fa fa-play'></i>","align"=>"left"],
// 		["title"=>"Search Roles","type"=>"search","align"=>"right"],
			
			//['type'=>"bar"],
			//"rename"=>["icon"=>"<i class='fa fa-terminal'></i>","class"=>"onsidebarSelect onOnlyOneSelect","tips"=>"Rename Content"],
// 		"deleteTemplate"=>["icon"=>"<i class='fa fa-trash'></i>","class"=>"onsidebarSelect"],
		],
		"sidebar"=>"pageSidebar",
		"contentArea"=>"pageContentArea"
	]);

_js(["codeQuality"]);
?>
<style>
  * {
    border-radius: 0px;
  }
</style>
<script>
var loadedPlugins = [];
$(function() {
    
});
function reloadPluginList() {
  $("#contentArea").html("<h3 align=center>Run Code Analysis</h3></div>");
}
function runQA() {
  loadedPlugins = [];
  $("#contentArea").html("");
  $("input[name='plugins']:checked","#sidebarArea").each(function() {
    loadedPlugins.push($(this).val());
    
    $("#contentArea").append("<div id='PLUGIN_"+$(this).val().replace(/ /g,'_')+"' class='panel'><div align=center><br><br><i class='fa fa-spinner fa-spin fa-2x'></i></div></div>");
  });
  if(loadedPlugins.length<=0) {
    $("#contentArea").html("<h3 align=center>No plugins selected</h3>");
    return;
  }
}
</script>