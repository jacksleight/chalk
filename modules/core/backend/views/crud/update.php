<?php if (!$req->isAjax()) { ?>
    <?php
    $path = new Coast\Path($req->path());
    $this->outer("layout/page_{$path->part(1)}", [
        'title' => $info->plural,
    ], $path->part(0));
    ?>
    <?php $this->block('main') ?>
<?php } ?>

<form action="<?= $this->url->route() . $this->url->query() ?>" method="post" data-modal-size="800">
    <?= $this->inner("form") ?>
</form>