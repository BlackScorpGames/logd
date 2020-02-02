<?php
		page_header("Update Clan Description / MoTD");
		output::addnav("Clan Options");
		if ($session['user']['clanrank']>=CLAN_OFFICER){
			$clanmotd = substr(httppost('clanmotd'),0,4096);
			if (httppostisset('clanmotd') &&
					stripslashes($clanmotd)!=$claninfo['clanmotd']){
				$sql = "UPDATE " . db_prefix("clans") . " SET clanmotd='$clanmotd',motdauthor={$session['user']['acctid']} WHERE clanid={$claninfo['clanid']}";
				db_query($sql);
				invalidatedatacache("clandata-{$claninfo['clanid']}");
				$claninfo['clanmotd']=stripslashes($clanmotd);
				output::doOutput("Updating MoTD`n");
				$claninfo['motdauthor']=$session['user']['acctid'];
			}
			$clandesc = httppost('clandesc');
			if (httppostisset('clandesc') &&
					stripslashes($clandesc)!=$claninfo['clandesc'] &&
					$claninfo['descauthor']!=4294967295){
				$sql = "UPDATE " . db_prefix("clans") . " SET clandesc='".addslashes(substr(stripslashes($clandesc),0,4096))."',descauthor={$session['user']['acctid']} WHERE clanid={$claninfo['clanid']}";
				db_query($sql);
				invalidatedatacache("clandata-{$claninfo['clanid']}");
				output::doOutput("Updating description`n");
				$claninfo['clandesc']=stripslashes($clandesc);
				$claninfo['descauthor']=$session['user']['acctid'];
			}
			$customsay = httppost('customsay');
			if (httppostisset('customsay') && $customsay!=$claninfo['customsay'] && $session['user']['clanrank']>=CLAN_LEADER){
				$sql = "UPDATE " . db_prefix("clans") . " SET customsay='$customsay' WHERE clanid={$claninfo['clanid']}";
				db_query($sql);
				invalidatedatacache("clandata-{$claninfo['clanid']}");
				output::doOutput("Updating custom say line`n");
				$claninfo['customsay']=stripslashes($customsay);
			}
			$sql = "SELECT name FROM " . db_prefix("accounts") . " WHERE acctid={$claninfo['motdauthor']}";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$motdauthname = $row['name'];

			$sql = "SELECT name FROM " . db_prefix("accounts") . " WHERE acctid={$claninfo['descauthor']}";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$descauthname = $row['name'];

			output::doOutput("`&`bCurrent MoTD:`b `#by %s`2`n",$motdauthname);
			output_notl(nltoappon($claninfo['clanmotd'])."`n");
			output::doOutput("`&`bCurrent Description:`b `#by %s`2`n",$descauthname);
			output_notl(nltoappon($claninfo['clandesc'])."`n");

			rawoutput("<form action='clan.php?op=motd' method='POST'>");
			output::addnav("","clan.php?op=motd");
			output::doOutput("`&`bMoTD:`b `7(4096 chars)`n");
			rawoutput("<textarea name='clanmotd' cols='50' rows='10' class='input' style='width: 66%'>".htmlentities($claninfo['clanmotd'], ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"))."</textarea><br>");
			output::doOutput("`n`&`bDescription:`b `7(4096 chars)`n");
			$blocked = translator::translate_inline("Your clan has been blocked from posting a description.`n");
			if ($claninfo['descauthor']==INT_MAX){
				output_notl($blocked);
			}else{
				rawoutput("<textarea name='clandesc' cols='50' rows='10' class='input' style='width: 66%'>".htmlentities($claninfo['clandesc'], ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"))."</textarea><br>");
			}
			if ($session['user']['clanrank']>=CLAN_LEADER){
				output::doOutput("`n`&`bCustom Talk Line`b `7(blank means \"says\" -- 15 chars max)`n");
				rawoutput("<input name='customsay' value=\"".htmlentities($claninfo['customsay'], ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"))."\" class='input' maxlength=\"15\"><br/>");
			}
			$save = translator::translate_inline("Save");
			rawoutput("<input type='submit' class='button' value='$save'>");
			rawoutput("</form>");
		}else{
			output::doOutput("You do not have authority to change your clan's motd or description.");
		}
		output::addnav("Return to your clan hall","clan.php");
