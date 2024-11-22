function confirmAccountDisable(root) {
    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Confirm Account Deactivation</h2>
        </section>
        
        <section class="text-dark tracking-tighter flex flex-col gap-4 text-xs md:text-sm lg:text-base">
            <p>By deactivating your account, you will no longer have access to your profile, purchase history, or any associated services. Please read the following:</p>
            <ul class="list-disc pl-6">
                <li>Account deactivation is permanent and will remove access to all your data and settings.</li>
                <li>If you wish to recover your account, you must submit a support ticket.</li>
                <li>Support ticket requests for reactivation are processed within 5-7 business days.</li>
                <li>Ensure that you have all necessary data backed up before proceeding with account deactivation.</li>
            </ul>
            <p class="text-danger font-semibold">Warning: Account deactivation is a serious action. Once completed, your account will be permanently disabled until reactivation is requested via a support ticket.</p>
            <form action="${root}disable" method="POST" class="flex flex-col gap-4">
                <section class="flex justify-end items-center gap-2">
                    <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Cancel</button>
                    <button type="submit" onclick="disableAccount()" class="w-fit bg-danger interactive text-primary font-semibold py-2 px-6 rounded shadow">Deactivate Account</button>
                </section>
            </form>

        </section>
    `;
    
    openOverlayModal(content);
}
