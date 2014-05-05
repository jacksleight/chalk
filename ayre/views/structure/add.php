<? $this->layout('/layouts/page_structure') ?>
<? $this->block('main') ?>

<?php
$struct = $this->em('Ayre\Entity\Structure')->fetch($req->id);
?>

<?= $this->render('/content/browser', ['struct' => $struct]) ?>