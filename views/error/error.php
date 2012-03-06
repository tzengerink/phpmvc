<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
		<title>Exception - <?php echo $message; ?></title>
		<style type="text/css">
			body { color:#333; font-family:"Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Lucida, sans-serif; }
			#header { 
				background-color:               #dd4b39;
				-moz-border-radius-topleft:     4px;
				-moz-border-radius-topright:    4px;
				-moz-border-radius-bottomright: 0px;
				-moz-border-radius-bottomleft:  0px;
				-webkit-border-radius:          4px 4px 0px 0px;
				border-radius:                  4px 4px 0px 0px;
				color:                          #fff;
				font-size:                      18px;
				padding:                        8px;
			}	
			.step { font-size:14px; padding:5px; }
			.step:nth-child(even) { background-color:#efefef; }
			.step:last-child { 
				-moz-border-radius-topleft:     0px;
				-moz-border-radius-topright:    0px;
				-moz-border-radius-bottomright: 4px;
				-moz-border-radius-bottomleft:  4px;
				-webkit-border-radius:          0px 0px 4px 4px;
				border-radius:                  0px 0px 4px 4px;
			}
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
