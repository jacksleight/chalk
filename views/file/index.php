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
		<span class="btn upload-button">
			<i class="fa fa-upload"></i> Upload <?= $entityType->info->singular ?>
		</span>
	</li>
</ul>
<h1><?= $entityType->info->plural ?></h1>
<?= $this->render('/content/filters', ['filter' => $filter]) ?>
<ul class="thumbs upload-list">
	<? foreach ($entites as $entity) { ?>
		<?= $this->render('/content/thumb', ['content' => $entity]) ?>
	<? } ?>
</ul>
<input class="upload-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
<script type="x-tmpl-mustache" class="upload-template">
	<?= $this->render('/content/thumb', ['template' => true]) ?>
</script>