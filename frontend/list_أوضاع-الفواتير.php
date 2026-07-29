**list_أوضاع-الفواتير.php**

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
    <title>أوضاع الفواتير</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
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
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
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
        <h1>أوضاع الفواتير</h1>
        <nav>
            <a href="index.php" class="text-indigo-500 hover:text-indigo-700">الصفحة الرئيسية</a>
            <span class="text-gray-400">|</span>
            <span class="text-gray-400"><?= $_SESSION['username'] ?></span>
            <span class="text-gray-400">|</span>
            <a href="logout.php" class="text-red-500 hover:text-red-700">تسجيل الخروج</a>
        </nav>
    </div>
    <div class="container mx-auto p-4">
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_أوضاع-الفواتير.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>وصف</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/أوضاع-الفواتير.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        recordsContainer.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.اسم}</td>
                                <td>${record.وصف}</td>
                                <td>${record.حالة}</td>
                                <td>
                                    <a href="edit_أوضاع-الفواتير.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsContainer.appendChild(row);
                        });
                    });
            } else {
                loadRecords();
            }
        }

        function loadRecords() {
            fetch('../backend/أوضاع-الفواتير.php')
                .then(response => response.json())
                .then(data => {
                    recordsContainer.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.اسم}</td>
                            <td>${record.وصف}</td>
                            <td>${record.حالة}</td>
                            <td>
                                <a href="edit_أوضاع-الفواتير.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsContainer.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/أوضاع-الفواتير.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadRecords();
                    } else {
                        alert(data.message);
                    }
                });
            }
        }

        loadRecords();
    </script>
</body>
</html>

**backend/أوضاع-الفواتير.php**

<?php
// Assume this is your backend script to fetch or delete records
// Replace this with your actual logic
if (isset($_GET['search'])) {
    // Search records
    $searchQuery = $_GET['search'];
    $records = array_filter($GLOBALS['records'], function($record) use ($searchQuery) {
        return strpos($record['اسم'], $searchQuery) !== false || strpos($record['وصف'], $searchQuery) !== false;
    });
} else {
    // Fetch all records
    $records = $GLOBALS['records'];
}

if (isset($_GET['id']) && $_GET['id'] && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete record
    $id = $_GET['id'];
    $records = array_filter($records, function($record) use ($id) {
        return $record['id'] !== $id;
    });
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode($records);

Note that this is a basic implementation and you should adapt it to your specific use case. Also, make sure to replace the backend script with your actual logic.