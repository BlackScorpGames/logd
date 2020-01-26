<?php
// translator ready
// addnews ready
// mail ready
require_once("common.php");
require_once("lib/commentary.php");
require_once("lib/http.php");
require_once("lib/events.php");
require_once("lib/experience.php");

translator::tlschema('village');
//mass_module_prepare(array("village","validlocation","villagetext","village-desc"));
// See if the user is in a valid location and if not, put them back to
// a place which is valid
$valid_loc = array();
$vname = settings::getsetting("villagename", LOCATION_FIELDS);
$iname = settings::getsetting("innname", LOCATION_INN);
$valid_loc[$vname]="village";
$valid_loc = modules::modulehook("validlocation", $valid_loc);
if (!isset($valid_loc[$session['user']['location']])) {
	$session['user']['location']=$vname;
}

$newestname = "";
$newestplayer = settings::getsetting("newestplayer", "");
if ($newestplayer == $session['user']['acctid']) {
	$newtext = "`nYou're the newest member of the village.  As such, you wander around, gaping at the sights, and generally looking lost.";
	$newestname = $session['user']['name'];
} else {
	$newtext = "`n`2Wandering near the inn is `&%s`2, looking completely lost.";
	if ((int)$newestplayer != 0) {
		$sql = "SELECT name FROM " . db_prefix("accounts") . " WHERE acctid='$newestplayer'";
		$result = db_query_cached($sql, "newest");
		if (db_num_rows($result) == 1) {
			$row = db_fetch_assoc($result);
			$newestname = $row['name'];
		} else {
			$newestplayer = "";
		}
	} else {
		if ($newestplayer > "") {
			$newestname = $newestplayer;
		} else {
			$newestname = "";
		}
	}
}

$basetext = array(
	"`@`c`b%s Square`b`cThe village of %s hustles and bustles.  No one really notices that you're standing there.  ".
	"You see various shops and businesses along main street.  There is a curious looking rock to one side.  ".
	"On every side the village is surrounded by deep dark forest.`n`n",$vname,$vname
	);
$origtexts = array(
	"text"=>$basetext,
	"clock"=>"The clock on the inn reads `^%s`@.`n",
	"title"=>array("%s Square", $vname),
	"talk"=>"`n`%`@Nearby some villagers talk:`n",
	"sayline"=>"says",
	"newest"=>$newtext,
	"newestplayer"=>$newestname,
	"newestid"=>$newestplayer,
	"gatenav"=>"City Gates",
	"fightnav"=>"Blades Boulevard",
	"marketnav"=>"Market Street",
	"tavernnav"=>"Tavern Street",
	"infonav"=>"Info",
	"othernav"=>"Other",
	"section"=>"village",
	"innname"=>$iname,
	"stablename"=>"Merick's Stables",
	"mercenarycamp"=>"Mercenary Camp",
	"armorshop"=>"Pegasus Armor",
	"weaponshop"=>"MightyE's Weaponry"
	);
$schemas = array(
	"text"=>"village",
	"clock"=>"village",
	"title"=>"village",
	"talk"=>"village",
	"sayline"=>"village",
	"newest"=>"village",
	"newestplayer"=>"village",
	"newestid"=>"village",
	"gatenav"=>"village",
	"fightnav"=>"village",
	"marketnav"=>"village",
	"tavernnav"=>"village",
	"infonav"=>"village",
	"othernav"=>"village",
	"section"=>"village",
	"innname"=>"village",
	"stablename"=>"village",
	"mercenarycamp"=>"village",
	"armorshop"=>"village",
	"weaponshop"=>"village"
	);
// Now store the schemas
$origtexts['schemas'] = $schemas;

// don't hook on to this text for your standard modules please, use "village"
// instead.
// This hook is specifically to allow modules that do other villages to create
// ambience.
$texts = modules::modulehook("villagetext",$origtexts);
//and now a special hook for the village
$texts = modules::modulehook("villagetext-{$session['user']['location']}",$texts);
$schemas = $texts['schemas'];

translator::tlschema($schemas['title']);
page_header($texts['title']);
translator::tlschema();

addcommentary();
$skipvillagedesc = handle_event("village");
checkday();

if ($session['user']['slaydragon'] == 1) {
	$session['user']['slaydragon'] = 0;
}


if ($session['user']['alive']){ }else{
	redirect("shades.php");
}

if (settings::getsetting("automaster",1) && $session['user']['seenmaster']!=1){
	//masters hunt down truant students
	$level = $session['user']['level']+1;
	$dks = $session['user']['dragonkills'];
	$expreqd = exp_for_next_level($level, $dks);
	if ($session['user']['experience']>$expreqd &&
			$session['user']['level']<15){
		redirect("train.php?op=autochallenge");
	}
}

$op = http::httpget('op');
$com = http::httpget('comscroll');
$refresh = http::httpget("refresh");
$commenting = http::httpget("commenting");
$comment = httppost('insertcommentary');
// Don't give people a chance at a special event if they are just browsing
// the commentary (or talking) or dealing with any of the hooks in the village.
if (!$op && $com=="" && !$comment && !$refresh && !$commenting) {
	// The '1' should really be sysadmin customizable.
	if (module_events("village", settings::getsetting("villagechance", 0)) != 0) {
		if (checknavs()) {
			page_footer();
		} else {
			// Reset the special for good.
			$session['user']['specialinc'] = "";
			$session['user']['specialmisc'] = "";
			$skipvillagedesc=true;
			$op = "";
			httpset("op", "");
		}
	}
}

translator::tlschema($schemas['gatenav']);
output::addnav($texts['gatenav']);
translator::tlschema();

