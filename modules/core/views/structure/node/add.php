<?php
$struct = $this->em('Ayre\Core\Structure')->fetch($req->structure);
?>
<? $this->layout('/layouts/page_structure') ?>
<? $this->block('main') ?>

<?= $this->render('/content/browser', ['struct' => $struct]) ?>