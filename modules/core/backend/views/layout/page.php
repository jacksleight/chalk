<?php if (!$req->isAjax()) { ?>
    <?php
    $root = $this->navList->root();
    $info = Chalk\Chalk::info($root['name']);
    $this->outer("layout/page_{$info->local->name}", [], $info->module->name);
    ?>
    <?php $this->block('main') ?>
    <?= $this->content('main') ?>
    <?php $this->block('sidebar') ?>
    <?= $this->content('sidebar') ?>
    <?php $this->block('foot') ?>
    <?= $this->content('foot') ?>
<?php } else { ?>
    <?= $this->content('main') ?>
<?php } ?>