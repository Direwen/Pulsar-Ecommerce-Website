document.addEventListener("DOMContentLoaded", function () {

    (async function () {
        var trendContainer = document.getElementById('revenue-trend-chart');
        if (!trendContainer) return;
        var root = trendContainer.getAttribute('root-directory');

        var data = [];
        axios.get(root + "api/revenue-trend")
            .then(response => {
                data = response.data.records;
                new Chart(
                    trendContainer,
                    {
                        type: 'line',
                        data: {
                            labels: data.map(row => new Date(row.created_at).toLocaleString()),
                            datasets: [
                                {
                                    label: 'Revenue Trend of Last 30 Days',
                                    data: data.map(row => row.total_price),
                                    borderColor: '#898888',
                                    backgroundColor: '#898888',
                                }
                            ]
                        },
                        options: {
                            scales: {
                                y: {
                                    ticks: {
                                        callback: function (value) {
                                            return '$' + value.toLocaleString(); // Format as currency
                                        }
                                    }
                                },
                                x: {
                                    ticks: {
                                        autoSkip: true,
                                        maxTicksLimit: 10 // Limit the number of ticks
                                    }
                                }
                            }
                        }
                    }
                );

            })
            .catch(err => console.log(err));

    })();

    (async function () {
        var chartContainer = document.getElementById('order-status-chart');
        if (!chartContainer) return;
        var root = chartContainer.getAttribute('root-directory');

        var data = [];
        axios.get(root + "api/order-status")
            .then(response => {
                data = response.data.records;
                new Chart(
                    chartContainer,
                    {
                        type: 'pie',
                        data: {
                            labels: data.map(row => row.status),
                            datasets: [{
                                label: 'My First Dataset',
                                data: data.map(row => row.status_count),
                                backgroundColor: getRandomColors(data.length),
                                hoverOffset: 4
                            }]
                        }
                    }
                );

            })
            .catch(err => console.log(err));

    })();

    (async function () {
        var chartContainer = document.getElementById('most-selling-products-chart');
        if (!chartContainer) return;
        var root = chartContainer.getAttribute('root-directory');

        var data = [];
        axios.get(root + "api/top-selling-products")
            .then(response => {
                data = response.data.records;

                const backgroundColors = getRandomColors(data.length);
                const borderColors = getRandomColors(data.length);

                new Chart(
                    chartContainer,
                    {
                        type: 'bar',
                        data: {
                            labels: data.map(row => row.product_name), // Use product names as labels
                            datasets: [{
                                label: 'Top Selling Products',
                                data: data.map(row => row.total_sold),
                                backgroundColor: backgroundColors,
                                borderColor: borderColors,
                                borderWidth: 1
                            }]
                        }
                    }
                );

            })
            .catch(err => console.log(err));

    })();


});