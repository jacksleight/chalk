<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<?php
$trees	= $this->entity('Ayre\Entity\Tree')->fetchAll();
$tree	= $this->entity('Ayre\Entity\Tree')->fetch($req->id);
if (!isset($tree)) {
	$tree = $trees[0];
}
?>
<div class="dropdown">
	<div class="value">
		<? if ($tree instanceof \Ayre\Entity\Domain) { ?>
			<i class="fa fa-globe fa-fw"></i>
		<? } else if ($tree instanceof \Ayre\Entity\Menu) { ?>
			<i class="fa fa-bars fa-fw"></i>
		<? } ?>
		<?= $tree->name ?>		
	</div>
	<nav class="menu">
		<ul>
			<? foreach ($trees as $tree) { ?>
				<li>
					<a href="<?= $this->url([
						'id' => $tree->id,
					]) ?>">
						<? if ($tree instanceof \Ayre\Entity\Domain) { ?>
							<i class="fa fa-globe fa-fw"></i>
						<? } else if ($tree instanceof \Ayre\Entity\Menu) { ?>
							<i class="fa fa-bars fa-fw"></i>
						<? } ?>
						<?= $tree->name ?>
					</a>
				</li>
			<? } ?>
		</ul>
	</nav>
</div>
<ol>
	<li class="tree-item" data-id="<?= $tree->root->id ?>">
		<div class="tree-handle ">Node <?= $tree->root->id ?></div>
	</li>
</ol>
<div class="tree">
	<?= $this->entity('Ayre\Entity\Tree\Node')->childrenHierarchy($tree->root, false, [
		'decorate'		=> true,
		'rootOpen'		=> '<ol class="tree-list">',
		'rootClose'		=> '</ol>',
		'childOpen'		=> '',
		'childClose'	=> '</li>',
		'nodeDecorator'	=> function($node) {
			return '
				<li class="tree-item" data-id="' . $node['id'] . '">
				<div class="tree-handle ">Node ' . $node['id'] . '</div>
			';
		}
	]) ?>
</div>
<p>
	<a href="<?= $this->url([
		'action'	=> 'node',
		'id'		=> $tree->id,
	]) ?>" class="btn btn-focus btn-block">Add Node</a>
</p>