<?php
	page_header("Clan Hall for %s",  full_sanitize($claninfo['clanname']));
	output::addnav("Clan Options");
	if ($op==""){
		require_once("lib/clan/clan_default.php");
	}elseif ($op=="motd"){
		require_once("lib/clan/clan_motd.php");
	}elseif ($op=="membership"){
		require_once("lib/clan/clan_membership.php");
	}elseif ($op=="withdrawconfirm"){
		output::doOutput("Are you sure you want to withdraw from your clan?");
		output::addnav("Withdraw?");
		output::addnav("No","clan.php");
		output::addnav("!?Yes","clan.php?op=withdraw");
	}elseif ($op=="withdraw"){
		require_once("lib/clan/clan_withdraw.php");
	}
