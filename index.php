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
		   $entries_array[] = $file;
		   $entry_xml = simplexml_load_file(DIRECTORY.$file);
		   $entries[$i]['date'] = strtotime($entry_xml->dict->date);
		   $entries[$i]['content'] = $entry_xml->dict->string[1];

		   $i++;

		   /*
			 * create a new CFPropertyList instance which loads the sample.plist on construct.
			 * since we know it's an XML file, we can skip format-determination
			 */
			$plist = new CFPropertyList\CFPropertyList(DIRECTORY.$file, CFPropertyList\CFPropertyList::FORMAT_XML);

			/*
			 * retrieve the array structure of sample.plist and dump to stdout
			 */

			echo '<pre>';
			var_dump( $plist->toArray() );
			echo '</pre>';

	   }
   }
 
   closedir($handle);
}


?>

<?php /* HERE IS THE VIEW */ ?>
<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>The HTML5 Herald</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="css/styles.css?v=1.0">

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>

	<?php foreach($entries as $entry): ?>
		<?php echo strftime('%A %m %B %Y à %R', $entry['date']); ?><br>
		<p><?php echo $entry['content']; ?></p>
		<hr>
	<?php endforeach; ?>


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