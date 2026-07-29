**create_دفع.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Create New دفع</h1>

    <form id="create-dfa" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="amount" class="block text-sm font-medium text-slate-900">Amount</label>
            <input type="number" id="amount" name="amount" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="date" class="block text-sm font-medium text-slate-900">Date</label>
            <input type="date" id="date" name="date" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create دفع</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-dfa').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/دفع.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_دفع.php';
                    } else {
                        alert('Error creating دفع');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**Note:** This code assumes you have jQuery and a backend PHP script (`../backend/دفع.php`) to handle the form submission. You'll need to replace `list_دفع.php` with the actual URL of the page to redirect to after creating a new دفع record.