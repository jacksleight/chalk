<?php
$struct = $this->em('Chalk\Core\Structure')->id($req->structure);
?>
<?php $this->layout('/layouts/page_structure') ?>
<?php $this->block('main') ?>

<?= $this->render('/content/browser', ['struct' => $struct]) ?>