<div class="min-h-screen">
    <div class="relative h-[25rem] lg:h-[30rem] bg-[url('<?= $root_directory ?>assets/pulsar_wallpaper2.webp')] bg-cover bg-center">
        <!-- Overlay -->
        <section class="absolute top-0 left-0 w-full h-full bg-dark/70 text-secondary gap-10 flex flex-col justify-center items-center px-2 py-14">
            <!-- Product Link -->
            <section class="uppercase font-medium">
                <h1 class="text-4xl md:text-5xl lg:text-7xl text-center tracking-tighter">Pulsar Support<span class="material-symbols-outlined animate-bounce">contact_support</span></h1>
            </section>
            <section class="w-full flex flex-col justify-center items-center gap-2">
                <p class="text-xl lg:text-2xl font-thin tracking-tighter">How can we help you today?</p>
                <section class="w-full md:w-1/2 lg:w-2/5 flex flex-col md:flex-row justify-between items-stretch gap-2">
                    <input type="text"
                        id="faq-search"
                        class="flex-grow px-4 py-3 bg-transparent border-2 border-light-gray outline-none rounded"
                        placeholder="Enter your issue term here">
                    <button 
                        type='button'
                        onclick="handleFAQSearch()"
                        class="w-full py-2 md:py-0 md:w-1/5 px-6 self-stretch bg-accent text-secondary rounded interactive"
                    >
                        Search
                    </button>
                </section>
            </section>
        </section>
    </div>

    <div class="p-4 flex flex-col items-center justify-center bg-secondary border shadow p-10 w-11/12 md:w-8/12 lg:w-1/2 mx-auto my-10 gap-4">
        <span class="text-2xl md:text-4xl lg:text-5xl tracking-tighter font-semibold">FAQs</span>
        <div class="w-full" id="faq-container"></div>
    </div>
</div>