output::addnav("F?Forest","forest.php");
if (settings::getsetting("pvp",1)){
	output::addnav("S?Slay Other Players","pvp.php");
}
output::addnav("Q?`%Quit`0 to the fields","login.php?op=logout",true);
if (settings::getsetting("enablecompanions",true)) {
	translator::tlschema($schemas['mercenarycamp']);
	output::addnav($texts['mercenarycamp'], "mercenarycamp.php");
	translator::tlschema();
}

translator::tlschema($schemas['fightnav']);
output::addnav($texts['fightnav']);
translator::tlschema();
output::addnav("u?Bluspring's Warrior Training","train.php");
if (@file_exists("lodge.php")) {
	output::addnav("J?JCP's Hunter Lodge","lodge.php");
}

translator::tlschema($schemas['marketnav']);
output::addnav($texts['marketnav']);
translator::tlschema();
translator::tlschema($schemas['weaponshop']);
output::addnav("W?".$texts['weaponshop'],"weapons.php");
translator::tlschema();
translator::tlschema($schemas['armorshop']);
output::addnav("A?".$texts['armorshop'],"armor.php");
translator::tlschema();
output::addnav("B?Ye Olde Bank","bank.php");
output::addnav("Z?Ze Gypsy Tent","gypsy.php");
if (settings::getsetting("betaperplayer", 1) == 1 && @file_exists("pavilion.php")) {
	output::addnav("E?Eye-catching Pavilion","pavilion.php");
}

translator::tlschema($schemas['tavernnav']);
output::addnav($texts['tavernnav']);
translator::tlschema();
translator::tlschema($schemas['innname']);
output::addnav("I?".$texts['innname']."`0","inn.php",true);
translator::tlschema();
translator::tlschema($schemas['stablename']);
output::addnav("M?".$texts['stablename']."`0","stables.php");
translator::tlschema();

output::addnav("G?The Gardens", "gardens.php");
output::addnav("R?Curious Looking Rock", "rock.php");
if (settings::getsetting("allowclans",1)) output::addnav("C?Clan Halls","clan.php");

translator::tlschema($schemas['infonav']);
output::addnav($texts['infonav']);
translator::tlschema();
output::addnav("??F.A.Q. (newbies start here)", "petition.php?op=faq",false,true);
output::addnav("N?Daily News","news.php");
output::addnav("L?List Warriors","list.php");
output::addnav("o?Hall o' Fame","hof.php");

translator::tlschema($schemas['othernav']);
output::addnav($texts['othernav']);
translator::tlschema();
output::addnav("P?Preferences","prefs.php");
if (!file_exists("lodge.php")) {
	output::addnav("Refer a Friend", "referral.php");
}

translator::tlschema('nav');
output::addnav("Superuser");
if ($session['user']['superuser'] & SU_EDIT_COMMENTS){
	output::addnav(",?Comment Moderation","moderate.php");
}
if ($session['user']['superuser']&~SU_DOESNT_GIVE_GROTTO){
  output::addnav("X?`bSuperuser Grotto`b","superuser.php");
}
if ($session['user']['superuser'] & SU_INFINITE_DAYS){
  output::addnav("/?New Day","newday.php");
}
translator::tlschema();
//let users try to cheat, we protect against this and will know if they try.
output::addnav("","superuser.php");
output::addnav("","user.php");
output::addnav("","taunt.php");
output::addnav("","creatures.php");
output::addnav("","configuration.php");
output::addnav("","badword.php");
output::addnav("","armoreditor.php");
output::addnav("","bios.php");
output::addnav("","badword.php");
output::addnav("","donators.php");
output::addnav("","referers.php");
output::addnav("","retitle.php");
output::addnav("","stats.php");
output::addnav("","viewpetition.php");
output::addnav("","weaponeditor.php");

if (!$skipvillagedesc) {
	modules::modulehook("collapse{", array("name"=>"villagedesc-".$session['user']['location']));
	translator::tlschema($schemas['text']);
	output::doOutput($texts['text']);
	translator::tlschema();
	modules::modulehook("}collapse");
	modules::modulehook("collapse{", array("name"=>"villageclock-".$session['user']['location']));
	translator::tlschema($schemas['clock']);
	output::doOutput($texts['clock'],getgametime());
	translator::tlschema();
	modules::modulehook("}collapse");
	modules::modulehook("village-desc",$texts);
	//support for a special village-only hook
	modules::modulehook("village-desc-{$session['user']['location']}",$texts);
	if ($texts['newestplayer'] > "" && $texts['newest']) {
		modules::modulehook("collapse{", array("name"=>"villagenewest-".$session['user']['location']));
		translator::tlschema($schemas['newest']);
		output::doOutput($texts['newest'], $texts['newestplayer']);
		translator::tlschema();
		$id = $texts['newestid'];
		if ($session['user']['superuser'] & SU_EDIT_USERS && $id) {
			$edit = translator::translate_inline("Edit");
			rawoutput(" [<a href='user.php?op=edit&userid=$id'>$edit</a>]");
			output::addnav("","user.php?op=edit&userid=$id");
		}
		output_notl("`n");
		modules::modulehook("}collapse");
	}
}
modules::modulehook("village",$texts);
//special hook for all villages... saves queries...
modules::modulehook("village-{$session['user']['location']}",$texts);

if ($skipvillagedesc)output::doOutput("`n");

$args = modules::modulehook("blockcommentarea", array("section"=>$texts['section']));
if (!isset($args['block']) || $args['block'] != 'yes') {
		translator::tlschema($schemas['talk']);
		output::doOutput($texts['talk']);
		translator::tlschema();
		commentdisplay("",$texts['section'],"Speak",25,$texts['sayline'], $schemas['sayline']);
}

module_display_events("village", "village.php");
page_footer();
