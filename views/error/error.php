<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
		<title>Exception - <?php echo $message; ?></title>
		<style type="text/css">
			body { color:#333; font-family:"Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Lucida, sans-serif; }
			#header { background-color:#f50; color:#fff; font-size:18px; padding:8px; }	
			.step { font-size:14px; padding:5px; }
			.step:nth-child(even) { background-color:#efefef; }
			.file { color:#aaa; }
		</style>
	</head>
	<body>
		<div id="page">
			<div id="header">Exception: <?php echo $message; ?> [<?php echo $code; ?>]</div>
			<div id="body">
				<?php foreach ($trace as $count => $step): ?>
					<div class="step">
						<span class="count"><?php echo $count+1; ?>.</span>
						<span class="class"><?php echo $step['class'].$step['type'].$step['function'].'()'; ?></span>
						<span class="file"><?php echo $step['file'].' ['.$step['line'].']'; ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</body>
</html>
