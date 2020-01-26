<?php
require("lib/installer/installer_sqlstatements.php");
if (httppost("type")>""){
	if (httppost("type")=="install") {
		$session['fromversion']="-1";
		$session['dbinfo']['upgrade']=false;
	}else{
		$session['fromversion']=httppost("version");
		$session['dbinfo']['upgrade']=true;
	}
}

if (!isset($session['fromversion']) || $session['fromversion']==""){
	output::doOutput("`@`c`bConfirmation`b`c");
	output::doOutput("`2Please confirm the following:`0`n");
	rawoutput("<form action='installer.php?stage=7' method='POST'>");
	rawoutput("<table border='0' cellpadding='0' cellspacing='0'><tr><td valign='top'>");
	output::doOutput("`2I should:`0");
	rawoutput("</td><td>");
	$version = settings::getsetting("installer_version","-1");
	if ($version != "-1") $session['dbinfo']['upgrade']=true;
	rawoutput("<input type='radio' value='upgrade' name='type'".($session['dbinfo']['upgrade']?" checked":"").">");
	output::doOutput(" `2Perform an upgrade from ");
	if ($version=="-1") $version="0.9.7";
	reset($sql_upgrade_statements);
	rawoutput("<select name='version'>");
	while(list($key,$val)=each($sql_upgrade_statements)){
		if ($key!="-1"){
			rawoutput("<option value='$key'".($version==$key?" selected":"").">$key</option>");
		}
	}
	rawoutput("</select>");
	rawoutput("<br><input type='radio' value='install' name='type'".($session['dbinfo']['upgrade']?"":" checked").">");
	output::doOutput(" `2Perform a clean install.");
	rawoutput("</td></tr></table>");
	$submit=translator::translate_inline("Submit");
	rawoutput("<input type='submit' value='$submit' class='button'>");
	rawoutput("</form>");
	$session['stagecompleted']=$stage - 1;
}else{
	$session['stagecompleted']=$stage;
	header("Location: installer.php?stage=".($stage+1));
	exit();
}
