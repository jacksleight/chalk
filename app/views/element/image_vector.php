<?php
$src	= new \Coast\File("{$src}");
$srcPng	= $this->url(new \Coast\File($src->dirname() . '/2x/' . $src->filename() . '.png'));
$src 	= $src->open();
preg_match('/^<svg[^>]*width="([^"]+)"[^>]*height="([^"]+)"[^>]*>(.*)<\/svg>$/is', $src->read(), $data);
$src	= $src->close();
?>
<div
	<?= isset($id) ? "id=\"{$id}\"" : null ?>
	class="image <?= isset($class) ? "{$class}" : null ?>">
	<svg
		preserveaspectratio="x<?= $data[1] ?>y<?= $data[2] ?> meet"
		viewbox="0 0 <?= $data[1] ?> <?= $data[2] ?>"
		role="img"
		<?= isset($alt) ? "aria-label=\"{$alt}\"" : null ?>>
		<?php if (isset($alt)) { ?>
			<title><?= $alt ?></title>
		<?php } ?>
		<switch>
			<g><?= $data[3] ?></g>
			<foreignobject>
				<img
					src="<?= $srcPng ?>"
					width="<?= $data[1] ?>"
					height="<?= $data[2] ?>"
					<?= isset($alt) ? "alt=\"{$alt}\"" : null ?>>
			</foreignobject>
		</switch>
	</svg>
</div>