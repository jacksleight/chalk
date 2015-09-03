<?php
$filters = isset($filters)
    ? $filters
    : $index->filters;
$classes = [];
foreach ($this->contentList as $contentInfo) {
    $classes[] = $contentInfo->class;
}
if ($filters == 'node') {
	$filters = [];
	foreach ($this->contentList as $contentInfo) {
		if ($contentInfo->isNode) {
			$filters[] = $contentInfo->name;
		}
	}
} else if ($filters == 'url') {
	$filters = [];
	foreach ($this->contentList as $contentInfo) {
		if ($contentInfo->isUrl) {
			$filters[] = $contentInfo->name;
		}
	}
} else if ($filters == 'image') {
	$filters = [
		'core_file' => [
			'image/gif',
			'image/jpeg',
			'image/png',
			'image/webp',
		],
	];
} else if (!isset($filters)) {
	$filters = $classes;
}
$filters = $this->em('core_content')->parseTypes($filters);
$filters = \Coast\array_intersect_key($filters, $classes);
if (!isset($index->type)) {
	$index->type = $this->contentList->first()->name;
}
$info = \Chalk\Chalk::info($index->type);
?>
<div class="flex flex-row">
    <? if (count($filters) > 1) { ?>  
        <div class="sidebar">
            <div class="body">
                <nav class="nav" role="navigation">
                    <ul>
                        <? foreach ($filters as $filtersType => $filtersSubfilters) { ?>
                            <?php
                            $filtersInfo = \Chalk\Chalk::info($filtersType);
                            ?>
                            <li><a href="<?= $this->url([]) . $this->url->query([
                                'filters' => $index->filters,
                                'type'      => $filtersInfo->name,
                            ], true) ?>" class="item <?= $filtersInfo->name == $info->name ? 'active' : null ?>"><?= $filtersInfo->plural ?></a></li>
                        <? } ?>
                    </ul>
                </nav>
            </div>
        </div>
    <? } ?>
	<div class="flex">
		<?= $this->render("/{$info->local->path}/list", [
			'contents'		=> $this->em($info)->paged(['types' => $filters] + $index->toArray()),
			'isNewAllowed'	=> false,
			'isEditAllowed'	=> false,
			'info'			=> $info,
			'index'			=> $index,
			'filters'		=> $filters,
		], $info->module->name) ?>
        <?= $this->render('/element/form-input', array(
            'type'          => 'input_hidden',
            'entity'        => $index,
            'name'          => 'type',
        ), 'core') ?>
        <?= $this->render('/element/form-input', array(
            'type'          => 'input_hidden_array',
            'entity'        => $index,
            'name'          => 'filters',
        ), 'core') ?>
	</div>
</div>