<?php $this->layout('/layout/page_settings') ?>
<?php $this->block('main') ?>
<?php
$domains = $this->em($info)
	->fetchAll();
?>

<ul class="toolbar">
	<li>
		<a href="<?= $this->url([
			'action' => 'edit',
		]) ?>" class="btn btn-focus icon-add">
			New <?= $info->singular ?>
		</a>
	</li>
</ul>
<h1><?= $info->plural ?></h1>
<table>
	<colgroup>
		<col class="col-name">
		<col class="col-date">
	</colgroup>
	<thead>
		<tr>
			<th scope="col" class="col-name">Domain Name</th>
			<th scope="col" class="col-date">Added</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($domains as $domain) { ?>
			<tr class="clickable">
				<th class="col-name" scope="row">
					<a href="<?= $this->url([
						'action'	=> 'edit',
						'id'		=> $domain->id,
					]) ?>">
						<?= $domain->name ?>
					</a>
				</th>
				<td class="col-date">
					<?= $domain->createDate->diffForHumans() ?>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>