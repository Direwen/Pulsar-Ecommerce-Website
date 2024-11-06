<div class="relative w-full min-h-[35rem] max-h-screen bg-[url('<?= $backgroundImage; ?>')] bg-cover bg-center group">

    <!-- Overlay -->
    <section class="medium-banner absolute top-0 left-0 w-full h-full transition-all duration-1000 ease-in-out bg-primary/50 sm:bg-transparent sm:hover:bg-primary/70 p-4 sm:px-20 sm:py-6 flex <?= $center ? 'justify-center items-center' : 'justify-start items-end'; ?>">

        <!-- Product Link -->
        <section class="uppercase sm:opacity-0 group-hover:opacity-100 group-hover:-translate-y-5 transition-all duration-1000 ease-in-out">
            <h1 class="text-4xl font-semibold tracking-tigher"><?= $mainTitle; ?></h1>

            <?php if ($subTitle): ?>
                <h2 class="text-2xl tracking-tighter"><?= $subTitle; ?></h2>
            <?php endif; ?>

            <?php if ($buttonText): ?>
                <?php renderLinkButton($buttonText, $buttonUrl); ?>
            <?php endif; ?>
        </section>

    </section>

</div>