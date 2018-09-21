<?php $this->outer('layout/body_center', [
    'title' => $info->plural,
], 'core') ?>
<?php $this->block('main') ?>

<? foreach ($req->data->entities as $entity) { ?>
    <?php
    echo $entity['card'];
    unset($entity['card']);
    var_dump($entity);
    ?>
<? } ?>