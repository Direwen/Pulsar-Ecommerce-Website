<div id="navbar" class="hide-scrollbar w-10/12 min-h-screen overflow-y-scroll bg-white px-3 py-5 flex flex-col gap-6 justify-start items-start shadow-inner hidden z-50">

    <section class="flex justify-end items-center w-full">
        <span class="material-symbols-outlined interactive text-light-dark font-thin" onclick="forceOverlayToClose()">close</span>
    </section>

    <section class="w-full flex justify-between items-center cursor-pointer text-2xl" id="product-section">
        <span class="">PRODUCT</span>
        <span class="material-symbols-outlined text-light-dark text-base" id="product-selection-add-icon">add</span>
        <span class="material-symbols-outlined text-light-dark text-base hidden" id="product-selection-add-icon">remove</span>
    </section>

    <!-- Product Items List -->
    <section class="w-full flex flex-col gap-2 px-5 py-2 bg-white hidden" id="product-items">
        <?php renderCategories(false); ?>
    </section>

    <a href="<?= $root_directory . 'support' ?>" class="w-full cursor-pointer text-2xl">SUPPORT</a> <!-- Link to Support -->
    <a href="<?= $root_directory . 'about' ?>" class="w-full cursor-pointer text-2xl">ABOUT</a> <!-- Link to Support -->
    
    <?php if ($auth_service->getAuthUser()): ?>
        <a href="<?= $root_directory . 'history' ?>" class="w-full cursor-pointer text-sm">Order History</a> <!-- Link to Pulsar By You -->
        <?php if ($_SESSION["user_role"] != "user"): ?>
            <a href="<?= $root_directory . 'admin/dashboard' ?>" class="w-full cursor-pointer text-sm">Dashboard</a> <!-- Link to Pulsar By You -->
        <?php endif; ?>
        <a href="#" class="w-full cursor-pointer text-sm">Disable Account</a> <!-- Link to Pulsar By You -->
        <a href="<?= $root_directory . 'logout' ?>" class="w-full cursor-pointer text-sm">Logout</a> <!-- Link to Pulsar By You -->
    <?php else: ?>
        <a href="<?= $root_directory . 'login' ?>" class="w-full cursor-pointer text-sm">Login</a> <!-- Link to Pulsar By You -->
    <?php endif; ?>
    
</div>