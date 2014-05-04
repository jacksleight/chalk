<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<?php
$repo    = $this->em('Ayre\Entity\Structure');
$structs = $repo->fetchAll();
$struct	 = $repo->fetch($req->id);
if (!isset($struct)) {
	$struct = $structs[0];
}
?>
<form action="<?= $this->url([
	'id'		=> $struct->id,
	'action'	=> 'reorder',
]) ?>" class="fill structure" method="post">
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
					<? foreach ($structs as $entity) { ?>
						<li>
							<a href="<?= $this->url([
								'id' => $entity->id,
							]) ?>">
								<? if ($entity instanceof \Ayre\Entity\Domain) { ?>
									<i class="fa fa-globe fa-fw"></i>
								<? } else if ($entity instanceof \Ayre\Entity\Menu) { ?>
									<i class="fa fa-bars fa-fw"></i>
								<? } ?>
								<?= $entity->label ?>
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
			<li class="tree-item" data-id="<?= $tree[0]->id ?>">
				<div class="tree-handle "><?= $tree[0]->name ?></div>
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
					<li class="tree-item" data-id="<?= $node->id ?>">
						<div class="tree-handle tree-status-<?= $content->status ?>"><?= $node->name ?></div>
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
		<a href="<?= $this->url([
			'action'	=> 'add',
			'id'		=> $struct->id,
		]) ?>" class="btn btn-focus btn-block active">
			<i class="fa fa-plus"></i> Add Content
		</a>
		<button class="btn-positive btn-block structure-submit" disabled>
			<i class="fa fa-check"></i> Save Changes
		</button>
	</div>
	<input type="hidden" name="data" class="structure-data">
</form>