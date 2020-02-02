<?php
// translator ready
// addnews ready
// mail ready

// Okay, someone wants to use this outside of normal game flow.. no real harm
define("OVERRIDE_FORCED_NAV",true);

// Translate Untranslated Strings
// Originally Written by Christian Rutsch
// Slightly modified by JT Traub
require_once("common.php");
require_once("lib/http.php");

check_su_access(SU_IS_TRANSLATOR);

translator::tlschema("untranslated");

$op = http::httpget('op');
page_header("Untranslated Texts");

if ($op == "list") {
	$mode = http::httpget('mode');
	$namespace = http::httpget('ns');

	if ($mode == "save") {
		$intext = httppost('intext');
		$outtext = httppost('outtext');
		if ($outtext <> "") {
			$login = $session['user']['login'];
			$language = $session['user']['prefs']['language'];
			$sql = "INSERT INTO " . db_prefix("translations") . " (language,uri,intext,outtext,author,version) VALUES" . " ('$language','$namespace','$intext','$outtext','$login','$logd_version')";
			db_query($sql);
			$sql = "DELETE FROM " . db_prefix("untranslated") . " WHERE intext = '$intext' AND language = '$language' AND namespace = '$namespace'";
			db_query($sql);
		}
	}

	if ($mode == "edit") {
		rawoutput("<form action='untranslated.php?op=list&mode=save&ns=".rawurlencode($namespace)."' method='post'>");
		output::addnav("", "untranslated.php?op=list&mode=save&ns=".rawurlencode($namespace));
	} else {
		rawoutput("<form action='untranslated.php?op=list' method='get'>");
		output::addnav("", "untranslated.php?op=list");
	}

	$sql = "SELECT namespace,count(*) AS c FROM " . db_prefix("untranslated") . " WHERE language='".$session['user']['prefs']['language']."' GROUP BY namespace ORDER BY namespace ASC";
	$result = db_query($sql);
	rawoutput("<input type='hidden' name='op' value='list'>");
	output::doOutput("Known Namespaces:");
	rawoutput("<select name='ns'>");
	while ($row = db_fetch_assoc($result)){
		rawoutput("<option value=\"".htmlentities($row['namespace'], ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"))."\"".((htmlentities($row['namespace'], ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1")) == $namespace) ? "selected" : "").">".htmlentities($row['namespace'], ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"))." ({$row['c']})</option>");
	}
	rawoutput("</select>");
	rawoutput("<input type='submit' class='button' value='". translator::translate_inline("Show") ."'>");
	rawoutput("<br>");

	if ($mode == "edit") {
		rawoutput(translator::translate_inline("Text:"). "<br>");
		rawoutput("<textarea name='intext' cols='60' rows='5' readonly>".htmlentities(stripslashes(http::httpget('intext')), ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1"))."</textarea><br/>");
		rawoutput(translator::translate_inline("Translation:"). "<br>");
		rawoutput("<textarea name='outtext' cols='60' rows='5'></textarea><br/>");
		rawoutput("<input type='submit' value='". translator::translate_inline("Save") ."' class='button'>");
	} else {
		rawoutput("<table border='0' cellpadding='2' cellspacing='0'>");
		rawoutput("<tr class='trhead'><td>". translator::translate_inline("Ops") ."</td><td>". translator::translate_inline("Text") ."</td></tr>");
		$sql = "SELECT * FROM " . db_prefix("untranslated") . " WHERE language='".$session['user']['prefs']['language']."' AND namespace='".$namespace."'";
		$result = db_query($sql);
		if (db_num_rows($result)>0){
			$i = 0;
			while ($row = db_fetch_assoc($result)){
				$i++;
				rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
				rawoutput("<a href='untranslated.php?op=list&mode=edit&ns=". rawurlencode($row['namespace']) ."&intext=". rawurlencode($row['intext']) ."'>". translator::translate_inline("Edit") ."</a>");
				output::addnav("", "untranslated.php?op=list&mode=edit&ns=". rawurlencode($row['namespace']) ."&intext=". rawurlencode($row['intext']));
				rawoutput("</td><td>");
				rawoutput(htmlentities($row['intext'], ENT_COMPAT, settings::getsetting("charset", "ISO-8859-1")));
				rawoutput("</td></tr>");
			}
		}else{
			rawoutput("<tr><td colspan='2'>". translator::translate_inline("No rows found") ."</td></tr>");
		}
		rawoutput("</table>");
	}

	rawoutput("</form>");

} else {
	if ($op == "step2") {
		$intext = httppost('intext');
		$outtext = httppost('outtext');
		$namespace = httppost('namespace');
		$language = httppost('language');
		if ($outtext <> "") {
			$login = $session['user']['login'];
			$sql = "INSERT INTO " . db_prefix("translations") . " (language,uri,intext,outtext,author,version) VALUES" . " ('$language','$namespace','$intext','$outtext','$login','$logd_version')";
			db_query($sql);
			$sql = "DELETE FROM " . db_prefix("untranslated") . " WHERE intext = '$intext' AND language = '$language' AND namespace = '$namespace'";
			db_query($sql);
			invalidatedatacache("translations-".$namespace."-".$language);
		}
	}

	$sql = "SELECT count(intext) AS count FROM " . db_prefix("untranslated");
	$count = db_fetch_assoc(db_query($sql));
	if ($count['count'] > 0) {
		$sql = "SELECT * FROM " . db_prefix("untranslated") . " WHERE language = '" . $session['user']['prefs']['language'] . "' ORDER BY rand(".e_rand().") LIMIT 1";
		$result = db_query($sql);
		if (db_num_rows($result) == 1) {
			$row = db_fetch_assoc($result);
			$row['intext'] = stripslashes($row['intext']);
			$submit = translator::translate_inline("Save Translation");
			$skip = translator::translate_inline("Skip Translation");
			rawoutput("<form action='untranslated.php?op=step2' method='post'>");
			output::doOutput("`^`cThere are `&%s`^ untranslated texts in the database.`c`n`n", $count['count']);
			rawoutput("<table width='80%'>");
			rawoutput("<tr><td width='30%'>");
			output::doOutput("Target Language: %s", $row['language']);
			rawoutput("</td><td></td></tr>");
			rawoutput("<tr><td width='30%'>");
			output::doOutput("Namespace: %s", $row['namespace']);
			rawoutput("</td><td></td></tr>");
			rawoutput("<tr><td width='30%'><textarea cols='35' rows='4' name='intext'>".$row['intext']."</textarea></td>");
			rawoutput("<td width='30%'><textarea cols='25' rows='4' name='outtext'></textarea></td></tr></table>");
			rawoutput("<input type='hidden' name='id' value='{$row['id']}'>");
			rawoutput("<input type='hidden' name='language' value='{$row['language']}'>");
			rawoutput("<input type='hidden' name='namespace' value='{$row['namespace']}'>");
			rawoutput("<input type='submit' value='$submit' class='button'>");
			rawoutput("</form>");
			rawoutput("<form action='untranslated.php' method='post'>");
			rawoutput("<input type='submit' value='$skip' class='button'>");
			rawoutput("</form>");
			output::addnav("", "untranslated.php?op=step2");
			output::addnav("", "untranslated.php");
		} else {
			output::doOutput("There are `&%s`^ untranslated texts in the database, but none for your selected language.", $count['count']);
			output::doOutput("Please change your language to translate these texts.");
		}
	} else {
		output::doOutput("There are no untranslated texts in the database!");
		output::doOutput("Congratulations!!!");
	} // end if
} // end list if
output::addnav("R?Restart Translator", "untranslated.php");
output::addnav("N?Translate by Namespace", "untranslated.php?op=list");
require_once("lib/superusernav.php");
superusernav();
page_footer();
