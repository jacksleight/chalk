<?php if ($isEditAllowed) { ?>
    <a href="<?= $this->url([
        'action' => 'edit',
        'id'     => $content->id,
    ]) ?>"><?php } ?><?= $content->name ?><?php if ($isEditAllowed) { ?></a>
<?php } ?>
<br>
<small><?= implode(' – ', $content->previewText($info->class != 'Chalk\Core\Content')) ?></small>