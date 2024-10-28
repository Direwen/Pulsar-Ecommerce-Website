<a
    href="<?= $redirectUrl?>"
    class="relative block w-full h-96 bg-[url('<?= $imageUrl; ?>')] bg-cover bg-center sm:bg-left rounded flex items-end group">

    <section class="absolute top-0 left-0 w-full h-full px-4 py-2 bg-dark/90 font-extrabold break-words flex flex-col justify-center items-start opacity-0 group-hover:opacity-100 transition-all ease-in-out duration-700">
        <h1 class="text-5xl md:text-7xl tracking-tighter text-primary"><?= $mainTitle; ?></h1>

        <?php if ($subTitle): ?>
            <p class="text-3xl md:text-5xl tracking-tigher text-accent"><?= $subTitle; ?></p>
        <?php endif; ?>
    </section>

</a>