<?php
$classes = $this->app->fire('Chalk\Core\Event\ListContents')->contents();
$restricts = isset($restricts)
	? $restricts
	: $index->restricts;
if ($restricts == 'node') {
	$restricts = [];
	foreach ($classes as $class) {
		$classInfo = \Chalk\Chalk::info($class);
		if ($classInfo->isNode) {
			$restricts[] = $class;
		}
	}
} else if ($restricts == 'url') {
	$restricts = [];
	foreach ($classes as $class) {
		$classInfo = \Chalk\Chalk::info($class);
		if ($classInfo->isUrl) {
			$restricts[] = $class;
		}
	}
} else if ($restricts == 'image') {
	$restricts = [
		'core_file' => [
			'image/gif',
			'image/jpeg',
			'image/png',
			'image/webp',
		],
	];
} else if (!isset($restricts)) {
	$restricts = $classes;
}
$restricts = $this->em('core_content')->parseTypes($restricts);
$restricts = \Coast\array_intersect_key($restricts, $classes);
if (!isset($index->type)) {
	$index->type = key($restricts);
}
$info = \Chalk\Chalk::info($index->type);
?>
<div class="flex flex-row">
	<? if (count($restricts) > 1) { ?>	
		<div class="sidebar">
			<div class="body">
				<nav class="nav" role="navigation">
					<ul>
						<? foreach ($restricts as $restrictsType => $restrictsSubrestricts) { ?>
							<?php
							$restrictsInfo = \Chalk\Chalk::info($restrictsType);
							?>
							<li><a href="<?= $this->url([]) . $this->url->query([
								'restricts'	=> $index->restricts,
								'type'		=> $restrictsInfo->name,
							], true) ?>" class="item <?= $restrictsInfo->name == $info->name ? 'active' : null ?>"><?= $restrictsInfo->plural ?></a></li>
						<? } ?>
					</ul>
				</nav>
			</div>
		</div>
	<? } ?>
	<div class="flex">
		<?= $this->render("/{$info->local->path}/list", [
			'contents'		=> $this->em($info)->paged(['types' => $restricts] + $index->toArray()),
			'isNewAllowed'	=> false,
			'isEditAllowed'	=> false,
			'info'			=> $info,
			'index'			=> $index,
			'restricts'		=> $restricts,
		], $info->module->class) ?>
        <?= $this->render('/element/form-input', array(
            'type'          => 'input_hidden',
            'entity'        => $index,
            'name'          => 'type',
        )) ?>
        <?= $this->render('/element/form-input', array(
            'type'          => 'input_hidden_array',
            'entity'        => $index,
            'name'          => 'restricts',
        )) ?>
	</div>
</div>