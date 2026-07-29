**list_دفع.php**

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
    <title>دفع</title>
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
            background-color: #1a1d23;
            color: #fff;
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
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">قائمة دفع</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_دفع.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم السجل</th>
                    <th>اسم المالك</th>
                    <th>تاريخ الدفع</th>
                    <th>حالة الدفع</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['id']; ?></td>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td>
                            <a href="edit_دفع.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            const response = await fetch('../backend/دفع.php', { method: 'GET' });
            const data = await response.json();
            return data.records;
        }

        // Search records
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            fetchRecords().then(records => {
                const tbody = document.getElementById('records');
                tbody.innerHTML = '';
                records.forEach(record => {
                    if (record.name.includes(searchValue) || record.date.includes(searchValue)) {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>${record.date}</td>
                            <td>${record.status}</td>
                            <td>
                                <a href="edit_دفع.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    }
                });
            });
        }

        // Delete record
        async function deleteRecord(id) {
            const response = await fetch('../backend/دفع.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
            const data = await response.json();
            if (data.success) {
                alert('تم حذف السجل بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف السجل');
            }
        }
    </script>
</body>
</html>


**backend/دفع.php**

<?php
// Database connection
$dsn = 'mysql:host=localhost;dbname=database';
$username = 'username';
$password = 'password';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}

// Fetch records
$records = array();
$stmt = $pdo->prepare('SELECT * FROM دفع');
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $records[] = $row;
}

// Search records
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $stmt = $pdo->prepare('SELECT * FROM دفع WHERE name LIKE :search OR date LIKE :search');
    $stmt->bindParam(':search', '%' . $searchValue . '%');
    $stmt->execute();
    $records = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $records[] = $row;
    }
}

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('DELETE FROM دفع WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(array('success' => true));
    exit;
}

// Output records
echo json_encode(array('records' => $records));
exit;
?>

Note: This code assumes you have a MySQL database with a table named `دفع` containing the columns `id`, `name`, `date`, and `status`. You should replace the database connection settings and table name with your actual settings.