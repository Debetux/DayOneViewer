<?php

define('DIRECTORY', 'entries/'); 
setlocale(LC_ALL, 'fr');

/**
 * Require CFPropertyList
 */
require_once(__DIR__.'/libs/CFPropertyList/CFPropertyList.php');


$entries = array();
$i = 0;
if ($handle = opendir(DIRECTORY)) {
	while (false !== ($file = readdir($handle))) {
		if($file != '.' && $file != '..'){
			
			// All the files :
			$entries_array[] = $file;


			/*
			 * create a new CFPropertyList instance which loads the sample.plist on construct.
			 * since we know it's an XML file, we can skip format-determination
			 */
			$plist = new CFPropertyList\CFPropertyList(DIRECTORY.$file, CFPropertyList\CFPropertyList::FORMAT_XML);
			$o = $plist->toArray();
			$entries[$o["Creation Date"]] = $o;
			$i++;

	   }
   }
 
   closedir($handle);
}

ksort($entries);

?>

<?php /* HERE IS THE VIEW */ ?>
<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>The HTML5 Herald</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="./DayOne.css?v=1.0">

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>

	<ul id="contenu" class="journal">
		<?php foreach($entries as $entry): ?>
			<li>
				<h2><a href="#<?php echo $entry['Creation Date']; ?>" id="<?php echo $entry['Creation Date']; ?>"><?php echo date('l j F Y, H:i', $entry['Creation Date']); ?></a></h2><br>
				<p><?php echo nl2br($entry['Entry Text']); ?></p>
				<hr>
			</li>
		<?php endforeach; ?>
	</ul>

	<hr>
	<?php 
	foreach ($entries_array as $key => $value) {
	$xml = simplexml_load_file(DIRECTORY.$value);
	echo "<pre>";
	print_r($xml);
	echo "</pre>";

	}
	?>
</body>
</html>