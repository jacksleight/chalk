<?php $this->parent('/layout/page_settings') ?>
<?php $this->block('main') ?>
<?php
$structures = $this->em($info)
	->all();
?>

<div class="flex-col">
	<div class="body">
		<ul class="toolbar toolbar-right">
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
				<col class="col-contract col-right">
			</colgroup>
			<thead>
				<tr>
					<th scope="col" class="col-name">Name</th>
					<th scope="col" class="col-contract col-right">Added</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($structures as $structure) { ?>
					<tr class="clickable">
						<th class="col-name" scope="row">
							<a href="<?= $this->url([
								'action'	=> 'edit',
								'id'		=> $structure->id,
							]) ?>">
								<?= $structure->name ?>
							</a>
						</th>
						<td class="col-contract col-right">
							<?= $structure->createDate->diffForHumans() ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>