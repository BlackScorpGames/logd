<?php
// addnews ready
// translator ready
// mail ready
require_once("common.php");
require_once("lib/http.php");
require_once("lib/sanitize.php");
check_su_access(SU_MANAGE_MODULES);
translator::tlschema("modulemanage");

page_header("Module Manager");

require_once("lib/superusernav.php");
superusernav();

output::addnav("Module Categories");

output::addnav("",$REQUEST_URI);
$op = http::httpget('op');
$module = http::httpget('module');

if ($op == 'mass'){
	if (httppost("activate")) $op = "activate";
	if (httppost("deactivate")) $op = "deactivate";
	if (httppost("uninstall")) $op = "uninstall";
	if (httppost("reinstall")) $op = "reinstall";
	if (httppost("install")) $op = "install";
	$module = httppost("module");
}
$theOp = $op;
if (is_array($module)){
	$modules = $module;
}else{
	if ($module) $modules = array($module);
	else $modules = array();
}
reset($modules);
while (list($key,$module)=each($modules)){
	$op = $theOp;
	output::doOutput("`2Performing `^%s`2 on `%%s`0`n", translator::translate_inline($op), $module);
	if($op=="install"){
		if (install_module($module)){

		}else{
			httpset('cat','');
		}
		$op="";
		httpset('op', "");
	}elseif($op=="uninstall"){
		if (uninstall_module($module)) {
		} else {
			output::doOutput("Unable to inject module.  Module not uninstalled.`n");
		}
		$op="";
		httpset('op', "");
	}elseif($op=="activate"){
		activate_module($module);
		$op="";
		httpset('op', "");
		invalidatedatacache("inject-$module");
	}elseif($op=="deactivate"){
		deactivate_module($module);
		$op="";
		httpset('op', "");
		invalidatedatacache("inject-$module");
	}elseif($op=="reinstall"){
		$sql = "UPDATE " . db_prefix("modules") . " SET filemoddate='0000-00-00 00:00:00' WHERE modulename='$module'";
		db_query($sql);
		// We don't care about the return value here at all.
		injectmodule($module, true);
		$op="";
		httpset('op', "");
		invalidatedatacache("inject-$module");
	}
}

$install_status = get_module_install_status();
$uninstmodules = $install_status['uninstalledmodules'];
$seencats = $install_status['installedcategories'];
$ucount = $install_status['uninstcount'];

ksort($seencats);
output::addnav(array(" ?Uninstalled - (%s modules)", $ucount), "modules.php");
reset($seencats);
foreach ($seencats as $cat=>$count) {
	output::addnav(array(" ?%s - (%s modules)", $cat, $count), "modules.php?cat=$cat");
}

$cat = http::httpget('cat');

