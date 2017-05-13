<?php
use Chalk\App as Chalk;
use Chalk\Repository;
$info = Chalk::info($index->type);
?>
<div class="flex flex-row">
    <div class="leftbar">
        <div class="body">
            <nav class="nav" role="navigation">
                <ul>
                    <?php foreach ($filters as $filter) { ?>
                        <li class="item <?= $filter->name == $info->name ? 'active' : null ?>"><a href="<?= $this->url([]) . $this->url->query([
                            'filters' => $index->filters,
                            'type'    => $filter->name,
                        ], true) ?>" class="item <?= $filter->name == $info->name ? 'active' : null ?>">
                            <span class="icon-sidebar icon-<?= $filter->icon ?>"></span>
                            <?= $filter->plural ?>
                        </a></li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
	<div class="flex rightbar">
		<?= $this->render("/{$info->local->path}/list", [
			'contents'		=> $this->em($info)->all(['types' => $filters] + $index->toArray(), [], Repository::FETCH_ALL_PAGED),
			'isAddAllowed'	=> false,
			'isEditAllowed'	=> false,
			'info'			=> $info,
			'index'			=> $index,
            'filters'       => $filters,
		], $info->module->name) ?>
        <?= $this->render('/element/form-input', array(
            'type'          => 'input_hidden',
            'entity'        => $index,
            'name'          => 'type',
        ), 'core') ?>
        <?= $this->render('/element/form-input', array(
            'type'          => 'input_hidden_array',
            'entity'        => $index,
            'name'          => 'filters',
        ), 'core') ?>
	</div>
</div>