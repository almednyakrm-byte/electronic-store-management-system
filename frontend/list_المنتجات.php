**list_المنتجات.php**

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
    <title>المنتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-teal-500 font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-emerald-600 font-bold mb-4">المنتجات</h1>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_المنتجات.php'">إضافة منتج جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchProducts()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المنتج</th>
                    <th>وصف المنتج</th>
                    <th>سعر المنتج</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="products-list">
                <!-- Products will be listed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch products list from backend
        async function fetchProducts() {
            try {
                const response = await fetch('../backend/المنتجات.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                const productsList = document.getElementById('products-list');
                productsList.innerHTML = '';
                data.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${product.name}</td>
                        <td>${product.description}</td>
                        <td>${product.price}</td>
                        <td>
                            <a href="edit_المنتجات.php?id=${product.id}" class="text-teal-500 hover:text-teal-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteProduct(${product.id})">حذف</button>
                        </td>
                    `;
                    productsList.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search products
        function searchProducts() {
            const searchInput = document.getElementById('search-input').value;
            fetchProducts(searchInput);
        }

        // Delete product
        async function deleteProduct(id) {
            try {
                const response = await fetch('../backend/المنتجات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    fetchProducts();
                } else {
                    console.error('Error deleting product');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Initialize products list
        fetchProducts();
    </script>
</body>
</html>

This code uses PHP to validate the user session and redirect to the login page if not authenticated. It also uses Tailwind CSS to style the UI. The JavaScript code uses the Fetch API to fetch the products list from the backend and delete products. The search functionality is also implemented using JavaScript.