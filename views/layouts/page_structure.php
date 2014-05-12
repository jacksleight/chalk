<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<?php
$repo    = $this->em('Ayre\Entity\Domain');
$structs = $repo->fetchAll();
$struct	 = $repo->fetch($req->structure);
if (!isset($struct)) {
	$struct = $structs[0];
}
?>
<form action="<?= $this->url([
	'action'	=> 'reorder',
	'structure'	=> $struct->id,
], 'structure', true) ?>" class="fill structure" method="post">
	<div class="fix">
		<div class="dropdown">
			<div class="value">
				<? if ($struct instanceof \Ayre\Entity\Domain) { ?>
					<i class="fa fa-globe fa-fw"></i>
				<? } else if ($struct instanceof \Ayre\Entity\Menu) { ?>
					<i class="fa fa-bars fa-fw"></i>
				<? } ?>
				<strong><?= $struct->label ?></strong>		
			</div>
			<nav class="menu">
				<ul>
					<? foreach ($structs as $listStruct) { ?>
						<li>
							<a href="<?= $this->url([
								'structure' => $listStruct->id,
							]) ?>">
								<? if ($listStruct instanceof \Ayre\Entity\Domain) { ?>
									<i class="fa fa-globe fa-fw"></i>
								<? } else if ($listStruct instanceof \Ayre\Entity\Menu) { ?>
									<i class="fa fa-bars fa-fw"></i>
								<? } ?>
								<?= $listStruct->label ?>
							</a>
						</li>
					<? } ?>
				</ul>
			</nav>
		</div>
	</div>
	<div class="flex">
		<?php
		$tree = $repo->fetchTree($struct);
		?>
		<ol class="tree-root">
			<li class="tree-node" data-id="<?= $tree[0]->id ?>">
				<a href="<?= $this->url([
					'structure'	=> $struct->id,
					'action'	=> 'edit',
					'node'		=> $tree[0]->id,
				], 'structure_node') ?>" class="tree-item <?= $tree[0]->id == $req->node ? 'active' : '' ?>">
					<?= $tree[0]->nameSmart ?>
				</a>
			</li>
		</ol>
		<div class="tree">
			<?php  
			$it = new \RecursiveIteratorIterator(
				new \Ayre\Entity\Structure\Iterator($tree[0]->children),
				\RecursiveIteratorIterator::SELF_FIRST);
			$depth = 0;
			$i	   = 0;
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
							'structure'	=> $struct->id,
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
			'structure'	=> $struct->id,
			'node'		=> $req->node,
		], 'structure_node', true) ?>" class="btn btn-focus btn-block">
			<i class="fa fa-plus"></i> Add Content
		</a>
	</div>
	<input type="hidden" name="data" class="structure-data">
</form>