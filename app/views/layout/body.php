<?php $this->parent('/layout/html') ?>
<?php $this->block('body') ?>

<?php
$primary	= $navigation->items('Chalk\Core\Primary');
$secondary	= $navigation->items('Chalk\Core\Secondary');
?>
<div class="flex-row">
	<div class="flex-col sidebar dark">
		<? if (isset($primary)) { ?>
			<?= $this->child('nav', [
				'items'	=> $primary,
				'class'	=> 'toggles',
			]) ?>
		<? } ?>
		<div class="flex body">
			<?= $this->content('sidebar') ?>
		</div>
		<? if (isset($secondary)) { ?>
			<?= $this->child('nav', [
				'items' => $secondary,
				'class' => 'toggles',
			]) ?>
		<? } ?>
	</div>
	<div class="flex flex-col">
		<div class="header topbar">
			<ul class="toolbar toolbar-right toolbar-space">
				<li>
					<a href="<?= $this->url([], 'profile', true) ?>" class="icon-user"> <?= $req->user->name ?></a>
				</li>
				<li class="space">
					<a href="<?= $this->url([], 'logout', true) ?>" class="icon-logout"> Logout</a>
				</li>
			</ul>
			<ul class="toolbar">
				<?php
				$url = isset($content)
					? $this->frontend->url->content($content)
					: $this->frontend->url();
				?>
				<li>
					<a href="<?= $url ?>" target="_blank" class="btn btn-out icon-view">
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
							'action'	 => 'publish',
						], 'index', true) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-positive btn-out icon-publish">
							Publish All
						</a>						
					</li>				
				<?php } ?>
			</ul>
			<h1><?= $this->config->name ?></h1>
			<ul class="notifications"></ul>
		</div>
		<div class="flex">
			<?= $this->content('main') ?>
		</div>
	</div>
</div>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>