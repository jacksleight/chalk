<? $this->layout('/layouts/page') ?>
<? $this->block('main') ?>

<input id="fileupload" type="file" name="files[]" data-url="<?= $this->url('index/upload') ?>" multiple>