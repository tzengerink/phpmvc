<style type="text/css">
	div.benchmark { margin:10px 0; }
		div.benchmark table { border:1px solid #aaa; color:#aaa; font-size:11px; margin:auto; }
		div.benchmark table tr:nth-child(odd) { background-color:#f4f4f4; }
		div.benchmark table th { background-color:#f4f4f4; color:#333; font-weight:600; }
			div.benchmark table th.name { text-align:right; }
		div.benchmark table th,
		div.benchmark table td { padding:3px; text-align:center; }
			div.benchmark table th.fastest,
			div.benchmark table td.fastest { background-color:#57a957; color:#fff; }
</style>
<?php $count = 0; /* Used for table head */ ?>
<div class="benchmark">
	<table>
	<?php foreach ($runtimes as $name => $data): ?>
		<tr>
			<th class="name<?php if ($best_method === $name): ?> fastest<?php endif; ?>"><?php echo $name; ?></th>
			<?php foreach ($data as $key => $value): ?>
				<?php if ( ! $count): ?><th><?php else: ?><td<?php if ($value === $fastest[$key]): ?> class="fastest"<?php endif; ?>><?php endif; ?>
					<?php echo $value; ?>
				<?php if ( ! $count): ?></th><?php else: ?></td><?php endif; ?>
			<?php endforeach; ?>
		</tr>
		<?php $count++; ?>
	<?php endforeach; ?>
	</table>
</div>
