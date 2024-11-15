<div class="px-4 lg:px-10 ">
    <form action="<?= $root_directory . 'order' ?>" method="POST"
        class="py-12 pt-24 flex flex-col lg:flex-row lg:flex-row-reverse lg:h-screen">

        <div class="lg:w-1/3 bg-secondary p-4 border shadow flex flex-col gap-6 items-stretch">
            <div class="flex flex-col gap-4 overflow-y-scroll flex-1 p-4 hide-scrollbar">
                <?php
                $subtotal = 0; // Initialize subtotal
                foreach ($selected_variants as $item):
                    $itemTotal = $item["quantity"] * $item["unit_price"];
                    $subtotal += $itemTotal; // Calculate subtotal
                    ?>
                    <section class="flex justify-between items-start gap-1">
                        <!-- LEFT SIDE of ITEM LIST -->
                        <section class="flex justify-start items-center gap-4">
                            <section class="relative border bg-secondary shadow">
                                <img class="w-16 h-16" src="<?= $root_directory . 'assets/products/' . $item['img']; ?>"
                                    alt="item image">
                                <span
                                    class="absolute -top-1 -right-1 bg-light-dark text-primary px-2 rounded-full text-sm"><?= $item['quantity']; ?></span>
                            </section>

                            <section class="flex flex-col items-start justify-center text-dark grow">
                                <span
                                    class="text-xl text-wrap"><?= ucwords(htmlspecialchars($item["product_name"])); ?></span>
                                <span
                                    class="text-sm text-light-dark"><?= ucwords(htmlspecialchars($item["name"])); ?></span>
                            </section>
                        </section>
                        <!-- RIGHT SIDE OF ITEM LIST -->
                        <span class="">$<?= number_format($itemTotal, 2); ?></span>
                    </section>
                <?php endforeach; ?>
            </div>

            <div class="flex flex-col gap-4">
                <section class="w-full flex justify-between items-stretch gap-2">
                    <input type="text" name="discount_code" id="discount_input"
                        class="grow px-4 py-3 bg-transparent border-2 border-light-gray focus:outline-accent"
                        placeholder="Gift card or Coupon Code">
                    <input type="hidden" name="applied_discount_code" id="applied_discount_code">
                    <button path_to_validate="<?= $root_directory . 'api/discount' ?>" type='button'
                        onclick="toggleDiscount(this)"
                        class="w-fit px-6 self-stretch bg-accent text-secondary border rounded interactive shadow">
                        Apply
                    </button>
                </section>

                <section class="flex justify-between items-center text-dark">
                    <p>Subtotal • <?= count($selected_variants); ?> items</p>
                    <p>
                        <span>$</span><span id="sub-total"><?= number_format($subtotal, 2); ?></span>
                    </p>
                </section>

                <section class="flex justify-between items-center text-dark">
                    <p>Discount</p>
                    <p class="text-dark tracking-tighter" id="discount-message">
                        Not Applied
                    </p>
                </section>

                <section class="flex justify-between items-center text-dark">
                    <p class="">Shipping</p>
                    <span class="text-light-dark">
                        <span>$</span><span id="shipping-fee">0.00</span>
                    </span>
                </section>

                <section class="flex justify-between items-center text-dark text-xl lg:text-2xl tracking-tighter">
                    <p class="">Total</p>
                    <p>
                        <span class="text-sm text-light-dark mr-2">USD</span>
                        <span>$</span><span id="total"><?= number_format($subtotal, 2); ?></span>
                    </p>
                </section>
            </div>
        </div>

        <div class="md:grow md:overflow-y-scroll hide-scrollbar">
            <!-- Main Container for Form Sections -->
            <div class="w-full md:w-8/12 mx-auto space-y-4">

                <!-- Express Checkout Section -->
                <div class="w-full flex flex-col items-center gap-2">
                    <span class="text-light-dark text-lg tracking-tighter">Express Checkout</span>
                    <button class="bg-light-gray w-1/2 py-2 rounded border shadow">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/2560px-PayPal.svg.png"
                            alt="payment logo" class="mx-auto w-24 h-8">
                    </button>
                    <div class="w-full flex items-center justify-center">
                        <span class="flex-grow border-t border-light-gray"></span>
                        <span class="px-3 text-light-dark">OR</span>
                        <span class="flex-grow border-t border-light-gray"></span>
                    </div>
                </div>

                <!-- Contact Section -->
                <div>
                    <section class="flex justify-between items-center mb-3">
                        <span class="text-light-dark text-xl md:text-2xl tracking-tighter">Contact</span>
                    </section>

                    <input type="email" name="email" required disabled
                        class="border border-light-dark w-full px-4 py-2 rounded cursor-not-allowed focus:outline-accent"
                        placeholder="Email" value="<?= $_SESSION['user_email']; ?>">
                </div>

                <!-- Delivery Section -->
                <div>
                    <span class="text-light-dark text-xl md:text-2xl tracking-tighter mb-3">Delivery</span>
                    <div class="mt-4 space-y-4">
                        <!-- Country Selector -->
                        <select name="delivery[country]" onchange="updateShippingAndTotal(this)" required
                            class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent">
                            <option value="" disabled selected>Select a country</option>

                            <?php foreach ($countries as $name => $value): ?>
                                <option value="<?= $name ?>" shipping="<?= $value['shipping'] ?>">
                                    <?= $name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Name Inputs -->
                        <div class="flex gap-4">
                            <input type="text" name="delivery[first_name]" required
                                class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                                placeholder="First name">
                            <input type="text" name="delivery[last_name]" required
                                class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                                placeholder="Last name">
                        </div>

                        <!-- Company, Address, and Additional Address Information -->
                        <input type="text" name="delivery[company]"
                            class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                            placeholder="Company (optional)">
                        <input type="text" name="delivery[address]" required
                            class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                            placeholder="Address">
                        <input type="text" name="delivery[apartment]"
                            class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                            placeholder="Apartment, suite, etc. (optional)">

                        <!-- Postal Code and City Inputs -->
                        <div class="flex gap-4">
                            <input type="text" name="delivery[postal_code]" required
                                class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                                placeholder="Postal code">
                            <input type="text" name="delivery[city]" required
                                class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                                placeholder="City">
                        </div>

                        <!-- Phone Input -->
                        <input type="text" name="delivery[phone]" required
                            class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                            placeholder="Phone">

                        <!-- Save Information Checkbox -->
                        <div class="flex items-center gap-2 mt-2">
                            <input type="checkbox" name="delivery[save_address]" id="save-info" class="accent-accent">
                            <label for="save-info" class="text-light-dark">Save this information for next time</label>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div>
                    <p class="text-light-dark text-xl md:text-2xl tracking-tighter mb-3">Payment</p>
                    <p class="text-light-dark text-sm mb-4">All transactions are secure and encrypted.</p>

                    <div>
                        <!-- Paypal Option -->
                        <div class="payment-option border border-light-gray rounded-t cursor-pointer">
                            <section class="flex justify-between items-center px-4 py-2">
                                <span>
                                    <input type="radio" name="payment[method]" value="paypal"
                                        class="align-middle mr-1">Paypal
                                </span>
                                <img class="w-32 h-8"
                                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/2560px-PayPal.svg.png"
                                    alt="paypal logo">
                            </section>
                            <section
                                class="inner-section mt-2 bg-secondary flex flex-col justify-center items-center p-4"
                                style="display: none;">
                                <img src="<?= $root_directory . 'assets/design/card.gif' ?>" alt="card"
                                    class="w-64 h-48 grayscale">
                                <p class="w-9/12 text-center">After clicking "Pay with PayPal", you will be redirected
                                    to
                                    PayPal to complete your purchase securely.</p>
                            </section>
                        </div>

                        <!-- Paymentwall Option -->
                        <div class="payment-option border border-light-gray rounded-b cursor-pointer">
                            <section class="flex justify-between items-center px-4 py-2">
                                <span>
                                    <input type="radio" name="payment[method]" value="paymentwall"
                                        class="align-middle mr-1">All Payment Methods (by Paymentwall)
                                </span>
                                <img class="w-32 h-8"
                                    src="https://www.openbucks.com/fileadmin/user_upload/assets/partners/paymentwall-%403x.png"
                                    alt="Paymentwall logo">
                            </section>
                            <section
                                class="inner-section mt-2 bg-secondary flex flex-col justify-center items-center p-4"
                                style="display: none;">
                                <img src="<?= $root_directory . 'assets/design/card.gif' ?>" alt="card"
                                    class="w-64 h-48 grayscale">
                                <p class="w-9/12 text-center">After clicking “Pay now”, you will be redirected to
                                    Paymentwall to complete your purchase securely.</p>
                            </section>
                        </div>
                    </div>
                </div>

                <!-- Billing Address Section -->
                <div>
                    <p class="text-light-dark text-xl md:text-2xl tracking-tighter mb-4">Billing Address</p>

                    <!-- Same as Shipping Address Option -->
                    <div class="billing-option px-4 py-2 border border-light-gray rounded-t cursor-pointer">
                        <span>
                            <input type="radio" name="billing[same_as_shipping]" value="yes"
                                class="align-middle mr-1">Same
                            as shipping address
                        </span>
                    </div>

                    <!-- Different Billing Address Option -->
                    <div class="billing-option px-4 py-2 border border-light-gray rounded-b cursor-pointer">
                        <section>
                            <input type="radio" name="billing[same_as_shipping]" value="no"
                                class="align-middle mr-1">Use a
                            different billing address
                        </section>
                    </div>

                    <!-- Billing Form Section (Initially Hidden) -->
                    <div class="billing-form mt-4 space-y-4" style="display: none;">
                        <div class="flex gap-4">
                            <input type="text" name="billing[first_name]"
                                class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                                placeholder="First name">
                            <input type="text" name="billing[last_name]"
                                class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                                placeholder="Last name">
                        </div>
                        <input type="text" name="billing[company]"
                            class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                            placeholder="Company (optional)">
                        <input type="text" name="billing[address]"
                            class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                            placeholder="Address">
                        <input type="text" name="billing[apartment]"
                            class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                            placeholder="Apartment, suite, etc. (optional)">
                        <div class="flex gap-4">
                            <input type="text" name="billing[postal_code]"
                                class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                                placeholder="Postal code">
                            <input type="text" name="billing[city]"
                                class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                                placeholder="City">
                        </div>
                        <input type="text" name="billing[phone]"
                            class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                            placeholder="Phone">
                    </div>
                </div>

                <input type="text" name="user_id" class="hidden" value="<?= $_SESSION["user_id"]; ?>">
                <input type="email" name="user_email" class="hidden" value="<?= $_SESSION["user_email"]; ?>">

                <button type="submit" class="bg-accent interactive py-3 w-full text-primary rounded font-semibold">Pay
                    now</button>
            </div>
        </div>

    </form>


</div>