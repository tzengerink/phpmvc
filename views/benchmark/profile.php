<style type="text/css">
	div.profile { color:#aaa; font-size:11px; margin:10px 0; text-align:center; }
		div.profile span.time, 
		div.profile span.memory { color:#333; font-weight:600; }
		div.profile span.error { color:#9d261d; }
</style>
<div class="profile">
	<?php echo __('execution', array(
		':time'   => '<span class="time'.($time > $time_limit ? ' error' : '').'">'.$time.'</span>',
		':memory' => '<span class="memory'.($memory > $memory_limit ? ' error' : '').'">'.$memory.'</span>',
	)); ?>
</div>
