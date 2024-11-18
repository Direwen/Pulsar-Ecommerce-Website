<div id="navbar" class="hide-scrollbar w-8/12 min-h-screen overflow-y-scroll bg-white px-2 py-5 flex flex-col gap-2 justify-start items-start shadow-inner hidden z-50">

    <section class="flex justify-end items-center w-full py-5">
        <span class="material-symbols-outlined interactive text-4xl" onclick="forceOverlayToClose()">close</span>
    </section>

    <section class="w-full flex justify-between items-center cursor-pointer text-2xl" id="product-section">
        <span class="">PRODUCT</span>
        <span class="material-symbols-outlined" id="product-selection-add-icon">add</span>
        <span class="material-symbols-outlined hidden" id="product-selection-add-icon">remove</span>
    </section>

    <!-- Product Items List -->
    <section class="w-full flex flex-col gap-2 px-5 py-2 bg-white hidden" id="product-items">
        <?php renderCategories(false); ?>
    </section>

    <a href="#" class="w-full cursor-pointer text-2xl">SUPPORT</a> <!-- Link to Support -->
    <a href="#" class="w-full cursor-pointer text-2xl">WHERE TO BUY</a> <!-- Link to Where to Buy -->
    <a href="#" class="w-full cursor-pointer text-2xl">eSports</a> <!-- Link to eSports -->
    <a href="#" class="w-full cursor-pointer text-2xl">RELEASE</a> <!-- Link to Release -->
    <a href="#" class="w-full cursor-pointer text-2xl">PULSAR BY YOU</a> <!-- Link to Pulsar By You -->

    <hr>
    
    <?php if ($auth_service->getAuthUser()): ?>
        <a href="<?= $root_directory . 'history' ?>" class="w-full cursor-pointer">Order History</a> <!-- Link to Pulsar By You -->
        <?php if ($_SESSION["user_role"] != "user"): ?>
            <a href="<?= $root_directory . 'admin/dashboard' ?>" class="w-full cursor-pointer">Dashboard</a> <!-- Link to Pulsar By You -->
        <?php endif; ?>
        <a href="#" class="w-full cursor-pointer">Disable Account</a> <!-- Link to Pulsar By You -->
        <a href="<?= $root_directory . 'logout' ?>" class="w-full cursor-pointer">Logout</a> <!-- Link to Pulsar By You -->
    <?php else: ?>
        <a href="<?= $root_directory . 'login' ?>" class="w-full cursor-pointer">Login</a> <!-- Link to Pulsar By You -->
    <?php endif; ?>
    
</div>