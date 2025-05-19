<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer l'ID de la tâche
if (!isset($_GET['id'])) {
    header("Location: welcome.php");
    exit();
}

$task_id = $_GET['id'];

// Récupérer les données de la tâche
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $task_id, 'user_id' => $user_id]);
$task = $stmt->fetch();

if (!$task) {
    echo "Tâche introuvable.";
    exit();
}

// Mettre à jour la tâche
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $color = $_POST['color'];

    $update = $conn->prepare("UPDATE tasks SET description = :description, color = :color WHERE id = :id AND user_id = :user_id");
    $update->execute([
        'description' => $description,
        'color' => $color,
        'id' => $task_id,
        'user_id' => $user_id
    ]);
    
    header("Location: welcome.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-semibold mb-4">Edit Task</h2>
        <form method="POST">
            <textarea name="description" required class="w-full p-2 border rounded mb-4"><?= htmlspecialchars($task['description']) ?></textarea>

            <div class="flex items-center gap-4 mb-4">
                <label class="text-sm font-medium">Criticity:</label>

                <label class="cursor-pointer">
                    <input type="radio" name="color" value="red" <?= $task['color'] == 'red' ? 'checked' : '' ?> class="hidden">
                    <span class="w-6 h-6 inline-block rounded-full bg-red-500 border-2 border-white shadow-md"></span>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="color" value="yellow" <?= $task['color'] == 'yellow' ? 'checked' : '' ?> class="hidden">
                    <span class="w-6 h-6 inline-block rounded-full bg-yellow-400 border-2 border-white shadow-md"></span>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="color" value="green" <?= $task['color'] == 'green' ? 'checked' : '' ?> class="hidden">
                    <span class="w-6 h-6 inline-block rounded-full bg-green-500 border-2 border-white shadow-md"></span>
                </label>
            </div>

            <div class="flex justify-between">
                <a href="welcome.php" class="text-gray-500 hover:underline">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Update</button>
            </div>
        </form>
    </div>
</body>
</html>
