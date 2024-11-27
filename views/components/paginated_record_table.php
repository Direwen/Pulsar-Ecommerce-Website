<?php
global $root_directory;
// Extracting records, current page, and pagination data from fetched data
$records = $fetched_data["records"];
unset($fetched_data["records"]);
$currentPage = $fetched_data["currentPage"];
$totalPages = $fetched_data["totalPages"];
$hasMore = $fetched_data["hasMore"];
$metadata = $fetched_data["metadata"];
$toggleBtnCount = 0;

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

$search_attribute = isset($_GET['search_attribute']) ? $_GET['search_attribute'] : null;
$record_search = isset($_GET['record_search']) ? $_GET['record_search'] : null;
$record_search_end_date = isset($_GET['record_search_end_date']) ? $_GET['record_search_end_date'] : null;

?>




<div class="w-full md:w-10/12 mx-auto rounded px-2">

    <div class="w-full mb-4">
        <form action="" method="GET" class="flex flex-col md:flex-row items-center gap-2 overflow-hidden">

            <!-- Dropdown for selecting column to search -->
            <select name="search_attribute" id="search_attribute" class="p-3 border rounded focus:outline-accent mb-2 md:mb-0 w-full md:w-2/12" onchange="updateInputField()">
                <option value="" <?= empty($search_attribute) ? 'selected' : '' ?>  disabled>Search By</option>
                <?php foreach ($metadata as $attr_title => $arr): ?>
                    <!-- Skip 'id' field -->
                    <?php if (in_array($attr_title, $filtered_metadata)): ?>
                        <option 
                            value="<?= htmlspecialchars($arr['sql_name']) ?>" 
                            data-type="<?= htmlspecialchars($arr['type']) ?>"
                            <?= (htmlspecialchars($search_attribute) == htmlspecialchars($arr['sql_name'])) ? 'selected' : '' ?>
                        >
                            <?= ucwords(str_replace('_', ' ', $attr_title)) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>

            <!-- Dynamic Search Input (changes between text or date) -->
            <div id="search_input_container" class="relative grow w-full">
                <!-- Initially, a text input if no attribute is selected -->
                <input
                    type="text"
                    id="search_input"
                    name="record_search"
                    value="<?= isset($_GET['record_search']) ? htmlspecialchars($_GET['record_search']) : '' ?>"
                    placeholder="Search records..."
                    class="w-full p-3 pr-28 border shadow rounded focus:outline-none focus:border-accent mb-2 md:mb-0 md:mr-2" 
                />

                <section class="absolute top-0 right-0 w-fit flex items-stretch">
                    <!-- Clear Button -->
                    <a 
                        href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" 
                        class="w-full interactive text-light-dark text-center flex justify-center items-center p-2">
                        <span class="material-symbols-outlined">restart_alt</span>
                    </a>

                    <!-- Search Button -->
                    <button type="submit" class="w-full text-light-dark interactive p-2">
                        <span class="material-symbols-outlined">search</span>
                    </button>
                </section>
            </div>

            
        </form>
    </div>

    <?php if (!empty($records)): ?>
        <div class="overflow-x-auto border shadow rounded hide-scrollbar">
            <table class="min-w-full border-collapse">
                <thead class="bg-accent">
                    <tr>
                        <?php foreach ($metadata as $attr_title => $arr): ?>
                            <!-- Skip 'id' field in headers -->
                            <?php if (in_array($attr_title, $filtered_metadata)): ?>
                                <th class="p-3 text-left font-semibold whitespace-nowrap">
                                    <span class="text-secondary">
                                        <?= htmlspecialchars(ucwords(str_replace('_', ' ', $attr_title))) ?>
                                    </span>
                                </th>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <th class="p-3 text-left font-semibold whitespace-nowrap">
                            <span class="text-secondary">
                                Actions
                            </span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr class="hover:bg-light-gray/50 transition ease-in-out transition-150">

                            <?php foreach ($record as $attr_name => $attr_value): ?>
                                <?php if ($attr_name !== "id"): ?>
                                    <td class="p-3 text-left  min-w-[100px] max-w-[400px] whitespace-nowrap <?= !in_array($attr_name, $filtered_metadata) ? 'hidden' : '' ?>">
                                        <?php if ($metadata[$attr_name]['type'] == "tinyint(1)"): ?>
                                            <?= $attr_value ? "true" : "false" ?>
                                        <?php else: ?>
                                            <?php if ($attr_value != null): ?>
                                                <?php
                                                $decodedValue = json_decode($attr_value);
                                                $valueType = gettype($decodedValue);

                                                // Check if it's an array
                                                if ($valueType === 'array'): 
                                                ?>

                                                    <?php if(count($decodedValue) > 0): ?>

                                                        <div class="">
                                                            <?php $toggleBtnCount++; ?>
                                                            <button class="text-dark interactive px-3 rounded" data-toggle="dashabordToggleBtn<?= $toggleBtnCount; ?>" onclick="toggleDropdown(this)">
                                                                Show
                                                                <span class="material-symbols-outlined">arrow_drop_down</span>
                                                            </button>
                                                            <section class="py-3 px-4 border shadow rounded hidden flex flex-col space-y-2" data-toggle="dashabordToggleBtn<?= $toggleBtnCount; ?>">
                                                                <?php foreach ($decodedValue as $each): ?>
                                                                    <span><?= htmlspecialchars($each); ?></span>
                                                                <?php endforeach; ?>
                                                            </section>
                                                        </div>

                                                    <?php else: ?>

                                                        <span>N/A</span>

                                                    <?php endif; ?>

                                                <?php elseif ($valueType === 'object'): ?>
                                                    <div class="flex justify-start items-center gap-2 flex-wrap">

                                                        <?php foreach ($decodedValue as $key => $value): ?>

                                                            <?php $toggleBtnCount++; ?>

                                                            <section class="">
                                                                <button class="border shadow px-3 rounded" data-toggle="dashabordToggleBtn<?= $toggleBtnCount; ?>" onclick="toggleDropdown(this)">
                                                                    <?= htmlspecialchars($key); ?>
                                                                    <span class="material-symbols-outlined">arrow_drop_down</span>
                                                                </button>

                                                                <?php if (is_array($value)): ?>

                                                                    <section class="py-3 px-4 border shadow rounded hidden flex flex-col space-y-2" data-toggle="dashabordToggleBtn<?= $toggleBtnCount; ?>">
                                                                        <?php foreach ($value as $each): ?>
                                                                            <span><?= htmlspecialchars($each); ?></span>
                                                                        <?php endforeach; ?>
                                                                    </section>

                                                                <?php else: ?>

                                                                    <section class="py-3 px-4 border shadow rounded hidden flex flex-col space-y-2" data-toggle="dashabordToggleBtn<?= $toggleBtnCount; ?>">
                                                                        <span><?= htmlspecialchars($value); ?></span>
                                                                    </section>

                                                                <?php endif; ?>
                                                                
                                                            </section>
                                                        <?php endforeach; ?>
                                                    </div>

                                                <?php else: ?>
                                                    <p class="truncate"><?= ucwords(htmlspecialchars($attr_value)); ?></p>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-light-dark font-light">N/A</span>
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
                                            if ($k != 'id') {
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
            <span class="block bg-light-gray text-light-dark py-3 text-center rounded cursor-not-allowed">Next</span>
        <?php endif; ?>
    </div>

    <!-- Pagination Section -->
    <div class="flex justify-between items-center mt-6">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?>" class="bg-accent text-white py-2 px-4 rounded interactive">Previous</a>
        <?php else: ?>
            <span class="bg-light-gray text-light-dark py-2 px-4 rounded cursor-not-allowed">Previous</span>
        <?php endif; ?>

        <!-- Page Numbers -->
        <div class="flex space-x-3 items-center">
            <?php
            if ($totalPages <= 4) {
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo $i == $currentPage
                        ? "<span class='bg-accent text-primary py-2 px-4 rounded'>{$i}</span>"
                        : "<a href='?page={$i}' class='shadow hover:bg-light-gray text-light-dark py-2 px-4 rounded interactive'>{$i}</a>";
                }
            } else {
                if ($currentPage > 2) {
                    echo "<a href='?page=1' class='shadow hover:bg-light-gray text-light-dark py-2 px-4 rounded interactive'>1</a>";
                    echo "<a href='?page=2' class='shadow hover:bg-light-gray text-light-dark py-2 px-4 rounded interactive'>2</a>";
                    echo "<span class='py-2 px-4'>...</span>";
                }
                echo "<span class='bg-accent text-white py-2 px-4 rounded'>{$currentPage}</span>";
                if ($currentPage < $totalPages) {
                    echo "<span class='py-2 px-4'>...</span>";
                    echo "<a href='?page={$totalPages}' class='shadow hover:bg-light-gray text-light-dark py-2 px-4 rounded interactive'>{$totalPages}</a>";
                }
            }
            ?>
        </div>

        <!-- Next Button -->
        <?php if ($hasMore): ?>
            <a href="?page=<?= $currentPage + 1 ?>" class="bg-accent text-white py-2 px-4 rounded interactive">Next</a>
        <?php else: ?>
            <span class="bg-light-gray text-light-dark py-2 px-4 rounded cursor-not-allowed">Next</span>
        <?php endif; ?>
    </div>

</div>