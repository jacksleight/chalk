<?php
$count = $this->em('Ayre\Entity\Content')->fetchCountForPublish();
?>
<? $this->layout('/layouts/html') ?>
<? $this->block('body') ?>

<div class="frame">
	<div class="sidebar">
		<?= $this->render('nav', ['items' => [
			[
				'label' => 'Content',
				'icon'	=> 'fa fa-picture-o',
				'name'	=> 'content',
			], [
				'label' => 'Structure',
				'icon'	=> 'fa fa-sitemap',
				'params'=> ['controller' => 'structure'],
			], [
				'label' => 'Live',
				'icon'	=> 'fa fa-eye',
				'params'=> ['controller' => null],
			]
		], 'class' => 'toggle']) ?>
		<div class="body">
			<?= $content->sidebar ?>
		</div>
		<?= $this->render('nav', ['items' => [
			[
				'label' => 'Settings',
				'icon'	=> 'fa fa-gear fa-fw',
				'params'=> ['controller' => 'user'],
			], [
				'label' => 'Activity',
				'icon'	=> 'fa fa-bar-chart-o fa-fw',
				'params'=> ['controller' => null],
			]
		], 'class' => 'toggle']) ?>
		<footer class="footer c" role="contentinfo">
			<p>Ayre 0.1.0 © <?= date('Y') ?> <a href="http://jacksleight.com/">Jack Sleight</a></p>
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
						<small>&nbsp;&nbsp;<strong><?= $count ?></strong> pending items</small>
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
					<?= $req->user->name ?>
				</li>
				<li class="space">
					<i class="fa fa-sign-out"></i>
					<a href="<?= $this->url([], 'logout', true) ?>">Logout</a>
				</li>
			</ul>
			<p class="title"><?= $this->options->name ?></p>
		</div>
		<section class="body" role="main">
			<?= $content->main ?>
		</section>
	</div>
</div>


