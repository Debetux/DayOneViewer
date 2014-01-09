<?php

define('DIRECTORY', 'entries/'); 
setlocale(LC_ALL, 'fr');

/**
 * Require CFPropertyList
 */
require_once(__DIR__.'/libs/CFPropertyList/CFPropertyList.php');


$entries_by_date = array();
$entries = array();

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
			$entries_by_date[$o["Creation Date"]] = $o;
			

	   }
   }
 
   closedir($handle);
}

ksort($entries_by_date);

// I want an index with number sorted by date, so we must do that again :
$i = 0;
foreach ($entries_by_date as $key_entry => $entry):
	$entries[$i] = $entries_by_date[$key_entry];
	$i++;
endforeach;

?>

<?php 
	/* HERE IS THE VIEW */

	// Init some variables
	$last_month = null;
?>
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
		<?php foreach($entries as $key => $entry): ?>
			<?php if($last_month != date('n', $entry['Creation Date'])): // if previous month is different from current_month ?>
				<h3><?php echo date('F Y', $entry['Creation Date']); ?></h3>
				<hr>
				<ul class="entries">
			<?php else: ?>
				
			<?php endif; ?>

			<?php $last_month = date('n', $entry['Creation Date']); ?>

			<li>
				<h2><a href="#<?php echo $entry['Creation Date']; ?>" id="<?php echo $entry['Creation Date']; ?>"><?php echo date('l j F Y, H:i', $entry['Creation Date']); ?></a></h2><br>
				<p><?php echo nl2br($entry['Entry Text']); ?></p>
				<hr class="styled_hr">
			</li>

			<?php if(date('n', $entry['Creation Date']) != date('n', $entries[$key+1]['Creation Date'])): // If next month is different from current month ?>
				</ul>CLOSE
			<?php else: ?>
				
			<?php endif; ?>

			
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