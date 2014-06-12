<?php
$filter = $this->em->wrap(new \Ayre\Core\Model\Index())
	->graphFromArray($req->queryParams());
$contents = $this->em($entity->class)
	->fetchAll($filter->toArray());
?>

<form action="<?= $this->url->route() ?>" class="uploadable">
	<ul class="toolbar">
		<li>
			<span class="btn btn-focus uploadable-button">
				<i class="fa fa-upload"></i> Upload <?= $entity->singular ?>
			</span>
		</li>
	</ul>
	<h1><?= $entity->plural ?></h1>
	<?= $this->render('/content/filters', ['filter' => $filter]) ?>
	<ul class="thumbs uploadable-list multiselectable">
		<? if (count($contents)) { ?>
			<? foreach ($contents as $content) { ?>
				<li><?= $this->render('/content/thumb', ['content' => $content]) ?></li>
			<? } ?>
		<? } else { ?>
			<li><?= $this->render('/content/thumb', ['template' => true]) ?></li>
		<? } ?>		
	</ul>
	<input class="uploadable-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
	<script type="x-tmpl-mustache" class="uploadable-template">
		<?= $this->render('/content/thumb', ['template' => true]) ?>
	</script>
</form>