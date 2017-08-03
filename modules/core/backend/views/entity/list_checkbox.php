<input
    type="checkbox"
    id="<?= "__select-" . $uniqid = uniqid(true) ?>"
    value="<?= $entity->id ?>"
    <?= array_intersect([$entity->id], $model->selected) ? 'checked' : null ?>>
<label for="<?= "__select-" . $uniqid ?>" class="checkbox"></label>