
<div class="w-full h-screen bg-secondary flex justify-center items-center">
    <div 
        class="w-full h-full sm:w-9/12 sm:h-fit md:w-4/12 bg-primary py-12 px-6 rounded shadow flex flex-col justify-center items-start gap-4  tracking-tighter"
    >
        <!-- logo pic -->
        <img src="<?= $root_directory ?>assets/pulsar_logo.jpg" alt="logo image" class="w-26 h-10 mx-auto">
         <!-- title -->
        <section class="flex flex-col justify-center items-start">
            <span class="font-bold text-lg sm:text-2xl">Log in</span>
            <span class="font-light text-light-dark text-xs sm:text-sm lg:text-base">Enter your email and we'll send you a login code</span>
        </section>
          <!-- description -->
        <form action="" method="POST" class="w-full flex flex-col justify-center items-start gap-4">
            <section class="w-full">
                <label for="" class="inline-block mb-1">Email</label>
                <input type="email" name="email" class="border focus:outline-accent py-1 px-2 w-full rounded">
            </section>
            <button type="submit" class="inline-block w-full bg-accent interactive text-primary p-2 rounded">Continue</button>
        </form>

        <span onclick="openTermsForAuthentication(<?= $root_directory ?>)" class="text-accent cursor-pointer">Privacy</>
    </div>
</div>