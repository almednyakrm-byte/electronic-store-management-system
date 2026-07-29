**create_منتجات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_منتجات_form.php';

// Include footer
include 'footer.php';
?>


**create_منتجات_form.php**

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Create New منتجات</h2>
            <form id="create-product-form" class="space-y-6" method="post" action="../backend/منتجات.php">
                <div class="grid grid-cols-1 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                        <input type="text" name="name" id="name" autocomplete="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 pl-9 text-base focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 pl-9 text-base focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="price" class="block text-sm font-medium text-slate-700">Price</label>
                        <input type="number" name="price" id="price" autocomplete="price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 pl-9 text-base focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <label for="category" class="block text-sm font-medium text-slate-700">Category</label>
                        <select name="category" id="category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 pl-9 text-base focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select a category</option>
                            <option value="Electronics">Electronics</option>
                            <option value="Fashion">Fashion</option>
                            <option value="Home Goods">Home Goods</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Product</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-product-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/منتجات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_منتجات.php';
                    } else {
                        alert('Error creating product');
                    }
                }
            });
        });
    });
</script>


**backend/منتجات.php**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['category'])) {
    // Prepare SQL query
    $sql = "INSERT INTO products (name, description, price, category) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['category']);
    // Execute query
    $stmt->execute();
    // Check if query was successful
    if ($stmt->affected_rows > 0) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false));
    }
} else {
    echo json_encode(array('success' => false));
}
?>