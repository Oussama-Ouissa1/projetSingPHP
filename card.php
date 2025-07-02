<?php 
    session_start();
    include "conn.php";

    if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
    }

    $user_id = $_SESSION['user_id'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php 
    include "header.php";
?>
<body class="bg-gray-50 dark:bg-gray-900 ">
    <div class="flex justify-center ">
        <form action="upload_card.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md w-full max-w-5xl my-5">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 text-center">Ajouter une carte</h2>

            <label class="block mb-2 text-sm font-medium text-gray-700">Titre</label>
            <input type="text" name="title" required class="w-full px-4 py-2 mb-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

            <label class="block mb-2 text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="4" required class="w-full px-4 py-2 mb-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

            <label class="block mb-2 text-sm font-medium text-gray-700">Image</label>
            <input type="file" name="image" accept="image/*" required class="mb-4">

            <button type="submit" name="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-500 transition">
                Ajouter
            </button>
        </form>
    </div>

    <div class="flex justify-center">
        <div class=" w-2/5 py-5">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 my-5">
                <a href="#">
                    <img class="rounded-t-lg" src="/docs/images/blog/image-1.jpg" alt="" />
                </a>
                <div class="p-5">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Noteworthy technology acquisitions 2021</h5>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 my-5">
                <a href="#">
                    <img class="rounded-t-lg" src="/docs/images/blog/image-1.jpg" alt="" />
                </a>
                <div class="p-5">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Noteworthy technology acquisitions 2021</h5>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.</p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>