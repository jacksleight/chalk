<? $this->layout('/layouts/html') ?>
<? $this->block('body') ?>

<div class="frame">
	<header class="header">
		<ul class="toggle">
			<li>
				<a href="">
					<i class="fa fa-sitemap"></i>
					Structure
				</a>
			</li>
			<li>
				<a href="#" class="active">
					<i class="fa fa-picture-o"></i>
					Content
				</a>
			</li>
		</ul>
		<div class="topbar">
			<ul class="toolbar">
				<li>
					<i class="fa fa-user"></i>
					Jack Sleight
				</li>
				<li>
					<a href="#">Logout</a>
				</li>
			</ul>
			<ul class="toolbar">
				<li class="title">
					Example Site
				</li>
				<li>
					Publish <span class="label">14</span>
				</li>
			</ul>
		</div>
	</header>
	<div>
		<?= $content->main ?>
	</div>
</div>