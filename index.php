<?php
if(!defined('ROOT')) exit('No direct script access allowed');
loadModule("pages");

function pageContentArea() {
    return "<div style='padding: 20px;'><div id='contentArea' class='container-fluid'><h3 align=center>Run Code Analysis</h3></div></div>";
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
      $html[] = "<li class='list-group-item'><label><input type='checkbox' name='plugins' class='pull-left' value='{$v}' /> &nbsp;&nbsp;{$t}</label></li>";
    }
    $html = implode("",$html);
    return "<div id='sidebarArea'><ul class='list-group'>{$html}</ul></div>";
}

echo _css(["codeQuality"]);

printPageComponent(false,[
		"toolbar"=>[
 			"reloadPluginList"=>["icon"=>"<i class='fa fa-refresh'></i>"],
			"runQA"=>["title"=>"Run","icon"=>"<i class='fa fa-play'></i>","align"=>"left"],
// 		["title"=>"Search Roles","type"=>"search","align"=>"right"],
			
			//['type'=>"bar"],
			//"rename"=>["icon"=>"<i class='fa fa-terminal'></i>","class"=>"onsidebarSelect onOnlyOneSelect","tips"=>"Rename Content"],
		  "printReport"=>["icon"=>"<i class='fa fa-print'></i>","align"=>"right"],
		],
		"sidebar"=>"pageSidebar",
		"contentArea"=>"pageContentArea"
	]);

echo _js(["chart","codeQuality"]);
?>
<style>
  * {
    border-radius: 0px;
  }
  label {
    font-weight: normal;
  }
  .panel .panel-body {
    overflow-wrap: break-word;
  }
</style>
<script>
var loadedPlugins = [];
$(function() {
  loadedPlugins = $.cookie("CODEQUALITY-LOADEDPLUGINS");
  if(loadedPlugins==null) loadedPlugins =[];
  else loadedPlugins = loadedPlugins.split(",");
  
  $.each(loadedPlugins, function(a,b) {
    $("input[name='plugins'][value='"+b+"']","#sidebarArea")[0].checked=true;
  })
});
function reloadPluginList() {
  $("#contentArea").html("<h3 align=center>Run Code Analysis</h3></div>");
}
function runQA() {//panel-danger, panel-success, panel-info, panel-warning
  loadedPlugins = [];
  $("#contentArea").html("");
  $("input[name='plugins']:checked","#sidebarArea").each(function() {
    loadedPlugins.push($(this).val());
    
    pluginID = "PLUGIN_"+$(this).val().replace(/ /g,'_');
    pluginTitle = $(this).parent().text().replace(/&nbsp;/g,"").trim().replace(/^[0-9] /g,"").trim();
    
    $("#contentArea").append("<div id='"+pluginID+"' class='panel panel-default'>"+
                             "<div class='panel-heading'>"+pluginTitle+" Plugin Results</div><div class='panel-body'>"+
                             "</div></div>");
    $("#"+pluginID).find(".panel-body").html("<div align=center><br><br><i class='fa fa-spinner fa-spin fa-2x'></i></div>");
    $("#"+pluginID).find(".panel-body").load(_service("codeQuality","runplugin")+"&plugin="+$(this).val());
  });
  
  if(loadedPlugins.length<=0) {
    $("#contentArea").html("<h3 align=center>No plugins selected</h3>");
  } else {
    $("#contentArea").prepend("<div id='reportInfo' class='panel panel-default' style='border: 0px;box-shadow: none;'>"+
                             "<div class='panel-body' style='padding: 0px;'>Loading Code Quality Report</div></div>");
    $("#reportInfo").find(".panel-body").load(_service("codeQuality","reportinfo"));
    
    $.cookie("CODEQUALITY-LOADEDPLUGINS", loadedPlugins);
  }
}
function printReport() {
  $("#pgsidebar").hide();
  window.print();
  $("#pgsidebar").show();
}
</script>