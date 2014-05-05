<?php 
$menu	= $this->ayre->em('Ayre\Entity\Domain')->fetch(3);
$nodes	= $this->ayre->em('Ayre\Entity\Structure\Node')->fetchChildren($menu->root, true);
?>
<ul>
	<? foreach ($nodes as $node) { ?>
		<li><a href="<?= $this->url($node->slugPathReal) ?>"><?= $node->content->last->name ?></a></li>
	<? } ?>
</ul>

<h1><?= $page->name ?></h1>
<?= $content ?>