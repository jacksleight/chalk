<li class="thumbs_i <?= array_intersect(['batch', 'select-all'], $actions) ? 'selectable' : null ?> <?= array_intersect(['update', 'select-one'], $actions) ? 'clickable' : null ?>">
    <?php if (array_intersect(['batch', 'select-all'], $actions)) { ?>
        <?= $this->inner('list_checkbox', [
            'entity' => $entity,
        ]) ?>
    <?php } ?>
    <?= $this->inner('list_link', [
        'entity'  => $entity,
        'content' => $this->inner('/element/thumb', ['entity' => $entity], 'core'),
    ]) ?>
</li>