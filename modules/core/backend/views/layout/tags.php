<?php
$tags = $this->em('core_tag')->all();
?>
<ul class="tags">
    <?php foreach ($tags as $tag) { ?>
        <li>
            <a href="<?= $item['url'] . $this->url->query([
                'tagsList' => $model->tagsListToggle($tag->id),
            ]) ?>" class="<?= $model->tagsHas($tag->id) ? 'active' : null ?>">
                <?= $tag->name ?>
            </a>
        </li>
    <?php } ?>
    <li>
        <a href="<?= $item['url'] . $this->url->query([
            'tagsList' => $model->tagsListToggle('none', true),
        ]) ?>" class="<?= $model->tagsHas('none') ? 'active' : null ?>">
            Untagged
        </a>
    </li>
</ul>