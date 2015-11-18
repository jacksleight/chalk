<? if ($isEditAllowed) { ?>
    <a href="<?= $this->url([
        'action'    => 'edit',
        'content'   => $content->id,
    ]) ?>"><? } ?><?= $content->name ?><? if ($isEditAllowed) { ?></a>
<? } ?>
<br>
<small><?= implode(' – ', $content->previewText($info->class != 'Chalk\Core\Content')) ?></small>