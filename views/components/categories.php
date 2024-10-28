<?php foreach ($categories as $category): ?>
    <?php if($showImages): ?>
        <div class="<?= $cssForContainer; ?>">
            <img
                class="<?= $cssForImg; ?>" 
                src="<?= $category['image'] ?>"
                alt="Category Pic"
            >
    <?php endif; ?>

    <a 
        class="<?= $cssForCategoryName; ?>"
        href="<?= $category['link']; ?>">
        <?= $category['name']; ?>
    </a>

    <?php if($showImages): ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
