<div class="multiselectable">
    <?= $this->render('/element/form-input', [
        'type'   => 'input_hidden',
        'entity' => $index,
        'name'   => 'contentIds',
        'class'  => 'multiselectable-values',
    ], 'core') ?>
    <ul class="thumbs <?= $isUploadable ? 'uploadable-list' : null ?>">
        <?php foreach ($contents as $content) { ?>
            <li class="thumbs_i"><?= $this->inner('thumb', ['content' => $content]) ?></li>
        <?php } ?>     
        <?= str_repeat('<li></li>', 10) ?>
    </ul>
    <?php if (!count($contents)) { ?>
        <div class="notice">
            <h2>No <?= $info->plural ?> Found</h2>
            <?php if ($isAddAllowed) { ?>
                <p>To create a new <?= strtolower($info->singular) ?> click the 'New <?= $info->singular ?>' button above.</p>
            <?php } ?>
        </div>
    <?php } ?>  
</div>