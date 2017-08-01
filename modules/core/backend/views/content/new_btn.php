<?php if (isset($entity) && $entity->isNode()) { ?>
    <?php
    $info = $this->hook->fire('core_info_node', new Chalk\Info())->fetch('isPrimary')[0];
    ?>
    <a href="<?= $this->url([
        'action' => 'update',
        'id'     => null,
    ]) . $this->url->query([
        'nodeType' => $info->name,
    ], true) ?>" class="btn btn-focus <?= isset($class) ? $class : null ?> icon-add">
        New <?= $info->singular ?>
    </a>
<?php } else { ?>
    <?= $this->parent() ?>
<?php } ?>