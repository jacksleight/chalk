<?php $this->parent('/layout/html') ?>
<?php $this->block('body') ?>

<?php
$primary	= $navigation->items('Chalk\Core\Primary');
$secondary	= $navigation->items('Chalk\Core\Secondary');
?>
<div class="flex-row">
	<div class="flex-col sidebar">
		<? if (isset($primary)) { ?>
			<?= $this->child('nav', [
				'items'	=> $primary,
				'class'	=> 'toggles',
			]) ?>
		<? } ?>
		<div class="flex">
			<?= $this->content('sidebar') ?>
		</div>
		<? if (isset($secondary)) { ?>
			<?= $this->child('nav', [
				'items' => $secondary,
				'class' => 'toggles',
			]) ?>
		<? } ?>
		<footer class="footer c" role="contentinfo">
			<p><a href="<?= $this->url([], 'about', true) ?>" rel="modal">Chalk <?= \Chalk\Chalk::VERSION ?></a></p>
		</footer>
	</div>
	<div class="flex flex-col">
		<div class="header topbar">
			<ul class="toolbar">
				<?php
				$count = $this->em('Chalk\Core\Content')->count(['isPublishable' => true]);
				?>
				<?php if ($count) { ?>
					<li>
						<a href="<?= $this->url([
							'controller' => 'index',
							'action'	 => 'publish',
						], 'index', true) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-pending icon-publish">
							Publish
						</a>
						<small>&nbsp;&nbsp;<strong><?= $count ?></strong> draft items</small>
					</li>
				<?php } else { ?>
					<?php
					$url = isset($content)
						? $this->frontend->url->content($content)
						: $this->frontend->url();
					?>
					<li>
						<a href="<?= $url ?>" target="_blank" class="btn btn-quiet icon-view">
							View Site
						</a>
					</li>					
				<?php } ?>
			</ul>
			<ul class="toolbar">
				<li>
					<a href="<?= $this->url([], 'profile', true) ?>" class="icon-user-dark"><?= $req->user->name ?></a>
				</li>
				<li class="space">
					<a href="<?= $this->url([], 'logout', true) ?>" class="icon-logout-dark">Logout</a>
				</li>
			</ul>
			<h1><?= $this->config->name ?></h1>
			<ul class="notifications"></ul>
		</div>
		<div class="flex">
			<?= $this->content('main') ?>
		</div>
	</div>
</div>


