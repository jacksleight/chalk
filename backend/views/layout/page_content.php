<?php $this->outer('/layout/body') ?>
<?php $this->block('main') ?>

<?= $this->content('main') ?>

<?php $this->block('sidebar') ?>

<?php
$entities = $this->chalk->module('core')->contentEntities();
?>
<nav class="nav" role="navigation">
    <ul>
        <? foreach ($entities as $entityInfo) { ?>
            <li><a href="<?= $this->url([
                'entity' => $entityInfo->name,
            ], 'content', true) ?>" class="item <?= $entityInfo->name == $info->name ? 'active' : null ?>"><?= $entityInfo->plural ?></a></li>
        <? } ?>
    </ul>
</nav>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>