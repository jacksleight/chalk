<?php
$repo		= $this->em('core_structure');
$structures	= $repo->fetchAll();
$structure	= $repo->id($req->structure);
$repo->fetchTree($structure);
?>
<?php $this->layout('/layouts/body') ?>
<?php $this->block('main') ?>

<?= $content->main ?>

<?php $this->block('sidebar') ?>

<form action="<?= $this->url([
	'action'	=> 'reorder',
	'structure'	=> $structure->id,
], 'structure', true) ?>" class="fill structure" method="post">
	<div class="fix">
		<div class="dropdown">
			<div class="value">
				<strong><?= $structure->name ?></strong>
			</div>
			<nav class="menu">
				<ul>
					<?php foreach ($structures as $listStructure) { ?>
						<li>
							<a href="<?= $this->url([
								'structure'	=> $listStructure->id,
								'action'	=> 'index',
							], 'structure') ?>" class="item">
								<?= $listStructure->name ?>
							</a>
						</li>
					<?php } ?>
				</ul>
			</nav>
		</div>
	</div>
	<div class="flex">
		<?php
		$repo->fetchTree($structure);
		?>
		<?php if (isset($structure->root->content)) { ?>
			<ol class="tree-root">
				<li class="tree-node" data-id="<?= $structure->root->id ?>">
					<a href="<?= $this->url([
						'structure'	=> $structure->id,
						'action'	=> 'edit',
						'node'		=> $structure->root->id,
					], 'structure_node') ?>" class="tree-item <?= $structure->root->id == $req->node ? 'active' : '' ?> tree-item-<?= $structure->root->content->status ?> <?= $structure->root->isHidden ? 'tree-item-hidden' : '' ?>">
						<?= $structure->root->nameSmart ?>
					</a>
				</li>
			</ol>
		<?php } ?>
		<div class="tree">
			<?php  
			$it		= $structure->root->iterator();
			$depth	= 0;
			$i		= 0;
			?>
			<ol class="tree-list">
				<?php foreach ($it as $node) { ?>
					<?php
					$content = $node->content->last;
					?>
					<?php if ($it->getDepth() > $depth) { ?>
						<ol class="tree-list">
					<?php } else if ($it->getDepth() < $depth) { ?>
						<?= str_repeat('</li></ol>', $depth - $it->getDepth()) ?>
					<?php } else if ($i > 0) { ?>
						</li>
					<?php } ?>
					<li class="tree-node" data-id="<?= $node->id ?>">
						<a href="<?= $this->url([
							'structure'	=> $structure->id,
							'action'	=> 'edit',
							'node'		=> $node->id,
						], 'structure_node') ?>" class="tree-item <?= $node->id == $req->node ? 'active' : '' ?> tree-item-<?= $content->status ?> <?= $node->isHidden ? 'tree-item-hidden' : '' ?>">
							<?= $node->nameSmart ?>
						</a>
						<span class="tree-handle"></span>
					<?php				
					$depth = $it->getDepth();
					$i++;
					?>
				<?php } ?>
				<?php if ($depth > 0) { ?>
					<?= str_repeat('</li></ol>', $depth) ?>
				<?php } ?>
			</ol>
		</div>
	</div>
	<div class="fix">
		<button class="btn btn-positive btn-block structure-submit btn-collapse" disabled>
			<i class="fa fa-check"></i> Save Changes
		</button>
		<a href="<?= $this->url([
			'action'	=> 'add',
			'structure'	=> $structure->id,
			'node'		=> $req->node,
		], 'structure_node', true) ?>" class="btn btn-focus btn-block">
			<i class="fa fa-plus"></i> Add Content
		</a>
	</div>
	<input type="hidden" name="data" class="structure-data">
</form>