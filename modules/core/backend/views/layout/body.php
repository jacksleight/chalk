<?php $this->outer('/layout/html') ?>
<?php $this->block('body') ?>

<div class="flex-col">
	<div class="flex-row topbar dark">
		<div class="leftbar header">
			<?php
			$items = $this->nav->children('root');
			$main  = $this->nav->main();
			?>
			<div class="dropdown">
				<div class="input-pseudo input-extra dropdown-toggle">
					<span class="icon-fw icon-<?= isset($main['icon-block']) ? $main['icon-block'] : $main['icon'] ?>"></span>
					<?= $main['label'] ?>
				</div>
				<nav class="menu">
					<ul>
						<?php foreach ($items as $item) { ?>
							<li>
								<a href="<?= $item['url'] ?>" class="item">
									<span class="icon-fw icon-<?= isset($item['icon-block']) ? $item['icon-block'] : $item['icon'] ?>"></span>
									<?= $item['label'] ?>
								</a>
							</li>
						<?php } ?>
					</ul>
				</nav>
			</div>
			<?php $this->inner('nav', [
				'items'	=> $items,
				'class'	=> 'toggles',
			]) ?>
		</div>
		<div class="flex rightbar header">
			<ul class="toolbar toolbar-extra toolbar-right toolbar-space">
				<li>
					<a href="<?= $this->url([], 'core_profile', true) ?>" class="icon-user" rel="modal"> <?= $this->user->name ?></a>
				</li>
				<li>
					<a href="<?= $this->url([], 'core_logout', true) ?>" class="icon-logout"> Logout</a>
				</li>
			</ul>
			<ul class="toolbar toolbar-extra toolbar-space">
				<li>
					<a href="<?= $this->frontendUrl() ?>" target="_blank" class="icon-view">
						Open Site
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
			            ], 'core_index', true) . $this->url->query([
		                    'redirect' => $this->url([]),
		                ], true) ?>" class="confirmable positive">
			                <span class="badge badge-figure badge-positive"><?= $count ?></span> Publish All
			            </a>
			        </a>
			    <?php } ?>
			</ul>
			<h1><a href="<?= $this->url([], 'core_about', true) ?>" rel="modal"><?= $this->domain->label ?></a></h1>
		</div>
	</div>
	<?= $this->content('main') ?>
</div>
<ul class="notifications"></ul>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>