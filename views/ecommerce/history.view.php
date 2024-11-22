<div class="py-24 text-dark px-2 md:px-10">
    <div class="flex flex-col gap-4">
        <span class="text-2xl tracking-tighter font-semibold">
            Your Orders
            <span
                class="bg-accent text-primary px-3 py-1 rounded-full text-base border shadow ml-1"><?= count($orders); ?></span>
        </span>

        <div class="text-dark md:border-b md:border-light-dark md:py-4 tracking-tigher flex flex-col items-start md:flex-row md:justify-between md:items-center">
            <section class="hidden md:block">
                <span class="material-symbols-outlined text-2xl">tune</span>
                <span class="text-sm">Show Filters</span>
            </section>
            <section class="text-sm border rounded shadow p-1 text-dark w-fit flex justify-center items-center gap-2 flex-wrap">
                <a href="?status=all"
                    class="px-3 py-1 rounded interactive <?= $status_search_for == 'all' ? 'bg-accent text-secondary' : 'bg-primary ' ?>">All
                    Orders</a>
                <a href="?status=pending"
                    class="px-3 py-1 rounded interactive <?= $status_search_for == 'pending' ? 'bg-accent text-secondary' : 'bg-primary ' ?>">Pending</a>
                <a href="?status=delivered"
                    class="px-3 py-1 rounded interactive <?= $status_search_for == 'delivered' ? 'bg-accent text-secondary' : 'bg-primary ' ?>">Delivered</a>
                <a href="?status=not-yet-shipped"
                    class="px-3 py-1 rounded interactive <?= $status_search_for == 'not-yet-shipped' ? 'bg-accent text-secondary' : 'bg-primary ' ?>">Shipping</a>
                <a href="?status=cancelled"
                    class="px-3 py-1 rounded interactive <?= $status_search_for == 'cancelled' ? 'bg-accent text-secondary' : 'bg-primary ' ?>">Cancelled</a>
            </section>
        </div>

    </div>

    <?php if (count($orders) <= 0): ?>
        <div class="flex flex-col justify-center items-center px-3 py-6 gap-4">
            <p class="text-2xl md:text-4xl font-thin tracking-tighter uppercase">No Orders Found</p>
            <img src="<?= $root_directory . 'assets/illustrations/not_found.svg' ?>" alt="svg"
                class="w-1/2 md:w-1/4 lg:w-1/6 animate-pulse">
        </div>
    <?php endif; ?>

    <div class="order-cards grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
        <?php
        // Define status colors
        $statusColors = [
            'pending' => 'bg-yellow-500/60', // Yellow represents "awaiting action."
            'confirmed' => 'bg-green-500/60', // Green conveys positivity and approval.
            'processing' => 'bg-blue-500/60', // Blue suggests ongoing action or a process.
            'cancelled' => 'bg-red-500/60', // Red strongly indicates a stop or issue.
            'shipping' => 'bg-indigo-500/60', // Indigo/blue implies movement or transition.
            'delivered' => 'bg-green-600/60', // Darker green represents completion.
        ];
        

        foreach ($orders as $order):
            $statusClass = $statusColors[$order['status']] ?? 'bg-secondary'; // Default if status is not listed
            $disableActions = !in_array($order['status'], ['pending', 'confirmed']); // Disable if not pending or confirmed
            ?>
            <div
                class="order-card bg-secondary border border-light-gray rounded-lg shadow p-4 flex flex-col justify-between transition-all ease-in-out duration-200 lg:hover:shadow-xl lg:hover:-translate-y-2">
                <!-- Order Details -->
                <div>
                    <h3 class="text-base font-bold text-dark mb-1">Order Code:
                        <?= htmlspecialchars($order['order_code']); ?>
                    </h3>
                    <p class="text-sm text-light-dark mb-1">
                        <span class="font-medium">Status:</span>
                        <span
                            class="<?= $statusClass ?> text-white font-semibold tracking-tighter px-3 py-[1px] inline-block rounded-full">
                            <?= ucwords(htmlspecialchars($order['status'])); ?>
                        </span>
                    </p>
                    <p class="text-sm text-light-dark">
                        <span class="font-medium">Order At:</span>
                        <?= date('F d, Y h:i A', strtotime($order['created_at'])); ?>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center mt-4 rounded-lg border shadow">
                    <button onclick="viewOrderDetails(this)" root-directory="<?= $root_directory ?>"
                        data-id="<?= $order['id']; ?>" type="button"
                        class="rounded-l-lg w-full bg-primary py-2 interactive hover:bg-accent hover:text-secondary text-light-dark tracking-tighter">
                        View
                    </button>
                    <button onclick="cancelOrder(this)" code="<?= $order['order_code']; ?>"
                        root-directory="<?= $root_directory ?>" data-id="<?= $order['id']; ?>" type="button"
                        class="rounded-r-lg w-full <?= $disableActions ? 'bg-light-gray text-dark opacity-50 cursor-not-allowed' : 'bg-primary text-light-dark interactive hover:bg-danger hover:text-secondary' ?> py-2 tracking-tighter"
                        <?= $disableActions ? 'disabled' : '' ?>>
                        Cancel
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


</div>