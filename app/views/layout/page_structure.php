<?php
$structures	= $this->em('Chalk\Core\Structure')->all();
$structure	= $this->em('Chalk\Core\Structure')->id($req->structure);
?>
<?php $this->layout('/layout/body') ?>
<?php $this->block('main') ?>

<?= $content->main ?>

<?php $this->block('sidebar') ?>

<form action="<?= $this->url([
	'action'	=> 'reorder',
	'structure'	=> $structure->id,
], 'structure', true) ?>?redirect=<?= $this->url([]) ?>" class="fill structure" method="post">
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
		<?php if (isset($structure->root->content)) { ?>
			<ol class="tree-root">
				<?php
				$content = $structure->root['content'];
				?>
				<li class="tree-node" data-id="<?= $structure->root['id'] ?>">
					<a href="<?= $this->url([
						'structure'	=> $structure->id,
						'action'	=> 'edit',
						'node'		=> $structure->root['id'],
					], 'structure_node') ?>" class="tree-item <?= $structure->root['id'] == $req->node ? 'active' : '' ?> tree-item-<?= $content['status'] ?> <?= $structure->root['isHidden'] ? 'tree-item-hidden' : '' ?>">
						<?= isset($structure->root['name']) ? $structure->root['name'] : $structure->root['content']['name'] ?>
					</a>
				</li>
			</ol>
		<?php } ?>
		<div class="tree">
			<?php  
			$nodes		= $this->em('Chalk\Core\Structure\Node')->children($structure->root);
			$depth		= 0;
			$i			= 0;
			$statuses	= $req->user->pref('nodes');
			?>
			<ol class="tree-list">
				<?php foreach ($nodes as $node) { ?>
					<?php
					$content = $node['content'];
					?>
					<?php if (($node['depth'] - 1) > $depth) { ?>
						<ol class="tree-list">
					<?php } else if (($node['depth'] - 1) < $depth) { ?>
						<?= str_repeat('</li></ol>', $depth - ($node['depth'] - 1)) ?>
					<?php } else if ($i > 0) { ?>
						</li>
					<?php } ?>
					<li class="tree-node <?= (!isset($statuses[$node['id']]) || !$statuses[$node['id']]) && $node['right'] > $node['left'] + 1 ? 'tree-collapsed' : null ?>" data-id="<?= $node['id'] ?>">
						<a href="<?= $this->url([
							'structure'	=> $structure->id,
							'action'	=> 'edit',
							'node'		=> $node['id'],
						], 'structure_node') ?>" class="tree-item <?= $node['id'] == $req->node ? 'active' : '' ?> tree-item-<?= $content['status'] ?> <?= $node['isHidden'] ? 'tree-item-hidden' : '' ?>">
							<?= isset($node['name']) ? $node['name'] : $node['content']['name'] ?>
						</a>
						<span class="tree-handle"></span>
					<?php				
					$depth = ($node['depth'] - 1);
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