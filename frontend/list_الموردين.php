**list_الموردين.php**

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
    <title>الموردين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a202c;
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
            text-align: center;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
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
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-teal-500 font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-emerald-600 mb-4">الموردين</h1>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_الموردين.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المورد</th>
                    <th>عنوان المورد</th>
                    <th>تليفون المورد</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-list">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const recordsList = document.getElementById('records-list');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/الموردين.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    recordsList.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم_المورد}</td>
                            <td>${record.عنوان_المورد}</td>
                            <td>${record.تليفون_المورد}</td>
                            <td>
                                <a href="edit_الموردين.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                                <button class="text-teal-500 hover:text-teal-900" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsList.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
            } else {
                loadRecords();
            }
        }

        function loadRecords() {
            fetch('../backend/الموردين.php', {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                recordsList.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم_المورد}</td>
                        <td>${record.عنوان_المورد}</td>
                        <td>${record.تليفون_المورد}</td>
                        <td>
                            <a href="edit_الموردين.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                            <button class="text-teal-500 hover:text-teal-900" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsList.appendChild(row);
                });
            })
            .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المورد؟')) {
                fetch('../backend/الموردين.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        loadRecords();
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP script (`../backend/الموردين.php`) that handles GET and DELETE requests for retrieving and deleting records. You will need to implement this script separately.