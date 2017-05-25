<?php 
$filters = isset($filters)
    ? $filters
    : null;
if (in_array($md['type'], ['oneToOne', 'manyToOne'])) {
    $scope = 'local';
    $info  = Chalk\Chalk::info($md['entity']);
    if (isset($value)) {
        $value = $this->em($info)->id($value);
    }
    if (!isset($filters)) {
        $filters = [
            $info->name => [],
        ];
    }
} else {
    $scope = 'global';
    if (isset($value)) {
        $info  = Chalk\Chalk::info($value);
        $value = $this->em($info)->id($value->id);
    }
}
?>
<div class="input-chalk" data-scope="<?= $scope ?>" data-query="<?= $this->escape($this->url->query([
    'mode'        => 'one',
    'filtersList' => \Chalk\filters_list_build($filters)
], true, false)) ?>">
    <div class="input-chalk-controls">
        <span class="input-chalk-remove btn btn-lighter btn-out btn-icon icon-remove"><span>Remove</span></span>
        <span class="input-chalk-select btn btn-lighter btn-icon icon-browse"><span>Browse</span></span>
    </div>
    <div class="input-chalk-holder">
        <?php if (isset($value)) { ?>
            <?= $this->inner('/element/card', [
                'entity' => $value
            ]) ?>
        <?php } else if (isset($placeholder)) { ?>
            <span class="placeholder"><?= $placeholder ?></span>
        <?php } else { ?>
            <span class="placeholder">Nothing Selected</span>
        <?php } ?>
    </div>
    <?= $this->inner('input', [
        'type'  => 'hidden',
        'value' => isset($value)
            ? ($scope == 'local' ? $value->id : json_encode(['type' => $info->name, 'id' => $value->id]))
            : null,
    ]) ?>
</div>