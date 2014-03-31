<?php
$parents = isset($parents)
	? $parents
	: [];
?>
<ul class="<?= isset($class) ? $class : null ?>">
	<? foreach ($items as $label => $children) { ?>
		<?php
		$id      = \Coast\str_simplify($label, '-');
		$parts	 = array_merge($parents, [$id]);
		$path	 = implode('/', $parts);
		$current = isset($req) ? $req->path() : '';
		$current = strlen($current) ? $current : 'home';
		$class	 = [
			strpos($current, $path) === 0 ? 'active' : null,
			$path == $current ? 'current' : null,
		];
		$url = $this->url($path == 'home' ? '' : $path);
		?>
		<li class="<?= implode(' ', $class) ?>">
			<a href="<?= $url ?>"><?= $this->escape($label) ?></a>
			<? if (count($children) > 0) { ?>
				<?= $this->render('_nav', [
					'parents'	=> $parts,
					'items'		=> $children,
					'class'		=> null,
				]) ?>
			<? } ?>
		</li>
	<? } ?>
</ul>