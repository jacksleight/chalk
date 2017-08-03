<?php
$infos = $this->hook->fire('core_info_node', new Chalk\Info())->fetch('isPrimary', false);
?>
<?php foreach ($infos as $info) { ?>
    <?php
    if (isset($info->isExisting)) {
        continue;
    }
    ?>
    <li><a href="<?= $this->url([
        'action' => 'update',
        'id'     => null,
    ]) . $this->url->query([
        'nodeType' => $info->name,
    ], true) ?>" class="item icon-<?= $info->icon ?>">
        New <?= $info->singular ?>
    </a></li>
<?php } ?>
<li><a href="<?= $this->url([], 'core_select', true) . $this->url->query([
    'mode'        => 'all',
    'filtersList' => \Chalk\filters_list_build('core_info_node'),
    'selectedUrl' => $this->url([
        'action' => 'existing',
    ]),
]) ?>" class="item icon-browse" rel="modal">
    Add Existing
</a></li>