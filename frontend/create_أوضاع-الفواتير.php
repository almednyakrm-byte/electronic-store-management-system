**create_أوضاع-الفواتير.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Check if fields are not empty
    if (!empty($name) && !empty($description)) {
        // Insert data into database
        $sql = "INSERT INTO `أوضاع الفواتير` (`name`, `description`) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_أوضاع-الفواتير.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Create New أوضاع الفواتير</h1>
    <form action="" method="post" class="space-y-6">
        <div class="grid grid-cols-1 gap-6">
            <div class="col-span-6 sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full pl-10 pr-12 py-2 text-base border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea name="description" id="description" class="mt-1 block w-full pl-10 pr-12 py-2 text-base border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
            </div>
        </div>
        <button type="submit" name="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 text-sm mt-2"><?= $error ?></p>
        <?php endif; ?>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_أوضاع-الفواتير.js**
javascript
$(document).ready(function() {
    // Submit form via AJAX
    $('form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../backend/أوضاع-الفواتير.php',
            data: $(this).serialize(),
            success: function(data) {
                if (data === 'success') {
                    window.location.href = 'list_أوضاع-الفواتير.php';
                } else {
                    alert('Error creating أوضاع الفواتير');
                }
            }
        });
    });
});


**../backend/أوضاع-الفواتير.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data has been submitted
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Insert data into database
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $sql = "INSERT INTO `أوضاع الفواتير` (`name`, `description`) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();

    // Return success message
    echo 'success';
} else {
    // Return error message
    echo 'Error creating أوضاع الفواتير';
}
?>