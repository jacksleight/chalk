<?php $this->outer('/layout/body') ?>
<?php $this->block('main') ?>

<?= $this->content('main') ?>

<?php $this->block('sidebar') ?>

<nav class="nav" role="navigation">
    <ul>
        <? foreach ($this->contentList as $name => $classInfo) { ?>
            <li><a href="<?= $this->url([
                'entity' => $classInfo->name,
            ], 'core_content', true) ?>" class="item <?= $classInfo->name == $info->name ? 'active' : null ?>"><?= $classInfo->plural ?></a></li>
        <? } ?>
    </ul>
</nav>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>