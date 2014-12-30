<?php
$struct = $this->em('Chalk\Core\Structure')->id($req->structure);
?>
<?php $this->parent('/layout/page_structure') ?>
<?php $this->block('main') ?>

<?= $this->child('/content/browser', ['struct' => $struct]) ?>