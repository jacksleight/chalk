<?php $this->outer('/layout/html') ?>
<?php $this->block('body') ?>

<div class="flex-row">
	<div class="flex-col sidebar dark">
		<?= $this->inner('nav', [
			'items'	=> $this->navList->children('core_primary'),
			'class'	=> 'toggles',
		]) ?>
		<div class="flex body">
			<?= $this->content('sidebar') ?>
		</div>
		<?= $this->inner('nav', [
			'items'	=> $this->navList->children('core_secondary'),
			'class'	=> 'toggles',
		]) ?>
	</div>
	<div class="flex flex-col">
		<div class="header topbar">
			<ul class="toolbar toolbar-right toolbar-space">
				<li>
					<a href="<?= $this->url([], 'core_profile', true) ?>" class="icon-user"> <?= $req->user->name ?></a>
				</li>
				<li>
					<a href="<?= $this->url([], 'core_logout', true) ?>" class="icon-logout"> Logout</a>
				</li>
			</ul>
			<ul class="toolbar">
				<li>
					<a href="<?= $this->frontend->url() ?>" target="_blank" class="btn btn-out icon-view">
						View Site
					</a>
				</li>
				<?php
			    $count = $this->em('Chalk\Core\Content')->count(['isPublishable' => true]);
			    ?>
			    <?php if ($count) { ?>
			        <li>
			            <a href="<?= $this->url([
			                'controller' => 'index',
			                'action'     => 'publish',
			            ], 'core_index', true) ?>?redirect=<?= $this->url([]) ?>" class="confirmable btn btn-out btn-positive btn-block icon-publish">
			                Publish All
			            </a>
			        </a>
			    <?php } ?>
			</ul>
			<h1><a href="<?= $this->url([], 'core_about', true) ?>" rel="modal"><?= $this->domain->label ?></a></h1>
		</div>
		<div class="flex main">
			<?= $this->content('main') ?>
		</div>
	</div>
</div>
<ul class="notifications"></ul>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>