<h2>No <?= $info->plural ?> Found</h2>
<?php if (isset($model->search) && strrpos($model->search, '*') === false) { ?>
    <p>To search for partial words use an asterisk, eg. "<a href="<?= $this->url->query(['search' => "*{$model->search}*"]) ?>"><?= "*{$model->search}*" ?></a>".</p>
<?php } else if (array_intersect(['create'], $actions)) { ?>
    <p>To create a new <?= strtolower($info->singular) ?> click the 'New <?= $info->singular ?>' button above.</p>
<?php } ?>