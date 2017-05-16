<?php
$this->params([
    'filterFields' => $filterFields = [
        'subtype' => [
            'class'   => 'flex-2',
            'partial' => 'subtype',
            'sort'    => 15,
        ],
    ] + (isset($filterFields) ? $filterFields : []),
]);
?>
<?= $this->parent() ?>