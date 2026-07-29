**create_الموردين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);

    // Check for empty fields
    if (empty($name) || empty($phone) || empty($email) || empty($address)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO الموردين (name, phone, email, address) VALUES ('$name', '$phone', '$email', '$address')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_الموردين.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create موردين form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create New موردين</h2>
    <form id="create-moradiin-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-emerald-600 focus:ring-emerald-600" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-emerald-600 focus:ring-emerald-600" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-emerald-600 focus:ring-emerald-600" required>
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">Address:</label>
            <textarea id="address" name="address" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-emerald-600 focus:ring-emerald-600" required></textarea>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">Create</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500 mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#create-moradiin-form').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/الموردين.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_الموردين.php';
                    } else {
                        alert('Error creating موردين');
                    }
                }
            });
        });
    });
</script>


**Note:** This code assumes you have a `db.php` file that connects to your database and a `header.php` and `footer.php` file that includes the HTML header and footer respectively. You'll need to modify the code to fit your specific database schema and file structure. Additionally, this code uses the jQuery library for the AJAX request, so make sure to include it in your HTML file.