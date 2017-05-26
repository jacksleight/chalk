<?php 
if (isset($scope)) {
    $info = $scope;
    if (isset($value)) {
        if (is_scalar($value)) {
            $value = $this->em($info)->id($value);
        } else {
            // scoped object, dont need to do anything
        }
    }
    if (!isset($filters)) {
        $filters = [
            $scope->name => [],
        ];
    }
} else {
    if (isset($value)) {
        if (is_scalar($value)) {
            $value = json_decode($value, true);
            $info  = Chalk\Chalk::info($value['type']);
            $value = $this->em($info)->id($value['id']);
        } else {
            $info  = Chalk\Chalk::info($value);
            $value = $this->em($info)->id($value->id);
        }
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
                'entity' => $value
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
                ? $value->id
                : json_encode(['type' => $info->name, 'id' => $value->id]),
        ]) ?>
    <?php } else { ?>
        <?= $this->inner('input', [
            'type'  => 'hidden',
            'value' => null,
        ]) ?>
    <?php } ?>
</div>