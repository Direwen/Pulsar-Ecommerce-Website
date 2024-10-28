<?php

$records = $db_data["records"];
unset($db_data["records"]);

$currentPage = $db_data["currentPage"];
$totalPages = $db_data["totalPages"];
$hasMore = $db_data["hasMore"];

?>

<div class="w-full md:w-10/12 mx-auto rounded shadow">

    <div class="overflow-x-scroll">
        <table class="min-w-full border-collapse border">
            <thead>
                <tr>
                    <?php if (!empty($records)): ?>
                        <?php foreach (array_keys($records[0]) as $header): ?>
                            <th class="border border-gray-300 p-2 text-left whitespace-nowrap"><?= htmlspecialchars($header) ?></th> <!-- Table header -->
                        <?php endforeach; ?>
                        <th class="border border-gray-300 p-2 text-left whitespace-nowrap">Edit</th> <!-- Table header -->
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <?php foreach ($record as $attribute): ?>
                            <td class="border border-gray-300 p-2 text-left whitespace-nowrap"><?= $attribute !== null ? htmlspecialchars($attribute) : 'N/A' ?></td> <!-- Display 'N/A' for null values -->
                        <?php endforeach; ?>
                        <td class="border border-gray-300 p-2 text-left whitespace-nowrap">
                            <span
                                submission-path="<?= $root_directory . 'admin/users/update' ?>" 
                                data-id="<?= $record['id'] ?>" 
                                user-email="<?= $record['email'] ?>" 
                                class="material-symbols-outlined interactive edit-user-button"
                            >
                                edit
                            </span>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Full-width Next Button -->
    <div class="w-full mt-4">
        <?php if ($hasMore): ?>
            <a href="?page=<?= $currentPage + 1 ?>" class="block bg-blue-500 text-white p-4 text-center rounded">Next</a>
        <?php else: ?>
            <span class="block bg-gray-400 text-white p-4 text-center rounded cursor-not-allowed">Next</span>
        <?php endif; ?>
    </div>

    <!-- Pagination Section with Arrows and Limited Pages -->
    <div class="flex justify-between items-center mt-4 w-full">
        <!-- Previous Button -->
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?>" class="bg-blue-500 text-white p-2 rounded">Previous</a>
        <?php else: ?>
            <span class="bg-gray-400 text-white p-2 rounded cursor-not-allowed">Previous</span>
        <?php endif; ?>

        <!-- Page Numbers -->
        <div class="flex space-x-2 items-center">
            <?php
            // Display pagination logic
            if ($totalPages <= 4) {
                // If there are 4 or fewer pages, display all
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo $i == $currentPage
                        ? "<span class='bg-blue-500 text-white p-2 rounded'>{$i}</span>"
                        : "<a href='?page={$i}' class='bg-gray-200 text-gray-800 p-2 rounded'>{$i}</a>";
                }
            } else {
                // Display first two pages, current page, last page, with ellipsis if necessary
                if ($currentPage > 2) {
                    echo "<a href='?page=1' class='bg-gray-200 text-gray-800 p-2 rounded'>1</a>";
                    echo "<a href='?page=2' class='bg-gray-200 text-gray-800 p-2 rounded'>2</a>";
                    echo "<span class='p-2'>...</span>";
                }
                // Display current page
                echo "<span class='bg-blue-500 text-white p-2 rounded'>{$currentPage}</span>";

                // Display last page if current page is not the last
                if ($currentPage < $totalPages) {
                    echo "<span class='p-2'>...</span>";
                    echo "<a href='?page={$totalPages}' class='bg-gray-200 text-gray-800 p-2 rounded'>{$totalPages}</a>";
                }
            }
            ?>
        </div>

        <!-- Next Button -->
        <?php if ($hasMore): ?>
            <a href="?page=<?= $currentPage + 1 ?>" class="bg-blue-500 text-white p-2 rounded">Next</a>
        <?php else: ?>
            <span class="bg-gray-400 text-white p-2 rounded cursor-not-allowed">Next</span>
        <?php endif; ?>
    </div>


</div>

<!-- Debugging Table -->
<div class="w-3/12 bg-gray-200 overflow-x-scroll mt-4">
    <table class="min-w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 p-2 text-left whitespace-nowrap">Key</th> <!-- Display key for remaining data -->
                <th class="border border-gray-300 p-2 text-left whitespace-nowrap">Value</th> <!-- Display value for remaining data -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($db_data as $key => $value): ?>
                <tr>
                    <td class="border border-gray-300 p-2 text-left whitespace-nowrap"><?= htmlspecialchars($key) ?></td>
                    <td class="border border-gray-300 p-2 text-left whitespace-nowrap"><?= htmlspecialchars($value) ?></td> <!-- Display remaining data -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


