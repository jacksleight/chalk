<?php
$this->params([
    'tableCols' => $tableCols = (isset($tableCols) ? $tableCols : []) + [
        'name' => [
            'label'   => 'Name',
            'partial' => 'preview',
            'params'  => ['icon' => true],
            'sort'    => 10,
        ],
        'isHidden' => [
            'label'   => 'Hidden',
            'partial' => 'icon',
            'class'   => 'col-contract col-center',
            'params'  => ['name' => 'isHidden', 'true' => 'eye-blocked'],
            'sort'    => 70,
        ],
    ],
]);
?>
<?= $this->parent() ?>