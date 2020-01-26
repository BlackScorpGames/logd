<?php
// mail ready
// addnews ready
// translator ready
function foilwench_getmoduleinfo(){
	$info = array(
		"name"=>"Foilwench",
		"version"=>"1.1",
		"author"=>"Eric Stevens",
		"category"=>"Forest Specials",
		"download"=>"core_module",
	);
	return $info;
}

function foilwench_install(){
	module_addeventhook("forest", "return 100;");
	return true;
}

function foilwench_uninstall(){
	return true;
}

function foilwench_dohook($hookname,$args){
	return $args;
}

function foilwench_runevent($type)
{
	require_once("lib/increment_specialty.php");
	global $session;
	// We assume this event only shows up in the forest currently.
	$from = "forest.php?";
	$session['user']['specialinc'] = "module:foilwench";

	$colors = array(""=>"`7");
	$colors = modules::modulehook("specialtycolor", $colors);
	$c = $colors[$session['user']['specialty']];
	if (!$c) $c = "`7";
	if ($session['user']['specialty'] == "") {
		output::doOutput("You have no direction in the world, you should rest and make some important decisions about your life.");
		$session['user']['specialinc']="";
		return;
	}
	$skills = modules::modulehook("specialtynames");

	$op = http::httpget('op');
	if ($op=="give"){
		if ($session['user']['gems']>0){
			output::doOutput("%sYou give `@Foil`&wench%s a gem, and she hands you a slip of parchment with instructions on how to advance in your specialty.`n`n", $c, $c);
			output::doOutput("You study it intensely, shred it up, and eat it lest infidels get ahold of the information.`n`n");
			output::doOutput("`@Foil`&wench%s sighs... \"`&You didn't have to eat it...  Oh well, now be gone from here!%s\"`3", $c, $c);
			increment_specialty("`3");
			$session['user']['gems']--;
			debuglog("gave 1 gem to Foilwench");
		}else{
			output::doOutput("%sYou hand over your imaginary gem.", $c);
			output::doOutput("`@Foil`&wench%s stares blankly back at you.", $c);
			output::doOutput("\"`&Come back when you have a `breal`b gem you simpleton.%s\"`n`n", $c);
			output::doOutput("\"`#Simpleton?%s\" you ask.`n`n", $c);
			output::doOutput("With that, `@Foil`&wench%s throws you out.`0", $c);
		}
		$session['user']['specialinc']="";
	}elseif($op=="dont"){
		output::doOutput("%sYou inform `@Foil`&wench%s that if she would like to get rich, she will have to do so on her efforts, and stomp away.", $c, $c);
		$session['user']['specialinc']="";
	}elseif($session['user']['specialty']!=""){
		output::doOutput("%sYou are seeking prey in the forest when you stumble across a strange hut.", $c);
		output::doOutput("Ducking inside, you are met by the grizzled face of a battle-hardened old woman.");
		output::doOutput("\"`&Greetings %s`&, I am `@Foil`&wench, master of all.%s\"`n`n", $session['user']['name'], $c);
		output::doOutput("\"`#Master of all?%s\" you inquire.`n`n", $c);
		output::doOutput("\"`&Yes, master of all.  All the skills are mine to control, and to teach.%s\"`n`n", $c);
		output::doOutput("\"`#Yours to teach?%s\" you query.`n`n", $c);
		output::doOutput("The old woman sighs, \"`&Yes, mine to teach.  I will teach you how to advance in %s on two conditions.%s\"`n`n", $skills[$session['user']['specialty']], $c);
		output::doOutput("\"`#Two conditions?%s\" you repeat inquisitively.`n`n", $c);
		output::doOutput("\"`&Yes.  First, you must give me a gem, and second you must stop repeating what I say in the form of a question!%s\"`n`n", $c);
		output::doOutput("\"`#A gem!%s\" you state definitively.`n`n", $c);
		output::doOutput("\"`&Well... I guess that wasn't a question.  So how about that gem?%s\"", $c);
		output::addnav("Give her a gem", $from."op=give");
		output::addnav("Don't give her a gem",$from."op=dont");
	}
}

function foilwench_run(){
}

