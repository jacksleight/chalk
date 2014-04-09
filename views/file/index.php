<? $this->layout('/layouts/page', [
	'class' => 'upload',
]) ?>
<? $this->block('main') ?>

<ul class="toolbar">
	<li>
		<span class="button upload-button">
			<i class="fa fa-upload"></i> Upload <?= $req->type->info->singular ?>
		</span>
	</li>
</ul>
<h1><?= $req->type->info->plural ?></h1>
<?php
$contents = array_reverse($this->entity($req->type->class)->findAll());
?>
<ul class="filters">
	<li>
		<input type="search" placeholder="Search">
	</li>
	<li>
		<div><i class="fa fa-file-o"></i>Type</div>
	</li>
	<li>
		<div><i class="fa fa-calendar"></i> Date Added</div>
	</li>
	<li>
		<div><i class="fa fa-user"></i> Added By</div>
	</li>
	<li>
		<div><i class="fa fa-check-circle"></i>Draft, Pending, Published</div>
	</li>
</ul>
<ul class="thumbs upload-list">
	<? foreach ($contents as $content) { ?>
		<?= $this->render('/content/thumb', ['content' => $content]) ?>
	<? } ?>
</ul>
<input class="upload-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
<script type="x-tmpl-mustache" class="upload-template">
	<?= $this->render('/content/thumb', ['template' => true]) ?>
</script>