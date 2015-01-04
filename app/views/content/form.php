<? $this->block() ?>

<div class="fill">
<div class="flex">

<? if ($this->block('tools')) { ?>

<ul class="toolbar">
	<?= $this->content('tools-top') ?>
	<?php if (false && !$content->isNewMaster()) { ?>
		<li><a href="#" class="btn icon-history">
			View <?= $info->singular ?> History
		</a></li>
	<?php } ?>
	<?php if (!$content->isNewMaster() && $info->class != 'Chalk\Core\File') { ?>
		<li><a href="<?= $this->url([
				'entity'	=> $info->name,
				'action'	=> 'edit',
			], 'content', true) ?>" class="btn btn-focus btn-quiet icon-add">
			New <?= $info->singular ?>
		</a></li>
	<?php } ?>
	<?= $this->content('tools-bottom') ?>
</ul>

<? } if ($this->block('header')) { ?>

<h1>
	<?php if (!$content->isNewMaster()) { ?>
		<?= $content->name ?>
	<?php } else { ?>
		New <?= $info->singular ?>
	<?php } ?>
</h1>
<?= $this->content('header-after') ?>

<? } if ($this->block('meta')) { ?>

<ul class="meta">
	<?= $this->content('meta-top') ?>
	<li>
		<span class="badge badge-status badge-<?= $content->status ?>">
			<?= $content->status ?>
		</span>
	</li>
	<!-- <li>
		Version <em><?= $content->version ?></em>
	</li> -->
	<?php if (!$content->isNew()) { ?>
		<li class="icon icon-updated-dark">
			Updated <em><?= $content->modifyDate->diffForHumans() ?></em>
			by <em><?= $content->modifyUserName ?></em>
		</li>
	<?php } ?>
	<?= $this->content('meta-bottom') ?>
</ul>

<? } if ($this->block('general')) { ?>

<fieldset class="form-block">
	<div class="form-legend">
		<h2>General</h2>
	</div>
	<div class="form-items">
		<?= $this->content('general-top') ?>
		<?= $this->render('/element/form-item', array(
			'entity'	=> $content,
			'name'		=> 'name',
			'label'		=> 'Name',
			'autofocus'	=> true,
			'disabled'	=> $content->isLocked(),
		), 'Chalk\Core') ?>
		<?= $this->content('general-bottom') ?>
	</div>
</fieldset>
<?= $this->content('general-after') ?>

<? } $this->block() ?>

<?= $this->child('/behaviour/publishable/form', ['publishable' => $content], 'Chalk\Core') ?>
<?php if (isset($node)) { ?>
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Structure</h2>
			<p>These settings only apply when the <?= strtolower($info->singular) ?> is used in the <strong><?= $node->structure->name ?></strong>.</p>
		</div>
		<div class="form-items">
			<?= $this->render('/element/form-item', array(
				'entity'		=> $node,
				'name'			=> 'name',
				'label'			=> 'Label',
				'placeholder'	=> $content->name,
				'note'			=> 'Text used in navigation and URLs',
			)) ?>
			<?= $this->render('/element/form-item', array(
				'entity'		=> $node,
				'name'			=> 'isHidden',
				'label'			=> 'Hidden',
			)) ?>	
		</div>
	</fieldset>
<?php } ?>
<?php if ($req->user->isRoot()) { ?>
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Administration</h2>
		</div>
		<div class="form-items">
			<?= $this->render('/element/form-item', array(
				'entity'		=> $content,
				'name'			=> 'id',
				'label'			=> 'ID',
				'type'			=> 'value',
				'readOnly'		=> true,
			)) ?>
			<?= $this->render('/element/form-item', array(
				'entity'		=> $content,
				'name'			=> 'slug',
				'label'			=> 'Slug',
				'type'			=> 'value',
				'readOnly'		=> true,
			)) ?>
			<?= $this->render('/element/form-item', array(
				'entity'		=> $content,
				'name'			=> 'isLocked',
				'label'			=> 'Locked',
			)) ?>	
		</div>
	</fieldset>
<?php } ?>

</div>
<fieldset class="fix">

<? if ($this->block('actions-primary')) { ?>

<ul class="toolbar">
	<?php if (!$content->isArchived()) { ?>
		<li><button class="btn btn-positive icon-ok">
			Save <?= $info->singular ?>
		</button></li>
	<?php } else { ?>
		<li><a href="<?= $this->url([
			'entity'	=> $info->name,
			'action'		=> 'restore',
			'content'		=> $content->id,
		], 'content', true) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-positive icon-restore">
			Restore <?= $info->singular ?>
		</a></li>
	<?php } ?>
</ul>

<? } if ($this->block('actions-secondary')) { ?>

<ul class="toolbar">
	<? if (!$content->isLocked()) { ?>		
		<?php if (!$content->isArchived() && !$content->isNewMaster()) { ?>
			<li><a href="<?= $this->url([
				'entity'	=> $info->name,
				'action'	=> 'archive',
				'content'	=> $content->id,
			], 'content', true) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-negative btn-quiet confirmable icon-archive">
				Archive <?= $info->singular ?>
			</a></li>
		<?php } ?>
		<?php if (!isset($node) && $content->isArchived()) { ?>
			<li><a href="<?= $this->url([
				'entity'	=> $info->name,
				'action'	=> 'delete',
				'content'	=> $content->id,
			], 'content', true) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-negative btn-quiet confirmable icon-delete" data-message="Are you sure?<?= "\n" ?>This will delete the <?= strtolower($info->singular) ?> and cannot be undone.">
				Delete <?= $info->singular ?>
			</a></li>
		<?php } ?>
	<? } ?>
	<?php if (isset($node) && !$node->isRoot()) { ?>
		<li><a href="<?= $this->url([
			'action' => 'delete'
		]) ?>" class="btn btn-negative btn-quiet confirmable icon-remove">
			Remove <?= $info->singular ?> <small>from <strong><?= $structure->name ?></strong></small>
		</a>
		</li>
	<?php } ?>
</ul>

<? } $this->block() ?>

</fieldset>
</div>