<?php
$tags = $this->em('core_tag')->all();
?>
<ul class="tags">
    <?php foreach ($tags as $tag) { ?>
        <li>
            <a href="<?= $url->queryParams([
                'tagsList' => $model->tagsListToggle($tag->id),
            ]) ?>" class="<?= $model->tagsHas($tag->id) ? 'active' : null ?>">
                <?= $tag->name ?>
            </a>
        </li>
    <?php } ?>
    <li>
        <a href="<?= $url->queryParams([
            'tagsList' => $model->tagsListToggle('none'),
        ]) ?>" class="<?= $model->tagsHas('none') ? 'active' : null ?>">
            Untagged
        </a>
    </li>
    <?= str_repeat('<li></li>', 10) ?>
</ul>