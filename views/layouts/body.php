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
				'params'=> ['controller' => 'activity'],
			]
		], 'class' => 'toggle']) ?>
		<footer class="footer c" role="contentinfo">
			<p>Ayre 0.1.0 © <?= date('Y') ?> <a href="http://jacksleight.com/">Jack Sleight</a></p>
		</footer>
	</div>
	<div class="main">
		<div class="topbar">
			<ul class="toolbar">
				<li>
					<a href="<?= $this->url([
						'controller' => 'index',
						'action'	 => 'publish',
					], 'index', true) ?>" class="btn btn-inline btn-published btn-quiet">
						<i class="fa fa-globe"></i>
						Publish Pending Items…
					</a>
				</li>
			</ul>
			<ul class="toolbar">
				<li>
					<i class="fa fa-user"></i>
					Jack Sleight
				</li>
				<li class="space">
					<i class="fa fa-sign-out"></i>
					<a href="#">Logout</a>
				</li>
			</ul>
			<p class="title">Example Site</p>
		</div>
		<section class="body" role="main">
			<?= $content->main ?>
		</section>
	</div>
</div>


