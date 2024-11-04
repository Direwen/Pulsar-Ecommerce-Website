<?php
global $root_directory;
// Extracting records, current page, and pagination data from fetched data
$records = $fetched_data["records"];
unset($fetched_data["records"]);
$currentPage = $fetched_data["currentPage"];
$totalPages = $fetched_data["totalPages"];
$hasMore = $fetched_data["hasMore"];
$metadata = $fetched_data["metadata"];

$filtered_metadata = [];

foreach ($metadata as $attr_title => $arr) {
    // Get the sql_name
    $sql_name = $arr['sql_name'];

    // Check if the sql_name ends with "img" or "id" (case insensitive)
    if (!(strpos(strtolower($sql_name), 'img') !== false || str_ends_with(strtolower($sql_name), 'id'))) {
        // Add the attribute to the filtered array if it doesn't match
        $filtered_metadata[] = $attr_title;
    }
}

?>




<div class="w-full md:w-10/12 mx-auto rounded px-2">

    <div class="w-full mb-4">
        <form action="" method="GET" class="flex flex-col md:flex-row items-center gap-2">

            <!-- Dropdown for selecting column to search -->
            <select name="search_attribute" id="search_attribute" class="p-3 border rounded focus:outline-none focus:border-accent mb-2 md:mb-0 w-full md:w-2/12" onchange="updateInputField()">
                <?php foreach ($metadata as $attr_title => $arr): ?>
                    <!-- Skip 'id' field -->
                    <?php if (in_array($attr_title, $filtered_metadata)): ?>
                        <option value="<?= htmlspecialchars($arr['sql_name']) ?>" data-type="<?= htmlspecialchars($arr['type']) ?>"
                            <?= isset($_GET['search_attribute']) && $_GET['search_attribute'] === $attr_title ? 'selected' : '' ?>>
                            <?= ucwords(str_replace('_', ' ', $attr_title)) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>

            <!-- Dynamic Search Input (changes between text or date) -->
            <div id="search_input_container" class="grow w-full">
                <!-- Initially, a text input if no attribute is selected -->
                <input
                    type="text"
                    id="search_input"
                    name="record_search"
                    value="<?= isset($_GET['record_search']) ? htmlspecialchars($_GET['record_search']) : '' ?>"
                    placeholder="Search records..."
                    class="w-full p-3 border rounded focus:outline-none focus:border-accent mb-2 md:mb-0 md:mr-2" />
            </div>

            <!-- Buttons for search and clear -->
            <section class="w-full md:w-2/12 flex justify-between items-center gap-1">
                <!-- Search Button -->
                <button type="submit" class="w-full bg-accent text-white p-2 md:py-3 md:px-6 rounded interactive hover:bg-accent-dark">
                    <span class="material-symbols-outlined">manage_search</span>
                </button>

                <!-- Clear Button -->
                <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="w-full bg-light-gray text-dark p-2 md:py-3 md:px-6 rounded interactive hover:bg-gray-400 text-center">
                    <span class="material-symbols-outlined">search_off</span>
                </a>
            </section>
        </form>
    </div>

    <?php if (!empty($records)): ?>
        <div class="overflow-x-auto border shadow rounded">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr>
                        <?php foreach ($metadata as $attr_title => $arr): ?>
                            <!-- Skip 'id' field in headers -->
                            <?php if (in_array($attr_title, $filtered_metadata)): ?>
                                <th class="p-3 text-left font-semibold whitespace-nowrap">
                                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $attr_title))) ?>
                                </th>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <th class="p-3 text-left font-semibold whitespace-nowrap">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr class="hover:bg-accent/20">

                            <?php foreach ($record as $attr_name => $attr_value): ?>
                                <?php if ($attr_name !== "id"): ?>
                                    <td class="p-3 text-left whitespace-nowrap <?= !in_array($attr_name, $filtered_metadata) ? 'hidden' : '' ?>">
                                        <?php if ($metadata[$attr_name]['type'] == "tinyint(1)"): ?>
                                            <?= $attr_value ? "true" : "false" ?>
                                        <?php else: ?>
                                            <?php if ($attr_value !== null): ?>
                                                <?php
                                                $decodedValue = json_decode($attr_value);
                                                $valueType = gettype($decodedValue);

                                                // Check if it's an array
                                                if ($valueType === 'array'):
                                                ?>
                                                    <div class="flex flex-wrap gap-2">
                                                        <?php foreach ($decodedValue as $each): ?>
                                                            <span class="border shadow py-1 px-2 rounded"><?= htmlspecialchars($each); ?></span>
                                                        <?php endforeach; ?>
                                                    </div>

                                                <?php elseif ($valueType === 'object'): ?>
                                                    <div class="flex flex-col gap-2">
                                                        <?php foreach ($decodedValue as $key => $value): ?>
                                                            <section class="border shadow p-2">
                                                                <strong class=""><?= htmlspecialchars($key); ?>:</strong>
                                                                <?php if (is_array($value)): ?>
                                                                    <div class="flex flex-wrap gap-2">
                                                                        <?php foreach ($value as $value2): ?>
                                                                            <span class="border shadow py-1 px-2 rounded"><?= htmlspecialchars($value2); ?></span>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <span class="border shadow py-1 px-2 rounded"><?= htmlspecialchars($value); ?></span>
                                                                <?php endif; ?>
                                                            </section>
                                                        <?php endforeach; ?>
                                                    </div>

                                                <?php else: ?>
                                                    <span class=""><?= htmlspecialchars($attr_value); ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-light-gray">N/A</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            <?php endforeach; ?>


                            <!-- Action buttons (edit and delete) -->
                            <td class="p-3 flex justify-start items-center gap-2">
                                <?php if ($update_submission_file_path && $edit_btn_class): ?>
                                    <span
                                        <?php
                                        foreach ($record as $k => $v) {
                                            if ($k != 'id' && !empty($v)) {
                                                $value = is_array($v) || is_object($v) ? htmlspecialchars(json_encode($v)) : htmlspecialchars($v);
                                                echo " {$k}=\"{$value}\" ";
                                            }
                                        }

                                        foreach ($extra_info as $k => $v) {
                                            $value = is_array($v) || is_object($v) ? htmlspecialchars(json_encode($v)) : htmlspecialchars($v);
                                            echo " {$k}=\"{$value}\" ";
                                        }
                                        ?>
                                        root-directory="<?= htmlspecialchars($root_directory); ?>"
                                        submission-path="<?= htmlspecialchars($root_directory . $update_submission_file_path); ?>"
                                        data-id="<?= htmlspecialchars($record['id']); ?>"
                                        class="material-symbols-outlined interactive <?= htmlspecialchars($edit_btn_class); ?> px-2 py-1 rounded-full text-accent hover:bg-accent hover:text-primary cursor-pointer">
                                        border_color
                                    </span>
                                <?php endif; ?>


                                <?php if ($delete_submission_file_path && $delete_btn_class): ?>
                                    <span
                                        <?php
                                        foreach ($record as $k => $v) {
                                            if ($k != 'id' && !empty($v)) echo " {$k}={$v} ";
                                        }
                                        foreach ($extra_info as $k => $v) {
                                            echo " {$k}={$v} ";
                                        }
                                        ?>
                                        submission-path="<?= $root_directory . $delete_submission_file_path; ?>"
                                        <?php if ($attribute_to_confirm_deletion): ?>
                                        <?= $attribute_to_confirm_deletion . "=" . $record[$attribute_to_confirm_deletion]; ?>
                                        <?php endif; ?>
                                        data-id="<?= $record['id'] ?>"
                                        class="material-symbols-outlined interactive <?= $delete_btn_class; ?> px-2 py-1 rounded-full text-red-700 hover:bg-accent hover:text-primary cursor-pointer">
                                        delete_forever
                                    </span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    <?php else: ?>
        <div class="flex justify-center items-center shadow py-3 text-accent">
            <span class="material-symbols-outlined">info</span>
            <p class="font-semibold ">No Record Found</p>
        </div>
    <?php endif; ?>

    <!-- Full-width Next Button -->
    <div class="w-full mt-6 shadow">
        <?php if ($hasMore): ?>
            <a href="?page=<?= $currentPage + 1 ?>" class="block bg-accent text-white py-3 text-center rounded interactive">Next</a>
        <?php else: ?>
            <span class="block bg-light-dark text-white py-3 text-center rounded cursor-not-allowed">Next</span>
        <?php endif; ?>
    </div>

    <!-- Pagination Section -->
    <div class="flex justify-between items-center mt-6">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?>" class="bg-accent text-white py-2 px-4 rounded interactive">Previous</a>
        <?php else: ?>
            <span class="bg-light-dark text-white py-2 px-4 rounded cursor-not-allowed">Previous</span>
        <?php endif; ?>

        <!-- Page Numbers -->
        <div class="flex space-x-3 items-center">
            <?php
            if ($totalPages <= 4) {
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo $i == $currentPage
                        ? "<span class='bg-accent text-white py-2 px-4 rounded'>{$i}</span>"
                        : "<a href='?page={$i}' class='shadow hover:bg-light-gray text-gray-800 py-2 px-4 rounded interactive'>{$i}</a>";
                }
            } else {
                if ($currentPage > 2) {
                    echo "<a href='?page=1' class='shadow hover:bg-light-gray text-gray-800 py-2 px-4 rounded interactive'>1</a>";
                    echo "<a href='?page=2' class='shadow hover:bg-light-gray text-gray-800 py-2 px-4 rounded interactive'>2</a>";
                    echo "<span class='py-2 px-4'>...</span>";
                }
                echo "<span class='bg-accent text-white py-2 px-4 rounded'>{$currentPage}</span>";
                if ($currentPage < $totalPages) {
                    echo "<span class='py-2 px-4'>...</span>";
                    echo "<a href='?page={$totalPages}' class='shadow hover:bg-light-gray text-gray-800 py-2 px-4 rounded interactive'>{$totalPages}</a>";
                }
            }
            ?>
        </div>

        <!-- Next Button -->
        <?php if ($hasMore): ?>
            <a href="?page=<?= $currentPage + 1 ?>" class="bg-accent text-white py-2 px-4 rounded interactive">Next</a>
        <?php else: ?>
            <span class="bg-light-dark text-white py-2 px-4 rounded cursor-not-allowed">Next</span>
        <?php endif; ?>
    </div>

</div>

<!-- Debugging Table -->
<div class="w-3/12 bg-gray-200 overflow-x-auto mt-6 p-4 rounded">
    <table class="min-w-full border-collapse border border-gray-300">
        <thead class="bg-primary text-dark">
            <tr>
                <th class="border border-gray-300 p-2 text-left">Key</th>
                <th class="border border-gray-300 p-2 text-left">Value</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fetched_data as $key => $value): ?>
                <tr>
                    <td class="border border-gray-300 p-2 text-left"><?= $key ?></td>
                    <td class="border border-gray-300 p-2 text-left"><?php var_dump($value); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>