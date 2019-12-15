<?php
if ($display == 1){
	$q = "";
	if ($query) {
		$q = "&q=$query";
	}
	$ops=translator::translate_inline("Ops");
	$acid =translator::translate_inline("AcctID");
	$login =translator::translate_inline("Login");
	$nm =translator::translate_inline("Name");
	$lev =translator::translate_inline("Level");
	$lon =translator::translate_inline("Last On");
	$hits =translator::translate_inline("Hits");
	$lip =translator::translate_inline("Last IP");
	$lid =translator::translate_inline("Last ID");
	$email =translator::translate_inline("Email");
	$ed = translator::translate_inline("Edit");
	$del = translator::translate_inline("Del");
	$conf = translator::translate_inline("Are you sure you wish to delete this user?");
	$ban = translator::translate_inline("Ban");
	$log = translator::translate_inline("Log");
		rawoutput("<table>");
	rawoutput("<tr class='trhead'><td>$ops</td><td><a href='user.php?sort=acctid$q'>$acid</a></td><td><a href='user.php?sort=login$q'>$login</a></td><td><a href='user.php?sort=name$q'>$nm</a></td><td><a href='user.php?sort=level$q'>$lev</a></td><td><a href='user.php?sort=laston$q'>$lon</a></td><td><a href='user.php?sort=gentimecount$q'>$hits</a></td><td><a href='user.php?sort=lastip$q'>$lip</a></td><td><a href='user.php?sort=uniqueid$q'>$lid</a></td><td><a href='user.php?sort=emailaddress$q'>$email</a></td></tr>");
	addnav("","user.php?sort=acctid$q");
	addnav("","user.php?sort=login$q");
	addnav("","user.php?sort=name$q");
	addnav("","user.php?sort=level$q");
	addnav("","user.php?sort=laston$q");
	addnav("","user.php?sort=gentimecount$q");
	addnav("","user.php?sort=lastip$q");
	addnav("","user.php?sort=uniqueid$q");
	$rn=0;
	$oorder = "";
	$number3=db_num_rows($searchresult);
	for ($i=0;$i<$number3;$i++){
		$row=db_fetch_assoc($searchresult);
		$laston = relativedate($row['laston']);
		$loggedin =
			(date("U") - strtotime($row['laston']) <
			 getsetting("LOGINTIMEOUT",900) && $row['loggedin']);
		if ($loggedin)
			$laston=translator::translate_inline("`#Online`0");
		$row['laston']=$laston;
		if ($row[$order]!=$oorder) $rn++;
		$oorder = $row[$order];
		rawoutput("<tr class='".($rn%2?"trlight":"trdark")."'>");
		rawoutput("<td nowrap>");
		rawoutput("[ <a href='user.php?op=edit&userid={$row['acctid']}$m'>$ed</a> | <a href='user.php?op=del&userid={$row['acctid']}' onClick=\"return confirm('$conf');\">$del</a> | <a href='user.php?op=setupban&userid={$row['acctid']}'>$ban</a> | <a href='user.php?op=debuglog&userid={$row['acctid']}'>$log</a> ]");
		addnav("","user.php?op=edit&userid={$row['acctid']}$m");
		addnav("","user.php?op=del&userid={$row['acctid']}");
		addnav("","user.php?op=setupban&userid={$row['acctid']}");
		addnav("","user.php?op=debuglog&userid={$row['acctid']}");
		rawoutput("</td><td>");
		output_notl("%s", $row['acctid']);
		rawoutput("</td><td>");
		output_notl("%s", $row['login']);
		rawoutput("</td><td>");
		output_notl("`&%s`0", $row['name']);
		rawoutput("</td><td>");
		output_notl("`^%s`0", $row['level']);
		rawoutput("</td><td>");
		output_notl("%s", $row['laston']);
		rawoutput("</td><td>");
		output_notl("%s", $row['gentimecount']);
		rawoutput("</td><td>");
		output_notl("%s", $row['lastip']);
		rawoutput("</td><td>");
		output_notl("%s", $row['uniqueid']);
		rawoutput("</td><td>");
		output_notl("%s", $row['emailaddress']);
		rawoutput("</td></tr>");
		$gentimecount+=$row['gentimecount'];
		$gentime+=$row['gentime'];
	}
	rawoutput("</table>");
	output("Total hits: %s`n", $gentimecount);
	output("Total CPU time: %s seconds`n", round($gentime,3));
	output("Average page gen time is %s seconds`n", round($gentime/max($gentimecount,1),4));
}
