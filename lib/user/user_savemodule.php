<?php
//save module settings.
$userid = http::httpget('userid');
$module = http::httpget('module');
$post = httpallpost();
$post = modulehook("validateprefs", $post, true, $module);
if (isset($post['validation_error']) && $post['validation_error']) {
	translator::tlschema("module-$module");
	$post['validation_error'] =
		translator::translate_inline($post['validation_error']);
	translator::tlschema();
	output::doOutput("Unable to change settings: `\$%s`0", $post['validation_error']);
} else {
	reset($post);
	while (list($key,$val)=each($post)){
		output::doOutput("Setting %s to %s`n", $key, stripslashes($val));
		$sql = "REPLACE INTO " . db_prefix("module_userprefs") . " (modulename,userid,setting,value) VALUES ('$module','$userid','$key','$val')";
		db_query($sql);
	}
	output::doOutput("`^Preferences for module %s saved.`n", $module);
}
$op = "edit";
httpset("op", "edit");
httpset("subop", "module", true);
