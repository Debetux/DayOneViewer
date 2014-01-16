<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>Activity</key>
	<string>Stationary</string>

	<key>Creation Date</key>
	<date><?php echo gmdate("Y-m-d\TH:i:s\Z", $entry['Creation Date']); ?></date>
	
	<key>Creator</key>
	<dict>
		<key>Device Agent</key>
		<string><?php echo DEVICE_AGENT; ?></string>
		<key>Generation Date</key>
		<date><?php echo gmdate("Y-m-d\TH:i:s\Z", $entry['Creator']['Generation Date']); ?></date>
		<key>Host Name</key>
		<string><?php echo HOST_NAME; ?></string>
		<key>OS Agent</key>
		<string><?php echo OS_AGENT; ?></string>
		<key>Software Agent</key>
		<string><?php echo SOFTWARE_AGENT; ?></string>
	</dict>

	<?php if(!empty($entry['Entry Text'])): ?>
		<key>Entry Text</key>
		<string><?php echo $entry['Entry Text']; ?></string>
	<?php endif; ?>

	<?php if(!empty($entry['Location'])): ?>
		<key>Location</key>
		<dict>
			<key>Latitude</key>
			<real><?php echo $entry['Location']['Latitude']; ?></real>
			<key>Longitude</key>
			<real><?php echo $entry['Location']['Longitude']; ?></real>
		</dict>
	<?php endif; ?>

	<key>Starred</key>
	<false/>

	<key>Time Zone</key>
	<string>Europe/Paris</string>

	<key>UUID</key>
	<string><?php echo $entry['UUID']; ?></string>

</dict>
</plist>
