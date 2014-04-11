<? $this->layout('/layouts/html') ?>
<? $this->block('body') ?>

<div class="frame">
	<header class="header">
		<ul class="toggle">
			<li>
				<a href="#" class="active">
					<i class="fa fa-picture-o"></i>
					Content
				</a>
			</li>
			<li>
				<a href="">
					<i class="fa fa-sitemap"></i>
					Structure
				</a>
			</li>
		</ul>
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
	</header>
	<div>
		<?= $content->main ?>
	</div>
</div>