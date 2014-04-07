<? $this->layout('/layouts/page', [
	'class' => 'upload',
]) ?>
<? $this->block('main') ?>

<ul class="toolbar">
	<!-- <li>
		<a href="<?= $this->url(['action' => 'edit']) ?>" class="button">
			<i class="fa fa-plus"></i>
			Add
		</a>
	</li> -->
	<li>
		<span class="button upload-button">
			<i class="fa fa-upload"></i> Upload
		</span>
	</li>
</ul>
<h1>Files</h1>
<?php
$files = array_reverse($this->entity('core_file')->findAll());
?>
<ul class="thumbs upload-list">
	<? foreach ($files as $file) { ?>
		<?= $this->render('thumb', ['file' => $file]) ?>
	<? } ?>
	<!-- <li class="intro">
		Hit Upload or drag and drop files/folders here.
	</li> -->
</ul>
<input class="upload-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
<script type="x-tmpl-mustache" class="upload-template">
	<?= $this->render('thumb', ['template' => true]) ?>
</script>