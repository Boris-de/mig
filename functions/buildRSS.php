<?
function buildRSS($currDir, $mig_language, $SERVER) {
	global $mig_config;

	$x = $mig_config['albumdir'].'/'.$currDir;
	if (is_dir($x)) {
		// Open directory handle
		$dir = opendir($x);
	} else {
		print "ERROR: no such currDir '$currDir'<br>";
		exit;
	}

	// $currDir abarbeiten
	while ($file = readdir($dir)) {

		// Ignore . and ..
		if ($file == '.' || $file == '..') {
			continue;
		}

		if (is_dir($mig_config['albumdir'].'/'.$currDir.'/'.$file)) {
			$type = 'dir';
		} else {
			$type = getFileType($file);
		}

		// Ignore unknown files
		if(!$type) {
			continue;
		}

		// Ignore files and directories whose name begins with "."
		// if the appropriate option is set
		if ($mig_config['ignoredotdirectories'] && ereg('^\.', $file)) {
			continue;
		}

		// Ignore directories whose name does not match currDirNameRegexpr
		if ($type == 'dir'
			&& !preg_match($mig_config['currDirNameRegexpr'], $file)) {
			continue;
		}

		// Ignore images whose name does not match imageFilenameRegexpr
		if ($type != 'dir'
			&& !preg_match($mig_config['imageFilenameRegexpr'], $file)) {
			continue;
		}

		// get mtime of the file/dir
		$time = filemtime("${x}/$file" . ($type=='dir')? '/.' : '');

		// set link depending on dir/file
		if($type == 'dir') {
			$folderLink = $SERVER . $mig_config['baseurl'] .
					"?pageType=folder&amp;currDir=$currDir/$file";
		} else {
			$folderLink = $SERVER . $mig_config['baseurl'] .
					"?pageType=folder&amp;currDir=$currDir";
		}

		$files[] = array( $file, $time, $folderLink );
	}

	// get last modification time
	foreach($files as $cur) {
		$maxTime = ($cur[1]>$maxTime)? $cur[1] : $maxTime;
	}
	$lastModified = date('D, d M Y H:i:s T', $maxTime);

	// send header
	header('Content-Type: text/xml; charset=ISO-8859-1');
	header("Last-Modified: $lastModified");
	header("ETag: \"$lastModified\"");
	echo '<?xml version="1.0" encoding="ISO-8859-1" ?>'."\n";
?>
<rss version="2.0">
	<channel>
		<title><? echo $mig_config['pagetitle']; ?></title>
		<description><? echo $mig_config['pagetitle']; ?></description>
		<link><? echo $mig_config['homelink']; ?></link>
		<language><? echo $mig_language; ?></language>
		<generator>Mig <? echo $version; ?></generator>
		<lastBuildDate><? echo $lastModified; ?></lastBuildDate>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<?
	foreach($files as $cur) {
		list($file, $filetime, $folderLink) = $cur;
?>
		<item>
			<title><? echo $file; ?></title>
			<link><? echo $folderLink; ?></link>
			<description>TODO</description>
			<pubDate><? echo date('D, d M Y H:i:s O', $filetime);?></pubDate>
			<guid><? echo $folderLink; ?></guid>
		</item>
<?
	}
?>
	</channel>
</rss>
<?
	exit;
}
?>