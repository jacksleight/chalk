<? $this->layout('/layouts/page', [
	'class' => 'upload',
]) ?>
<? $this->block('main') ?>
<?php
$filter = $this->entity->wrap(new \Ayre\Filter())
	->graphFromArray($req->queryParams());
$entites = $this->entity($entityType->class)
	->fetchAll($filter->toArray());
?>

<ul class="toolbar">
	<li>
		<span class="btn btn-focus upload-button">
			<i class="fa fa-upload"></i> Upload <?= $entityType->singular ?>
		</span>
	</li>
</ul>
<h1><?= $entityType->plural ?></h1>
<?= $this->render('/content/filters', ['filter' => $filter]) ?>
<ul class="thumbs upload-list">
	<? foreach ($entites as $entity) { ?>
		<?= $this->render('thumb', ['entity' => $entity]) ?>
	<? } ?>
</ul>
<input class="upload-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
<script type="x-tmpl-mustache" class="upload-template">
	<?= $this->render('thumb', ['template' => true]) ?>
</script>