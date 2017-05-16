<div class="multiselectable">
    <?= $this->render('/element/form-input', [
        'type'   => 'input_hidden',
        'entity' => $index,
        'name'   => 'entityIds',
        'class'  => 'multiselectable-values',
    ], 'core') ?>
    <ul class="thumbs <?= $isUploadable ? 'uploadable-list' : null ?>">
        <?php foreach ($entities as $entity) { ?>
            <li class="thumbs_i"><?= $this->inner('thumb', ['content' => $entity]) ?></li>
        <?php } ?>     
        <?= str_repeat('<li></li>', 10) ?>
    </ul>
    <?php if (!count($entities)) { ?>
        <div class="notice">
            <h2>No <?= $info->plural ?> Found</h2>
            <?php if (isset($index->search) && strrpos($index->search, '*') === false) { ?>
                <p>To search for partial words use an asterisk, eg. "<a href="<?= $this->url->query(['search' => "*{$index->search}*"]) ?>"><?= "*{$index->search}*" ?></a>".</p>
            <?php } else if ($isAddAllowed) { ?>
                <p>To create a new <?= strtolower($info->singular) ?> click the 'New <?= $info->singular ?>' button above.</p>
            <?php } ?>
        </div>
    <?php } ?>  
</div>