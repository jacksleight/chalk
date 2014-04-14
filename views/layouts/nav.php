<ul class="<?= isset($class) ? $class : null ?>">
	<? foreach ($items as $item) { ?>
		<?php
		$name = isset($item['name'])
			? $item['name']
			: 'index';
		$params = isset($item['params'])
			? $item['params']
			: [];
		$path = $this->url($params, $name, true, false);
		$class = [
			strpos($req->path(), $path->toString()) === 0 ? 'active' : null,
		];
		?>
		<li>
			<a href="<?= $req->base() . $path ?>" class="<?= implode(' ', $class) ?>">
				<? if (isset($item['icon'])) { ?>
					<i class="<?= $item['icon'] ?>"></i>
				<? } ?>
				<?= $item['label'] ?>
			</a>
		</li>
	<? } ?>
</ul>