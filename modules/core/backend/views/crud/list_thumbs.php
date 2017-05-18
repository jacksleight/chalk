<div class="multiselectable">
    <?= $this->render('/element/form-input', [
        'type'   => 'input_hidden',
        'entity' => $index,
        'name'   => 'selected',
        'class'  => 'multiselectable-values',
    ], 'core') ?>
    <ul class="thumbs uploadable-list">
        <?php foreach ($entities as $entity) { ?>
            <li class="thumbs_i"><?= $this->partial('thumb', ['entity' => $entity]) ?></li>
        <?php } ?>     
        <?= str_repeat('<li></li>', 10) ?>
    </ul>
    <?php if (!count($entities)) { ?>
        <div class="notice">
            <?= $this->partial('notice') ?>
        </div>
    <?php } ?>  
</div>