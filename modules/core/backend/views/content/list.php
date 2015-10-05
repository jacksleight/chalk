<?php
$this->params([
    'bodyType'      => $bodyType      = isset($bodyType)      ? $bodyType       : 'table',
    'bodyClass'     => $bodyClass     = isset($bodyClass)     ? $bodyClass      : null,
    'isNewAllowed'  => $isNewAllowed  = isset($isNewAllowed)  ? $isNewAllowed   : true,
    'isEditAllowed' => $isEditAllowed = isset($isEditAllowed) ? $isEditAllowed  : true,
    'isClose'       => $isClose       = isset($isClose)       ? $isClose        : false,
    'isUploadable'  => $isUploadable  = is_a($info->class, 'Chalk\Core\File', true),
]);
?>

<? if ($isUploadable) { ?>
    <div class="uploadable">
<? } ?>

<div class="flex-col">
    <div class="header">
        <?= $this->partial('tools') ?>
        <?= $this->partial('header') ?>
    </div>
    <div class="flex body">
        <div class="hanging">
            <?= $this->partial('filters') ?>
        </div>
        <?= $this->partial('body') ?>
    </div>
    <div class="footer">
        <?= $this->partial('pagination') ?>
    </div>
</div>

<? if ($isUploadable) { ?>
    <input class="uploadable-input" type="file" name="files[]" data-url="<?= $this->url([
        'entity' => $info->name,
        'action' => 'upload',
    ], 'core_content', true) . $this->url->query([
        'isEditAllowed' => (int) $isEditAllowed,
    ], true) ?>" multiple>
    <script type="x-tmpl-mustache" class="uploadable-template">
        <?= $this->inner('/content/thumb', ['template' => true]) ?>
    </script>
    </div>
<? } ?>