<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['add_task'])) {
    $description = $_POST['description'];
    $color = $_POST['color'];
    $sql = "INSERT INTO tasks (description, user_id, color) VALUES (:description, :user_id, :color)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['description' => $description, 'user_id' => $user_id, 'color' => $color]);
    header("Location: welcome.php");
    exit();
}

if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $task_id, 'user_id' => $user_id]);
    header("Location: welcome.php");
    exit();
}

if (isset($_GET['done'])) {
    $task_id = $_GET['done'];
    $stmt = $conn->prepare("UPDATE tasks SET is_done = 1 WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $task_id, 'user_id' => $user_id]);
    header("Location: welcome.php");
    exit();
}

$tasks = $conn->prepare("SELECT * FROM tasks WHERE user_id = :user_id");
$tasks->execute(['user_id' => $user_id]);
$tasks = $tasks->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Welcome</title>
    <script>
        function confirmAction(actionUrl, message) {
            if (confirm(message)) {
                window.location.href = actionUrl + '&confirm=yes';
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-2xl mx-auto py-8">
        <div class="max-w-2xl mx-auto flex justify-between mb-6 pt-6">
            <h1 class="text-3xl font-bold  text-center">Welcome, <?= $_SESSION['fullName']; ?> 👋</h1>
            <a href="logout.php" class="text-white bg-gray-600 hover:bg-gray-500 px-4 py-2 rounded font-semibold">Logout</a>
        </div>
        <form method="POST" class="mb-6 bg-white p-6 rounded shadow-md">
            <textarea name="description" placeholder="New task..." class="w-full p-2 border rounded mb-4"></textarea>
            <div class="flex items-center gap-4 mb-4">
                <label class="text-sm font-medium">Criticity:</label>
                <label class="cursor-pointer">
                    <input type="radio" name="color" value="red" class="hidden" required>
                    <span class="w-6 h-6 inline-block rounded-full bg-red-500 border-2 border-white shadow-md"></span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="color" value="yellow" class="hidden">
                    <span class="w-6 h-6 inline-block rounded-full bg-yellow-400 border-2 border-white shadow-md"></span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="color" value="green" class="hidden">
                    <span class="w-6 h-6 inline-block rounded-full bg-green-500 border-2 border-white shadow-md"></span>
                </label>
            </div>
            <button type="submit" name="add_task" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Add Task</button>
        </form>

        <div class="space-y-4">
            <?php foreach ($tasks as $task): ?>
                <?php
                    $colorClass = [
                        'red' => 'bg-red-200',
                        'yellow' => 'bg-yellow-200',
                        'green' => 'bg-green-200'
                    ];
                    $taskColor = $colorClass[$task['color']] ?? 'bg-white';
                ?>
                <div class="flex items-center justify-between <?= $taskColor ?> p-4 rounded shadow-md">
                    <div class="flex items-center gap-3">
                        <p class="<?= $task['is_done'] ? 'line-through text-gray-400' : '' ?>">
                            <?= htmlspecialchars($task['description']) ?>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <?php if (!$task['is_done']): ?>
                            <button onclick="confirmAction('?done=<?= $task['id'] ?>', 'Marquer cette tâche comme terminée ?')" class="text-green-600 hover:underline">Done</button>
                        <?php endif; ?>
                        <a href="edit.php?id=<?= $task['id'] ?>" class="text-yellow-500 hover:underline">Edit</a>
                        <button onclick="confirmAction('?delete=<?= $task['id'] ?>', 'Voulez-vous vraiment supprimer cette tâche ?')" class="text-red-500 hover:underline">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
