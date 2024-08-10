<div>
    <!-- Total Statistics -->
    <div>
        <h3>Total Statistics</h3>
        <p>Total Customers: {{ $customerCount }}</p>
        <p>Total Orders: {{ $orderCount }}</p>
        <p>Total Purchases: {{ $totalPurchases }}</p>
    </div>

    <!-- Order Chart -->
    <div>
        <h4>Monthly Orders</h4>
        <canvas id="orderChart"></canvas>
        <script>
            const ctxOrder = document.getElementById('orderChart').getContext('2d');
            new Chart(ctxOrder, {
                type: 'line',
                data: {
                    labels: JSON.parse('{!! json_encode(range(1, 12)) !!}'), // Bulan Januari sampai Desember
                    datasets: [{
                        label: 'Orders',
                        data: JSON.parse('{!! json_encode($monthlyOrders) !!}'),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>

    <!-- Purchase Chart -->
    <div>
        <h4>Monthly Purchases</h4>
        <canvas id="purchaseChart"></canvas>
        <script>
            const ctxPurchase = document.getElementById('purchaseChart').getContext('2d');
            new Chart(ctxPurchase, {
                type: 'line',
                data: {
                    labels: JSON.parse('{!! json_encode(range(1, 12)) !!}'), // Bulan Januari sampai Desember
                    datasets: [{
                        label: 'Purchases',
                        data: JSON.parse('{!! json_encode($monthlyPurchases) !!}'),
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>
</div>
