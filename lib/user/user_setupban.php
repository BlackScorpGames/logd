<?php
$sql = "SELECT name,lastip,uniqueid FROM " . db_prefix("accounts") . " WHERE acctid=\"$userid\"";
$result = db_query($sql);
$row = db_fetch_assoc($result);
if ($row['name']!="")
	output::doOutput("Setting up ban information based on `\$%s`0", $row['name']);
rawoutput("<form action='user.php?op=saveban' method='POST'>");
output::doOutput("Set up a new ban by IP or by ID (recommended IP, though if you have several different users behind a NAT, you can try ID which is easily defeated)`n");
rawoutput("<input type='radio' value='ip' id='ipradio' name='type' checked>");
output::doOutput("IP: ");
rawoutput("<input name='ip' id='ip' value=\"".HTMLEntities($row['lastip'], ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"))."\">");
output_notl("`n");
rawoutput("<input type='radio' value='id' name='type'>");
output::doOutput("ID: ");
rawoutput("<input name='id' value=\"".HTMLEntities($row['uniqueid'], ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"))."\">");
output::doOutput("`nDuration: ");
rawoutput("<input name='duration' id='duration' size='3' value='14'>");
output::doOutput("Days (0 for permanent)`n");
$reason = http::httpget("reason");
if ($reason == "")
	$reason=translator::translate_inline("Don't mess with me.");
output::doOutput("Reason for the ban: ");
rawoutput("<input name='reason' size=50 value=\"$reason\">");
output_notl("`n");
$pban = translator::translate_inline("Post ban");
$conf = translator::translate_inline("Are you sure you wish to issue a permanent ban?");
rawoutput("<input type='submit' class='button' value='$pban' onClick='if (document.getElementById(\"duration\").value==0) {return confirm(\"$conf\");} else {return true;}'>");
rawoutput("</form>");
output::doOutput("For an IP ban, enter the beginning part of the IP you wish to ban if you wish to ban a range, or simply a full IP to ban a single IP`n`n");
addnav("","user.php?op=saveban");
if ($row['name']!=""){
	$id = $row['uniqueid'];
	$ip = $row['lastip'];
	$name = $row['name'];
	output::doOutput("`0To help locate similar users to `@%s`0, here are some other users who are close:`n", $name);
	output::doOutput("`bSame ID (%s):`b`n", $id);
	$sql = "SELECT name, lastip, uniqueid, laston, gentimecount FROM " . db_prefix("accounts") . " WHERE uniqueid='".addslashes($id)."' ORDER BY lastip";
	$result = db_query($sql);
	while ($row = db_fetch_assoc($result)){
		output::doOutput("`0� (%s) `%%s`0 - %s hits, last: %s`n", $row['lastip'],
				$row['name'], $row['gentimecount'],
				reltime(strtotime($row['laston'])));
	}
	output_notl("`n");
		$oip = "";
	$dots = 0;
	output::doOutput("`bSimilar IP's`b`n");
	for ($x=strlen($ip); $x>0; $x--){
		if ($dots>1) break;
		$thisip = substr($ip,0,$x);
		$sql = "SELECT name, lastip, uniqueid, laston, gentimecount FROM " . db_prefix("accounts") . " WHERE lastip LIKE '$thisip%' AND NOT (lastip LIKE '$oip') ORDER BY uniqueid";
		//output::doOutput("$sql`n");
		$result = db_query($sql);
		if (db_num_rows($result)>0){
			output::doOutput("� IP Filter: %s ", $thisip);
			rawoutput("<a href='#' onClick=\"document.getElementById('ip').value='$thisip'; document.getElementById('ipradio').checked = true; return false\">");
			output::doOutput("Use this filter");
			rawoutput("</a>");
			output_notl("`n");
			while ($row=db_fetch_assoc($result)){
				output::doOutput("&nbsp;&nbsp;",true);
				output::doOutput("� (%s) [%s] `%%s`0 - %s hits, last: %s`n",
						$row['lastip'], $row['uniqueid'], $row['name'],
						$row['gentimecount'],
						reltime(strtotime($row['laston'])));
			}
			output_notl("`n");
		}
		if (substr($ip,$x-1,1)==".") {
			$x--;
			$dots++;
		}
		$oip = $thisip."%";
	}
}
