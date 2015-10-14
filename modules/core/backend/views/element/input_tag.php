<?php
$values = array_map(function($value) {
    return [
        'value' => $value,
        'text'  => $value,
    ];
}, $values);
$values = array_values($values);
?>
<input
    type="text"
    class="input-tag <?= isset($class) ? $class : null ?>"
    data-values="<?= $this->escape(json_encode($values)) ?>"
    <?= isset($name) ? "name=\"{$name}\"" : null ?>
    <?= isset($id) ? "id=\"{$id}\"" : null ?>
    <?= isset($value) ? "value=\"" . $this->escape($value) . "\"" : null ?>
    <?= isset($disabled) && $disabled ? "disabled" : null ?>
    <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>