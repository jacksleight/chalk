<?php if (!$req->isAjax()) { ?>
	<?php $this->layout('/layouts/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<div class="fill" data-modal-size="400x400">
	<div class="flex">
		<h1>
			Chalk
			<small>v0.1.0</small>
		</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat, quod, corporis nostrum aspernatur impedit possimus provident! Ex, accusamus, dolorem magni provident quo necessitatibus deserunt molestiae quisquam atque quibusdam. Ipsam, fugiat.</p>
	</div>
	<div class="fix">
		<ul class="toolbar">
			<li><span class="btn modal-close">
				<i class="fa fa-times"></i>
				Close
			</span></li>
		</ul>
	</div>
</div>