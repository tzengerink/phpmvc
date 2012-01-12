<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
		<title>Site Title</title>
		<link rel="author" href="<?php echo BASEURL; ?>humans.txt" />
	</head>
	<body>
		<div id="page"><?php echo $content; ?></div>
		<?php if ( ! Config::load('core')->get('is_production')): ?>
			<?php echo Benchmark::profile(); ?>
		<?php endif; ?>
	</body>
</html>
