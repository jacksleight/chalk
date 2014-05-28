<tr class="selectable clickable">
	<td class="col-select">
		<?= $this->render('/content/checkbox', [
			'entity'	=> $index,
			'value'		=> $content,
		]) ?>
	</td>
	<th class="col-name" scope="row">
		<? if ($content instanceof \Ayre\Core\File && $content->file->exists() && $content->isGdCompatible()) { ?>
			<img src="<?= $this->image(
				$content->file,
				'resize',
				['size' => '48', 'crop' => true]
			) ?>">
		<? } ?>
		<a href="<?= $this->url([
			'action'	=> 'edit',
			'entityType'=> \Ayre::type($content)->slug,
			'content'	=> $content->id,
		]) ?>"><?= $content->name ?></a><br>
		<small><?= $content->subname ?></small>
	</th>
	<td>
		<a href="<?= $this->url([
			'action'	=> 'index',
			'entityType'=> \Ayre::type($content)->slug,
		]) ?>"><?= $content->typeLabel ?></a>
	</td>
	<td class="col-date">
		<?= $content->modifyDate->diffForHumans() ?><br>
		<small>by <?= $content->modifyUserName ?></small>
	</td>
	<td class="col-status">
		<span class="badge badge-status badge-<?= $content->status ?>"><?= $content->status ?></span>
	</td>	
</tr>