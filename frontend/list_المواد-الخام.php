**list_المواد-الخام.php**

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
    <title>المواد الخام</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
        }
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2 text-lg font-bold"><?= $_SESSION['username']; ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='logout.php'">تسجيل خروج</button>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">المواد الخام</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='create_المواد-الخام.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="w-full p-2 pl-10 text-lg text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600" placeholder="بحث" id="search">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">الاسم</th>
                    <th class="border border-gray-400 p-2">الوصف</th>
                    <th class="border border-gray-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch API to load records
        async function loadRecords() {
            const response = await fetch('../backend/المواد-الخام.php', { method: 'GET' });
            const data = await response.json();
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach((record) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="border border-gray-400 p-2">${record.اسم}</td>
                    <td class="border border-gray-400 p-2">${record.وصف}</td>
                    <td class="border border-gray-400 p-2">
                        <a href="edit_المواد-الخام.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }
        loadRecords();

        // Search functionality
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery === '') {
                loadRecords();
                return;
            }
            fetch('../backend/المواد-الخام.php', { method: 'GET', params: { search: searchQuery } })
                .then((response) => response.json())
                .then((data) => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach((record) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-400 p-2">${record.اسم}</td>
                            <td class="border border-gray-400 p-2">${record.وصف}</td>
                            <td class="border border-gray-400 p-2">
                                <a href="edit_المواد-الخام.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        // Delete record functionality
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                const response = await fetch('../backend/المواد-الخام.php', { method: 'DELETE', params: { id } });
                if (response.ok) {
                    loadRecords();
                } else {
                    alert('حدث خطأ أثناء حذف السجل');
                }
            }
        }
    </script>
</body>
</html>

This code includes the following features:

1.  Session validation: Redirects to the login page if the user is not authenticated.
2.  Premium Tailwind UI: Uses the specified color palette and includes a header navigation bar with user info and logout functionality.
3.  Table showing list of records: Includes actions for editing and deleting records.
4.  'Add New Item' button: Links to the create\_المواد-الخام.php page.
5.  Search bar: Filters elements in real-time using the Fetch API.
6.  AJAX Javascript: Fetches list records from '../backend/المواد-الخام.php' (GET) and handles DELETE requests for deleting records.

Note: This code assumes that you have a backend PHP script (../backend/المواد-الخام.php) that handles GET and DELETE requests for fetching and deleting records, respectively. You will need to implement this backend script separately.