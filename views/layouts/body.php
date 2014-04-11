<? $this->layout('/layouts/html') ?>
<? $this->block('body') ?>

<div class="frame">
	<div class="sidebar">
		<ul class="toggle">
			<li>
				<a href="#" class="active">
					<i class="fa fa-picture-o fa-fw"></i>
					Content
				</a>
			</li>
			<li>
				<a href="">
					<i class="fa fa-sitemap fa-fw"></i>
					Structure
				</a>
			</li>
		</ul>
		<nav class="nav">
			<?= $content->nav ?>			
		</nav>
		<ul class="toggle">
			<li>
				<a href="<?= $this->url([
					'controller' => 'user',
				], 'index', true) ?>">
					<i class="fa fa-gear fa-fw"></i>
					Settings
				</a>
			</li>
			<li>
				<a href="<?= $this->url([
					'controller' => 'user',
				], 'index', true) ?>">
					<i class="fa fa-bar-chart-o fa-fw"></i>
					Activity
				</a>
			</li>
		</ul>
		<footer class="footer c" role="contentinfo">
			<p>Ayre 0.1.0 © <?= date('Y') ?> <a href="http://jacksleight.com/">Jack Sleight</a></p>
		</footer>
	</div>
	<div class="body">
		<div class="topbar">
			<ul class="toolbar">
				<li>
					<a href="#" class="button button-inline button-pending button-invert">
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


