<?php $this->outer('/layout/body') ?>
<?php $this->block('main') ?>

<?= $this->content('main') ?>

<?php $this->block('sidebar') ?>

<?php
$classes = $this->event->fire('core_listContents')->contents();
?>
<nav class="nav" role="navigation">
    <ul>
        <? foreach ($classes as $class) { ?>
            <?php
            $classInfo = \Chalk\Chalk::info($class);
            ?>
            <li><a href="<?= $this->url([
                'entity' => $classInfo->name,
            ], 'content', true) ?>" class="item <?= $classInfo->name == $info->name ? 'active' : null ?>"><?= $classInfo->plural ?></a></li>
        <? } ?>
    </ul>
</nav>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>