<h2>No <?= $info->plural ?> Found</h2>
<?php if (isset($index->search) && strrpos($index->search, '*') === false) { ?>
    <p>To search for partial words use an asterisk, eg. "<a href="<?= $this->url->query(['search' => "*{$index->search}*"]) ?>"><?= "*{$index->search}*" ?></a>".</p>
<?php } else if ($isAddAllowed) { ?>
    <p>To create a new <?= strtolower($info->singular) ?> click the 'New <?= $info->singular ?>' button above.</p>
<?php } ?>