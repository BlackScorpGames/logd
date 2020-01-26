<?php
// addnews ready
// translator ready
// mail ready
require_once("common.php");
require_once("lib/sanitize.php");

translator::tlschema("bio");

checkday();

$ret = http::httpget('ret');
if ($ret==""){
	$return = "/list.php";
}else{
	$return = cmd_sanitize($ret);
}

$char = http::httpget('char');
//Legacy support
if (is_numeric($char)){
	$where = "acctid = $char";
} else {
	$where = "login = '$char'";
}
$sql = "SELECT login, name, level, sex, title, specialty, hashorse, acctid, resurrections, bio, dragonkills, race, clanname, clanshort, clanrank, ".db_prefix("accounts").".clanid, laston, loggedin FROM " . db_prefix("accounts") . " LEFT JOIN " . db_prefix("clans") . " ON " . db_prefix("accounts") . ".clanid = " . db_prefix("clans") . ".clanid WHERE $where";
$result = db_query($sql);
if ($target = db_fetch_assoc($result)) {
  $target['login'] = rawurlencode($target['login']);
  $id = $target['acctid'];
  $target['return_link']=$return;

  page_header("Character Biography: %s", full_sanitize($target['name']));

  translator::tlschema("nav");
  output::addnav("Return");
  translator::tlschema();

  if ($session['user']['superuser'] & SU_EDIT_USERS){
	  output::addnav("Superuser");
	  output::addnav("Edit User","user.php?op=edit&userid=$id");
  }

  modules::modulehook("biotop", $target);

 output::doOutput("`^Biography for %s`^.",$target['name']);
  $write = translator::translate_inline("Write Mail");
  if ($session['user']['loggedin'])
	  rawoutput("<a href=\"mail.php?op=write&to={$target['login']}\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to={$target['login']}").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='$write' border='0'></a>");
  output_notl("`n`n");

  if ($target['clanname']>"" && settings::getsetting("allowclans",false)){
	  $ranks = array(CLAN_APPLICANT=>"`!Applicant`0",CLAN_MEMBER=>"`#Member`0",CLAN_OFFICER=>"`^Officer`0",CLAN_LEADER=>"`&Leader`0", CLAN_FOUNDER=>"`\$Founder");
	  $ranks = modules::modulehook("clanranks", array("ranks"=>$ranks, "clanid"=>$target['clanid']));
	  translator::tlschema("clans"); //just to be in the right schema
	  array_push($ranks['ranks'],"`\$Founder");
	  $ranks = translator::translate_inline($ranks['ranks']);
	  translator::tlschema();
	 output::doOutput("`@%s`2 is a %s`2 to `%%s`2`n", $target['name'], $ranks[$target['clanrank']], $target['clanname']);
  }

 output::doOutput("`^Title: `@%s`n",$target['title']);
 output::doOutput("`^Level: `@%s`n",$target['level']);
  $loggedin = false;
  if ($target['loggedin'] &&
		  (date("U") - strtotime($target['laston']) <
			settings::getsetting("LOGINTIMEOUT", 900))) {
	  $loggedin = true;
  }
  $status = translator::translate_inline($loggedin?"`#Online`0":"`\$Offline`0");
 output::doOutput("`^Status: %s`n",$status);

 output::doOutput("`^Resurrections: `@%s`n",$target['resurrections']);

  $race = $target['race'];
  if (!$race) $race = RACE_UNKNOWN;
  translator::tlschema("race");
  $race = translator::translate_inline($race);
  translator::tlschema();
 output::doOutput("`^Race: `@%s`n",$race);

  $genders = array("Male","Female");
  $genders = translator::translate_inline($genders);
 output::doOutput("`^Gender: `@%s`n",$genders[$target['sex']]);

  $specialties = modules::modulehook("specialtynames",
		  array(""=>translator::translate_inline("Unspecified")));
  if (isset($specialties[$target['specialty']])) {
		output::doOutput("`^Specialty: `@%s`n",$specialties[$target['specialty']]);
  }
  $sql = "SELECT * FROM " . db_prefix("mounts") . " WHERE mountid='{$target['hashorse']}'";
  $result = db_query_cached($sql, "mountdata-{$target['hashorse']}", 3600);
  $mount = db_fetch_assoc($result);

  $mount['acctid']=$target['acctid'];
  $mount = modules::modulehook("bio-mount",$mount);
  $none = translator::translate_inline("`iNone`i");
  if (!isset($mount['mountname']) || $mount['mountname']=="")
		  $mount['mountname'] = $none;
 output::doOutput("`^Creature: `@%s`0`n",$mount['mountname']);

  modules::modulehook("biostat", $target);

  if ($target['dragonkills']>0)
	 output::doOutput("`^Dragon Kills: `@%s`n",$target['dragonkills']);

  if ($target['bio']>"")
	 output::doOutput("`^Bio: `@`n%s`n",soap($target['bio']));

  modules::modulehook("bioinfo", $target);

 output::doOutput("`n`^Recent accomplishments (and defeats) of %s`^",$target['name']);
  $result = db_query("SELECT * FROM " . db_prefix("news") . " WHERE accountid={$target['acctid']} ORDER BY newsdate DESC,newsid ASC LIMIT 100");

  $odate="";
  translator::tlschema("news");
  while ($row = db_fetch_assoc($result)) {
	  translator::tlschema($row['tlschema']);
	  if ($row['arguments'] > "") {
		  $arguments = array();
		  $base_arguments = unserialize($row['arguments']);
		  array_push($arguments, $row['newstext']);
		  while(list($key, $val) = each($base_arguments)) {
			  array_push($arguments, $val);
		  }
		  $news = call_user_func_array("translator::sprintf_translate", $arguments);
		  rawoutput(translator::tlbutton_clear());
	  } else {
		  $news = translator::translate_inline($row['newstext']);
		  rawoutput(translator::tlbutton_clear());
	  }
	  translator::tlschema();
	  if ($odate!=$row['newsdate']){
		  output_notl("`n`b`@%s`0`b`n",
				  date("D, M d", strtotime($row['newsdate'])));
		  $odate=$row['newsdate'];
	  }
	  output_notl("`@$news`0`n");
  }
  translator::tlschema();

  if ($ret==""){
	  $return = substr($return,strrpos($return,"/")+1);
	  translator::tlschema("nav");
	  output::addnav("Return");
	  output::addnav("Return to the warrior list",$return);
	  translator::tlschema();
  }else{
	  $return = substr($return,strrpos($return,"/")+1);
	  translator::tlschema("nav");
	  output::addnav("Return");
	  if ($return=="list.php") {
		  output::addnav("Return to the warrior list",$return);
	  } else {
		  output::addnav("Return whence you came",$return);
	  }
	  translator::tlschema();
  }

  modules::modulehook("bioend", $target);
  page_footer();
} else {
	page_header("Character has been deleted");
	output::doOutput("This character is already deleted.");
  if ($ret==""){
	  $return = substr($return,strrpos($return,"/")+1);
	  translator::tlschema("nav");
	  output::addnav("Return");
	  output::addnav("Return to the warrior list",$return);
	  translator::tlschema();
  }else{
	  $return = substr($return,strrpos($return,"/")+1);
	  translator::tlschema("nav");
	  output::addnav("Return");
	  if ($return=="list.php") {
		  output::addnav("Return to the warrior list",$return);
	  } else {
		  output::addnav("Return whence you came",$return);
	  }
	  translator::tlschema();
  }
	page_footer();
}
