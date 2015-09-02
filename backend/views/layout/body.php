<?php $this->outer('/layout/html') ?>
<?php $this->block('body') ?>

<div class="flex-row">
	<div class="flex-col sidebar dark">
		<?= $this->inner('nav', [
			'items'	=> $this->nav->children('core_primary'),
			'class'	=> 'toggles',
		]) ?>
		<div class="flex body">
			<?= $this->content('sidebar') ?>
		</div>
		<?= $this->inner('nav', [
			'items'	=> $this->nav->children('core_secondary'),
			'class'	=> 'toggles',
		]) ?>
	</div>
	<div class="flex flex-col">
		<div class="header topbar">
			<ul class="toolbar toolbar-right toolbar-space">
				<li>
					<a href="<?= $this->url([], 'profile', true) ?>" class="icon-user"> <?= $req->user->name ?></a>
				</li>
				<li>
					<a href="<?= $this->url([], 'logout', true) ?>" class="icon-logout"> Logout</a>
				</li>
			</ul>
			<ul class="toolbar">
				<li>
					<a href="<?= $this->frontend->url(isset($content) ? $content->getObject() : null) ?>" target="_blank" class="btn btn-out icon-view">
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
							'action'	 => 'publish',
						], 'index', true) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-positive btn-out icon-publish">
							Publish All
						</a>						
					</li>				
				<?php } ?>
			</ul>
			<h1><?= $this->chalk->config->name ?></h1>
			<ul class="notifications"></ul>
		</div>
		<div class="flex">
			<?= $this->content('main') ?>
		</div>
	</div>
</div>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>