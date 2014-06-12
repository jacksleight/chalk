<?php
$structure	= $this->em('Ayre\Core\Structure')->fetch($req->structure);
$content	= $node->content;
$entity	= \Ayre::entity($content->getObject());
?>

<? $this->layout('/layouts/page_structure') ?>
<? $this->block('main') ?>

<? if ($entity->name != 'core_content') { ?>
	<?= $this->render("/{$entity->entity->path}/form", [
		'structure'		=> $structure,
		'content'		=> $content,
		'entity'	=> $entity,
	]) ?>
<? } ?>