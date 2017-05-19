<?php
$subtypes = $this->em($info)->subtypes(['types' => isset($filters) ? $filters : null]);
$values   = [];
$class    = $info->class;
foreach ($subtypes as $subtype) {
    $values[$subtype['subtype']] = $class::staticSubtypeLabel($subtype['subtype']);
}
asort($values);
if (!count($subtypes)) {
    return;
}
?>
<?= $this->render('/element/form-input', array(
    'type'          => 'dropdown_multiple',
    'entity'        => $model,
    'name'          => 'subtypes',
    'icon'          => 'icon-subtype',
    'placeholder'   => 'Type',
    'values'        => $values,
), 'core') ?>