<div class="w-11/12 mx-auto sm:p-4 lg:p-8 bg-primary rounded-lg">

    <!-- Summary Cards Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <?php renderDataSummaryCard("Total Sales", $total_sales, true); ?>
        <?php renderDataSummaryCard("Total Orders", $total_orders); ?>
        <?php renderDataSummaryCard("Pending Orders", $pending_orders_count); ?>
        <?php renderDataSummaryCard("Total Products Sold", $total_products_sold); ?>
        <?php renderDataSummaryCard("Total Refunds", $total_refunds, true); ?>
        <?php renderDataSummaryCard("Total Active Users", $total_active_users); ?>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="p-6 bg-secondary rounded-lg shadow-md lg:row-span-2">
            <h2 class="text-light-dark text-xl uppercase tracking-tighter mb-4">Order Status</h2>
            <canvas id="order-status-chart" root-directory="<?= $root_directory; ?>"></canvas>
        </div>
        <div class="p-6 bg-secondary rounded-lg shadow-md lg:row-span-1">
            <h2 class="text-light-dark text-xl uppercase tracking-tighter mb-4">Revenue Trend</h2>
            <canvas id="revenue-trend-chart" root-directory="<?= $root_directory; ?>"></canvas>
        </div>
        <div class="p-6 bg-secondary rounded-lg shadow-md lg:row-span-1">
            <h2 class="text-light-dark text-xl uppercase tracking-tighter mb-4">Top Selling Products</h2>
            <canvas id="most-selling-products-chart" root-directory="<?= $root_directory; ?>"></canvas>
        </div>
    </div>

</div>
