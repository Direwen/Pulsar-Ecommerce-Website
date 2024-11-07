<?php
// At the start of product-details.php
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$fetched_data = ErrorHandler::handle(fn () => $product_model->getAll(
    select: [
        ["column" => $product_model->getColumnId()],
        ["column" => $product_model->getColumnName()],
        ["column" => $product_model->getColumnDescription()],
        ["column" => $product_model->getColumnDimension()],
        ["column" => $product_model->getColumnFeature()],
        ["column" => $product_model->getColumnImportantFeature()],
        ["column" => $product_model->getColumnRequirement()],
        ["column" => $product_model->getColumnPackageContent()],
        ["column" => $product_model->getColumnImgForAds()],
        ["column" => $product_model->getColumnImg()],
        ["column" => $variant_model->getColumnId(), "alias" => "variant_id", "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnType(), "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnName(), "alias" => "variant_name", "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnUnitPrice(), "table" => $variant_model->getTableName()],
        ["column" => $variant_model->getColumnImg(), "alias" => "variant_img", "table" => $variant_model->getTableName()],
    ],
    joins: [
        [
            'type' => 'LEFT JOIN',
            'table' => $variant_model->getTableName(),
            'on' => "{$product_model->getTableName()}.{$product_model->getColumnId()} = {$variant_model->getTableName()}.{$variant_model->getColumnProductId()}"
        ]
    ],
    conditions: [
        [
            'attribute' => "{$product_model->getTableName()}.{$product_model->getColumnId()}",
            'operator' => "=",
            'value' => $_GET["id"] 
        ]
    ]    
));

// Initialize arrays to store product and variants
$product = [];
$variants = [];

// Iterate over each record and organize the data
foreach ($fetched_data['records'] as $record) {
    // Store common product data in the $product array (only once)
    $product = [
        "id" => $record['id'],
        "name" => $record['name'],
        "description" => $record['description'],
        "dimension" => $record['dimension'],
        "feature" => json_decode($record['feature'], true), // Decode feature string to array
        "important_feature" => json_decode($record['important_feature'], true), // Decode important_feature to array
        "requirement" => json_decode($record['requirement'], true),
        "package_content" => json_decode($record['package_content'], true),
        "img_for_ads" => json_decode($record['img_for_ads'], true),
        "img" => $record['img']
    ];

    // Add variant-specific data to the $variants array
    $variants[] = [
        "variant_id" => $record['variant_id'],
        "type" => $record['type'],
        "variant_name" => $record['variant_name'],
        "unit_price" => $record['unit_price'],
        "variant_img" => $record['variant_img']
    ];
}

?>

<div>

    <!-- description -->
    <section class="tracking-tigher text-dark flex flex-col justify-center items-center gap-4 p-16 md:p-48">
        <span class="font-bold text-2xl md:text-6xl uppercase"><?= ucwords("Description"); ?></span>
        <p class="text-justify text-sm w-full md:w-10/12 lg:w-8/12"><?= ucwords(htmlspecialchars($product["description"])); ?></p>
    </section>

    <!-- ads img -->
    <?php foreach($product["img_for_ads"] as $each): ?>
        <img src="<?= $root_directory . "assets/products/" . $each ?>" alt="ads image" class="w-full h-[45rem] object-cover">
    <?php endforeach; ?>

    <!-- Specifications -->
    <section class="w-10/12 mx-auto my-10">
        <h1 class="font-bold text-left text-xl md:text-5xl mb-4">Techinical Specifications</h1>
<!-- Dimensions Section -->
<section class="bg-secondary">
    <div 
        class="border-b border-light-dark flex justify-between items-center px-3 py-6 uppercase font-bold text-xl  md:text-2xl tracking-tighter"
        data-toggle="dimension"
        onclick="toggleDropdown(this)"
    >
        <span>Dimensions</span>
        <span class="material-symbols-outlined">add</span>
    </div>

    <section class="px-3 py-6 hidden" data-toggle="dimension">
        <p class="tracking-tighter text-sm md:text-xl tracking-widest"><?= $product["dimension"]; ?></p>
    </section>
</section>

<!-- Important Features Section -->
<?php foreach($product["important_feature"] as $key => $value): ?>
    <section class="bg-secondary">
        <div 
            class="border-b border-light-dark flex justify-between items-center px-3 py-6 uppercase font-bold text-xl  md:text-2xl tracking-tighter" 
            data-toggle="<?= htmlspecialchars($key); ?>"
            onclick="toggleDropdown(this)"
        >
            <span><?= htmlspecialchars($key); ?></span>
            <span class="material-symbols-outlined">add</span>
        </div>

        <section class="px-3 py-6 hidden" data-toggle="<?= htmlspecialchars($key); ?>">
            <?php foreach($value as $each): ?>
                <p class="tracking-tighter text-sm md:text-xl tracking-widest">- <?= $each; ?></p>
            <?php endforeach; ?>
        </section>
    </section>
<?php endforeach; ?>

<!-- General Features Section -->
<section class="bg-secondary">
    <div 
        class="border-b border-light-dark flex justify-between items-center px-3 py-6 uppercase font-bold text-xl  md:text-2xl tracking-tighter"
        data-toggle="general"
        onclick="toggleDropdown(this)"
    >
        <span>General</span>
        <span class="material-symbols-outlined">add</span>
    </div>

    <section class="px-3 py-6 hidden" data-toggle="general">
        <?php foreach($product["feature"] as $each): ?>
            <p class="tracking-tighter text-sm md:text-xl tracking-widest">- <?= $each; ?></p>
        <?php endforeach; ?>
    </section>
</section>

<!-- Requirements Section -->
<section class="bg-secondary">
    <div 
        class="border-b border-light-dark flex justify-between items-center px-3 py-6 uppercase font-bold text-xl  md:text-2xl tracking-tighter"
        data-toggle="requirement"
        onclick="toggleDropdown(this)"
    >
        <span>Requirement</span>
        <span class="material-symbols-outlined">add</span>
    </div>

    <section class="px-3 py-6 hidden" data-toggle="requirement">
        <?php foreach($product["requirement"] as $each): ?>
            <p class="tracking-tighter text-sm md:text-xl tracking-widest">- <?= $each; ?></p>
        <?php endforeach; ?>
    </section>
</section>

<!-- Package Content Section -->
<section class="bg-secondary">
    <div 
        class="border-b border-light-dark flex justify-between items-center px-3 py-6 uppercase font-bold text-xl  md:text-2xl tracking-tighter"
        data-toggle="package_content"
        onclick="toggleDropdown(this)"
    >
        <span>Package Content</span>
        <span class="material-symbols-outlined">add</span>
    </div>

    <section class="px-3 py-6 hidden" data-toggle="package_content">
        <?php foreach($product["package_content"] as $each): ?>
            <p class="tracking-tighter text-sm sm:text-base md:text-xl tracking-widest">- <?= $each; ?></p>
        <?php endforeach; ?>
    </section>
</section>

    </section>
    <!-- manual -->
</div>