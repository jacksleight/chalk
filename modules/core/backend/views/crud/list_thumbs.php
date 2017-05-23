<div class="multiselectable">
    <!-- <input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label> -->
    <?= $this->render('/element/form-input', [
        'type'   => 'input_hidden',
        'entity' => $model,
        'name'   => 'selectedList',
        'class'  => 'multiselectable-values',
    ], 'core') ?>
    <ul class="thumbs uploadable-list">
        <?php foreach ($entities as $entity) { ?>
            <li class="thumbs_i <?= array_intersect(['batch', 'select-all'], $actions) ? 'selectable' : null ?> <?= array_intersect(['update', 'select-one'], $actions) ? 'clickable' : null ?>">
                <?php if (array_intersect(['batch', 'select-all'], $actions)) { ?>
                    <?= $this->partial('checkbox', [
                        'entity' => $entity,
                    ]) ?>
                <?php } ?>
                <?= $this->partial('link', [
                    'entity'  => $entity,
                    'content' => $this->inner('/element/thumb', ['entity' => $entity], 'core'),
                ]) ?>
            </li>
        <?php } ?>     
        <?= str_repeat('<li></li>', 10) ?>
    </ul>
    <?php if (!count($entities)) { ?>
        <div class="notice">
            <?= $this->partial('notice') ?>
        </div>
    <?php } ?>  
</div>