<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Add task
if (isset($_POST['add_task'])) {
    $description = $_POST['description'];
    $color = $_POST['color'];
    $sql = "INSERT INTO tasks (description, user_id, color, visible) VALUES (:description, :user_id, :color, true)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['description' => $description, 'user_id' => $user_id, 'color' => $color]);
    header("Location: welcome.php");
    exit();
}

// Mark task as not visible instead of deleting
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $stmt = $conn->prepare("UPDATE tasks SET visible = false WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $task_id, 'user_id' => $user_id]);
    header("Location: welcome.php");
    exit();
}

// Mark task as done
if (isset($_GET['done'])) {
    $task_id = $_GET['done'];
    $stmt = $conn->prepare("UPDATE tasks SET is_done = 1 WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $task_id, 'user_id' => $user_id]);
    header("Location: welcome.php");
    exit();
}

// Only fetch visible tasks
$tasks = $conn->prepare("SELECT * FROM tasks WHERE user_id = :user_id AND visible = true");
$tasks->execute(['user_id' => $user_id]);
$tasks = $tasks->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title>Welcome</title>
</head>
<?php 
    include "header.php";
?>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-2xl mx-auto py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-center">Welcome, <?= $_SESSION['fullName']; ?> 👋</h1>
            <a href="logout.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-500">Logout</a>
        </div>

        <form method="POST" class="mb-6 p-6 rounded shadow-md" style="background-color:rgb(247, 243, 243)">
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
            <button type="submit" name="add_task" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-400">Add Task</button>
        </form>

        <div class="space-y-4">
            <?php foreach ($tasks as $task): ?>
                <div class="flex items-center justify-between bg-gray-50 p-4 rounded shadow-md border-l-4 border-<?= htmlspecialchars($task['color']) ?>-500">
                    <div class="flex items-center gap-3">
                        <span class="w-4 h-4 rounded-full bg-<?= htmlspecialchars($task['color']) ?>-500"></span>
                        <p class="<?= $task['is_done'] ? 'line-through text-gray-400' : '' ?>">
                            <?= htmlspecialchars($task['description']) ?>
                        </p>
                    </div>
                    <div class="flex gap-3 text-xl">
                        <?php if (!$task['is_done']): ?>
                            <button onclick="confirmDone(<?= $task['id'] ?>)" class="text-green-600 hover:text-green-800">
                                <i class="ph ph-check-circle"></i>
                            </button>
                        <?php endif; ?>
                        <button onclick="confirmEdit(<?= $task['id'] ?>)" class="text-yellow-500 hover:text-yellow-700">
                            <i class="ph ph-pencil"></i>
                        </button>
                        <button onclick="confirmDelete(<?= $task['id'] ?>)" class="text-red-500 hover:text-red-700">
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This task will be removed from view!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, hide it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?delete=' + id;
                }
            });
        }

        function confirmDone(id) {
            Swal.fire({
                title: 'Mark as done?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?done=' + id;
                }
            });
        }

        function confirmEdit(id) {
            Swal.fire({
                title: 'Edit task?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#facc15',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Edit'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'edit.php?id=' + id;
                }
            });
        }
    </script>
</body>
</html>
