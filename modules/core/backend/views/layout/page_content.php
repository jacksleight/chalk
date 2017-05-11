<?php $this->outer('/layout/body') ?>
<?php $this->block('main') ?>

<?= $this->content('main') ?>

<?php $this->block('sidebar') ?>

<div class="body">
    <nav class="nav" role="navigation">
        <ul>
            <?php foreach ($this->contentList as $name => $classInfo) { ?>
                <?php
                $count = $this->em($classInfo)->count(['isPublishable' => true]);
                ?>
                <li><a href="<?= $this->url([
                    'entity' => $classInfo->name,
                ], 'core_content', true) ?>" class="item <?= $classInfo->name == $info->name ? 'active' : null ?>">
                    <span class="icon-sidebar icon-<?= $classInfo->icon ?>"></span>
                    <?= $classInfo->plural ?>
                    <?php if ($count) { ?>
                        <span class="badge badge-figure badge-negative badge-out"><?= $count ?></span>
                    <?php } ?>
                </a></li>
            <?php } ?>
        </ul>
    </nav>   
</div>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>