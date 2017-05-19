<h2>No <?= $info->plural ?> Found</h2>
<?php if (isset($model->search) && strrpos($model->search, '*') === false) { ?>
    <p>To search for partial words use an asterisk, eg. "<a href="<?= $this->url->query(['search' => "*{$model->search}*"]) ?>"><?= "*{$model->search}*" ?></a>".</p>
<?php } else if ($isAddAllowed) { ?>
    <p>To add <?= strtolower($info->plural) ?> click the 'Upload <?= $info->plural ?>' button above.</p>
<?php } ?>