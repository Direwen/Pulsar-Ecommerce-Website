<section class="<?= (isset($extra_info['title_css']) ? $extra_info['title_css'] : 'bg-secondary'); ?>">
    <div 
        class="<?= (isset($extra_info['trigger_css']) ? $extra_info['trigger_css'] : 'cursor-pointer border-b border-light-dark flex justify-between items-center px-3 py-6 uppercase font-bold text-xl  md:text-2xl tracking-tighter'); ?>"
        data-toggle="<?= $data_toggle_attr; ?>"
        onclick="toggleDropdown(this)"
    >
        <span><?= $title ?></span>
        <span class="material-symbols-outlined">add</span>
    </div>

    <section class="px-3 py-6 hidden" data-toggle="<?= $data_toggle_attr; ?>">
        <ul class="list-inside list-disc">
            <?php foreach($details as $key => $each): ?>
                <?php 
                    // Format the key
                    $key = is_numeric($key) ? "" : ucwords($key) . ": ";
                    
                    // Determine the unit based on the position in the dimension array
                    $unit = "";
                    if (isset($extra_info["add_unit"]) && $extra_info["add_unit"] === true) {
                        $unit = match ($key) {
                            "Length: ", "Width: ", "Height: " => " mm",
                            "Weight: " => " g",
                            default => ""
                        };
                    }
                ?>
                <li class="<?= (isset($extra_info['details_css']) ? $extra_info['details_css'] : 'tracking-tighter text-sm sm:text-base md:text-xl'); ?>">
                    <?= $key . $each . $unit; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

</section>