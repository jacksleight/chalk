<?php $this->outer('/layout/html') ?>
<?php $this->block('body') ?>

<div class="flex-col">
	<div class="flex-row topbar dark">
		<div class="sidebar">
			<?= $this->inner('nav', [
				'items'	=> array_merge(
					$this->navList->children('core_primary'),
					$this->navList->children('core_secondary')
				),
				'class'	=> 'toggles',
			]) ?>
		</div>
		<div class="flex header">
			<ul class="toolbar toolbar-right toolbar-space">
				<li>
					<a href="<?= $this->url([], 'core_profile', true) ?>" class="icon-user"> <?= $req->user->name ?></a>
				</li>
				<li>
					<a href="<?= $this->url([], 'core_logout', true) ?>" class="icon-logout"> Logout</a>
				</li>
			</ul>
			<ul class="toolbar">
				<?php
			    $count = $this->em('Chalk\Core\Content')->count(['isPublishable' => true]);
			    ?>
			    <?php if ($count) { ?>
			        <li>
			            <a href="<?= $this->url([
			                'controller' => 'index',
			                'action'     => 'publish',
			            ], 'core_index', true) ?>?redirect=<?= $this->url([]) ?>" class="confirmable btn btn-positive btn-block icon-publish">
			                Publish All
			            </a>
			        </a>
			    <?php } ?>
				<li>
					<a href="<?= $this->frontend->url() ?>" target="_blank" class="btn icon-view">
						View Site
					</a>
				</li>
			</ul>
			<h1><a href="<?= $this->url([], 'core_about', true) ?>" rel="modal"><?= $this->domain->label ?></a></h1>
		</div>
	</div>
	<div class="flex flex-row">
		<div class="sidebar ">
			<?= $this->content('sidebar') ?>
		</div>
		<div class="flex flex-col">
			<div class="flex main">
				<?= $this->content('main') ?>
			</div>
		</div>
	</div>
</div>
<ul class="notifications"></ul>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>