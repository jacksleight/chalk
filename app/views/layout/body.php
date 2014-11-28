<?php
$count = $this->em('Chalk\Core\Content')->count(['isPublishable' => true]);
?>
<?php $this->layout('/layout/html') ?>
<?php $this->block('body') ?>

<div class="frame">
	<div class="sidebar">
		<?php 
		$contents = $this->app->fire('Chalk\Core\Event\ListContents')->contents();
		?>
		<?= $this->render('nav', ['items' => [
			[
				'label' => 'Structure',
				'icon'	=> 'icon-structure',
				'name'	=> 'structure',
			],
			[
				'label' => 'Content',
				'icon'	=> 'icon-content',
				'name'	=> 'content',
			],
			[
				'label' => 'Settings',
				'icon'	=> 'icon-settings',
				'name'	=> 'setting',
			],
		], 'class' => 'toggle']) ?>
		<div class="body">
			<?= $content->sidebar ?>
		</div>
		<?php if (false && $req->user->isAdministrator()) { ?>
			<?= $this->render('nav', ['items' => [
				[
					'label' => 'Live',
					'icon'	=> '',
					'params'=> ['controller' => null],
				],
				[
					'label' => 'Activity',
					'icon'	=> '',
					'params'=> ['controller' => null],
				]
			], 'class' => 'toggle']) ?>
		<?php } ?>
		<footer class="footer c" role="contentinfo">
			<p>Chalk <?= \Chalk\Chalk::VERSION ?></p>
		</footer>
	</div>
	<div class="main">
		<div class="topbar">
			<ul class="toolbar">
				<?php if ($count) { ?>
					<li>
						<a href="<?= $this->url([
							'controller' => 'index',
							'action'	 => 'publish',
						], 'index', true) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-pending icon-publish">
							Publish
						</a>
						<small>&nbsp;&nbsp;<strong><?= $count ?></strong> draft items</small>
					</li>
				<?php } else { ?>
					<li>
						<a href="<?= $this->frontend->url->baseUrl() ?>" target="_blank" class="btn btn-quiet icon-view">
							View Site
						</a>
					</li>					
				<?php } ?>
			</ul>
			<ul class="toolbar">
				<li>
					<a href="<?= $this->url([], 'profile', true) ?>" class="icon-user"><?= $req->user->name ?></a>
				</li>
				<li class="space">
					<a href="<?= $this->url([], 'logout', true) ?>" class="icon-logout">Logout</a>
				</li>
			</ul>
			<p class="title"><?= $this->config->name ?></p>
			<ul class="notifications"></ul>
		</div>
		<section class="body" role="main">
			<?= $content->main ?>
		</section>
	</div>
</div>


