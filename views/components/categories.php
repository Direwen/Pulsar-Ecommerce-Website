<?php foreach ($categories as $category): ?>
    <div class="<?= $showImages ? $cssForContainer : ''; ?>">
        <?php if ($showImages): ?>
            <img
                class="<?= $cssForImg; ?>"
                src="<?= $root_directory; ?>assets/categories/<?= $category['img']; ?>" 
                alt="Category Pic"
            >
        <?php endif; ?>

        <a 
            class="<?= $cssForCategoryName; ?>"
            href="products/?category=<?= $category['id']; ?>">
            <?= ucwords($category['name']); ?>
        </a>
    </div>
<?php endforeach; ?>