if ($op==""){
	if ($cat) {
		$sortby=http::httpget('sortby');
		if (!$sortby) $sortby="installdate";
		$order=http::httpget('order');
		$tcat = translator::translate_inline($cat);
		output::doOutput("`n`b%s Modules`b`n", $tcat);
		$deactivate = translator::translate_inline("Deactivate");
		$activate = translator::translate_inline("Activate");
		$uninstall = translator::translate_inline("Uninstall");
		$reinstall = translator::translate_inline("Reinstall");
		$strsettings = translator::translate_inline("Settings");
		$strnosettings = translator::translate_inline("`\$No Settings`0");
		$uninstallconfirm = translator::translate_inline("Are you sure you wish to uninstall this module?  All user preferences and module settings will be lost.  If you wish to temporarily remove access to the module, you may simply deactivate it.");
		$status = translator::translate_inline("Status");
		$mname = translator::translate_inline("Module Name");
		$ops = translator::translate_inline("Ops");
		$mauth = translator::translate_inline("Module Author");
		$inon = translator::translate_inline("Installed On");
		$installstr = translator::translate_inline("by %s");
		$active = translator::translate_inline("`@Active`0");
		$inactive = translator::translate_inline("`\$Inactive`0");
		rawoutput("<form action='modules.php?op=mass&cat=$cat' method='POST'>");
		output::addnav("","modules.php?op=mass&cat=$cat");
		rawoutput("<table border='0' cellpadding='2' cellspacing='1' bgcolor='#999999'>",true);
		rawoutput("<tr class='trhead'><td>&nbsp;</td><td>$ops</td><td><a href='modules.php?cat=$cat&sortby=active&order=".($sortby=="active"?!$order:1)."'>$status</a></td><td><a href='modules.php?cat=$cat&sortby=formalname&order=".($sortby=="formalname"?!$order:1)."'>$mname</a></td><td><a href='modules.php?cat=$cat&sortby=moduleauthor&order=".($sortby=="moduleauthor"?!$order:1)."'>$mauth</a></td><td><a href='modules.php?cat=$cat&sortby=installdate&order=".($sortby=="installdate"?!$order:0)."'>$inon</a></td></tr>");
		output::addnav("","modules.php?cat=$cat&sortby=active&order=".($sortby=="active"?!$order:1));
		output::addnav("","modules.php?cat=$cat&sortby=formalname&order=".($sortby=="formalname"?!$order:1));
		output::addnav("","modules.php?cat=$cat&sortby=moduleauthor&order=".($sortby=="moduleauthor"?!$order:1));
		output::addnav("","modules.php?cat=$cat&sortby=installdate&order=".($sortby=="installdate"?$order:0));
		$sql = "SELECT * FROM " . db_prefix("modules") . " WHERE category='$cat' ORDER BY ".$sortby." ".($order?"ASC":"DESC");
		$result = db_query($sql);
		if (db_num_rows($result)==0){
			rawoutput("<tr class='trlight'><td colspan='6' align='center'>");
			output::doOutput("`i-- No Modules Installed--`i");
			rawoutput("</td></tr>");
		}
		$number=db_num_rows($result);
		for ($i=0;$i<$number;$i++){
			$row = db_fetch_assoc($result);
			rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>",true);
			rawoutput("<td nowrap valign='top'>");
			rawoutput("<input type='checkbox' name='module[]' value=\"{$row['modulename']}\">");
			rawoutput("</td><td valign='top' nowrap>[ ");
			if ($row['active']){
				rawoutput("<a href='modules.php?op=deactivate&module={$row['modulename']}&cat=$cat'>");
				output_notl($deactivate);
				rawoutput("</a>");
				output::addnav("","modules.php?op=deactivate&module={$row['modulename']}&cat=$cat");
			}else{
				rawoutput("<a href='modules.php?op=activate&module={$row['modulename']}&cat=$cat'>");
				output_notl($activate);
				rawoutput("</a>");
				output::addnav("","modules.php?op=activate&module={$row['modulename']}&cat=$cat");
			}
			rawoutput(" |<a href='modules.php?op=uninstall&module={$row['modulename']}&cat=$cat' onClick='return confirm(\"$uninstallconfirm\");'>");
			output_notl($uninstall);
			rawoutput("</a>");
			output::addnav("","modules.php?op=uninstall&module={$row['modulename']}&cat=$cat");
			rawoutput(" | <a href='modules.php?op=reinstall&module={$row['modulename']}&cat=$cat'>");
			output_notl($reinstall);
			rawoutput("</a>");
			output::addnav("","modules.php?op=reinstall&module={$row['modulename']}&cat=$cat");

			if ($session['user']['superuser'] & SU_EDIT_CONFIG) {
				if (strstr($row['infokeys'], "|settings|")) {
					rawoutput(" | <a href='configuration.php?op=modulesettings&module={$row['modulename']}'>");
					output_notl($strsettings);
					rawoutput("</a>");
					output::addnav("","configuration.php?op=modulesettings&module={$row['modulename']}");
				} else {
					output_notl(" | %s", $strnosettings);
				}
			}

			rawoutput(" ]</td><td valign='top'>");
			output_notl($row['active']?$active:$inactive);
			require_once("lib/sanitize.php");
			rawoutput("</td><td nowrap valign='top'><span title=\"".
					(isset($row['description'])&&$row['description']?
					 $row['description']:sanitize($row['formalname']))."\">");
			output_notl("%s %s", $row['formalname'], $row['version']);
			rawoutput("<br>");
			output_notl("(%s) ", $row['modulename'], $row['version']);
			rawoutput("</span></td><td valign='top'>");
			output_notl("`#%s`0", $row['moduleauthor'], true);
			rawoutput("</td><td nowrap valign='top'>");
			$line = sprintf($installstr, $row['installedby']);
			output_notl("%s", $row['installdate']);
			rawoutput("<br>");
			output_notl("%s", $line);
			rawoutput("</td></tr>");
		}
		rawoutput("</table><br />");
		$activate = translator::translate_inline("Activate");
		$deactivate = translator::translate_inline("Deactivate");
		$reinstall = translator::translate_inline("Reinstall");
		$uninstall = translator::translate_inline("Uninstall");
		rawoutput("<input type='submit' name='activate' class='button' value='$activate'>");
		rawoutput("<input type='submit' name='deactivate' class='button' value='$deactivate'>");
		rawoutput("<input type='submit' name='reinstall' class='button' value='$reinstall'>");
		rawoutput("<input type='submit' name='uninstall' class='button' value='$uninstall'>");
		rawoutput("</form>");
	} else {
		$sorting=http::httpget('sorting');
		if (!$sorting) $sorting="shortname";
		$order=http::httpget('order');
		output::doOutput("`bUninstalled Modules`b`n");
		$install = translator::translate_inline("Install");
		$mname = translator::translate_inline("Module Name");
		$ops = translator::translate_inline("Ops");
		$mauth = translator::translate_inline("Module Author");
		$categ = translator::translate_inline("Category");
		$fname = translator::translate_inline("Filename");
		rawoutput("<form action='modules.php?op=mass&cat=$cat' method='POST'>");
		output::addnav("","modules.php?op=mass&cat=$cat");
		rawoutput("<table border='0' cellpadding='2' cellspacing='1' bgcolor='#999999'>",true);
		rawoutput("<tr class='trhead'><td>&nbsp;</td><td>$ops</td><td><a href='modules.php?sorting=name&order=".($sorting=="name"?!$order:0)."'>$mname</a></td><td><a href='modules.php?sorting=author&order=".($sorting=="author"?!$order:0)."'>$mauth</a></td><td><a href='modules.php?sorting=category&order=".($sorting=="category"?!$order:0)."'>$categ</a></td><td><a href='modules.php?sorting=shortname&order=".($sorting=="shortname"?!$order:0)."'>$fname</a></td></tr>");
		output::addnav("","modules.php?sorting=name&order=".($sorting=="name"?!$order:0));
		output::addnav("","modules.php?sorting=author&order=".($sorting=="author"?!$order:0));
		output::addnav("","modules.php?sorting=category&order=".($sorting=="category"?!$order:0));
		output::addnav("","modules.php?sorting=shortname&order=".($sorting=="shortname"?!$order:0));
		if (count($uninstmodules) > 0) {
			$count = 0;
			$moduleinfo=array();
			$sortby=array();
			$numberarray=array();
			$invalidmodule = array(
				"version"=>"",
				"author"=>"",
				"category"=>"",
				"download"=>"",
				"invalid"=>true,
			);
			foreach($uninstmodules as $key=>$shortname) {
				//test if the file is a valid module or a lib file/whatever that got in, maybe even malcode that does not have module form
				$shortnamelower = strtolower($shortname);
				$file = strtolower(file_get_contents("modules/$shortname.php"));
				if (strpos($file,$shortnamelower."_getmoduleinfo")===false ||
					//strpos($file,$shortname."_dohook")===false ||
					//do_hook is not a necessity
					strpos($file,$shortnamelower."_install")===false ||
					strpos($file,$shortnamelower."_uninstall")===false) {
						//here the files has neither do_hook nor getinfo, which means it won't execute as a module here --> block it + notify the admin who is the manage modules section
						$temp=array_merge($invalidmodule,array("name"=>$shortname.".php ".appoencode(translator::translate_inline("(`\$Invalid Module! Contact Author or check file!`0)"))));
				} else {
					$temp= get_module_info($shortname);
				}
				//end of testing
				if (!$temp || empty($temp)) continue;
				$temp['shortname']=$shortname;
				array_push($moduleinfo,$temp);
				array_push($sortby,full_sanitize($temp[$sorting]));
				array_push($numberarray,$count);
				$count++;
			}
			array_multisort($sortby,($order?SORT_DESC:SORT_ASC),$numberarray,($order?SORT_DESC:SORT_ASC));
			for ($a=0;$a<count($moduleinfo);$a++) {
				$i=$numberarray[$a];
				rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
				if (isset($moduleinfo[$i]['invalid']) && $moduleinfo[$i]['invalid']===true) {
					rawoutput("<td></td><td nowrap valign='top'>");
						output::doOutput("Not installable");
						rawoutput("</td>");
				} else {
					rawoutput("<td><input type='checkbox' name='module[]' value='{$moduleinfo[$i]['shortname']}'></td>");
					rawoutput("<td nowrap valign='top'>");
					rawoutput("[ <a href='modules.php?op=install&module={$moduleinfo[$i]['shortname']}&cat={$moduleinfo[$i]['category']}'>");
					output_notl($install);
					rawoutput("</a>]</td>");
					output::addnav("","modules.php?op=install&module={$moduleinfo[$i]['shortname']}&cat={$moduleinfo[$i]['category']}");
				}
			    rawoutput("<td nowrap valign='top'><span title=\"".
					(isset($moduleinfo[$i]['description'])&&
					     $moduleinfo[$i]['description'] ?
					 $moduleinfo[$i]['description'] :
					 sanitize($moduleinfo[$i]['name']))."\">");
				rawoutput($moduleinfo[$i]['name']." ".$moduleinfo[$i]['version']);
				rawoutput("</span></td><td valign='top'>");
				output_notl("`#%s`0", $moduleinfo[$i]['author'], true);
				rawoutput("</td><td valign='top'>");
				rawoutput($moduleinfo[$i]['category']);
				rawoutput("</td><td valign='top'>");
				rawoutput($moduleinfo[$i]['shortname'] . ".php");
				rawoutput("</td>");
				rawoutput("</tr>");
				if (isset($moduleinfo[$i]['requires']) && count($moduleinfo[$i]['requires'])){
					rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
					rawoutput("<td>&nbsp;</td>");
					rawoutput("<td colspan='6'>");
					output::doOutput("`bRequires:`b`n");
					reset($moduleinfo[$i]['requires']);
					while (list($key,$val)=each($moduleinfo[$i]['requires'])){
						$info = explode("|",$val);
						if (module_check_requirements(array($key=>$val))){
							output_notl("`@");
						}else{
							output_notl("`\$");
						}
						if(isset($info[1])) output_notl("$key {$info[0]} -- {$info[1]}`n");
						else output_notl("$key {$info[0]}`n");
					}
					rawoutput("</td>");
					rawoutput("</tr>");
				}
				$count++;
			}
		} else {
			rawoutput("<tr class='trlight'><td colspan='6' align='center'>");
			output::doOutput("`i--No uninstalled modules were found--`i");
			rawoutput("</td></tr>");
		}
		rawoutput("</table><br />");
		$install = translator::translate_inline("Install");
		rawoutput("<input type='submit' name='install' class='button' value='$install'>");
	}
}

page_footer();
