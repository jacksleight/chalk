<?= $this->parent() ?>
<div class="form-item">
    <label>File</label>
    <div class="input-upload">
        <div class="input-upload-controls">
            <span class="input-upload-button btn btn-lighter btn-out btn-icon icon-upload"><span>Upload</span></span>
        </div>
        <div class="input-upload-holder">
            <?= $this->inner('/element/card-upload', ['entity' => $entity->getObject()]) ?>
        </div>
        <script type="x-tmpl-mustache" class="input-upload-template">
            <?= $this->inner('/element/card-upload', ['template' => true]) ?>
        </script>
        <input
            class="input-upload-input"
            type="file"
                data-url="<?= $this->url([
                'action' => 'upload',
            ]) ?>"
            data-max-file-size="<?= isset($this->chalk->config->maxFileSize) ? $this->chalk->config->maxFileSize : null ?>">
    </div>
</div>