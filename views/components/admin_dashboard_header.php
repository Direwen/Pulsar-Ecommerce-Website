<div class="w-full md:w-10/12 mx-auto flex justify-between items-center px-2 py-3">
    <span class="font-extrabold text-lg  sm:text-xl uppercase tracking-wide"><?= $title_name; ?></span>

    <span 
        <?php
        foreach($extra_info as $k => $v) {
            echo " {$k}={$v} ";
        }
        ?>
        submission-path=<?= $root_directory . $submission_path; ?> 
        class="interactive whitespace-nowrap cursor-pointer shadow bg-accent text-primary px-2 py-1 rounded <?= $create_user_btn_class; ?>"
    >
        <span class="material-symbols-outlined text-lg">add</span>    
        Create
        <span class="hidden md:inline"><?= $create_btn_desc; ?></span>
    </span>
</div>