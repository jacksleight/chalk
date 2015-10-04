<?php
if (!$items) {
	return;
}
?>
<ul class="<?= isset($class) ? $class : null ?>">
	<?php foreach ($items as $item) { ?>
		<?php
		if (!isset($item)) {
			continue;
		}
		$path	= $this->url->route($item['url'][0], $item['url'][1], true, false);
		$class  = [
			strlen($path->toString()) && strpos($req->path(), $path->toString()) === 0 ? 'active' : null,
		];
		?>
		<li>
			<a href="<?= $this->url() . $path ?>" class="item <?= implode(' ', $class) ?>">
				<?php if (isset($item['label'])) { ?>
					<?php if (isset($item['icon-block'])) { ?>
						<span class="icon-block icon-<?= $item['icon-block'] ?>">
							<span class="icon-block-text"><?= $item['label'] ?></span>
						</span>
					<?php } else if (isset($item['icon'])) { ?>
						<span class="icon-<?= $item['icon'] ?>"></span>
						<?= $item['label'] ?>
					<?php } else { ?>
						<?= $item['label'] ?>
					<?php } ?>					
				<?php } ?>
				<?php if (isset($item['badge'])) { ?>
					<span class="badge badge-figure badge-pending"><?= $item['badge'] ?></span>
				<?php } ?>
			</a>
		</li>
	<?php } ?>
</ul>