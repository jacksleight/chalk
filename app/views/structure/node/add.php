<?php
$struct = $this->em('core_structure')->id($req->structure);
?>
<? $this->layout('/layouts/page_structure') ?>
<? $this->block('main') ?>

<?= $this->render('/content/browser', ['struct' => $struct]) ?>