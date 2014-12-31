<? $this->block() ?>

<form action="<?= $this->url->route() ?>">
	
<? if ($this->block('tools')) { ?>

<ul class="toolbar">
	<?= $this->content('tools-top') ?>
	<li><a href="<?= $this->url([
			'action' => 'edit',
		]) ?>" class="btn btn-focus icon-add">
			New <?= $info->singular ?>
	</a></li>
	<?= $this->content('tools-bottom') ?>
</ul>
	
<? } if ($this->block('header')) { ?>

<h1><?= $info->plural ?></h1>
	
<? } if ($this->block('filters')) { ?>

<ul class="filters autosubmitable">
	<li>
		<?= $this->render('/element/form-input', array(
			'type'			=> 'input_search',
			'entity'		=> $index,
			'name'			=> 'search',
			'autofocus'		=> true,
			'placeholder'	=> 'Search…',
		)) ?>
	</li>
	<?= $this->content('filters-top') ?>
	<li>
		<?= $this->render('/element/form-input', array(
			'type'			=> 'dropdown_single',
			'entity'		=> $index,
			'null'			=> 'Any',
			'name'			=> 'modifyDateMin',
			'icon'			=> 'icon-updated-dark',
			'placeholder'	=> 'Updated',
		)) ?>
	</li>
	<li>
		<?= $this->render('/element/form-input', array(
			'type'			=> 'dropdown_multiple',
			'entity'		=> $index,
			'name'			=> 'statuses',
			'icon'			=> 'icon-status-dark',
			'placeholder'	=> 'Status',
		)) ?>
	</li>
	<?= $this->content('filters-bottom') ?>
</ul>
	
<? } if ($this->block('contents')) { ?>

<table class="multiselectable">
	<colgroup>
		<col class="col-select">
		<col class="col-name">
		<col class="col-date">
		<col class="col-badge">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-select">
				<input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
			</th>
			<th scope="col" class="col-name">Name</th>
			<th scope="col" class="col-date">Updated</th>
			<th scope="col" class="col-badge">Status</th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($contents)) { ?>
			<?php foreach ($contents as $content) { ?>
				<tr class="clickable selectable">
					<td class="col-select">
						<?= $this->child('/content/checkbox', [
							'entity'	=> $index,
							'value'		=> $content,
						]) ?>
					</td>
					<th class="col-name" scope="row">
						<a href="<?= $this->url([
							'action'	=> 'edit',
							'content'	=> $content->id,
						]) ?>"><?= $content->name ?></a><br>
						<small><?= $content->subname(true) ?></small>
					</th>
					<td class="col-date">
						<?= $content->modifyDate->diffForHumans() ?>
						<small>by <?= $content->modifyUserName ?></small>
					</td>	
					<td class="col-badge">
						<span class="badge badge-status badge-<?= $content->status ?>"><?= $content->status ?></span>
					</td>	
				</tr>
			<?php } ?>
		<?php } else { ?>
			<tr>
				<td class="panel" colspan="4">
					No <?= strtolower($info->plural) ?> found
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
	
<? } if ($this->block('pagination')) { ?>

<ul class="toolbar right autosubmitable">
	<li>
		Show&nbsp;
		<?= $this->render('/element/form-input', array(
			'type'			=> 'select',
			'entity'		=> $index,
			'name'			=> 'limit',
			'null'			=> 'All',
		)) ?>
	</li>
</ul>
<?= $this->render('/element/form-input', [
	'type'		=> 'paginator',
	'entity'	=> $index,
	'name'		=> 'page',
	'limit'		=> $index->limit,
	'count'		=> $contents->count(),
]) ?>
	
<? } ?>
<? $this->block() ?>

</form>