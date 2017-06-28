<?= $this->render('/element/form-item', array(
	'entity'	=> $entity,
	'name'		=> 'name',
	'label'		=> 'Name',
	'autofocus'	=> true,
), 'core') ?>
<?php if ($entity->isNode()) { ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $entity->nodes[0],
        'name'          => 'name',
        'label'         => 'Label',
        'placeholder'   => $entity->name,
        'note'          => 'Alternative text used in navigation and URLs',
    ), 'core') ?>
    <?php /*
    $nodes = $this->em('Chalk\Core\Structure\Node')->all();
    $opts  = [];
    foreach ($nodes as $node) {
        $label = $node->isRoot()
            ? $node->structure->previewName
            : $node->previewName;
        $label = str_repeat('&nbsp;', $node->depth * 4) . $label;
        $opts[$node->id] = $label;
    }
    ?>
    <?= $this->render('/element/form-item', array(
        'entity'        => $entity->nodes[0],
        'name'          => 'parent',
        'label'         => 'Parent',
        'placeholder'   => $entity->name,
        'value'         => $entity->nodes[0]->parent->id,
        'values'        => $opts,
    ), 'core') */ ?>
<?php } ?>