<?php $this->outer('/layout/page_structure', [
    'title'   => isset($node) ? $node->content->name : null,
]) ?>
<?php $this->block('main') ?>

<?php if (isset($node->content)) { ?>
    <?php
    $structure	= $this->em('Chalk\Core\Structure')->id($req->structure);
    $content	= $node->content;
    $info	    = \Chalk\Chalk::info($content->getObject());
    ?>
    <?php if ($info->class != 'Chalk\Core\Content') { ?>
    	<form action="<?= $this->url->route() ?><?= $this->url->query() ?>" method="post">
    		<?= $this->inner("/{$info->local->path}/form", [
    			'structure'	=> $structure,
    			'content'	=> $content,
    			'info'	    => $info,
    		], $info->module->name) ?>
    	</form>
    <?php } ?>    
<?php } else { ?>
    <div class="notice">
        <h2>No Content Selected</h2>
        <p>
            To edit exising content select it in the sidebar.<br>
            To add new content click the plus button in the sidebar.<br>
        </p>
    </div>
<?php } ?>