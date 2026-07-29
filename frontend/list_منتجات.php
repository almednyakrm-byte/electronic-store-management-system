**list_منتجات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4">
        <header class="bg-indigo-500 p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-white hover:text-indigo-400">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-white mr-2">مرحباً, <?= $_SESSION['username'] ?></span>
                    <a href="logout.php" class="text-white hover:text-indigo-400">تسجيل الخروج</a>
                </div>
            </nav>
        </header>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-2xl text-indigo-500 mb-4">قائمة المنتجات</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_منتجات.php'">إضافة منتج جديد</button>
            <div class="flex justify-between mb-4">
                <input type="search" id="search" class="bg-gray-100 rounded-lg py-2 px-4 w-full" placeholder="بحث...">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchProducts()">بحث</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">اسم المنتج</th>
                        <th class="px-4 py-2">وصف المنتج</th>
                        <th class="px-4 py-2">سعر المنتج</th>
                        <th class="px-4 py-2">إجراءات</th>
                    </tr>
                </thead>
                <tbody id="products-list">
                    <!-- Products will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Fetch products list
        fetch('../backend/منتجات.php')
            .then(response => response.json())
            .then(data => {
                const productsList = document.getElementById('products-list');
                data.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${product.name}</td>
                        <td class="px-4 py-2">${product.description}</td>
                        <td class="px-4 py-2">${product.price}</td>
                        <td class="px-4 py-2">
                            <a href="edit_منتجات.php?id=${product.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteProduct(${product.id})">حذف</button>
                        </td>
                    `;
                    productsList.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Search products
        function searchProducts() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/منتجات.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const productsList = document.getElementById('products-list');
                        productsList.innerHTML = '';
                        data.forEach(product => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${product.name}</td>
                                <td class="px-4 py-2">${product.description}</td>
                                <td class="px-4 py-2">${product.price}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_منتجات.php?id=${product.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteProduct(${product.id})">حذف</button>
                                </td>
                            `;
                            productsList.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                fetch('../backend/منتجات.php')
                    .then(response => response.json())
                    .then(data => {
                        const productsList = document.getElementById('products-list');
                        productsList.innerHTML = '';
                        data.forEach(product => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="px-4 py-2">${product.name}</td>
                                <td class="px-4 py-2">${product.description}</td>
                                <td class="px-4 py-2">${product.price}</td>
                                <td class="px-4 py-2">
                                    <a href="edit_منتجات.php?id=${product.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteProduct(${product.id})">حذف</button>
                                </td>
                            `;
                            productsList.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        }

        // Delete product
        function deleteProduct(id) {
            if (confirm('هل تريد حذف المنتج؟')) {
                fetch('../backend/منتجات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(' المنتج حذف بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code uses the Fetch API to load the products list from the backend and display it in a table. It also includes a search bar that filters the products list in real-time. The delete button uses an AJAX request to delete the product from the backend.