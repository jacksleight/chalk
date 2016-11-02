<?php
$this->params([
    'bodyType'      => $bodyType      = isset($bodyType)      ? $bodyType       : 'table',
    'isAddAllowed'  => $isAddAllowed  = isset($isAddAllowed)  ? $isAddAllowed   : true,
    'isEditAllowed' => $isEditAllowed = isset($isEditAllowed) ? $isEditAllowed  : true,
    'isUploadable'  => $isUploadable  = is_a($info->class, 'Chalk\Core\File', true),
]);
?>

<?php if ($isUploadable) { ?>
    <div class="uploadable">
<?php } ?>

<div class="flex-col">
    <div class="header">
        <?= $this->partial('tools') ?>
        <?= $this->partial('header') ?>
    </div>
    <div class="flex body">
        <div class="hanging">
            <?= $this->partial('filters') ?>
        </div>
        <?= $this->partial($bodyType) ?>
    </div>
    <div class="footer">
        <?= $this->partial('pagination') ?>
    </div>
</div>

<?php if ($isUploadable) { ?>
    <input
        class="uploadable-input"
        type="file"
        name="files[]"
            data-url="<?= $this->url([
            'entity' => $info->name,
            'action' => 'upload',
        ], 'core_content', true) . $this->url->query([
            'isEditAllowed' => (int) $isEditAllowed,
        ], true) ?>"
        data-max-file-size="<?= isset($this->chalk->config->maxFileSize) ? $this->chalk->config->maxFileSize : null ?>"
        multiple>
    <script type="x-tmpl-mustache" class="uploadable-template">
        <?= $this->inner('/content/thumb', ['template' => true]) ?>
    </script>
    </div>
<?php } ?>