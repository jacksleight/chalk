<?php
// $restricts = [
// 	'core_file' => [
// 		'image/jpeg',
// 	],
// ];
if (!isset($restricts)) {
	$restricts = $this->em->getClassMetadata('Chalk\Core\Content')->subClasses;
}
$restricts = $this->em('core_content')->parseTypes($restricts);
if (!isset($index->type)) {
	$index->type = key($restricts);
}
$info = \Chalk\Chalk::info($index->type);
?>
<div class="flex flex-row <?= $info->class == 'Chalk\Core\File' ? 'uploadable' : null ?>">
	<div class="sidebar">
		<div class="body">
			<nav class="nav" role="navigation">
				<ul>
					<? foreach ($restricts as $restrictsType => $restrictsSubrestricts) { ?>
						<?php
						$restrictsInfo = \Chalk\Chalk::info($restrictsType);
						?>
						<li><a href="<?= $this->url([]) . $this->url->query([
							'type' => $restrictsInfo->name,
						], true) ?>" class="item <?= $restrictsInfo->name == $info->name ? 'active' : null ?>"><?= $restrictsInfo->plural ?></a></li>
					<? } ?>
				</ul>
			</nav>
		</div>
	</div>
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
	</div>
</div>