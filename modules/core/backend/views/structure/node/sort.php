<?php $this->outer('layout/page', [
    'title' => $info->plural,
], 'core') ?>
<?php $this->block('main') ?>
<?php
$entities = $this->em($info)
    ->all();
$roots = [];
foreach ($entities as $entity) {
    $root = $entity->structure->root;
    if (!isset($roots[$root->id])) {
        $roots[$root->id] = [$root, []];
    }
    if ($entity->depth == 0) {
        continue;
    }
    $roots[$root->id][1][] = $entity;
}
?>

<form novalidate data-modal-size="800" class="flex-col">
    <div class="header">
        <h1>
            Move <?= $info->plural ?>
        </h1>
    </div>
    <div class="body flex">
        <div class="list-table">
            <div class="list-table-item list-table-head">
                Name
            </div>
            <?php foreach ($roots as $root) { ?>
                <div class="sortable-nested" data-id="<?= $root[0]->id ?>">
                    <div class="list-table-item list-table-group">
                        <?= $root[0]->structure->previewName ?>
                    </div>
                    <?php if (isset($root[0]->content)) { ?>
                        <div class="list-table-item">
                            <?= $this->inner('/element/preview-text', [
                                'entity' => $root[0]->content,
                                'icon'   => true,
                            ], 'core') ?>
                        </div>
                    <?php } ?>
                    <ol class="sortable-nested-list">
                        <?php
                        $depth = 0;
                        ?>
                        <?php foreach ($root[1] as $i => $entity) { ?>
                            <?php if ($entity->depthFlat > $depth) { ?>
                                <ol class="sortable-nested-list">
                            <?php } else if ($entity->depthFlat < $depth) { ?>
                                <?= str_repeat('</li></ol></li>', $depth - $entity->depthFlat) ?>
                            <?php } else if ($i > 0) { ?>
                                </li>
                            <?php } ?>
                            <li class="sortable-nested-item" data-id="<?= $entity->id ?>">
                            <div class="sortable-nested-handle list-table-item">
                                <i class="icon-drag"></i>
                                <?= $this->inner('/element/preview-text', [
                                    'entity' => $entity,
                                    'icon'   => true,
                                ], 'core') ?>
                            </div>
                            <?php
                            $depth = ($entity->depthFlat);
                            ?>
                        <?php } ?>
                        <?php if ($depth > 0) { ?>
                            <?= str_repeat('</li></ol></li>', $depth) ?>
                        <?php } ?>
                        <li class="sortable-nested-item sortable-nested-blank sortable-nested-collapsed">
                            <div class="sortable-nested-handle list-table-item"></div>
                        </li>
                    </ol>
                </div>
            <?php } ?>
        </div>
        <div class="sortable-nested-data"></div>
    </div>
    <div class="footer">
        <ul class="toolbar toolbar-right">
            <li>
                <button class="btn btn-positive icon-ok confirmable">
                    Save Changes
                </button>
            </li>
        </ul>
    </div>
</form>