<?php
$structure	= $this->em('Ayre\Entity\Structure')->fetch($req->structure);
$content	= $node->content;
$entityType	= \Ayre::type($content->getObject());
?>

<? $this->layout('/layouts/page_structure') ?>
<? $this->block('main') ?>

<? if ($entityType->name != 'core_content') { ?>
	<?= $this->render("/{$entityType->entity->path}/form", [
		'structure'		=> $structure,
		'content'		=> $content,
		'entityType'	=> $entityType,
	]) ?>
<? } ?>