<div class="form-item">
    <label>File</label>
    <div class="input-upload">
        <div class="input-upload-controls">
            <span class="input-upload-button btn btn-lighter btn-out btn-icon icon-upload"><span>Upload</span></span>
        </div>
        <div class="input-upload-holder">
            <?= $this->inner('/content/card-upload', ['content' => $content->getObject()]) ?>
        </div>
        <script type="x-tmpl-mustache" class="input-upload-template">
            <?= $this->inner('/content/card-upload', ['template' => true]) ?>
        </script>
        <input class="input-upload-input" type="file" name="files[]" data-url="<?= $this->url([
            'action' => 'upload',
        ]) ?>">
    </div>
</div>