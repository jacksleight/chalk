<?php
$repo		= $this->em('Ayre\Core\Structure');
$structures	= $repo->fetchAll();
$structure	= $repo->fetch($req->structure ?: 3);
$repo->fetchTree($structure);
?>
<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<form action="<?= $this->url([
	'action'	=> 'reorder',
	'structure'	=> $structure->id,
], 'structure', true) ?>" class="fill structure" method="post">
	<div class="fix">
		<div class="dropdown">
			<div class="value">
				<? if ($structure instanceof \Ayre\Core\Domain) { ?>
					<i class="fa fa-globe fa-fw"></i>
				<? } else if ($structure instanceof \Ayre\Core\Menu) { ?>
					<i class="fa fa-bars fa-fw"></i>
				<? } ?>
				<strong><?= $structure->label ?></strong>
			</div>
			<nav class="menu">
				<ul>
					<? foreach ($structures as $listStructure) { ?>
						<li>
							<a href="<?= $this->url([
								'structure' => $listStructure->id,
							]) ?>" class="item">
								<? if ($listStructure instanceof \Ayre\Core\Domain) { ?>
									<i class="fa fa-globe fa-fw"></i>
								<? } else if ($listStructure instanceof \Ayre\Core\Menu) { ?>
									<i class="fa fa-bars fa-fw"></i>
								<? } ?>
								<?= $listStructure->label ?>
							</a>
						</li>
					<? } ?>
				</ul>
			</nav>
		</div>
	</div>
	<div class="flex">
		<?php
		$repo->fetchTree($structure);
		?>
		<ol class="tree-root">
			<li class="tree-node" data-id="<?= $structure->root->id ?>">
				<a href="<?= $this->url([
					'structure'	=> $structure->id,
					'action'	=> 'edit',
					'node'		=> $structure->root->id,
				], 'structure_node') ?>" class="tree-item <?= $structure->root->id == $req->node ? 'active' : '' ?>">
					<?= $structure->root->nameSmart ?>
				</a>
			</li>
		</ol>
		<div class="tree">
			<?php  
			$it		= $structure->root->iterator();
			$depth	= 0;
			$i		= 0;
			?>
			<ol class="tree-list">
				<? foreach ($it as $node) { ?>
					<?php
					$content = $node->content->last;
					?>
					<? if ($it->getDepth() > $depth) { ?>
						<ol class="tree-list">
					<? } else if ($it->getDepth() < $depth) { ?>
						<?= str_repeat('</li></ol>', $depth - $it->getDepth()) ?>
					<? } else if ($i > 0) { ?>
						</li>
					<? } ?>
					<li class="tree-node" data-id="<?= $node->id ?>">
						<a href="<?= $this->url([
							'structure'	=> $structure->id,
							'action'	=> 'edit',
							'node'		=> $node->id,
						], 'structure_node') ?>" class="tree-item <?= $node->id == $req->node ? 'active' : '' ?> tree-status-<?= $content->status ?>">
							<?= $node->nameSmart ?>
						</a>
						<span class="tree-handle"></span>
					<?php				
					$depth = $it->getDepth();
					$i++;
					?>
				<? } ?>
				<? if ($depth > 0) { ?>
					<?= str_repeat('</li></ol>', $depth) ?>
				<? } ?>
			</ol>
		</div>
	</div>
	<div class="fix">
		<button class="btn-positive btn-block structure-submit btn-collapse" disabled>
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