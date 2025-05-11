<?php
session_start();

if (!isset($_SESSION['fullName'])) {
    header("Location: index.php");
    exit;
}

$fullName = $_SESSION['fullName'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($fullName); ?>!</h1>
</body>
</html>