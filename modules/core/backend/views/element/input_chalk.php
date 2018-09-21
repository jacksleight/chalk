<?php
if (!isset($value) || (is_string($value) && strlen($value) == 0)) {
    $value = null;
}
$info   = null;
$entity = null;
$sub    = null;
if (isset($scope)) {
    $info = Chalk\Chalk::info($scope);
    if (isset($value)) {
        $entity = is_scalar($value)
            ? $this->em($info)->id($value)
            : $value;
    }
    if (!isset($filters)) {
        $filters = [$scope->name => []];
    }
} else {
    if (isset($value)) {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        $info   = Chalk\Chalk::info($value);
        $entity = $this->em($info)->id($value['id']);
        $sub    = $entity->sub($value['sub']);
    }
}
$filters = isset($filters)
    ? $filters
    : null;
?>
<div class="input-chalk" data-scope="<?= isset($scope) ? $scope->name : null ?>" data-query="<?= $this->escape($this->url->query([
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
                'entity' => $entity,
                'sub'    => $sub,
            ]) ?>
        <?php } else if (isset($placeholder)) { ?>
            <span class="placeholder"><?= $placeholder ?></span>
        <?php } else { ?>
            <span class="placeholder">Nothing Selected</span>
        <?php } ?>
    </div>
    <?php if (isset($value)) { ?>
        <?= $this->inner('input', [
            'type'  => 'hidden',
            'value' => isset($scope)
                ? $entity->id
                : json_encode($value),
        ]) ?>
    <?php } else { ?>
        <?= $this->inner('input', [
            'type'  => 'hidden',
            'value' => null,
        ]) ?>
    <?php } ?>
</div>