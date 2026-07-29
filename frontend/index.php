<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة متجر إلكتروني</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-between">
            <h1 class="text-3xl font-bold">مرحباً <?php echo $_SESSION['username']; ?></h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="logout()">تسجيل الخروج</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
            <div class="glass p-4 rounded">
                <h2 class="text-2xl font-bold">إجمالي المنتجات</h2>
                <p id="total-products" class="text-4xl font-bold"></p>
            </div>
            <div class="glass p-4 rounded">
                <h2 class="text-2xl font-bold">إجمالي الفواتير</h2>
                <p id="total-invoices" class="text-4xl font-bold"></p>
            </div>
            <div class="glass p-4 rounded">
                <h2 class="text-2xl font-bold">إجمالي الدفعات</h2>
                <p id="total-payments" class="text-4xl font-bold"></p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
            <div class="glass p-4 rounded">
                <h2 class="text-2xl font-bold">إدارة المنتجات</h2>
                <a href="products.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إدارة</a>
            </div>
            <div class="glass p-4 rounded">
                <h2 class="text-2xl font-bold">إدارة الفواتير</h2>
                <a href="invoices.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إدارة</a>
            </div>
            <div class="glass p-4 rounded">
                <h2 class="text-2xl font-bold">إدارة الدفعات</h2>
                <a href="payments.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إدارة</a>
            </div>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = 'logout.php';
        }

        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-products').innerText = data.totalProducts;
                document.getElementById('total-invoices').innerText = data.totalInvoices;
                document.getElementById('total-payments').innerText = data.totalPayments;
            });
    </script>
</body>
</html>