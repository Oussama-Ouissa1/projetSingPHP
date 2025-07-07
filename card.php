<?php 
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];



if (isset($_POST['add_card']) && isset($_FILES['image'])) {
    $title = $_POST['title'];
    $descrip_card = $_POST['descrip_card'];
    $imageTmp = $_FILES['image']['tmp_name'];

    if (is_uploaded_file($imageTmp)) {
        $imageData = file_get_contents($imageTmp);
        $imageBase64 = base64_encode($imageData);

        $sql = "INSERT INTO cards (title, descrip_card, image_base64, user_id) 
                VALUES (:title, :descrip_card, :image, :user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'title' => $title,
            'descrip_card' => $descrip_card,
            'image' => $imageBase64,
            'user_id' => $user_id
        ]);

        echo "<script>alert('Carte ajoutée avec succès !'); window.location.href='card.php';</script>";
        exit();
    } else {
        echo "Erreur lors du chargement de l'image.";
    }
}


$stmt = $conn->prepare("SELECT * FROM cards WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$cards = $stmt->fetchAll();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cartes</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<?php include "header.php"; ?>
<body class="bg-gray-50 dark:bg-gray-900">

<div class="flex justify-center">
    <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md w-full max-w-5xl my-5">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 text-center">Ajouter une carte</h2>

        <label class="block mb-2 text-sm font-medium text-gray-700">Titre</label>
        <input type="text" name="title" required class="w-full px-4 py-2 mb-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

        <label class="block mb-2 text-sm font-medium text-gray-700">Description</label>
        <textarea name="descrip_card" rows="4" required class="w-full px-4 py-2 mb-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

        <label class="block mb-2 text-sm font-medium text-gray-700">Image</label>
        <input type="file" name="image" accept="image/*" required class="mb-4">

        <button type="submit" name="add_card" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-500 transition">
            Ajouter
        </button>
    </form>
</div>

<div class="flex justify-center">
    <div class="max-w-2xl py-5 w-full">
        <?php foreach ($cards as $card): ?>
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 my-5">
            <img class="rounded-t-lg w-full object-cover h-64" src="data:image/jpeg;base64,<?= $card['image_base64'] ?>" alt="Image de la carte" />
            <div class="p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><?= htmlspecialchars($card['title']) ?></h5>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400"><?= htmlspecialchars($card['descrip_card']) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
