<?php
$this->params([
    'filterFields' => $filterFields = (isset($filterFields) ? $filterFields : []) + [
        'status' => null,
    ],
]);
?>
<?= $this->parent() ?>