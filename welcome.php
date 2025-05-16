<?php
session_start();
include "conn.php";


if (!isset($_SESSION['fullName'])) {
    header("Location: index.php");
    exit;
}


$fullName = $_SESSION['fullName'];

// Remplacer cette ligne par $_SESSION['user_id'] si authentification réelle
$userId = $_SESSION['user_id'] ?? 1;

// Ajouter une tâche
if (isset($_POST['add'])) {
    $description = $_POST['description'];
    $stmt = $conn->prepare("INSERT INTO tasks (description, user_id) VALUES (?, ?)");
    $stmt->execute([$description, $userId]);
}

// Supprimer une tâche
if (isset($_GET['delete'])) {
    $taskId = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$taskId, $userId]);
}

// Marquer comme terminée
if (isset($_GET['done'])) {
    $taskId = $_GET['done'];
    $stmt = $conn->prepare("UPDATE tasks SET is_done = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$taskId, $userId]);
}


// Récupérer les tâches
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$userId]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Bienvenue, <?php echo htmlspecialchars($fullName); ?>!</h1>
    <h1 class="text-2xl font-bold mb-4">Ma liste de tâches</h1>

    <!-- Ajouter une tâche -->
    <form method="POST" class="space-y-4 mb-6">
        <textarea name="description" class="w-full p-2 border rounded" placeholder="Ajouter une tâche..." required></textarea>
        <button type="submit" name="add" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Ajouter</button>
    </form>

    <!-- Afficher les tâches -->
    <ul class="space-y-3">
        <?php foreach ($tasks as $task): ?>
            <li class="flex justify-between items-center bg-gray-50 p-3 rounded border <?= $task['is_done'] ? 'line-through text-gray-400' : '' ?>">
                <span><?= htmlspecialchars($task['description']) ?></span>
                <div class="flex space-x-2">
                    <?php if (!$task['is_done']): ?>
                        <a href="?done=<?= $task['id'] ?>" class="text-green-600 hover:underline">✓</a>
                    <?php endif; ?>
                    <a href="?delete=<?= $task['id'] ?>" class="text-red-600 hover:underline">Supprimer</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>



<script>
function openEditForm(id, description) {
    document.getElementById('editTaskId').value = id;
    document.getElementById('editDescription').value = description;
    document.getElementById('editForm').classList.remove('hidden');
}
function closeEditForm() {
    document.getElementById('editForm').classList.add('hidden');
}
</script>

</body>
</html>
