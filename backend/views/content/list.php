<?php
$this->params([
    'isNewAllowed'  => $isNewAllowed  = isset($isNewAllowed) ? $isNewAllowed : true,
    'isEditAllowed' => $isEditAllowed = isset($isEditAllowed) ? $isEditAllowed : true,
    'isUploadable'  => $isUploadable  = is_a($info->class, 'Chalk\Core\File', true),
    'bodyType'      => $bodyType      = isset($bodyType)   ? $bodyType   : 'table',
    'bodyClass'     => $bodyClass     = isset($bodyClass)  ? $bodyClass  : null,
]);
?>

<? if ($isUploadable) { ?>
    <div class="uploadable">
<? } ?>

<div class="flex-col">
    <div class="flex body">
        <?= $this->partial('tools') ?>
        <?= $this->partial('header') ?>
        <?= $this->partial('filters') ?>
        <?= $this->partial('body') ?>
    </div>
    <div class="footer">
        <?= $this->partial('pagination') ?>
    </div>
</div>

<? if ($isUploadable) { ?>
    <input class="uploadable-input" type="file" name="files[]" data-url="<?= $this->url([
        'entity' => $info->name,
        'action' => 'upload'
    ], 'core_content', true) ?>" multiple>
    <script type="x-tmpl-mustache" class="uploadable-template">
        <?= $this->inner('/content/thumb', ['template' => true]) ?>
    </script>
    </div>
<? } ?>