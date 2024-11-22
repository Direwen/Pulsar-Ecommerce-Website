function openTermsForAuthentication(root) {
    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Terms & Conditions</h2>
        </section>
        
        <section class="text-dark tracking-tighter flex flex-col gap-4 text-xs md:text-sm lg:text-base">
            <p>By logging in, you agree to the following:</p>
            <ul class="list-disc pl-6">
                <li>You are responsible for keeping your account credentials secure.</li>
                <li>Any misuse of the account is your responsibility.</li>
                <li>Personal data is handled per our <a href="#" class="text-primary underline">Privacy Policy</a>.</li>
                <li>Unauthorized activities, such as fraudulent access, will result in account suspension.</li>
            </ul>
            <a href="${root}auth/terms-conditions" class="text-accent underline underline-offset-4 interactive">Read the full Terms and Conditions</a>
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Close</button>
            </section>
        </section>
    `;

    openOverlayModal(content);
}

function openTermsForPurchase() {
    const content = `
        <section class="border-b border-light-dark pb-4">
            <h2 class="text-xl font-semibold text-dark">Purchase Terms & Conditions</h2>
        </section>
        
        <section class="text-dark tracking-tighter flex flex-col gap-4 text-xs md:text-sm lg:text-base">
            <p>By completing your purchase, you agree to the following:</p>
            <ul class="list-disc pl-6">
                <li>Prices are final and include all applicable taxes.</li>
                <li>Payments are securely processed through trusted gateways.</li>
                <li>Orders are shipped within 1-3 business days.</li>
                <li>Returns are accepted within 30 days of delivery; the buyer is responsible for return shipping.</li>
                <li>Product availability may change, and orders may be canceled if an item is out of stock.</li>
            </ul>
            <section class="flex justify-end items-center gap-2">
                <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Close</button>
            </section>
        </section>
    `;
    
    openOverlayModal(content);
}



