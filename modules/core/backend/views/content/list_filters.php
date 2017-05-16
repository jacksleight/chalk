<?php
$this->params([
    'filterFields' => $filterFields = (isset($filterFields) ? $filterFields : []) + [
        'subtype' => [
            'class'   => 'flex-2',
            'partial' => 'subtype',
            'sort'    => 70,
        ],
    ],
]);
?>
<?= $this->parent() ?>