<?php $this->outer('layout/body_center', [
    'title' => $info->plural,
], 'core') ?>
<?php $this->block('main') ?>

<? foreach ($req->data->items as $item) { ?>
    <?php
    echo $item['card'];
    unset($item['card']);
    var_dump($item);
    ?>
<? } ?>