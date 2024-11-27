<?php foreach ($categories as $category): ?>

    <a 
        href="<?= $root_directory; ?>products/?category=<?= $category['id']; ?>" 
        class="select-none <?= $showImages ? $cssForContainer : ''; ?>"
    >

        <?php if ($showImages): ?>
            <img
                class="<?= $cssForImg; ?>"
                src="<?= $root_directory; ?>assets/categories/<?= $category['img']; ?>" 
                alt="Category Pic"
            >
        <?php endif; ?>

        <p class="<?= $cssForCategoryName; ?>">
            <?= ucwords($category['name']); ?>
        </p>

    </a>

<?php endforeach; ?>
