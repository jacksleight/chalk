<ul class="<?= isset($class) ? $class : null ?>">
	<? foreach ($items as $controller => $children) { ?>
		<?php
		$url = $this->url([
			'controller' => $controller,
		], 'index', true);
		?>
		<li>
			<a href="<?= $url ?>"><?= $this->locale->message('nav_' . $controller) ?></a>
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