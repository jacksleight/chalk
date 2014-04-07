<? $this->layout('/layouts/page', [
	'class' => 'upload',
]) ?>
<? $this->block('main') ?>

<ul class="toolbar">
	<!-- <li>
		<a href="<?= $this->url(['action' => 'edit']) ?>" class="button">
			<i class="fa fa-plus"></i>
			Add
		</a>
	</li> -->
	<li>
		<span class="button upload-button">
			<i class="fa fa-upload"></i>
			Upload
		</span>
	</li>
</ul>
<h1>Files</h1>
<ul class="thumbs upload-list"></ul>
<input class="upload-input" type="file" name="files[]" data-url="<?= $this->url(['action' => 'upload']) ?>" multiple>
<script type="x-tmpl-mustache" class="upload-template">
	<li>
		<figure>
			<div class="progress">
				<span class="progress-status" style="height: 0%;"><span>Waiting...</span></span>
			</div>
			<figcaption>
				<strong>{{name}}</strong><br>
				{{type}} – {{size}}
			</figcaption>
		</figure>
	</li>
</script>