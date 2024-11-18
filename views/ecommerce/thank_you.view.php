<div class="py-24 h-screen flex flex-col justify-center items-center gap-2">
    <img src="<?= $root_directory . 'assets/illustrations/thank_you_whole.svg' ?>" alt="404"
        class="w-1/2 md:w-1/4 lg:w-1/6 animate-pulse">
    <span class="text-2xl md:text-3xl lg:text-6xl text-accent font-semibold tracking-tighter">Thank you.</span>
    <p class="w-11/12 md:w-8/12 lg:w-1/2 text-center text-sm lg:text-base tracking-tighter">Your order was completed
        successfully.</p>
    <div
        class="w-11/12 md:w-1/2 lg:w-1/3 flex items-center justify-between gap-2 bg-secondary border shadow px-4 py-2 rounded">
        <img src="<?= $root_directory . 'assets/illustrations/mail_box.svg' ?>" alt="404" class="w-1/4 animate-pulse">

        <p class="text-xs lg:text-sm tracking-tighter">
            An email receipt including the details about your order has been sent to this account email address. Please
            keep it for you records.
        </p>
    </div>

    <a href="<?= $root_directory; ?>"
        class="w-11/12 md:w-1/2 lg:w-1/3 inline-block text-center px-2 py-2 bg-secondary uppercase border shadow rounded text-accent font-semibold cursor-pointer interactive text-xs md:text-sm">
        Explore for more
    </a>
</div>