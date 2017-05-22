<?php
$this->params([
    'bodyType'      => $bodyType      = isset($bodyType)      ? $bodyType       : 'thumbs',
]);
?>

<div class="uploadable">
    <?= $this->parent() ?>
    <input
        class="uploadable-input"
        type="file"
        name="files[]"
            data-url="<?= $this->url([
            'action' => 'upload',
        ]) . $this->url->query([
            'tagsList' => $model->tagsList,
        ], true) ?>"
        data-max-file-size="<?= isset($this->chalk->config->maxFileSize) ? $this->chalk->config->maxFileSize : null ?>"
        multiple>
    <script type="x-tmpl-mustache" class="uploadable-template">
        <?= $this->inner('/content/list_thumb', ['template' => true]) ?>
    </script>
</div>