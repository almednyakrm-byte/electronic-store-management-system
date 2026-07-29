**edit_دفع.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/دفع.php?id=' . $id), true);

// Check if record exists
if (!$record) {
    echo 'Record not found.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit دفع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">Edit دفع</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 text-sm font-bold">Name:</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $record['name'] ?>">
            </div>
            <div>
                <label for="amount" class="text-slate-900 text-sm font-bold">Amount:</label>
                <input type="number" id="amount" name="amount" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $record['amount'] ?>">
            </div>
            <div>
                <label for="date" class="text-slate-900 text-sm font-bold">Date:</label>
                <input type="date" id="date" name="date" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $record['date'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/دفع.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_دفع.php';
                        } else {
                            alert('Error updating record.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**Note:** Make sure to replace `list_دفع.php` with the actual URL of the list page. Also, ensure that the `دفع.php` file in the `backend` directory is configured to handle PUT requests and update the record accordingly.