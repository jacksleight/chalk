<?php
$repo		= $this->em('Chalk\Core\Structure');
$structures	= $repo->all();
$structure	= $repo->id($req->structure);
?>
<?php $this->layout('/layout/body') ?>
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
		$root = $repo->fetchTree($structure)[0];
		?>
		<?php if (isset($root->content)) { ?>
			<ol class="tree-root">
				<li class="tree-node" data-id="<?= $root->id ?>">
					<a href="<?= $this->url([
						'structure'	=> $structure->id,
						'action'	=> 'edit',
						'node'		=> $root->id,
					], 'structure_node') ?>" class="tree-item <?= $root->id == $req->node ? 'active' : '' ?> tree-item-<?= $root->content->status ?> <?= $root->isHidden ? 'tree-item-hidden' : '' ?>">
						<?= $root->nameSmart ?>
					</a>
				</li>
			</ol>
		<?php } ?>
		<div class="tree">
			<?php  
			$it		= $root->iterator();
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
		<button class="btn btn-block structure-edit btn-collapse icon-move" type="button">
			Move Content
		</button>
		<button class="btn btn-block structure-cancel btn-collapse icon-cancel" disabled type="button">
			Cancel
		</button>
		<button class="btn btn-positive btn-block structure-save btn-collapse icon-ok" disabled>
			Save Changes
		</button>
		<a href="<?= $this->url([
			'action'	=> 'add',
			'structure'	=> $structure->id,
			'node'		=> $req->node,
		], 'structure_node', true) ?>" class="btn btn-focus btn-block icon-add">
			Add Content
		</a>
	</div>
	<input type="hidden" name="data" class="structure-data">
</form>