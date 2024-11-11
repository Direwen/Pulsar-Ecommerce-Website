<div id="navbar" class="w-8/12 h-screen bg-white px-2 py-5 flex flex-col gap-2 justify-start items-start shadow-inner hidden z-50">

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
    
    <a href="#" class="w-full cursor-pointer">Login</a> <!-- Link to Pulsar By You -->
    <a href="#" class="w-full cursor-pointer">Create an Account</a> <!-- Link to Pulsar By You -->
</div>