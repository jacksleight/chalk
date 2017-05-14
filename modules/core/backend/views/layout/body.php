<?php $this->outer('/layout/html') ?>
<?php $this->block('body') ?>

<div class="flex-col">
	<div class="flex-row topbar dark">
		<div class="leftbar header">
			<?php
			$items = array_merge(
				$this->navList->children('core_primary'),
				$this->navList->children('core_secondary')
			);
			foreach ($items as $i => $item) {
				if ($item['isActivePath']) {
					$current = $item;
				}
			}
			?>
			<div class="dropdown">
				<div class="input-pseudo input-extra">
					<?php if (isset($current)) { ?>
						<span class="icon-<?= isset($current['icon-block']) ? $current['icon-block'] : $current['icon'] ?>"></span>
						<?= $current['label'] ?>
					<?php } else { ?>	
						Chalk
					<?php } ?>
				</div>
				<nav class="menu">
					<ul>
						<?php foreach ($items as $item) { ?>
							<li>
								<a href="<?= $item['url'] ?>" class="item">
									<span class="icon-<?= isset($item['icon-block']) ? $item['icon-block'] : $item['icon'] ?>"></span>
									<?= $item['label'] ?>
								</a>
							</li>
						<?php } ?>
					</ul>
				</nav>
			</div>
			<? $this->inner('nav', [
				'items'	=> $items,
				'class'	=> 'toggles',
			]) ?>
		</div>
		<div class="flex rightbar header">
			<ul class="toolbar toolbar-extra toolbar-right toolbar-space">
				<li>
					<a href="<?= $this->url([], 'core_profile', true) ?>" class="icon-user" rel="modal"> <?= $req->user->name ?></a>
				</li>
				<li>
					<a href="<?= $this->url([], 'core_logout', true) ?>" class="icon-logout"> Logout</a>
				</li>
			</ul>
			<ul class="toolbar toolbar-extra toolbar-space">
				<li>
					<a href="<?= $this->frontend->url() ?>" target="_blank" class="icon-view">
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
			                'action'     => 'publish',
			            ], 'core_index', true) ?>?redirect=<?= $this->url([]) ?>" class="confirmable positive">
			                <span class="badge badge-figure badge-positive"><?= $count ?></span> Publish 
			            </a>
			        </a>
			    <?php } ?>
			</ul>
			<h1><a href="<?= $this->url([], 'core_about', true) ?>" rel="modal"><?= $this->domain->label ?></a></h1>
		</div>
	</div>
	<div class="flex flex-row bottombar">
		<div class="flex-col leftbar">
			<div class="flex">
				<?= $this->content('sidebar') ?>
			</div>
		</div>
		<div class="flex flex-col rightbar">
			<div class="flex">
				<?= $this->content('main') ?>
			</div>
		</div>
	</div>
</div>
<ul class="notifications"></ul>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>