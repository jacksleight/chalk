<? $this->layout('/layouts/page', [
	'class' => 'uploadable',
]) ?>
<? $this->block('main') ?>
<?php
$filter = $this->em->wrap(new \Ayre\Index())
	->graphFromArray($req->queryParams());
$entites = $this->em($entityType->class)
	->fetchAll($filter->toArray());
?>

<ul class="toolbar">
	<li>
		<span class="btn btn-focus uploadable-button">
			<i class="fa fa-upload"></i> Upload <?= $entityType->singular ?>
		</span>
	</li>
</ul>
<h1><?= $entityType->plural ?></h1>
<?= $this->render('/content/filters', ['filter' => $filter]) ?>
<ul class="thumbs uploadable-list multiselectable">
	<? foreach ($entites as $entity) { ?>
		<?= $this->render('thumb', ['entity' => $entity]) ?>
	<? } ?>
</ul>
<input class="uploadable-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
<script type="x-tmpl-mustache" class="uploadable-template">
	<?= $this->render('thumb', ['template' => true]) ?>
</script>