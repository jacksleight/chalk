<ul class="toolbar toolbar-right">
	<?= $this->partial('tools-top') ?>
    <?php if (in_array('create', $actions) && !$entity->isNew()) { ?>
        <li><a href="<?= $this->url([
    			'action'	=> 'update',
    			'id'		=> null,
    		]) ?>" class="btn btn-focus btn-out icon-add">
    		New <?= $info->singular ?>
    	</a></li>
    <?php } ?>
	<?= $this->partial('tools-bottom') ?>
</ul>