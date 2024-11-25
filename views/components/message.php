<div
    onclick="hideMessageNoti()"
    path-for-api="<?= $root_directory . 'controllers/unset_session.php'; ?>"
    id="message-box"
    class="text-xs md:text-sm interactive tracking-tighter cursor-pointer fixed w-fit max-w-11/12 sm:max-w-1/2 border <?= $messageClass ?> bottom-4 right-4 z-50 py-1 px-4 sm:py-2 sm:px-6 rounded-lg shadow transition-transform transform duration-300 ease-in-out translate-y-0"
>
    <span class="text-xs sm:text-sm md:text-base"><?= $message ?></span>
    <div id="progress-bar" class="h-1 bg-white mt-2 rounded-lg"></div>
</div>
