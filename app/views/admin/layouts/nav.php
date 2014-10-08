<ul class="<?= isset($class) ? $class : null ?>">
	<?php foreach ($items as $item) { ?>
		<?php
		$name = isset($item['name'])
			? $item['name']
			: 'index';
		$params = isset($item['params'])
			? $item['params']
			: [];
		$path = $this->url($params, $name, true, false);
		$current = isset($req) ? $req->path() : '';
		$class = [
			strlen($path->toString()) && strpos($current, $path->toString()) === 0 ? 'active' : null,
		];
		?>
		<li>
			<a href="<?= $this->url() . $path ?>" class="item <?= implode(' ', $class) ?>">
				<?php if (isset($item['icon'])) { ?>
					<i class="<?= $item['icon'] ?>"></i>
				<?php } ?>
				<?php if (isset($item['label'])) { ?>
					<span><?= $item['label'] ?></span>
				<?php } ?>
				<?php if (isset($item['badge'])) { ?>
					<span class="badge badge-figure badge-pending"><?= $item['badge'] ?></span>
				<?php } ?>
			</a>
		</li>
	<?php } ?>
</ul>