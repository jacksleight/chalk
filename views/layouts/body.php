<? $this->layout('/layouts/html') ?>
<? $this->block('body') ?>

<div class="frame">
	<div class="sidebar">
		<?= $this->render('nav', ['items' => [
			[
				'label' => 'Content',
				'icon'	=> 'fa fa-picture-o fa-fw',
				'name'	=> 'content',
			], [
				'label' => 'Structure',
				'icon'	=> 'fa fa-sitemap fa-fw',
				'params'=> ['controller' => 'structure'],
			]
		], 'class' => 'toggle']) ?>
		<nav class="nav">
			<?= $content->nav ?>			
		</nav>
		<?= $this->render('nav', ['items' => [
			[
				'label' => 'Settings',
				'icon'	=> 'fa fa-gear fa-fw',
				'params'=> ['controller' => 'user'],
			], [
				'label' => 'Activity',
				'icon'	=> 'fa fa-bar-chart-o fa-fw',
				'params'=> ['controller' => 'activity'],
			]
		], 'class' => 'toggle']) ?>
		<footer class="footer c" role="contentinfo">
			<p>Ayre 0.1.0 © <?= date('Y') ?> <a href="http://jacksleight.com/">Jack Sleight</a></p>
		</footer>
	</div>
	<div class="body">
		<div class="topbar">
			<ul class="toolbar">
				<li>
					<a href="#" class="btn btn-inline btn-pending btn-quiet">
						<i class="fa fa-globe"></i>
						Publish <strong>14 Pending</strong> Items…
					</a>
				</li>
			</ul>
			<ul class="toolbar">
				<li>
					<i class="fa fa-user"></i>
					Jack Sleight
				</li>
				<li>
					<a href="#">Logout</a>
				</li>
			</ul>
			<p class="title">Example Site</p>
		</div>
		<section class="main" role="main">
			<?= $content->main ?>
		</section>
	</div>
</div>


