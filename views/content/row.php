<tr class="linkable">
	<th class="col-name" scope="row">
		<a href="<?= $this->url([
			'action'	=> 'edit',
			'id'		=> $content->id,
		]) ?>">
			<?= $content->name ?>
		</a>
	</th>
	<td class="col-create">
		<?= getRelativeDate($content->createDate) ?> <small>by</small>
		<?= isset($content->createUser) ? $content->createUser->name : 'System' ?>
	</td>
	<td class="col-status">
		<span class="label status status-<?= $content->status ?>"><?= $content->status ?></span>
	</td>	
</tr>