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


<div class="tree">
	<ol class="tree-list">
		<li class="tree-item" data-id="1">
			<div class="tree-handle ">Home</div>
		</li>
		<li class="tree-item" data-id="3">
			<div class="tree-handle">Our Services</div>
			<ol class="tree-list">
				<li class="tree-item" data-id="4">
					<div class="tree-handle">Explosions</div>
				</li>
				<li class="tree-item" data-id="5">
					<div class="tree-handle">Demolitions</div>
				</li>
				<li class="tree-item" data-id="5">
					<div class="tree-handle">Crash &amp; Burn</div>
				</li>
			</ol>
		</li>
		<li class="tree-item" data-id="2">
			<div class="tree-handle">About Us</div>
		</li>
		<li class="tree-item" data-id="2">
			<div class="tree-handle">Contact Us</div>
		</li>
	</ol>
</div>