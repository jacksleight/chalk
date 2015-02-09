<ul class="<?= isset($class) ? $class : null ?>">
	<?php foreach ($items as $item) { ?>
		<?php
		$path	= $this->url->route($item['url'][0], $item['url'][1], true, false);
		$class  = [
			strlen($path->toString()) && strpos($req->path(), $path->toString()) === 0 ? 'active' : null,
		];
		?>
		<li>
			<a href="<?= $this->url() . $path ?>" class="item <?= implode(' ', $class) ?>">
				<?php if (isset($item['label'])) { ?>
					<span class="<?= isset($item['icon']) ? "icon icon-block icon-{$item['icon']}" : null ?>"><?= $item['label'] ?></span>
				<?php } ?>
				<?php if (isset($item['badge'])) { ?>
					<span class="badge badge-figure badge-pending"><?= $item['badge'] ?></span>
				<?php } ?>
			</a>
		</li>
	<?php } ?>
</ul>