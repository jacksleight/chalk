<?php
$count = $this->em('core_content')->fetchCountForPublish();
?>
<? $this->layout('/layouts/html') ?>
<? $this->block('body') ?>

<div class="frame">
	<div class="sidebar">
		<?php 
		$contentClasses = $this->app->contentClasses();
		?>
		<?= $this->render('nav', ['items' => [
			[
				'label' => 'Content',
				'icon'	=> 'fa fa-file-text-o',
				'name'	=> 'content',
				'params'=> ['entity' => \Chalk::entity($contentClasses[0])->name],
			],
			[
				'label' => 'Structure',
				'icon'	=> 'fa fa-sitemap',
				'params'=> ['controller' => 'structure'],
			],
			// [
			// 	'label' => 'Live',
			// 	'icon'	=> 'fa fa-eye',
			// 	'params'=> ['controller' => null],
			// ]
		], 'class' => 'toggle']) ?>
		<div class="body">
			<?= $content->sidebar ?>
		</div>
		<? if ($req->user->isAdministrator()) { ?>
			<?= $this->render('nav', ['items' => [
				[
					'label' => 'Settings',
					'icon'	=> 'fa fa-gear fa-fw',
					'params'=> ['controller' => 'user'],
				],
				// [
				// 	'label' => 'Activity',
				// 	'icon'	=> 'fa fa-bar-chart-o fa-fw',
				// 	'params'=> ['controller' => null],
				// ]
			], 'class' => 'toggle']) ?>
		<? } ?>
		<footer class="footer c" role="contentinfo">
			<p>Chalk 0.1.0</p>
		</footer>
	</div>
	<div class="main">
		<div class="topbar">
			<ul class="toolbar">
				<? if ($count) { ?>
					<li>
						<a href="<?= $this->url([
							'controller' => 'index',
							'action'	 => 'publish',
						], 'index', true) ?>" class="btn btn-inline btn-pending">
							<i class="fa fa-globe"></i>
							Publish
						</a>
						<small>&nbsp;&nbsp;<strong><?= $count ?></strong> draft items</small>
					</li>
				<? } else { ?>
					<li>
						<a href="<?= $this->rootUrl->baseUrl() ?>" target="_blank" class="btn btn-inline btn-quiet">
							<i class="fa fa-external-link"></i>
							View Site
						</a>
					</li>					
				<? } ?>
			</ul>
			<ul class="toolbar">
				<li>
					<i class="fa fa-user"></i>
					<a href="<?= $this->url([], 'profile', true) ?>"><?= $req->user->name ?></a>
				</li>
				<li class="space">
					<i class="fa fa-sign-out"></i>
					<a href="<?= $this->url([], 'logout', true) ?>">Logout</a>
				</li>
			</ul>
			<p class="title"><?= $this->config->name ?></p>
		</div>
		<section class="body" role="main">
			<?= $content->main ?>
		</section>
	</div>
</div>


