<?php
$structures	= $this->em('Chalk\Core\Structure')->all();
$structure	= $this->em('Chalk\Core\Structure')->id($req->structure);
?>
<?php $this->outer('/layout/body') ?>
<?php $this->block('main') ?>

<?= $this->content('main') ?>

<?php $this->block('sidebar') ?>

<form action="<?= $this->url([
	'action'	=> 'reorder',
	'structure'	=> $structure->id,
], 'core_structure', true) ?>?redirect=<?= $this->url([]) ?>" class="flex-col structure" method="post">
	<div class="header">
		<ul class="toolbar">
			<li class="flex">
				<div class="dropdown">
					<div class="input-pseudo">
						<?= $structure->name ?>
					</div>
					<nav class="menu">
						<ul>
							<?php foreach ($structures as $listStructure) { ?>
								<li>
									<a href="<?= $this->url([
										'structure'	=> $listStructure->id,
										'action'	=> 'index',
									], 'core_structure') ?>" class="item">
										<?= $listStructure->name ?>
									</a>
								</li>
							<?php } ?>
						</ul>
					</nav>
				</div>
			</li>
			<li>
				<ul class="toolbar toolbar-tight">
					<li>
						<button class="btn btn-block btn-light btn-icon structure-edit btn-collapse icon-move" type="button">
							<span>Move Content</span>
						</button>
						<button class="btn btn-block btn-negative btn-icon structure-cancel btn-collapse icon-cancel disabled" type="button">
							<span>Cancel</span>
						</button>
					</li>
					<li>
						<a href="<?= $this->url([
							'action'	=> 'add',
							'structure'	=> $structure->id,
							'node'		=> $req->node,
						], 'core_structure_node', true) ?>" class="btn btn-focus structure-add btn-collapse btn-icon btn-block icon-add" rel="modal">
							<span>Add Content</span>
						</a>
						<button class="btn btn-positive btn-icon btn-block structure-save btn-collapse icon-ok disabled">
							<span>Save Changes</span>
						</button>
					</li>
				</ul>
			</li>
		</ul>
	</div>
	<div class="flex body">
		<?php if (isset($structure->root->content)) { ?>
			<ol class="tree-root">
				<?php
				$content = $structure->root['content'];
				?>
				<li class="tree-node" data-id="<?= $structure->root['id'] ?>">
					<a href="<?= $this->url([
						'structure'	=> $structure->id,
						'action'	=> 'update',
						'node'		=> $structure->root['id'],
					], 'core_structure_node') ?>" class="tree-item <?= $structure->root['id'] == $req->node ? 'active' : '' ?> tree-item-<?= $content['status'] ?> <?= $structure->root['isHidden'] ? 'tree-item-hidden' : '' ?>">
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
					<li class="tree-node <?= (isset($statuses[$node['id']]) && !$statuses[$node['id']]) && $node['right'] > $node['left'] + 1 ? 'tree-collapsed' : null ?>" data-id="<?= $node['id'] ?>">
						<a href="<?= $this->url([
							'structure'	=> $structure->id,
							'action'	=> 'update',
							'node'		=> $node['id'],
						], 'core_structure_node') ?>" class="tree-item <?= $node['id'] == $req->node ? 'active' : '' ?> tree-item-<?= $content['status'] ?> <?= $node['isHidden'] ? 'tree-item-hidden' : '' ?>">
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
	<input type="hidden" name="nodeData" class="structure-data">
</form>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>