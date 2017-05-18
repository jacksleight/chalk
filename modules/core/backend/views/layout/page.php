<?php if (!$req->isAjax()) { ?>
    <?php
    $path = new Coast\Path($req->path());
    $this->outer("layout/page_{$path->part(1)}", [], $path->part(0));
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