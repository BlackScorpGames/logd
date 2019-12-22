<?php

namespace blackscorp\logd\Mount;

use translator;
use http;


class MountController 
{

    private $mountRepository = null;
    
    public function __construct(MountRepository $mountRepository) 
    {
        $this->mountRepository = $mountRepository;
    }


    private function mountform(MountEntity $mount){	

	rawoutput("<form action='mounts.php?op=save&id={$mount->getID()}' method='POST'>");
	rawoutput("<input type='hidden' name='mount[mountactive]' value=\"".$mount->getActive()."\">");
	addnav("","mounts.php?op=save&id={$mount->getID()}");
	rawoutput("<table>");
	rawoutput("<tr><td nowrap>");
	output("Mount Name:");
	rawoutput("</td><td><input name='mount[mountname]' value=\"".htmlentities($mount->getName(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\"></td></tr>");
	rawoutput("<tr><td nowrap>");
	output("Mount Description:");
	rawoutput("</td><td><input name='mount[mountdesc]' value=\"".htmlentities($mount->getDesc(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\"></td></tr>");
	rawoutput("<tr><td nowrap>");
	output("Mount Category:");
	rawoutput("</td><td><input name='mount[mountcategory]' value=\"".htmlentities($mount->getCategory(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\"></td></tr>");
	rawoutput("<tr><td nowrap>");
	output("Mount Availability:");
	rawoutput("</td><td nowrap>");
	// Run a modulehook to find out where stables are located.  By default
	// they are located in 'Degolburg' (ie, getgamesetting('villagename'));
	// Some later module can remove them however.
	$vname = getsetting('villagename', LOCATION_FIELDS);
	$locs = array($vname => translator::sprintf_translate("The Village of %s", $vname));
	$locs = modulehook("stablelocs", $locs);
	$locs['all'] = translator::translate_inline("Everywhere");
	ksort($locs);
	reset($locs);
	rawoutput("<select name='mount[mountlocation]'>");
	foreach($locs as $loc=>$name) {
		rawoutput("<option value='$loc'".($mount->getLocation()==$loc?" selected":"").">$name</option>");
	}

	rawoutput("<tr><td nowrap>");
	output("Mount Cost (DKs):");
	rawoutput("</td><td><input name='mount[mountdkcost]' value=\"".htmlentities($mount->getDkCost(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\"></td></tr>");
	rawoutput("<tr><td nowrap>");
	output("Mount Cost (Gems):");
	rawoutput("</td><td><input name='mount[mountcostgems]' value=\"".htmlentities($mount->getCostGems(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\"></td></tr>");
	rawoutput("<tr><td nowrap>");
	output("Mount Cost (Gold):");
	rawoutput("</td><td><input name='mount[mountcostgold]' value=\"".htmlentities($mount->getCostGold(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\"></td></tr>");
	rawoutput("<tr><td nowrap>");
	output("Mount Feed Cost`n(Gold per level):");
	rawoutput("</td><td><input name='mount[mountfeedcost]' value=\"".htmlentities($mount->getFeedCost(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\"></td></tr>");
	rawoutput("<tr><td nowrap>");
	output("Delta Forest Fights:");
	rawoutput("</td><td><input name='mount[mountforestfights]' value=\"".htmlentities($mount->getForestFights(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='5'></td></tr>");
	rawoutput("<tr><td nowrap>");
	output("`bMount Messages:`b");
	rawoutput("</td><td></td></tr><tr><td nowrap>");
	output("New Day:");
	rawoutput("</td><td><input name='mount[newday]' value=\"".htmlentities($mount->getNewDay(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='40'></td></tr>");
	rawoutput("<tr><td nowrap>");
	output("Full Recharge:");
	rawoutput("</td><td><input name='mount[recharge]' value=\"".htmlentities($mount->getRecharge(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='40'></td></tr>");
	rawoutput("<tr><td nowrap>");
	output("Partial Recharge:");
	rawoutput("</td><td><input name='mount[partrecharge]' value=\"".htmlentities($mount->getPartRecharge(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='40'></td></tr>");
	rawoutput("<tr><td valign='top' nowrap>");
	output("Mount Buff:");
	rawoutput("</td><td>");
	output("Buff name:");
	rawoutput("<input name='mount[mountbuff][name]' value=\"".htmlentities($mount->getBuff()->getName(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("`bBuff Messages:`b`n");
	output("Each round:");
	rawoutput("<input name='mount[mountbuff][roundmsg]' value=\"".htmlentities($mount->getBuff()->getRoundMsg(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("Wear off:");
	rawoutput("<input name='mount[mountbuff][wearoff]' value=\"".htmlentities($mount->getBuff()->getWearoff(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("Effect:");
	rawoutput("<input name='mount[mountbuff][effectmsg]' value=\"".htmlentities($mount->getBuff()->getEffectMsg(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("Effect No Damage:");
	rawoutput("<input name='mount[mountbuff][effectnodmgmsg]' value=\"".htmlentities($mount->getBuff()->getEffectNoDmgMsg(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("Effect Fail:");
	rawoutput("<input name='mount[mountbuff][effectfailmsg]' value=\"".htmlentities($mount->getBuff()->getEffectFailMsg(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("(message replacements: {badguy}, {goodguy}, {weapon}, {armor}, {creatureweapon}, and where applicable {damage}.)`n");
	output("`n`bEffects:`b`n");
	output("Rounds to last (from new day):");
	rawoutput("<input name='mount[mountbuff][rounds]' value=\"".htmlentities($mount->getBuff()->getRounds(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("Player Atk mod:");
	rawoutput("<input name='mount[mountbuff][atkmod]' value=\"".htmlentities($mount->getBuff()->getAtkMod(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'>");
	output("(multiplier)`n");
	output("Player Def mod:");
	rawoutput("<input name='mount[mountbuff][defmod]' value=\"".htmlentities($mount->getBuff()->getDefMod(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'>");
	output("(multiplier)`n");
	output("Player is invulnerable (1 = yes, 0 = no):");
	rawoutput("<input name='mount[mountbuff][invulnerable]' value=\"".htmlentities($mount->getBuff()->getInvulnerable(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size=50><br/>");
	output("Regen:");
	rawoutput("<input name='mount[mountbuff][regen]' value=\"".htmlentities($mount->getBuff()->getRegen(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("Minion Count:");
	rawoutput("<input name='mount[mountbuff][minioncount]' value=\"".htmlentities($mount->getBuff()->getMinionCount(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");

	output("Min Badguy Damage:");
	rawoutput("<input name='mount[mountbuff][minbadguydamage]' value=\"".htmlentities($mount->getBuff()->getMinBadGuyDamage(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("Max Badguy Damage:");
	rawoutput("<input name='mount[mountbuff][maxbadguydamage]' value=\"".htmlentities($mount->getBuff()->getMaxBadGuyDamage(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("Min Goodguy Damage:");
	rawoutput("<input name='mount[mountbuff][mingoodguydamage]' value=\"".htmlentities($mount->getBuff()->getMinGoodGuyDamage(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");
	output("Max Goodguy Damage:");
	rawoutput("<input name='mount[mountbuff][maxgoodguydamage]' value=\"".htmlentities($mount->getBuff()->getMaxGoodGuyDamage(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'><br/>");

	output("Lifetap:");
	rawoutput("<input name='mount[mountbuff][lifetap]' value=\"".htmlentities($mount->getBuff()->getLifeTap(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'>");
	output("(multiplier)`n");
	output("Damage shield:");
	rawoutput("<input name='mount[mountbuff][damageshield]' value=\"".htmlentities($mount->getBuff()->getDamageShield(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'>");
	output("(multiplier)`n");
	output("Badguy Damage mod:");
	rawoutput("<input name='mount[mountbuff][badguydmgmod]' value=\"".htmlentities($mount->getBuff()->getBadGuyDmgMod(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'>");
	output("(multiplier)`n");
	output("Badguy Atk mod:");
	rawoutput("<input name='mount[mountbuff][badguyatkmod]' value=\"".htmlentities($mount->getBuff()->getBadGuyAtkMod(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'>");
	output("(multiplier)`n");
	output("Badguy Def mod:");
	rawoutput("<input name='mount[mountbuff][badguydefmod]' value=\"".htmlentities($mount->getBuff()->getBadGuyDefMod(), ENT_COMPAT, getsetting("charset", "UTF-8"))."\" size='50'>");
	output("(multiplier)`n");
	output("`bOn Dynamic Buffs`b`n");
	output("`@In the above, for most fields, you can choose to enter valid PHP code, substituting <fieldname> for fields in the user's account table.`n");
	output("Examples of code you might enter:`n");
	output("`^<charm>`n");
	output("round(<maxhitpoints>/10)`n");
	output("round(<level>/max(<gems>,1))`n");
	output("`@Fields you might be interested in for this: `n");
	output_notl("`3name, sex `7(0=male 1=female)`3, specialty `7(DA=darkarts MP=mystical TS=thief)`3,`n");
	output_notl("experience, gold, weapon `7(name)`3, armor `7(name)`3, level,`n");
	output_notl("defense, attack, alive, goldinbank,`n");
	output_notl("spirits `7(-2 to +2 or -6 for resurrection)`3, hitpoints, maxhitpoints, gems,`n");
	output_notl("weaponvalue `7(gold value)`3, armorvalue `7(gold value)`3, turns, title, weapondmg, armordef,`n");
	output_notl("age `7(days since last DK)`3, charm, playerfights, dragonkills, resurrections `7(times died since last DK)`3,`n");
	output_notl("soulpoints, gravefights, deathpower `7(%s favor)`3,`n", getsetting("deathoverlord", '`$Ramius'));
	output_notl("race, dragonage, bestdragonage`n`n");
	output("You can also use module preferences by using <modulename|preference> (for instance '<specialtymystic|uses>' or '<drinks|drunkeness>'`n`n");
	output("`@Finally, starting a field with 'debug:' will enable debug output for that field to help you locate errors in your implementation.");
	output("While testing new buffs, you should be sure to debug fields before you release them on the world, as the PHP script will otherwise throw errors to the user if you have any, and this can break the site at various spots (as in places that redirects should happen).");
	rawoutput("</td></tr></table>");
	$save = translator::translate_inline("Save");
	rawoutput("<input type='submit' class='button' value='$save'></form>");
}

    public function deactivateMount(MountEntity $mount)
    {
        $mount->setActive(0);        
        $this->mountRepository->update($mount);
	$this->defaultAction();
    }
    
    public function activateMount(MountEntity $mount)
    {
        $mount->setActive(1);        
        $this->mountRepository->update($mount);
	$this->defaultAction();
    }
    
    public function deleteMount(int $mountID)
    {
        //refund for anyone who has a mount of this type.
	$sql = "SELECT * FROM ".db_prefix("mounts")." WHERE mountid='$mountID'";
	$result = db_query_cached($sql, "mountdata-$mountID", 3600);
	$row = db_fetch_assoc($result);
	$sql = "UPDATE ".db_prefix("accounts")." SET gems=gems+{$row['mountcostgems']}, goldinbank=goldinbank+{$row['mountcostgold']}, hashorse=0 WHERE hashorse={$row['mountid']}";
	db_query($sql);
	//drop the mount.
	$sql = "DELETE FROM " . db_prefix("mounts") . " WHERE mountid='$mountID'";
	db_query($sql);
	module_delete_objprefs('mounts', $mountID);
	$this->defaultAction();
    }
    
    public function giveMountUser(int $mountID)
    {
        GLOBAL $session;        
        $session['user']['hashorse'] = $mountID;
	// changed to make use of the cached query
	$sql = "SELECT * FROM ".db_prefix("mounts")." WHERE mountid='$mountID'";
	$result = db_query_cached($sql, "mountdata-$mountID", 3600);
	$row = db_fetch_assoc($result);
        //$mount = $this->mountRepository->findMount($mountID);
	$buff = unserialize($row['mountbuff']);       
        //$buff = $mount->getBuff()->getMountBuffAsArray();
	if ($buff['schema'] == "") $buff['schema'] = "mounts";
	apply_buff("mount",$buff);
        $this->defaultAction();
    }
    
    public function addMount(MountEntity $mount)
    {        
        output("Add a mount:`n");
	addnav("Mount Editor Home","mounts.php");
	$this->mountform($mount);
    }
    
    public function editMount(int $mountID)
    {        
        addnav("Mount Editor Home","mounts.php");
	//$sql = "SELECT * FROM " . db_prefix("mounts") . " WHERE mountid='$mountID'";
	//$result = db_query_cached($sql, "mountdata-$mountID", 3600);
        $mount = $this->mountRepository->findMount($mountID);
	if (!$mount){
		output("`iThis mount was not found.`i");
	}else{
		addnav("Mount properties", "mounts.php?op=edit&id=$mountID");
		module_editor_navs("prefs-mounts", "mounts.php?op=edit&subop=module&id=$mountID&module=");
		$subop=http::httpget("subop");
		if ($subop=="module") {
			$module = http::httpget("module");
			rawoutput("<form action='mounts.php?op=save&subop=module&id=$mountID&module=$module' method='POST'>");
			module_objpref_edit("mounts", $module, $$mountID);
			rawoutput("</form>");
			addnav("", "mounts.php?op=save&subop=module&id=$mountID&module=$module");
		} else {
			output("Mount Editor:`n");
			//$row = db_fetch_assoc($result);
			//$row['mountbuff']=unserialize($row['mountbuff']);
			$this->mountform($mount);
		}
	}
        
    }
    
    public function saveMount(int $mountID)
    {
        $subop = http::httpget("subop");
	if ($subop == "") {
		$buff = array();
		$mount = httppost('mount');
		if ($mount) {
			reset($mount['mountbuff']);
			while (list($key,$val)=each($mount['mountbuff'])){
				if ($val>""){
					$buff[$key]=stripslashes($val);
				}
			}
			$buff['schema']="mounts";
			httppostset('mount', $buff, 'mountbuff');

			list($sql, $keys, $vals) = postparse(false, 'mount');
			if ($mountID>""){
				$sql="UPDATE " . db_prefix("mounts") .
					" SET $sql WHERE mountid='$mountID'";
			}else{
				$sql="INSERT INTO " . db_prefix("mounts") .
					" ($keys) VALUES ($vals)";
			}
			db_query($sql);
			invalidatedatacache("mountdata-$mountID");
			if (db_affected_rows()>0){
				output("`^Mount saved!`0`n");
			}else{
				output("`^Mount `\$not`^ saved: `\$%s`0`n", $sql);
			}
		}
	} elseif ($subop=="module") {
		// Save modules settings
		$module = http::httpget("module");
		$post = httpallpost();
		reset($post);
		while(list($key, $val) = each($post)) {
			set_module_objpref("mounts", $mountID, $key, $val, $module);
		}
		output("`^Saved!`0`n");
	}
	if ($mountID) {
		$this->editMount($mountID);
	} else {
		$this->defaultAction();
	}
    }

    public function defaultAction()
    {
        $sql = "SELECT count(acctid) AS c, hashorse FROM ".db_prefix("accounts")." GROUP BY hashorse";
	$result = db_query($sql);
	$mounts = array();
	while ($row = db_fetch_assoc($result)){
		$mounts[$row['hashorse']] = $row['c'];
	}
	rawoutput("<script language='JavaScript'>
	function getUserInfo(id,divid){
		var filename='mounts.php?op=xml&id='+id;
		var xmldom;
		if (document.implementation && document.implementation.createDocument){
			// Mozilla
			xmldom = document.implementation.createDocument('','',null);
		} else if (window.ActiveXObject) {
			// IE
			xmldom = new ActiveXObject('Microsoft.XMLDOM');
		}
		xmldom.async=false;
		xmldom.load(filename);
		var output='';
		for (var x=0; x<xmldom.documentElement.childNodes.length; x++) {
			output = output + unescape(xmldom.documentElement.childNodes[x].getAttribute('name').replace(/\\+/g, ' ')) + '<br />';
		}
		document.getElementById('mountusers'+divid).innerHTML=output;
	}
	</script>");        
	$sql = "SELECT * FROM " . db_prefix("mounts") . " ORDER BY mountcategory, mountcostgems, mountcostgold";
	$ops = translator::translate_inline("Ops");
	$name = translator::translate_inline("Name");
	$cost = translator::translate_inline("Cost");
	$feat = translator::translate_inline("Features");
	$owners = translator::translate_inline("Owners");

	$edit = translator::translate_inline("Edit");
	$give = translator::translate_inline("Give");
	$del = translator::translate_inline("Del");
	$deac = translator::translate_inline("Deactivate");
	$act = translator::translate_inline("Activate");

	$conf = translator::translate_inline("There are %s user(s) who own this mount, are you sure you wish to delete it?");

	rawoutput("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>");
	rawoutput("<tr class='trhead'><td nowrap>$ops</td><td>$name</td><td>$cost</td><td>$feat</td><td nowrap>$owners</td></tr>");
	$result = db_query($sql);
	$cat = "";
	$count=0;

	$number=db_num_rows($result);
	for ($i=0;$i<$number;$i++){
		$row = db_fetch_assoc($result);
		if ($cat!=$row['mountcategory']){
			rawoutput("<tr class='trlight'><td colspan='5'>");
			output("Category: %s", $row['mountcategory']);
			rawoutput("</td></tr>");
			$cat = $row['mountcategory'];
			$count=0;
		}
		if (isset($mounts[$row['mountid']])) {
			$mounts[$row['mountid']] = (int)$mounts[$row['mountid']];
		} else {
			$mounts[$row['mountid']] = 0;
		}
		rawoutput("<tr class='".($count%2?"trlight":"trdark")."'>");
		rawoutput("<td nowrap>[ <a href='mounts.php?op=edit&id={$row['mountid']}'>$edit</a> |");
		addnav("","mounts.php?op=edit&id={$row['mountid']}");
		rawoutput("<a href='mounts.php?op=give&id={$row['mountid']}'>$give</a> |",true);
		addnav("","mounts.php?op=give&id={$row['mountid']}");
		if ($row['mountactive']){
			rawoutput("$del |");
		}else{
			$mconf = sprintf($conf, $mounts[$row['mountid']]);
			rawoutput("<a href='mounts.php?op=del&id={$row['mountid']}' onClick=\"return confirm('$mconf');\">$del</a> |");
			addnav("","mounts.php?op=del&id={$row['mountid']}");
		}
		if ($row['mountactive']) {
			rawoutput("<a href='mounts.php?op=deactivate&id={$row['mountid']}'>$deac</a> ]</td>");
			addnav("","mounts.php?op=deactivate&id={$row['mountid']}");
		}else{
			rawoutput("<a href='mounts.php?op=activate&id={$row['mountid']}'>$act</a> ]</td>");
			addnav("","mounts.php?op=activate&id={$row['mountid']}");
		}
		rawoutput("<td>");
		output_notl("`&%s`0", $row['mountname']);
		rawoutput("</td><td>");
		output("`%%s gems`0, `^%s gold`0",$row['mountcostgems'], $row['mountcostgold']);
		rawoutput("</td><td>");
		$features = array("FF"=>$row['mountforestfights']);
		$args = array("id"=>$row['mountid'],"features"=>&$features);
		$args = modulehook("mountfeatures", $args);
		reset($features);
		$mcount = 1;
		$max = count($features);
		foreach ($features as $fname=>$fval) {
			$fname = translator::translate_inline($fname);
			output_notl("%s: %s%s", $fname,  $fval, ($mcount==$max?"":", "));
			$mcount++;
		}
		rawoutput("</td><td nowrap>");
		$file = "mounts.php?op=xml&id=".$row['mountid'];
		rawoutput("<div id='mountusers$i'><a href='$file' target='_blank' onClick=\"getUserInfo('".$row{'mountid'}."', $i); return false\">");
 		output_notl("`#%s`0", $mounts[$row['mountid']]);
		addnav("", $file);
		rawoutput("</a></div>");
		rawoutput("</td></tr>");
		$count++;
	}
	rawoutput("</table>");
	output("`nIf you wish to delete a mount, you have to deactivate it first.");
	output("If there are any owners of the mount when it is deleted, they will no longer have a mount, but they will get a FULL refund of the price of the mount at the time of deletion.");
    }
}
