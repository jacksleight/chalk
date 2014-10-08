<?php
$template	= isset($template) ? $template : false;
$covered	= isset($covered) ? $covered : false;
$link		= isset($link) ? $link : true;
?>
<tr class="selectable clickable">
	<td class="col-select">
		<?= $this->render('/content/checkbox', [
			'entity'	=> $index,
			'value'		=> $content,
		]) ?>
	</td>
	<th class="col-name" scope="row">
		<div class="card">
			<?php if ($content instanceof \Chalk\Core\File && $content->file->exists() && $content->isGdCompatible()) { ?>
				<img src="<?= $this->image(
					$content->file,
					'resize',
					['size' => '48', 'crop' => true]
				) ?>">
			<?php } ?>
			<?php if ($link) { ?>
				<a href="<?= $this->url([
					'action'	=> 'edit',
					'entity'	=> \Chalk::entity($content)->name,
					'content'	=> $content->id,
				]) ?>">
			<?php } ?>
			<?= $content->name ?>
			<?php if ($link) { ?>
				</a>
			<?php } ?>
			<br>
			<small><?= $content->subname($entity->name != 'core_content') ?></small>
		</div>
	</th>
	<td class="col-date">
		<?= $content->modifyDate->diffForHumans() ?><br>
		<small>by <?= $content->modifyUserName ?></small>
	</td>
	<td class="col-status">
		<span class="badge badge-status badge-<?= $content->status ?>"><?= $content->status ?></span>
	</td>	
</tr>