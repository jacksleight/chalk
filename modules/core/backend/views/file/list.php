<?php if (in_array('upload', $actions)) { ?>
    <div class="uploadable">
        <?= $this->parent() ?>
        <input
            class="uploadable-input"
            type="file"
                data-url="<?= $this->url([
                'action' => 'upload',
            ]) . $this->url->query([
                'mode'     => $model->mode,
                'tagsList' => $model->tagsList,
            ], true) ?>"
            data-max-file-size="<?= isset($this->chalk->config->maxFileSize) ? $this->chalk->config->maxFileSize : null ?>"
            multiple>
        <script type="x-tmpl-mustache" class="uploadable-template">
            <li class="thumbs_i">
                <?= $this->render('/element/thumb', ['template' => true], 'core') ?>
            </li>
        </script>
    </div>
<?php } else { ?>
    <?= $this->parent() ?>
<?php } ?>