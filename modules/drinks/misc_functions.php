<?php
function drinks_gettexts() {
	global $session;
	$iname = settings::getsetting("innname", LOCATION_INN);
	$drinktext = array(
			"title"=>"$iname",
			"barkeep"=>settings::getsetting("barkeep", "`tCedrik"),
			"return"=>"",
			"demand"=>"Pounding your fist on the bar, you demand another drink",
			"toodrunk"=>" but {barkeep} continues to clean the glass he was working on.  \"`%You've had enough ".($session['user']['sex']?"lass":"lad").",`0\" he declares.",
			"toomany"=>"{barkeep} eyes you critically. \"`%Ya've had enough of the hard stuff, my friend.  No more of that for you today.`0\"",
			"drinksubs"=>array(),
		);
	$schemas = array(
		'title'=>"module-drinks",
		'barkeep'=>"module-drinks",
		'return'=>"module-drinks",
		'demand'=>"module-drinks",
		'toodrunk'=>"module-drinks",
		'toomany'=>"module-drinks",
		'drinksubs'=>"module-drinks",
		);
	$drinktext['schemas'] = $schemas;
	return $drinktext;
}


// Support functions
function drinks_editor(){
	global $mostrecentmodule;
	if (!get_module_pref("canedit")) check_su_access(SU_EDIT_USERS);

	page_header("Drink Editor");
	require_once("lib/superusernav.php");
	superusernav();
	output::addnav("Drink Editor");
	output::addnav("Add a drink","runmodule.php?module=drinks&act=editor&op=add&admin=true");
	$op = http::httpget('op');
	$drinkid = http::httpget('drinkid');
	$header = "";
	if ($op != "") {
		output::addnav("Drink Editor Main","runmodule.php?module=drinks&act=editor&admin=true");
		if ($op == 'add') {
			$header = translator::translate_inline("Adding a new drink");
		} else if ($op == 'edit') {
			$header = translator::translate_inline("Editing a drink");
		}
	} else {
		$header = translator::translate_inline("Current drinks");
	}
	output_notl("`&<h3>$header`0</h3>", true);
	$drinksarray=array(
		"Drink,title",
		"drinkid"=>"Drink ID,hidden",
		"name"=>"Drink Name",
		"costperlevel"=>"Cost per level,int",
		"hpchance"=>"Chance of modifying HP (see below),range,0,10,1",
		"turnchance"=>"Chance of modifying turns (see below),range,0,10,1",
		"alwayshp"=>"Always modify hitpoints,bool",
		"alwaysturn"=>"Always modify turns,bool",
		"drunkeness"=>"Drunkeness,range,1,100,1",
		"harddrink"=>"Is drink hard alchohol?,bool",
		"hpmin"=>"Min HP to add (see below),range,-20,20,1",
		"hpmax"=>"Max HP to add (see below),range,-20,20,1",
		"hppercent"=>"Modify HP by some percent (see below),range,-25,25,5",
		"turnmin"=>"Min turns to add (see below),range,-5,5,1",
		"turnmax"=>"Max turns to add (see below),range,-5,5,1",
		"remarks"=>"Remarks",
		"buffname"=>"Name of the buff",
		"buffrounds"=>"Rounds buff lasts,range,1,20,1",
		"buffroundmsg"=>"Message each round of buff",
		"buffwearoff"=>"Message when buff wears off",
		"buffatkmod"=>"Attack modifier of buff",
		"buffdefmod"=>"Defense modifier of buff",
		"buffdmgmod"=>"Damage modifier of buff",
		"buffdmgshield"=>"Damage shield modifier of buff",
		"buffeffectfailmsg"=>"Effect failure message (see below)",
		"buffeffectnodmgmsg"=>"No damage message (see below)",
		"buffeffectmsg"=>"Effect message (see below)",
	);
	if($op=="del"){
		$sql = "DELETE FROM " . db_prefix("drinks") . " WHERE drinkid='$drinkid'";
		module_delete_objprefs('drinks', $drinkid);
		db_query($sql);
		$op = "";
		httpset('op', "");
	}
	if($op=="save"){
		$subop = http::httpget("subop");
		if ($subop=="") {
			$drinkid = httppost("drinkid");
			list($sql, $keys, $vals) = postparse($drinksarray);
			if ($drinkid > 0) {
				$sql = "UPDATE " . db_prefix("drinks") . " SET $sql WHERE drinkid='$drinkid'";
			} else {
				$sql = "INSERT INTO " . db_prefix("drinks") . " ($keys) VALUES ($vals)";
			}
			db_query($sql);
			if (db_affected_rows()> 0) {
				output::doOutput("`^Drink saved!");
			} else {
				$str = db_error();
				if ($str == "") {
					output::doOutput("`^Drink not saved: no changes detected.");
				} else {
					output::doOutput("`^Drink not saved: `\$%s`0", $sql);
				}
			}
		} elseif ($subop == "module") {
			$drinkid = http::httpget("drinkid");
			// Save module settings
			$module = http::httpget("editmodule");
			// This should obey the same rules as the configuration editor
			// So disabling
			//$sql = "DELETE FROM " . db_prefix("module_objprefs") . " WHERE objtype='drinks' AND objid='$drinkid' AND modulename='$module'";
			//db_query($sql);
			$post = httpallpost();
			reset($post);
			while(list($key, $val)=each($post)) {
				set_module_objpref("drinks", $drinkid,$key, $val, $module);
			}
			output::doOutput("`^Saved.");
		}
		if ($drinkid) {
			$op = "edit";
			httpset("drinkid", $drinkid, true);
		} else {
			$op = "";
		}
		httpset('op', $op);
	}
	if ($op == "activate") {
		$sql = "UPDATE " . db_prefix("drinks") . " SET active=1 WHERE drinkid='$drinkid'";
		db_query($sql);
		$op = "";
		httpset('op', "");
	}
	if ($op == "deactivate") {
		$sql = "UPDATE " . db_prefix("drinks") . " SET active=0 WHERE drinkid='$drinkid'";
		db_query($sql);
		$op = "";
		httpset('op', "");
	}
	if ($op==""){
		$op = translator::translate_inline("Ops");
		$id = translator::translate_inline("Id");
		$nm = translator::translate_inline("Name");
		$dkn = translator::translate_inline("Drunkeness");
		$hard = translator::translate_inline("Hard Alchohol?");
		$edit = translator::translate_inline("Edit");
		$deac = translator::translate_inline("Deactivate");
		$act = translator::translate_inline("Activate");
		$conf = translator::translate_inline("Are you sure you wish to delete this drink?");
		$del = translator::translate_inline("Del");
		rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>");
		rawoutput("<tr class='trhead'>");
		rawoutput("<td>$op</td><td>$id</td><td>$nm</td><td>$dkn</td><td>$hard</td>");
		rawoutput("</tr>");
		$sql = "SELECT drinkid,active,name,drunkeness,harddrink FROM " . db_prefix("drinks") . " ORDER BY drinkid";
		$result= db_query($sql);
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			$id = $row['drinkid'];
			rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
			rawoutput("<td nowrap>[ <a href='runmodule.php?module=drinks&act=editor&op=edit&drinkid=$id&admin=true'>$edit</a>");
			output::addnav("","runmodule.php?module=drinks&act=editor&op=edit&drinkid=$id&admin=true");
			if ($row['active']) {
				rawoutput(" | <a href='runmodule.php?module=drinks&act=editor&op=deactivate&drinkid=$id&admin=true'>$deac</a>");
				output::addnav("","runmodule.php?module=drinks&act=editor&op=deactivate&drinkid=$id&admin=true");
			} else {
				rawoutput(" | <a href='runmodule.php?module=drinks&act=editor&op=activate&drinkid=$id&admin=true'>$act</a>");
				output::addnav("","runmodule.php?module=drinks&act=editor&op=activate&drinkid=$id&admin=true");
			}

			rawoutput(" | <a href='runmodule.php?module=drinks&act=editor&op=del&drinkid=$id&admin=true' onClick='return confirm(\"$conf\");'>$del</a> ]</td>");
			output::addnav("","runmodule.php?module=drinks&act=editor&op=del&drinkid=$id&admin=true");
			output_notl("<td>`^%s</td>`0", $id, true);
			output_notl("<td>`&%s`0</td>", $row['name'], true);
			output_notl("<td>`^%s`0</td>", $row['drunkeness'], true);
			$hard = translator::translate_inline("`^No");
			if ($row['harddrink']) $hard = translator::translate_inline("`\$Yes");
			output_notl("<td>%s`0</td>", $hard, true);
			rawoutput("</tr>");
		}
		rawoutput("</table>");
	}
	$subop= http::httpget("subop");
	if($op=="edit"){
		output::addnav("Drink properties", "runmodule.php?module=drinks&act=editor&op=edit&drinkid=$drinkid&admin=true");
		module_editor_navs("prefs-drinks", "runmodule.php?module=drinks&act=editor&drinkid=$drinkid&op=edit&subop=module&editmodule=");
		if ($subop=="module") {
			$module = http::httpget("editmodule");
			$oldmodule = $mostrecentmodule;
			rawoutput("<form action='runmodule.php?module=drinks&act=editor&op=save&subop=module&editmodule=$module&drinkid=$drinkid&admin=true' method='POST'>");
			module_objpref_edit('drinks', $module, $drinkid);
			$mostrecentmodule = $oldmodule;
			rawoutput("</form>");
			output::addnav("", "runmodule.php?module=drinks&act=editor&op=save&subop=module&editmodule=$module&drinkid=$drinkid&admin=true");
		} elseif ($subop=="") {
				$sql = "SELECT * FROM " . db_prefix("drinks") . " WHERE drinkid='".http::httpget('drinkid')."'";
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
		}
	}elseif ($op=="add"){
		/* We're adding a new drink, make an empty row */
		$row = array();
		$row['drinkid'] = 0;
	}

	if (($op == "edit" || $op == "add") && $subop=="") {
		rawoutput("<form action='runmodule.php?module=drinks&act=editor&op=save&admin=true' method='POST'>");
		output::addnav("","runmodule.php?module=drinks&act=editor&op=save&admin=true");
		showform($drinksarray,$row);
		rawoutput("</form>");
		output::doOutput("`\$NOTE:`7 Make sure that you know what you are doing when modifying or adding drinks.`n");
		output::doOutput("Just because the drinks have a lot of options, doesn't mean you have to use all of them`n`n");
		output::doOutput("`2Drink ID: `7This field is used internally and should be unique.`n");
		output::doOutput("`2Name: `7The name of the drink the user will see.`n");
		output::doOutput("`2Cost per level: `7This value times the users level is the drink cost.`n");
		output::doOutput("`2Chance of modifying HP: `7If set, this is the number of chances out of the total of this and the turn chance for HP getting modified.`n");
		output::doOutput("`2Chance of modifying turns: `7If set, this is the number of chances out of the total of this and the HP chance for turns getting modified.`n");
		output::doOutput("`2Always modify HP: `7If set, hitpoints will be modified.  Should not be set alongside HP chance above.`n");
		output::doOutput("`2Always modify turns: `7If set, turns will be modified.  Should not be set alongside turn chance above.`n");
		output::doOutput("`2Drunkeness: `7How drunk will this make the player.`n");
		output::doOutput("`2Hard Drink: `7Users are only allowed a certain number of hard drinks per day regardless of drunkeness.`n");
		output::doOutput("`2Min HP to add: `7If we are modifying hitpoints, and if HP percent isn't set, use this and the HP max value to pick a random amount of HP to add.  Can be negative.`n");
		output::doOutput("`2Max HP to add: `7If we are modifying hitpoints and if HP percent isn't set, use this and the HP min value to pick a random amount of HP to add.  Can be negative.`n");
		output::doOutput("`2HP percent: `7If we are modifying hitpoints and if this is set, the users hitpoints are modified by this percentage.  Can be negative.`n");
		output::doOutput("`2Min turns to add: `7If we are modifying turns, use this and the turn max value to pick a random amount of turns to add.  Can be negative.`n");
		output::doOutput("`2Max turns to add: `7If we are modifying turns, use this and the turn min value to pick a random amount of turns to add.  Can be negative.`n");
		output::doOutput("`2Remarks: `7Text displayed to the user when they order the drink.`n");
		output::doOutput("`2Buff name: `7What is this buff called.`n");
		output::doOutput("`2Buff rounds: `7How many rounds this buff lasts.`n");
		output::doOutput("`2Buff round message: `7What message should show as each round occurs.`n");
		output::doOutput("`2Buff wearoff: `7What message is shown when this buff wears off.`n");
		output::doOutput("`2Buff attack modifier: `7Multiplier to modify attack points by? 1.0 is no modification, 2.0 doubles their attack points.`n");
		output::doOutput("`2Buff defense modifier: `7Multiplier to modify defense points by? 1.0 is no modification, 2.0 doubles their defense points.`n");
		output::doOutput("`2Buff damage modifier: `7Multiplier to modify damage by? 1.0 is no modification, 2.0 doubles their damage points. This is `\$VERY POTENT`7!`n");
		output::doOutput("`2Buff damage shield modifier: `7When you are hit, deals damage to your opponent based on damage done to you. 1.0 deals identical damage, 2.0 deals double damage back to the opponent.`n");
		output::doOutput("`2Effect failure message: Message if this buff fails. (Only used with damage shield)`n");
		output::doOutput("`2Effect no damage message: Message if no damage is done. (Only used with damage shield)`n");
		output::doOutput("`2Effect message: What shows when this buff has an effect. (Only used with damage shield)`n`n");
	}
	page_footer();
}
