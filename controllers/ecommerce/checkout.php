<?php

// Decode the JSON string from the CART cookie to get cart items (variant_id => quantity)
$cart = json_decode($_COOKIE["CART"]);

// Initialize an empty array to store selected variant details
$selected_variants = [];
$subtotal = 0;

foreach ($cart as $variant_id => $quantity) {
    // Retrieve the variant data along with the associated product name
    $variant = ErrorHandler::handle(fn() => $variant_model->get(
        conditions: [
            $variant_model->getTableName() . '.' . $variant_model->getColumnId() => $variant_id
        ],
        select: [
            ["column" => $variant_model->getColumnId()],
            ["column" => $variant_model->getColumnType()],
            ["column" => $variant_model->getColumnName()],
            ["column" => $variant_model->getColumnUnitPrice()],
            ["column" => $variant_model->getColumnImg()],
            ["column" => $product_model->getColumnName(), "alias" => "product_name", "table" => $product_model->getTableName()]
        ],
        join: [
            ["type" => "INNER JOIN", "table" => $product_model->getTableName(), "on" => "variants.product_id = products.id"]
        ]
    ));

    // If variant data is found, add it to the selected variants array with quantity
    if (!empty($variant)) {
        $selected_variants[] = ["quantity" => $quantity, ...$variant];
    }
}

?>

<div class="py-12 pt-24 flex flex-col md:flex-row md:flex-row-reverse">
    <div class="md:w-1/3 bg-secondary p-4">
        <div class="flex flex-col gap-4">
            <?php foreach ($selected_variants as $item): ?>
                <section class="flex justify-between items-start">
                    <!-- LEFT SIDE of ITEM LIST -->
                    <section class="flex justify-start items-center gap-4">
                        <section class="relative border bg-secondary shadow">
                            <img class="w-16 h-16" src="<?= $root_directory . 'assets/products/' . $item['img']; ?>"
                                alt="item image">
                            <span
                                class="absolute -top-1 -right-1 bg-light-dark text-primary px-2 rounded-full text-sm"><?= $item['quantity']; ?></span>
                        </section>

                        <section class="flex flex-col items-start justify-center text-dark">
                            <span class="text-xl"><?= ucwords(htmlspecialchars($item["product_name"])); ?></span>
                            <span class="text-sm text-light-dark"><?= ucwords(htmlspecialchars($item["name"])); ?></span>
                        </section>
                    </section>
                    <!-- RIGHT SIDE OF ITEM LIST -->
                    <?php $subtotal += $item["quantity"] * $item["unit_price"] ?>
                    <span class="">$<?= $item["quantity"] * $item["unit_price"] ?></span>
                </section>
            <?php endforeach; ?>

            <section class="w-full flex justify-between items-stretch gap-2">
                <input type="text" name="discount"
                    class="grow px-4 py-3 bg-transparent border-2 border-light-gray focus:outline-accent"
                    placeholder="Gift card or Coupon Code">
                <button
                    class="w-fit px-6 self-stretch bg-light-gray text-light-dark border interactive shadow">Apply</button>
            </section>

            <section class="flex justify-between items-center text-dark">
                <p>Subtotal • <?= count($selected_variants); ?> items</p>
                <span>$<?= $subtotal; ?></span>
            </section>

            <section class="flex justify-between items-center text-dark">
                <p class="">Shipping</p>
                <span class="text-light-dark">Enter Shipping Address</span>
            </section>

            <section class="flex justify-between items-center text-dark text-xl lg:text-2xl tracking-tighter">
                <p class="">Total</p>
                <p>
                    <span class="text-sm text-light-dark mr-2">USD</span>$<?= $subtotal; ?>
                </p>
            </section>
        </div>
    </div>

    <div class="md:grow">
        <!-- Main Container for Form Sections -->
        <div class="w-8/12 mx-auto space-y-4">

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
                    <a href="" class="text-accent underline">Login</a>
                </section>
                <input type="text" class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                    placeholder="Email">
            </div>

            <!-- Delivery Section -->
            <div>
                <span class="text-light-dark text-xl md:text-2xl tracking-tighter mb-3">Delivery</span>
                <div class="mt-4 space-y-4">
                    <!-- Country Selector -->
                    <select class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent">
                        <option>Country/Region</option>
                        <option>Germany</option>
                    </select>

                    <!-- Name Inputs -->
                    <div class="flex gap-4">
                        <input type="text" class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                            placeholder="First name">
                        <input type="text" class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                            placeholder="Last name">
                    </div>

                    <!-- Company, Address, and Additional Address Information -->
                    <input type="text" class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                        placeholder="Company (optional)">
                    <input type="text" class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                        placeholder="Address">
                    <input type="text" class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                        placeholder="Apartment, suite, etc. (optional)">

                    <!-- Postal Code and City Inputs -->
                    <div class="flex gap-4">
                        <input type="text" class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                            placeholder="Postal code">
                        <input type="text" class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                            placeholder="City">
                    </div>

                    <!-- Phone Input -->
                    <input type="text" class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                        placeholder="Phone">

                    <!-- Save Information Checkbox -->
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" id="save-info" class="accent-accent">
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
                            <span>Paypal</span>
                            <img class="w-32 h-8"
                                src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/2560px-PayPal.svg.png"
                                alt="paypal logo">
                        </section>
                        <section class="inner-section mt-2 bg-secondary flex flex-col justify-center items-center p-4"
                            style="display: none;">
                            <img src="<?= $root_directory . 'assets/design/card.gif' ?>" alt="card" class="w-64 h-48">
                            <p class="w-9/12 text-center">After clicking "Pay with PayPal", you will be redirected to
                                PayPal to complete your purchase securely.</p>
                        </section>
                    </div>

                    <!-- Paymentwall Option -->
                    <div class="payment-option border border-light-gray rounded-b cursor-pointer">
                        <section class="flex justify-between items-center px-4 py-2">
                            <span>All Payment Methods (by Paymentwall)</span>
                            <img class="w-32 h-8"
                                src="https://www.openbucks.com/fileadmin/user_upload/assets/partners/paymentwall-%403x.png"
                                alt="Paymentwall logo">
                        </section>
                        <section class="inner-section mt-2 bg-secondary flex flex-col justify-center items-center p-4"
                            style="display: none;">
                            <img src="<?= $root_directory . 'assets/design/card.gif' ?>" alt="card" class="w-64 h-48">
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
                    <span>Same as shipping address</span>
                </div>

                <!-- Different Billing Address Option -->
                <div class="billing-option px-4 py-2 border border-light-gray rounded-b cursor-pointer">
                    <section>Use a different billing address</section>
                </div>

                <!-- Billing Form Section (Initially Hidden) -->
                <div class="billing-form mt-4 space-y-4" style="display: none;">
                    <div class="flex gap-4">
                        <input type="text" class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                            placeholder="First name">
                        <input type="text" class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                            placeholder="Last name">
                    </div>
                    <input type="text" class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                        placeholder="Company (optional)">
                    <input type="text" class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                        placeholder="Address">
                    <input type="text" class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                        placeholder="Apartment, suite, etc. (optional)">
                    <div class="flex gap-4">
                        <input type="text" class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                            placeholder="Postal code">
                        <input type="text" class="border border-light-dark w-1/2 px-4 py-2 rounded focus:outline-accent"
                            placeholder="City">
                    </div>
                    <input type="text" class="border border-light-dark w-full px-4 py-2 rounded focus:outline-accent"
                        placeholder="Phone">
                </div>
            </div>

            <button class="bg-accent interactive py-3 w-full text-primary rounded font-semibold">Pay now</button>
        </div>
    </div>

</div>