<?php
// addnews ready
// mail ready
// translator ready
require_once("common.php");
require_once("lib/http.php");

translator::tlschema("taunt");

check_su_access(SU_EDIT_CREATURES);

page_header("Taunt Editor");
require_once("lib/superusernav.php");
superusernav();
$op = http::httpget('op');
$tauntid = http::httpget('tauntid');
if ($op=="edit"){
	output::addnav("Taunts");
	output::addnav("Return to the taunt editor","taunt.php");
	rawoutput("<form action='taunt.php?op=save&tauntid=$tauntid' method='POST'>",true);
	output::addnav("","taunt.php?op=save&tauntid=$tauntid");
	if ($tauntid!=""){
		$sql = "SELECT * FROM " . db_prefix("taunts") . " WHERE tauntid=\"$tauntid\"";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		require_once("lib/substitute.php");
		$badguy=array('creaturename'=>'Baron Munchausen', 'creatureweapon'=>'Bad Puns');
		$taunt = substitute_array($row['taunt']);
		$taunt = call_user_func_array("translator::sprintf_translate", $taunt);
		output::doOutput("Preview: %s`0`n`n", $taunt);
	} else {
		$row = array('tauntid'=>0, 'taunt'=>"");
	}
	output::doOutput("Taunt: ");
	rawoutput("<input name='taunt' value=\"".HTMLEntities($row['taunt'], ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"))."\" size='70'><br>");
	output::doOutput("The following codes are supported (case matters):`n");
	output::doOutput("%w = The player's name (also can be specified as {goodguy}`n");
	output::doOutput("%x = The player's weapon (also can be specified as {weapon}`n");
	output::doOutput("%a = The player's armor (also can be specified as {armor}`n");
	output::doOutput("%s = Subjective pronoun for the player (him her)`n");
	output::doOutput("%p = Possessive pronoun for the player (his her)`n");
	output::doOutput("%o = Objective pronoun for the player (he she)`n");
	output::doOutput("%W = The monster's name (also can be specified as {badguy}`n");
	output::doOutput("%X = The monster's weapon (also can be specified as {creatureweapon}`n");
	$save = translator::translate_inline("Save");
	rawoutput("<input type='submit' class='button' value='$save'>");
	rawoutput("</form>");
}else if($op=="del"){
	$sql = "DELETE FROM " . db_prefix("taunts") . " WHERE tauntid=\"$tauntid\"";
	db_query($sql);
	$op = "";
	httpset("op", "");
}else if($op=="save"){
	$taunt = httppost('taunt');
	if ($tauntid!=""){
		$sql = "UPDATE " . db_prefix("taunts") . " SET taunt=\"$taunt\",editor=\"".addslashes($session['user']['login'])."\" WHERE tauntid=\"$tauntid\"";
	}else{
		$sql = "INSERT INTO " . db_prefix("taunts") . " (taunt,editor) VALUES (\"$taunt\",\"".addslashes($session['user']['login'])."\")";
	}
	db_query($sql);
	$op = "";
	httpset("op", "");
}
if ($op == "") {
	$sql = "SELECT * FROM " . db_prefix("taunts");
	$result = db_query($sql);
	rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>");
	$op = translator::translate_inline("Ops");
	$t = translator::translate_inline("Taunt String");
	$auth = translator::translate_inline("Author");
	rawoutput("<tr class='trhead'><td nowrap>$op</td><td>$t</td><td>$auth</td></tr>");
	$number=db_num_rows($result);
	for ($i=0;$i<$number;$i++){
		$row=db_fetch_assoc($result);
		rawoutput("<tr class='".($i%2==0?"trdark":"trlight")."'>",true);
		rawoutput("<td nowrap>");
		$edit = translator::translate_inline("Edit");
		$del = translator::translate_inline("Del");
		$conf = translator::translate_inline("Are you sure you wish to delete this taunt?");
		$id = $row['tauntid'];
		rawoutput("[ <a href='taunt.php?op=edit&tauntid=$id'>$edit</a> | <a href='taunt.php?op=del&tauntid=$id' onClick='return confirm(\"$conf\");'>$del</a> ]");
		output::addnav("","taunt.php?op=edit&tauntid=$id");
		output::addnav("","taunt.php?op=del&tauntid=$id");
		rawoutput("</td><td>");
		output_notl("%s", $row['taunt']);
		rawoutput("</td><td>");
		output_notl("%s", $row['editor']);
		rawoutput("</td></tr>");
	}
	output::addnav("","taunt.php?c=".http::httpget('c'));
	rawoutput("</table>");
	output::addnav("Taunts");
	output::addnav("Add a new taunt","taunt.php?op=edit");
}
page_footer();
