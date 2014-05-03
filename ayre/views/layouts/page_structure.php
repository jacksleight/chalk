<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<?php
$structs	= $this->em('Ayre\Entity\Structure')->fetchAll();
$struct		= $this->em('Ayre\Entity\Structure')->fetch($req->id);
if (!isset($struct)) {
	$struct = $structs[0];
}
?>

<div class="flex">
	<div class="pad">
		<div class="dropdown">
			<div class="value">
				<? if ($struct instanceof \Ayre\Entity\Domain) { ?>
					<i class="fa fa-globe fa-fw"></i>
				<? } else if ($struct instanceof \Ayre\Entity\Menu) { ?>
					<i class="fa fa-bars fa-fw"></i>
				<? } ?>
				<?= $struct->name ?>		
			</div>
			<nav class="menu">
				<ul>
					<? foreach ($structs as $struct) { ?>
						<li>
							<a href="<?= $this->url([
								'id' => $struct->id,
							]) ?>">
								<? if ($struct instanceof \Ayre\Entity\Domain) { ?>
									<i class="fa fa-globe fa-fw"></i>
								<? } else if ($struct instanceof \Ayre\Entity\Menu) { ?>
									<i class="fa fa-bars fa-fw"></i>
								<? } ?>
								<?= $struct->name ?>
							</a>
						</li>
					<? } ?>
				</ul>
			</nav>
		</div>
	</div>
	<form action="<?= $this->url([
		'id'		=> $struct->id,
		'action'	=> 'reorder',
	]) ?>" class="structure" method="post">
		<ol class="tree-root">
			<li class="tree-item" data-id="<?= $struct->root->id ?>">
				<div class="tree-handle "><?= $struct->root->name ?></div>
			</li>
		</ol>
		<div class="tree">
			<?= $this->em('Ayre\Entity\Structure\Node')->childrenHierarchy($struct->root, false, [
				'decorate'		=> true,
				'rootOpen'		=> '<ol class="tree-list">',
				'rootClose'		=> '</ol>',
				'childOpen'		=> '',
				'childClose'	=> '</li>',
				'nodeDecorator'	=> function($node) {
					return '
						<li class="tree-item" data-id="' . $node['id'] . '">
						<div class="tree-handle ">' . $node['name'] . '</div>
					';
				}
			]) ?>
			<input type="hidden" name="data" class="tree-data">
		</div>
		<button class="btn-positive btn-block">Save Changes</button>
	</form>
</div>
<div class="fix">
	<a href="<?= $this->url([
		'action'	=> 'add',
		'id'		=> $struct->id,
	]) ?>" class="btn btn-focus btn-block">Add Content</a>
</div>