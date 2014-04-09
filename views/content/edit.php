<? $this->layout('/layouts/page') ?>
<? $this->block('main') ?>

<ul class="toolbar">
	<li>
		<a href="<?= $this->url([
			'action' => 'archive',
		]) ?>" class="button button-negative">
			<i class="fa fa-archive"></i> Archive <?= $req->type->info->singular ?>
		</a>
	</li>
</ul>
<h1><?= $req->type->info->singular ?></h1>
<form action="<?= $this->url->route() ?>" method="post" novalidate>
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Basic Details</h2>
		</div>
		<div class="form-items">
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $req->wrap,
				'name'		=> 'name',
				'label'		=> 'Name',
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $req->wrap,
				'name'		=> 'slug',
				'label'		=> 'Slug',
				'note'		=> 'Text used in URLs.',
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'entity'	=> $req->wrap,
				'name'		=> 'label',
				'label'		=> 'Label',
				'note'		=> 'Text used in navigation.',
				'placeholder' => $req->wrap->name,
			)) ?>
			<p>
				<button>
					<i class="fa fa-check"></i>
					Save <?= $req->type->info->singular ?>
				</button>
			</p>
		</div>
	</fieldset>
</form>