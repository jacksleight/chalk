<?php if (isset($entity) && $entity->isNode()) { ?>
    <?php
    $info = $this->hook->fire('core_info/core_node', new Chalk\Info())->fetch('isPrimary')[0];
    ?>
    <li><a href="<?= $this->url([
        'action' => 'update',
        'id'     => null,
    ]) . $this->url->query([
        'tagsList' => $model->tagsList,
        'node'     => $entity->nodes[0]->id,
        'nodeType' => $info->name,
    ], true) ?>" class="item">
        New Child <?= $info->singular ?>
    </a></li>
    <?php if (!$entity->nodes[0]->isRoot()) { ?>
        <li><a href="<?= $this->url([
            'action' => 'update',
            'id'     => null,
        ]) . $this->url->query([
            'tagsList' => $model->tagsList,
            'node'     => $entity->nodes[0]->parent->id,
            'nodeType' => $info->name,
        ], true) ?>" class="item">
            New Sibling <?= $info->singular ?>
        </a></li>
    <?php } ?>
<?php } ?>