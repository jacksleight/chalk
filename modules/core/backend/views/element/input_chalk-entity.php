<?php
$scope = Chalk\Chalk::info($scope);
if (is_string($value) && strlen($value) == 0) {
    $value = null;
}
$value = isset($value) && is_scalar($value)
    ? $this->em($scope)->id($value)
    : $value;
$filters = isset($filters)
    ? $filters
    : [$scope->name => []];
?>
<div class="input-chalk" data-mode="entity" data-query="<?= $this->escape($this->url->query([
    'mode'        => 'one',
    'filtersList' => \Chalk\filters_list_build($filters)
], true, false)) ?>">
    <div class="input-chalk-controls">
        <span class="input-chalk-remove btn btn-lighter btn-out btn-icon icon-remove"><span>Remove</span></span>
        <span class="input-chalk-select btn btn-lighter btn-icon icon-browse"><span>Browse</span></span>
    </div>
    <div class="input-chalk-holder">
        <?php if (isset($value)) { ?>
            <?= $this->render('/element/card', [
                'scope'  => $scope,
                'entity' => $value,
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
            ? $value['id']
            : null,
        'disabled' => !isset($value),
    ]) ?>
</div>