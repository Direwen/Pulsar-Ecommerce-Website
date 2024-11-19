<div
    id="overlay"
    class="w-screen h-screen bg-light-gray/95 fixed top-0 left-0 z-50 hidden"
    onclick="closeOverlay(event)">

    <section id="overlay_content_container" class="hide-scrollbar w-11/12 overflow-y-scroll sm:w-10/12 md:w-8/12 lg:w-1/2 bg-secondary p-5 hidden flex-col gap-4 rounded max-h-full overflow-y-scoll shadow-xl">
    </section>

    <section id="shopping_cart" class="hide-scrollbar relative overflow-y-scoll w-10/12 bg-primary h-screen hidden sm:w-8/12 md:w-1/2 lg:w-1/3"></section>

    <?php include("./views/components/navbar.php"); ?>
</div>