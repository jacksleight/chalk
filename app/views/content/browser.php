<?php
$info = \Chalk\Chalk::info($index->type ?: 'Chalk\Core\Page');
?>
<div class="flex flex-row <?= $info->class == 'Chalk\Core\File' ? 'uploadable' : null ?>">
	<div class="sidebar">
		<div class="body">
			<?php
			$classes = $this->em->getClassMetadata('Chalk\Core\Content')->subClasses;
			?>
			<nav class="nav" role="navigation">
				<ul>
					<? foreach ($classes as $class) { ?>
						<?php
						$classInfo = \Chalk\Chalk::info($class);
						?>
						<li><a href="<?= $this->url->query([
							'type' => $classInfo->name,
						]) ?>" class="item <?= $classInfo->name == $info->name ? 'active' : null ?>"><?= $classInfo->plural ?></a></li>
					<? } ?>
				</ul>
			</nav>
		</div>
	</div>
	<div class="flex">
		<?= $this->render("/{$info->local->path}/list", [
			'contents'		=> $this->em($info)->paged($index->toArray()),
			'isNewAllowed'	=> false,
			'isEditAllowed'	=> false,
			'info'			=> $info,
			'index'			=> $index,
		], $info->module->class) ?>
	</div>
</div>