<?php
$pages = isset($limit) && $count
	? ceil($count / $limit)
	: 1;
?>
<ul class="toolbar tight">
	<?php
	$min1 = $value - 4;
	$max1 = $value + 5;
	$min2 = $max1 > $pages	? $min1 + ($pages - $max1)	: $min1;
	$max2 = $min1 < 1		? $max1 + (1 - $min1)	 	: $max1;
	$min1 = max($min2, 1);
	$max1 = min($max2, $pages);
	?>
	<li>
		<a href="<?= $this->url([]) . $this->url->query(array(
			'page' => 1,
		)) ?>" class="btn btn-quieter btn-icon icon-first <?= $value == 1 ? 'disabled' : null ?>">First</a>
	</li>
	<li>
		<a href="<?= $this->url([]) . $this->url->query(array(
			'page' => max($value - 1, 1),
		)) ?>" class="btn btn-quieter btn-icon icon-prev <?= $value == 1 ? 'disabled' : null ?>" rel="prev">Previous</a>
	</li>
	<? for ($i = $min1; $i <= $max1; $i++) { ?>
		<li>
			<a href="<?= $this->url([]) . $this->url->query(array(
				'page' => $i,
			)) ?>" class="btn btn-quieter <?= $i == $value ? 'active' : '' ?>"><?= $i ?></a>
		</li>
	<? } ?>
	<li>
		<a href="<?= $this->url([]) . $this->url->query(array(
			'page' => min($value + 1, $pages),
		)) ?>" class="btn btn-quieter btn-icon icon-next <?= $value == $pages ? 'disabled' : null ?>" rel="next">Next</a>
	</li>
	<li>
		<a href="<?= $this->url([]) . $this->url->query(array(
			'page' => $pages,
		)) ?>" class="btn btn-quieter btn-icon icon-last <?= $value == $pages ? 'disabled' : null ?>">Last</a>
	</li>
</ul>